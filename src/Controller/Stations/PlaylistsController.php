<?php
namespace App\Controller\Stations;

use App\Form\EntityForm;
use App\Utilities;
use Cake\Chronos\Chronos;
use App\Entity;
use Psr\Http\Message\ResponseInterface;
use App\Http\Request;
use App\Http\Response;

class PlaylistsController extends AbstractStationCrudController
{
    /** @var Entity\Repository\StationPlaylistMediaRepository */
    protected $playlist_media_repo;

    /**
     * @param EntityForm $form
     *
     * @see \App\Provider\StationsProvider
     */
    public function __construct(EntityForm $form)
    {
        parent::__construct($form);

        $this->csrf_namespace = 'stations_playlists';
        $this->playlist_media_repo = $this->em->getRepository(Entity\StationPlaylistMedia::class);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param int|string $station_id
     * @return ResponseInterface
     */
    public function indexAction(Request $request, Response $response, $station_id): ResponseInterface
    {
        $station = $request->getStation();

        $backend = $request->getStationBackend();
        if (!$backend::supportsMedia()) {
            throw new \Azura\Exception(__('This feature is not currently supported on this station.'));
        }

        /** @var Entity\StationPlaylist[] $all_playlists */
        $all_playlists = $station->getPlaylists();

        $total_weights = 0;
        foreach ($all_playlists as $playlist) {
            if ($playlist->getIsEnabled() && $playlist->getType() === 'default') {
                $total_weights += $playlist->getWeight();
            }
        }

        $station_tz = $station->getTimezone();
        $now = Chronos::now(new \DateTimeZone($station_tz));

        $playlists = [];

        $songs_query = $this->em->createQuery(/** @lang DQL */'
            SELECT count(sm.id) AS num_songs, sum(sm.length) AS total_length
            FROM App\Entity\StationMedia sm
            JOIN sm.playlists spm
            WHERE spm.playlist = :playlist');

        foreach ($all_playlists as $playlist) {
            $playlist_row = $this->record_repo->toArray($playlist);

            if ($playlist->getIsEnabled()) {
                if (Entity\StationPlaylist::TYPE_DEFAULT === $playlist->getType()) {
                    $playlist_row['probability'] = round(($playlist->getWeight() / $total_weights) * 100, 1) . '%';
                } elseif (Entity\StationPlaylist::TYPE_SCHEDULED === $playlist->getType()) {
                    $schedule_start = Entity\StationPlaylist::getDateTime($playlist->getScheduleStartTime(), $now);
                    $schedule_end = Entity\StationPlaylist::getDateTime($playlist->getScheduleEndTime(), $now);

                    $playlist_row['schedule_start'] = $schedule_start->toIso8601String();
                    $playlist_row['schedule_end'] = $schedule_end->toIso8601String();
                }
            }

            $song_totals = $songs_query->setParameter('playlist', $playlist)
                ->getArrayResult();

            $playlist_row['num_songs'] = (int)$song_totals[0]['num_songs'];
            $playlist_row['total_length'] = (int)$song_totals[0]['total_length'];
            $playlists[$playlist->getId()] = $playlist_row;
        }

        return $request->getView()->renderToResponse($response, 'stations/playlists/index', [
            'playlists' => $playlists,
            'csrf' => $request->getSession()->getCsrf()->generate($this->csrf_namespace),
            'station_tz' => $station_tz,
            'station_now' => $now->toIso8601String(),
            'schedule_url' => $request->getRouter()->named('stations:playlists:schedule', ['station' => $station_id]),
        ]);
    }

    /**
     * Controller used to respond to AJAX requests from the playlist "Schedule View".
     *
     * @param Request $request
     * @param Response $response
     * @param int|string $station_id
     * @return Response
     */
    public function scheduleAction(Request $request, Response $response, $station_id): ResponseInterface
    {
        $station = $request->getStation();
        $tz = new \DateTimeZone($station->getTimezone());

        $start_date_str = substr($request->getParam('start'), 0, 10);
        $start_date = Chronos::createFromFormat('Y-m-d', $start_date_str, $tz)
            ->subDay();

        $end_date_str = substr($request->getParam('end'), 0, 10);
        $end_date = Chronos::createFromFormat('Y-m-d', $end_date_str, $tz);

        /** @var Entity\StationPlaylist[] $all_playlists */
        $playlists = $station->getPlaylists()->filter(function($record) {
            /** @var Entity\StationPlaylist $record */
            return ($record->getType() === Entity\StationPlaylist::TYPE_SCHEDULED && !$record->isJingle());
        });

        $events = [];
        $i = $start_date;

        while($i <= $end_date) {

            $day_of_week = $i->format('N');

            foreach ($playlists as $playlist) {
                /** @var Entity\StationPlaylist $playlist */
                if (!empty($playlist->getScheduleDays()) && !in_array($day_of_week, $playlist->getScheduleDays())) {
                    continue;
                }

                $playlist_start = Entity\StationPlaylist::getDateTime($playlist->getScheduleStartTime(), $i);
                $playlist_end = Entity\StationPlaylist::getDateTime($playlist->getScheduleEndTime(), $i);

                // Handle overnight playlists
                if ($playlist_end < $playlist_start) {
                    $playlist_end = $playlist_end->addDay();
                }

                $events[] = [
                    'id' => $playlist->getId(),
                    'title' => $playlist->getName(),
                    'start' => $playlist_start->toIso8601String(),
                    'end' => $playlist_end->toIso8601String(),
                    'url' => (string)$request->getRouter()->named('stations:playlists:edit', ['station' => $station_id, 'id' => $playlist->getId()]),
                ];
            }

            $i = $i->addDay();
        }

        return $response->withJson($events);
    }

    public function reorderAction(Request $request, Response $response, $station_id, $id): ResponseInterface
    {
        $record = $this->_getRecord($request->getStation(), $id);

        if (!$record instanceof Entity\StationPlaylist) {
            throw new \App\Exception\NotFound(__('%s not found.', __('Playlist')));
        }

        if ($record->getSource() !== Entity\StationPlaylist::SOURCE_SONGS
            || $record->getOrder() !== Entity\StationPlaylist::ORDER_SEQUENTIAL) {
            throw new \Azura\Exception(__('This playlist is not a sequential playlist.'));
        }

        if ($request->isPost()) {
            try {
                $request->getSession()->getCsrf()->verify($request->getParam('csrf'), $this->csrf_namespace);
            } catch(\Azura\Exception\CsrfValidation $e) {
                return $response->withStatus(403)
                    ->withJson(['error' => ['code' => 403, 'msg' => 'CSRF Failure: '.$e->getMessage()]]);
            }

            $order_raw = $request->getParam('order');
            $order = json_decode($order_raw, true);

            $mapping = [];
            foreach($order as $weight => $row_id) {
                $mapping[$row_id] = $weight+1;
            }

            $this->playlist_media_repo->setMediaOrder($record, $mapping);

            return $response->withJson($mapping);
        }

        $media_items = $this->em->createQuery(/** @lang DQL */'SELECT spm, sm 
            FROM App\Entity\StationPlaylistMedia spm
            JOIN spm.media sm
            WHERE spm.playlist_id = :playlist_id
            ORDER BY spm.weight ASC')
            ->setParameter('playlist_id', $id)
            ->getArrayResult();

        return $request->getView()->renderToResponse($response, 'stations/playlists/reorder', [
            'playlist' => $record,
            'csrf' => $request->getSession()->getCsrf()->generate($this->csrf_namespace),
            'media_items' => $media_items,
        ]);
    }

    public function exportAction(Request $request, Response $response, $station_id, $id, $format = 'pls'): ResponseInterface
    {
        $record = $this->_getRecord($request->getStation(), $id);

        $formats = [
            'pls' => 'audio/x-scpls',
            'm3u' => 'application/x-mpegURL',
        ];

        if (!isset($formats[$format])) {
            throw new \App\Exception\NotFound(__('%s not found.', __('Format')));
        }

        $file_name = 'playlist_' . $record->getShortName().'.'.$format;

        return $response
            ->withHeader('Content-Type', $formats[$format])
            ->withHeader('Content-Disposition', 'attachment; filename=' . $file_name)
            ->write($record->export($format));
    }

    public function toggleAction(Request $request, Response $response, $station_id, $id): ResponseInterface
    {
        $record = $this->_getRecord($request->getStation(), $id);

        if (!$record instanceof Entity\StationPlaylist) {
            throw new \App\Exception\NotFound(__('%s not found.', __('Playlist')));
        }

        $new_value = !$record->getIsEnabled();

        $record->setIsEnabled($new_value);
        $this->em->persist($record);
        $this->em->flush();

        $flash_message = ($new_value)
            ? __('Playlist enabled.')
            : __('Playlist disabled.');

        $request->getSession()->flash('<b>' . $flash_message . '</b><br>' . $record->getName(), 'green');

        return $response->withRedirect(
            $request->getReferrer($request->getRouter()->fromHere('stations:playlists:index'))
        );
    }

    public function editAction(Request $request, Response $response, $station_id, $id = null): ResponseInterface
    {
        if (false !== $this->_doEdit($request, $id)) {
            $request->getSession()->flash('<b>' . sprintf(($id) ? __('%s updated.') : __('%s added.'), __('Playlist')) . '</b>', 'green');
            return $response->withRedirect($request->getRouter()->fromHere('stations:playlists:index'));
        }

        return $request->getView()->renderToResponse($response, 'stations/playlists/edit', [
            'form' => $this->form,
            'title' => sprintf(($id) ? __('Edit %s') : __('Add %s'), __('Playlist'))
        ]);
    }

    public function deleteAction(Request $request, Response $response, $station_id, $id, $csrf_token): ResponseInterface
    {
        $this->_doDelete($request, $id, $csrf_token);

        $request->getSession()->flash('<b>' . __('%s deleted.', __('Playlist')) . '</b>', 'green');
        return $response->withRedirect($request->getRouter()->fromHere('stations:playlists:index'));
    }
}

<?php
namespace App\Controller\Admin;

use App\Controller\Traits\LogViewerTrait;
use App\Entity;
use Azura\Exception;
use App\Http\Request;
use App\Http\Response;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;

class RelaysController
{
    /** @var EntityManager */
    protected $em;

    /**
     * @param EntityManager $em
     *
     * @see \App\Provider\AdminProvider
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $record_repo = $this->em->getRepository(Entity\Relay::class);
        $relays = $record_repo->fetchArray(false);

        return $request->getView()->renderToResponse($response, 'admin/relays/index', [
            'relays' => $relays,
        ]);
    }
}

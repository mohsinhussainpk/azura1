<?php declare(strict_types=1);

namespace App\Entity\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190513163051 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        // Move "play once per day" playlists to be standard scheduled ones with the same start/end time.
        $this->addSql('UPDATE station_playlists SET type="scheduled", schedule_start_time=play_once_time, schedule_end_time=play_once_time, schedule_days=play_once_days WHERE type = "once_per_day"');

        $this->addSql('ALTER TABLE station ADD timezone VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE station_playlists DROP play_once_time, DROP play_once_days');
        $this->addSql('ALTER TABLE users DROP timezone');
    }

    public function postUp(Schema $schema)
    {
        // Use the system setting for "global timezone" to set the station timezones.
        $global_tz = $this->connection->fetchColumn('SELECT setting_value FROM settings WHERE setting_key="timezone"');

        if (!empty($global_tz)) {
            $global_tz = json_decode($global_tz, true);
        } else {
            $global_tz = 'UTC';
        }

        // Set all stations' timezones to this value.
        $this->connection->update('station', [
            'timezone' => $global_tz,
        ], [1 => 1]);

        // Calculate the offset of any currently scheduled playlists.
        if ('UTC' !== $global_tz) {

            $system_tz = new \DateTimeZone('UTC');
            $system_dt = new \DateTime('now', $system_tz);
            $system_offset = $system_tz->getOffset($system_dt);

            $app_tz = new \DateTimeZone($global_tz);
            $app_dt = new \DateTime('now', $app_tz);
            $app_offset = $app_tz->getOffset($app_dt);

            $offset = $system_offset - $app_offset;
            $offset_hours = (int)floor($offset / 3600);

            if (0 !== $offset_hours) {

                $playlists = $this->connection->fetchAll('SELECT sp.* FROM station_playlists AS sp WHERE sp.type = "scheduled"');

                foreach($playlists as $playlist) {
                    $this->connection->update('station_playlists', [
                        'schedule_start_time' => $this->_applyOffset($playlist['schedule_start_time'], $offset_hours),
                        'schedule_end_time'   => $this->_applyOffset($playlist['schedule_end_time'], $offset_hours),
                    ], [
                        'id' => $playlist['id'],
                    ]);
                }

            }
        }
    }

    /**
     * @param mixed $time_code
     * @param int $offset_hours
     * @return int
     */
    protected function _applyOffset($time_code, $offset_hours): int
    {
        $hours = (int)floor($time_code / 100);
        $mins = $time_code % 100;

        $hours += $offset_hours;

        /** @noinspection SummerTimeUnsafeTimeManipulationInspection */
        $hours %= 24;
        if ($hours < 0) {
            /** @noinspection SummerTimeUnsafeTimeManipulationInspection */
            $hours += 24;
        }

        return ($hours*100)+$mins;
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE station DROP timezone');
        $this->addSql('ALTER TABLE station_playlists ADD play_once_time SMALLINT NOT NULL, ADD play_once_days VARCHAR(50) DEFAULT NULL COLLATE utf8mb4_general_ci');
        $this->addSql('ALTER TABLE users ADD timezone VARCHAR(100) DEFAULT NULL COLLATE utf8mb4_general_ci');
    }
}

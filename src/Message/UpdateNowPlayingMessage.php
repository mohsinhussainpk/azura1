<?php
namespace App\Message;

class UpdateNowPlayingMessage extends AbstractDelayedMessage
{
    public function __construct()
    {
        $this->delay = self::ONE_SEC*2;
    }

    /** @var int */
    public $station_id;
}

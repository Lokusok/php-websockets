<?php

namespace App\Actions;

use Swoole\WebSocket\Server;

class Close
{
    public static function handle(Server $ws, mixed $fd)
    {
        echo "client-{$fd} is closed\n" . PHP_EOL;
    }
}
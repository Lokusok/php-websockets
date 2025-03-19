<?php

namespace App\Actions;

use Swoole\WebSocket\Server;

class Close
{
    public static function handle(Server $ws, int $fd): void
    {
        echo "client-{$fd} is closed\n" . PHP_EOL;
    }
}
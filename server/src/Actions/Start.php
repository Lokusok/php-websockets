<?php

namespace App\Actions;

use Swoole\WebSocket\Server;

class Start
{
    public static function handle(Server $ws): void
    {
        echo "Server started at port: {$ws->port}" . PHP_EOL;
    }
}

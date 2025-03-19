<?php

namespace App\Actions;

use App\Pools\SocketPool;
use Swoole\WebSocket\Server;

class Close
{
    public function handle(Server $ws, int $fd): void
    {
        SocketPool::removeClient($fd);
        echo "{$fd} is closed" . PHP_EOL;
    }
}
<?php

namespace App\Actions;

use App\Pools\SocketPool;
use Swoole\Http\Request;
use Swoole\WebSocket\Server;

class Open
{
    public function handle(Server $ws, Request $request): void
    {
        SocketPool::push($request->fd);
    }
}
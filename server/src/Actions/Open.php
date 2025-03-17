<?php

namespace App\Actions;

use App\Pools\SocketPool;
use Swoole\Http\Request;
use Swoole\WebSocket\Server;

class Open
{
    public static function handle(Server $ws, Request $request)
    {
        SocketPool::push($request->fd);

        $ws->push($request->fd, json_encode([
            'data' => [
                'message' => 'Hello, Welcome'
            ]
        ]));
    }
}
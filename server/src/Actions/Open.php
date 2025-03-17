<?php

namespace App\Actions;

use Swoole\Http\Request;
use Swoole\WebSocket\Server;

class Open
{
    public static function handle(Server $ws, Request $request)
    {
        $ws->push($request->fd, json_encode([
            'data' => [
                'message' => 'Hello, Welcome'
            ]
        ]));
    }
}
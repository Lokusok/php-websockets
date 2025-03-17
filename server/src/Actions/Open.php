<?php

namespace App\Actions;

use Swoole\Http\Request;
use Swoole\WebSocket\Server;

class Open
{
    public static function handle(Server $ws, Request $request)
    {
        $ws->push($request->fd, "hello, welcome\n");
    }
}
<?php

namespace App\Actions;

use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class Message
{
    public static function handle(Server $ws, Frame $frame)
    {
        echo "Data: " . $frame->data . PHP_EOL;
        $ws->push($frame->fd, "server: {$frame->data}");
    }
}
<?php

namespace App\Actions;

use App\Strategies\Factories\MessageStrategyFactory;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class Message
{
    public function handle(Server $ws, Frame $frame): void
    {
        $data = json_decode($frame->data, associative: true);

        echo 'Data: ' . PHP_EOL;
        var_dump($data);

        $strategy = MessageStrategyFactory::choose($data);
        $strategy->handle($ws, $frame);
    }
}
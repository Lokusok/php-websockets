<?php

namespace App\Actions;

use App\Services\SessionService;
use App\Strategies\Factories\MessageStrategyFactory;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class Message
{
    public function __construct(
        private SessionService $sessionService = new SessionService
    ) {}

    public function handle(Server $ws, Frame $frame)
    {
        $data = json_decode($frame->data, associative: true);

        $strategy = MessageStrategyFactory::choose($data);
        $strategy->handle($ws, $frame);

        $ws->push($frame->fd, "server: {$frame->data}");
    }
}
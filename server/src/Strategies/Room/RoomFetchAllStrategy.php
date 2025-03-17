<?php

namespace App\Strategies\Room;

use App\Services\RoomService;
use App\Strategies\StrategyInterface;
use Swoole\WebSocket\Server;
use Swoole\WebSocket\Frame;

class RoomFetchAllStrategy implements StrategyInterface
{
    public function __construct(
        private RoomService $roomService = new RoomService,
    ) {}

    public function handle(Server $ws, Frame $frame): void
    {
        $rooms = $this->roomService->getAllRooms();
        $ws->push($frame->fd, json_encode([
            'type' => 'room.fetch_all.success',
            'data' => $rooms,
        ]));
    }
}

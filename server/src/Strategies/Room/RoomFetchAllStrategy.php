<?php

namespace App\Strategies\Room;

use App\Services\RoomService;
use App\Strategies\StrategyInterface;
use Swoole\WebSocket\Server;
use Swoole\WebSocket\Frame;

class RoomFetchAllStrategy implements StrategyInterface
{
    public function __construct(
        private int $userId,
        private RoomService $roomService = new RoomService,
    ) {}

    public function handle(Server $ws, Frame $frame): void
    {
        $rooms = $this->roomService->getAllRooms();
        $roomsWhereCurrentUserIn = $this->roomService->roomsByUser($this->userId);

        $ws->push($frame->fd, json_encode([
            'type' => 'room.fetch_all.success',
            'data' => [
                'rooms' => $rooms,
                'current_user_in' => $roomsWhereCurrentUserIn,
            ],
        ]));
    }
}

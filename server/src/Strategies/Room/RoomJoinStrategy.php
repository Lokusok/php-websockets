<?php

namespace App\Strategies\Room;

use App\MessageTypes\RoomEnum;
use App\Pools\SocketPool;
use App\Services\RoomService;
use App\Strategies\StrategyInterface;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class RoomJoinStrategy implements StrategyInterface
{
    public function __construct(
        private int $roomId,
        private int $userId,
        private RoomService $roomService = new RoomService,
    )
    {}

    public function handle(Server $ws, Frame $frame): void
    {
        $this->roomService->joinRoom($this->roomId, $this->userId);

        $usersPerRoom = $this->roomService->usersPerRoom();

        SocketPool::broadcast($ws, json_encode([
            'type' => RoomEnum::ROOM_USERS_TOTAL->value,
            'data' => [
                'users_total' => $usersPerRoom,
            ],
        ]));

        $ws->push($frame->fd, json_encode([
            'type' => RoomEnum::ROOM_JOIN_SUCCESS->value,
            'data' => [
                'room_id' => $this->roomId,
                'user_id' => $this->userId,
            ],
        ]));
    }
}
<?php

namespace App\Strategies\Room;

use App\MessageTypes\RoomEnum;
use App\Services\RoomService;
use App\Strategies\StrategyInterface;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class RoomExitStrategy implements StrategyInterface
{
    public function __construct(
        private int $roomId,
        private int $userId,
        private RoomService $roomService = new RoomService,
    )
    {}

    public function handle(Server $ws, Frame $frame): void
    {
        $this->roomService->exitRoom($this->roomId, $this->userId);
        $ws->push($frame->fd, json_encode([
            'type' => RoomEnum::ROOM_EXIT_SUCCESS->value,
            'data' => [
                'room_id' => $this->roomId,
                'user_id' => $this->userId,
            ],
        ]));
    }
}
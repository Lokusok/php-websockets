<?php

namespace App\Strategies\Room;

use App\Exceptions\UniqueException;
use App\MessageTypes\RoomEnum;
use App\Pools\SocketPool;
use App\Services\RoomService;
use App\Strategies\StrategyInterface;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class RoomCreateStrategy implements StrategyInterface
{
    public function __construct(
        private string $title,
        private int $userId,
        private RoomService $roomService = new RoomService,
    )
    {}

    public function handle(Server $ws, Frame $frame): void
    {
        $roomId = null;
     
        try {
            $roomId = $this->roomService->createRoom($this->title, $this->userId);
        } catch (UniqueException $exception) {
            $ws->send($frame->fd, json_encode([
                RoomEnum::ROOM_CREATE_ERROR->value,
                'data' => [
                    'message' => $exception->getMessage(),
                ]
            ]));
            return;
        }

        SocketPool::broadcast($ws, json_encode([
            'type' => RoomEnum::ROOM_CREATE_SUCCESS->value,
            'data' => [
                'room_id' => $roomId,
                'title' => $this->title,
            ]
        ]));
    }
}
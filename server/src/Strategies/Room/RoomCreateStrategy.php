<?php

namespace App\Strategies\Room;

use App\Exceptions\UniqueException;
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
            echo 'Room id: ' . PHP_EOL;
            var_dump($roomId);
        } catch (UniqueException $exception) {
            $ws->send($frame->fd, json_encode([
                'room.create.error',
                'data' => [
                    'message' => $exception->getMessage(),
                ]
            ]));
            return;
        }

        $ws->push($frame->fd, json_encode([
            'type' => 'room.create.success',
            'data' => [
                'room_id' => $roomId,
                'title' => $this->title,
            ]
        ]));
    }
}
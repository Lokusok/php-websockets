<?php

namespace App\Strategies\Room;

use App\Exceptions\PolicyException;
use App\MessageTypes\RoomEnum;
use App\Services\RoomService;
use App\Strategies\StrategyInterface;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class RoomDeleteStrategy implements StrategyInterface
{
    public function __construct(
        private int $roomId,
        private int $userId,
        private RoomService $roomService = new RoomService,
    ) {}

    public function handle(Server $ws, Frame $frame): void
    {
        $affectedRowsByDelete = 0;

        try {
            $affectedRowsByDelete = $this->roomService->deleteRoom($this->roomId, $this->userId);
        } catch (PolicyException $exception) {
            $ws->push($frame->fd, json_encode([
                'type' => RoomEnum::ROOM_DELETE_ERROR->value,
                'data' => [
                    'message' => $exception->getMessage(),
                ],
            ]));
            return;
        }

        $ws->push($frame->fd, json_encode([
            'type' => RoomEnum::ROOM_DELETE_SUCCESS->value,
            'data' => [
                'deleted_id' => $this->roomId,
                'message' => "Successfully deleted {$affectedRowsByDelete} rows"
            ],
        ]));
    }
}

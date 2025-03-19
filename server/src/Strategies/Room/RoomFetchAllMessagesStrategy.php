<?php

namespace App\Strategies\Room;

use App\MessageTypes\RoomEnum;
use App\Services\RoomService;
use App\Strategies\StrategyInterface;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class RoomFetchAllMessagesStrategy implements StrategyInterface
{
    public function __construct(
        private int $roomId,
        private RoomService $roomService = new RoomService,
    )
    {}

    public function handle(Server $ws, Frame $frame): void
    {
        $messages = $this->roomService->getAllMessagesFromRoom($this->roomId);

        $ws->push($frame->fd, json_encode([
            'type' => RoomEnum::ROOM_MESSAGE_FETCH_ALL_SUCCESS,
            'data' => [
                'messages' => $messages,
            ],
        ]));
    }
}

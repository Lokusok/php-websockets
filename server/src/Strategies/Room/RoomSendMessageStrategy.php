<?php

namespace App\Strategies\Room;

use App\MessageTypes\RoomEnum;
use App\Pools\SocketPool;
use App\Services\RoomService;
use App\Strategies\StrategyInterface;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class RoomSendMessageStrategy implements StrategyInterface
{
    public function __construct(
        private int $roomId,
        private int $userId,
        private string $content,
        private RoomService $roomService = new RoomService,
    )
    {}

    public function handle(Server $ws, Frame $frame): void
    {
        $messageId = $this->roomService->sendMessage($this->roomId, $this->userId, $this->content);
        
        $messages = $this->roomService->getAllMessagesFromRoom($this->roomId);

        SocketPool::broadcast($ws, json_encode([
            'type' => RoomEnum::ROOM_MESSAGE_SEND->roomMessageSendTo($this->roomId),
            'data' => [
                'messages' => $messages,
            ],
        ]));
    }
}

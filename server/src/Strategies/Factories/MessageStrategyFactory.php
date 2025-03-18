<?php

namespace App\Strategies\Factories;

use App\MessageTypes\ConnectEnum;
use App\MessageTypes\RoomEnum;
use App\Strategies\Session\LoginStrategy;
use App\Strategies\Room\RoomCreateStrategy;
use App\Strategies\Room\RoomDeleteStrategy;
use App\Strategies\Room\RoomExitStrategy;
use App\Strategies\Room\RoomFetchAllMessagesStrategy;
use App\Strategies\Room\RoomFetchAllStrategy;
use App\Strategies\Room\RoomJoinStrategy;
use App\Strategies\Room\RoomSendMessageStrategy;
use App\Strategies\StrategyInterface;

class MessageStrategyFactory
{
    public static function choose(array $data): ?StrategyInterface
    {
        switch ($data['type']) {
            case ConnectEnum::CONNECT_OPEN->value: 
                return new LoginStrategy($data['data']['username'], $data['data']['token']);
            case RoomEnum::ROOM_FETCH_ALL->value:
                return new RoomFetchAllStrategy($data['data']['user_id']);
            case RoomEnum::ROOM_CREATE->value:
                return new RoomCreateStrategy($data['data']['title'], $data['data']['user_id']);
            case RoomEnum::ROOM_DELETE->value:
                return new RoomDeleteStrategy($data['data']['room_id'], $data['data']['user_id']);
            case RoomEnum::ROOM_JOIN->value:
                return new RoomJoinStrategy($data['data']['room_id'], $data['data']['user_id']);
            case RoomEnum::ROOM_EXIT->value:
                return new RoomExitStrategy($data['data']['room_id'], $data['data']['user_id']);
            case RoomEnum::ROOM_MESSAGE_FETCH_ALL->value:
                return new RoomFetchAllMessagesStrategy($data['data']['room_id']);
            case RoomEnum::ROOM_MESSAGE_SEND->value:
                return new RoomSendMessageStrategy($data['data']['room_id'], $data['data']['user_id'], $data['data']['content']);
        }

        return null;
    }
}

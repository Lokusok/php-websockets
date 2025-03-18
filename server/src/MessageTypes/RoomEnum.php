<?php

namespace App\MessageTypes;

enum RoomEnum: string
{
    case ROOM_FETCH_ALL = 'room.fetch_all';

    case ROOM_CREATE = 'room.create';
    case ROOM_CREATE_SUCCESS = 'room.create.success';
    case ROOM_CREATE_ERROR = 'room.create.error';

    case ROOM_DELETE = 'room.delete';
    case ROOM_DELETE_SUCCESS = 'room.delete.success';
    case ROOM_DELETE_ERROR = 'room.delete.error';

    case ROOM_JOIN = 'room.join';
    case ROOM_JOIN_ERROR = 'room.join.error';
    case ROOM_JOIN_SUCCESS = 'room.join.success';

    case ROOM_EXIT = 'room.exit';
    case ROOM_EXIT_SUCCESS = 'room.exit.success';

    case ROOM_USERS_TOTAL = 'room.users_total';

    case ROOM_MESSAGE_FETCH_ALL = 'room.message.fetch_all';
    case ROOM_MESSAGE_FETCH_ALL_SUCCESS = 'room.message.fetch_all.success';
    case ROOM_MESSAGE_SEND = 'room.message.send';

    public function roomMessageSendTo(int $roomId): string
    {
        return match ($this) {
            self::ROOM_MESSAGE_SEND => "room_{$roomId}_" . $this->value,
            default => $this->value,
        };
    }
}

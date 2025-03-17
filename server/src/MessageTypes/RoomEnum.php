<?php

namespace App\MessageTypes;

enum RoomEnum: string
{
    case ROOM_FETCH_ALL = 'room.fetch_all';
    case ROOM_CREATE = 'room.create';
    case ROOM_CREATE_SUCCESS = 'room.create.success';
    case ROOM_CREATE_ERROR = 'room.create.error';
}

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
    case ROOM_JOIN_SUCCESS = 'room.join.success';

    case ROOM_EXIT = 'room.exit';
    case ROOM_EXIT_SUCCESS = 'room.exit.success';

    case ROOM_USERS_TOTAL = 'room.users_total';
}

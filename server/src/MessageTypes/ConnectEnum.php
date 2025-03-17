<?php

namespace App\MessageTypes;

enum ConnectEnum: string
{
    case CONNECT_OPEN = 'connect';
    case CONNECT_SUCCESS = 'connect.success';
    case CONNECT_ERROR = 'connect.error';
}

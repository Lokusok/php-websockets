<?php

namespace App\Strategies\Factories;

use App\Strategies\Session\LoginStrategy;
use App\Strategies\Room\RoomCreateStrategy;
use App\Strategies\Room\RoomFetchAllStrategy;
use App\Strategies\StrategyInterface;

class MessageStrategyFactory
{
    public static function choose(array $data): ?StrategyInterface
    {
        switch ($data['type']) {
            case 'connect': 
                return new LoginStrategy($data['data']['username']);
            case 'room.create':
                return new RoomCreateStrategy($data['data']['title'], $data['data']['user_id']);
            case 'room.fetch_all':
                return new RoomFetchAllStrategy;
        }

        return null;
    }
}

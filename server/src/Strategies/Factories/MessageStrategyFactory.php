<?php

namespace App\Strategies\Factories;

use App\Strategies\LoginStrategy;
use App\Strategies\StrategyInterface;

class MessageStrategyFactory
{
    public static function choose(array $data): ?StrategyInterface
    {
        switch ($data['type']) {
            case 'connect': 
                return new LoginStrategy($data['data']['username']);
        }

        return null;
    }
}

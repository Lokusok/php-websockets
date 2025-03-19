<?php

namespace App\Pools;

use Swoole\WebSocket\Server;

class SocketPool
{
    /**
     * @var array<int>
     */
    private static array $clients = [];

    public static function push(int $clientId): void
    {
        self::$clients[] = $clientId;
    }

    public static function broadcast(Server $ws, string $data): void
    {
        foreach (self::$clients as $clientId) {
            $ws->push($clientId, $data);
        }
    }

    public static function removeClient(int $clientId): void
    {
        $clientIndex = array_find_key(self::$clients, fn ($id) => $id === $clientId);
        array_splice(self::$clients, $clientIndex, 1);
    }
}

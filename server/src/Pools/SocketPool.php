<?php

namespace App\Pools;

use Swoole\WebSocket\Server;

class SocketPool
{
    /**
     * @var array<int>
     */
    private static array $clients = [];

    public static function push(int $clientId)
    {
        self::$clients[] = $clientId;
    }

    public static function broadcast(Server $ws, string $data): void
    {
        echo 'Clients for broadcast' . PHP_EOL;
        var_dump(self::$clients);

        foreach (self::$clients as $clientId) {
            $ws->push($clientId, $data);
        }
    }
}

<?php

namespace App\Strategies;

use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

interface StrategyInterface
{
    public function handle(Server $server, Frame $frame): void;
}
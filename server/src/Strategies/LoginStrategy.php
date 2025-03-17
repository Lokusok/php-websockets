<?php

namespace App\Strategies;

use App\Exceptions\UniqueException;
use App\Services\SessionService;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class LoginStrategy implements StrategyInterface
{
    public function __construct(
        private string $username,
        private SessionService $sessionService = new SessionService,
    ) {}

    public function handle(Server $ws, Frame $frame): void
    {
        $username = $this->username;
        $token = bin2hex(random_bytes(28));

        try {
            $this->sessionService->login($username, $token);
        } catch (UniqueException $exception) {
            $ws->push($frame->fd, json_encode([
                'type' => 'connect.error',
                'data' => [
                    'message' => $exception->getMessage(),
                ],
            ]));
            return;
        }

        $ws->push($frame->fd, json_encode([
            'type' => 'connect.success',
            'data' => [
                'username' => $username,
                'token' => $token,
            ]
        ]));
    }
}

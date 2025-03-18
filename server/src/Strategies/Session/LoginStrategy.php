<?php

namespace App\Strategies\Session;

use App\Exceptions\UniqueException;
use App\MessageTypes\ConnectEnum;
use App\Services\SessionService;
use App\Strategies\StrategyInterface;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class LoginStrategy implements StrategyInterface
{
    public function __construct(
        private string $username,
        private ?string $token,
        private SessionService $sessionService = new SessionService,
    ) {}

    public function handle(Server $ws, Frame $frame): void
    {
        $username = $this->username;
        $token = $this->token ?? bin2hex(random_bytes(28));
        $userId = null;

        try {
            $userId = $this->sessionService->login($username, $token);
        } catch (UniqueException $exception) {
            $ws->push($frame->fd, json_encode([
                'type' => ConnectEnum::CONNECT_ERROR->value,
                'data' => [
                    'message' => $exception->getMessage(),
                ],
            ]));
            return;
        }

        $ws->push($frame->fd, json_encode([
            'type' => ConnectEnum::CONNECT_SUCCESS->value,
            'data' => [
                'user_id' => $userId,
                'username' => $username,
                'token' => $token,
            ]
        ]));
    }
}

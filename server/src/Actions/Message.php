<?php

namespace App\Actions;

use App\Connection\Connection;
use App\Exceptions\UniqueException;
use App\Services\SessionService;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class Message
{
    public function __construct(
        private SessionService $sessionService = new SessionService
    ) {}

    public function handle(Server $ws, Frame $frame)
    {
        $data = json_decode($frame->data, associative: true);

        if ($data['type'] === 'connect')  {
            $username = $data['data']['username'];
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

            return;
        }

        $ws->push($frame->fd, "server: {$frame->data}");
    }
}
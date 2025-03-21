<?php

namespace App;

require __DIR__ . '/../vendor/autoload.php';

use App\Actions\Close;
use App\Actions\Message;
use App\Actions\Open;
use App\Actions\Start;
use Swoole\WebSocket\Server;

$port = 9502;
$ws = new Server('0.0.0.0', $port);

$ws->on('Start', [new Start, 'handle']);
$ws->on('Open', [new Open, 'handle']);
$ws->on('Message', [new Message, 'handle']);
$ws->on('Close', [new Close, 'handle']);

$ws->start();

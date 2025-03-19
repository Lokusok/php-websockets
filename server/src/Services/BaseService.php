<?php

namespace App\Services;

use App\Database\Connection;
use App\Database\Enums\Errors;

abstract class BaseService
{
    protected Connection $connection;

    public function __construct()
    {
        $this->connection = Connection::create();
    }

    public function isUniqueException(\Exception $exception): int
    {
        return (int) ($exception->getCode() === Errors::UNIQUE->value);
    }
}

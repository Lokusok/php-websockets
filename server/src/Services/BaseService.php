<?php

namespace App\Services;

use App\Database\Connection;
use App\Database\Enums\Errors;

abstract class BaseService
{
    public function __construct(
        protected Connection $connection = new Connection
    ) {}

    public function isUniqueException(\Exception $exception)
    {
        return (int) $exception->getCode() === Errors::UNIQUE->value;
    }
}

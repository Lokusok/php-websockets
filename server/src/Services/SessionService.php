<?php

namespace App\Services;

use App\Database\Connection;
use App\Database\Enums\Errors;
use App\Exceptions\UniqueException;

class SessionService
{
    public function __construct(
        private Connection $connection = new Connection
    ) {}

    public function login(string $username, string $token): bool
    {
        try {
            $sql = "INSERT INTO users (username, token) VALUES (:username, :token)";
            $stmt = $this->connection->getConnection()->prepare($sql);
            $stmt->bindValue(':username', $username);
            $stmt->bindValue(':token', $token);
            $stmt->execute();
        } catch (\PDOException $exception)  {
            if ((int) $exception->getCode() === Errors::UNIQUE->value) {
                throw new UniqueException('Username must be unique');
            }
        }

        return true;
    }
}
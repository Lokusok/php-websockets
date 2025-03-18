<?php

namespace App\Services;

use App\Exceptions\UniqueException;

class SessionService extends BaseService
{
    public function login(string $username, string $token): int
    {
        // If already have this id by this token - then simply pass it
        $sql = "SELECT id FROM users WHERE token = :token";
        $stmt = $this->connection->getConnection()->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $userId = $stmt->fetchColumn();

        if ($userId) {
            return $userId;
        }

        // If no - start creating in db
        $userId = null;

        try {
            $sql = "INSERT INTO users (username, token) VALUES (:username, :token)";
            $stmt = $this->connection->getConnection()->prepare($sql);
            $stmt->bindValue(':username', $username);
            $stmt->bindValue(':token', $token);
            $stmt->execute();

            $userId = $this->connection->getConnection()->lastInsertId();
        } catch (\PDOException $exception)  {
            if ($this->isUniqueException($exception)) {
                throw new UniqueException('Username must be unique');
            }
        }

        return $userId;
    }
}
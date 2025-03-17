<?php

namespace App\Connection;

class Connection
{
    private \PDO $conn;

    public function __construct(string $dsn, ?string $user = null, ?string $password = null)
    {
        $this->conn = new \PDO($dsn, $user, $password, [
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ]);
    }

    public function getConnection(): \PDO
    {
        return $this->conn;
    }
}

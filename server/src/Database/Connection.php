<?php

namespace App\Database;

class Connection
{
    private const string DSN = 'sqlite:./database.db';
    private const string USER = '';
    private const string PASSWORD = '';

    private \PDO $conn;

    public function __construct()
    {
        $this->conn = new \PDO(self::DSN, self::USER, self::PASSWORD, [
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ]);
    }

    public function getConnection(): \PDO
    {
        return $this->conn;
    }
}

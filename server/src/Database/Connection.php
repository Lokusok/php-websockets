<?php

namespace App\Database;

class Connection
{
    private const string DSN = 'sqlite:./database.db';
    private const string USER = '';
    private const string PASSWORD = '';

    private static ?\PDO $instance = null;
    private \PDO $conn;

    private function __construct()
    {
        $this->conn = self::$instance;
    }

    public static function create(): self
    {
        if (! self::$instance) {
            self::$instance = new \PDO(self::DSN, self::USER, self::PASSWORD, [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ]);
        }

        return new self(self::$instance);
    }

    public function getConnection(): \PDO
    {
        return $this->conn;
    }
}

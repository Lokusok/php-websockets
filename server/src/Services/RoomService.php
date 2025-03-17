<?php

namespace App\Services;

use App\Exceptions\PolicyException;
use App\Exceptions\UniqueException;

class RoomService extends BaseService
{
    public function getAllRooms(): array
    {
        $sql = "SELECT id, title, user_id FROM rooms ORDER BY id DESC";
        $stmt = $this->connection->getConnection()->query($sql);
        $rooms = $stmt->fetchAll();

        return $rooms;
    }

    public function createRoom(string $title, int $userId): int
    {
        $roomId = null;

        try {
            $sql = "INSERT INTO rooms (title, user_id) VALUES (:title, :user_id)";
            $stmt = $this->connection->getConnection()->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();

            $roomId = $this->connection->getConnection()->lastInsertId();
        } catch (\PDOException $exception) {
            if ($this->isUniqueException($exception)) {
                throw new UniqueException('Room title must be unique');
            }
        }

        return $roomId;
    }

    public function deleteRoom(int $roomId, int $userId): int
    {
        // Check permission
        $sql = "SELECT count(1) FROM rooms WHERE id = :room_id AND user_id = :user_id";
        $stmt = $this->connection->getConnection()->prepare($sql);
        $stmt->bindParam(':room_id', $roomId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $result = $stmt->fetchColumn();

        if (! $result) {
            throw new PolicyException('Cannot perform delete on this room by your session');
        }

        // Then Delete
        $sql = "DELETE FROM rooms WHERE id = :room_id";
        $stmt = $this->connection->getConnection()->prepare($sql);
        $stmt->bindParam(':room_id', $roomId);
        $stmt->execute();
        $affectedRowsByDelete = $stmt->rowCount();
        
        return $affectedRowsByDelete;
    }
}

<?php

namespace App\Services;

use App\Exceptions\AlreadyInRoomException;
use App\Exceptions\PolicyException;
use App\Exceptions\UniqueException;

class RoomService extends BaseService
{
    public function getAllRooms(): array
    {
        $sql = "SELECT id, title, user_id, rg.users_total FROM rooms r
                LEFT JOIN (
                    SELECT ru.room_id , COUNT(1) as users_total FROM room_user ru
                    GROUP BY ru.room_id
                ) rg ON r.id = rg.room_id
                ORDER BY id DESC";
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

            if ($roomId !== false) {
                $roomId = (int) $roomId;
            }
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

    /**
     * Get count of users per every room
     *
     * @return array<int, int>
     */
    public function usersPerRoom(): array
    {
        $sql = "SELECT ru.room_id , COUNT(1) as total FROM room_user ru
                GROUP BY ru.room_id";
        $stmt = $this->connection->getConnection()->prepare($sql);
        $stmt->execute();
        $usersPerRoom =  [];

        while ($room = $stmt->fetch()) {
            $usersPerRoom[$room['room_id']] = $room['total'];
        }

        return $usersPerRoom;
    }

    /**
     * Get rooms ids where user with given id inside
     *
     * @return array<int, bool>
     */
    public function roomsByUser(int $userId): array
    {
        $sql = "SELECT room_id FROM room_user WHERE user_id = :user_id";
        $stmt = $this->connection->getConnection()->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        
        $roomsByUser = [];

        while (['room_id' => $roomId] = $stmt->fetch()) {
            $roomsByUser[$roomId] = true;
        }

        return $roomsByUser;
    }

    public function countUsersInRoom(int $roomId): int
    {
        $sql = "SELECT COUNT(1) FROM room_user WHERE room_id = :room_id";
        $stmt = $this->connection->getConnection()->prepare($sql);
        $stmt->bindParam(':room_id', $roomId);
        $stmt->execute();
        $countInRoom = $stmt->fetchColumn();

        return $countInRoom;
    }

    public function joinRoom(int $roomId, int $userId): void
    {
        // If in room - abrupt
        $sql = "SELECT COUNT(1) FROM room_user WHERE room_id = :room_id AND user_id = :user_id";
        $stmt = $this->connection->getConnection()->prepare($sql);
        $stmt->bindParam(':room_id', $roomId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $countInRooms = $stmt->fetchColumn();

        if ($countInRooms >= 1) {
            throw new AlreadyInRoomException('User already in this room');
        }

        // If not in room - let join
        $sql = "INSERT INTO room_user (room_id, user_id) VALUES (:room_id, :user_id)";
        $stmt = $this->connection->getConnection()->prepare($sql);
        $stmt->bindParam(':room_id', $roomId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
    }

    public function exitRoom(int $roomId, int $userId): void
    {
        $sql = "DELETE FROM room_user WHERE room_id = :room_id AND user_id = :user_id";
        $stmt = $this->connection->getConnection()->prepare($sql);
        $stmt->bindParam(':room_id', $roomId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
    }
    
    public function sendMessage(int $roomId, int $userId, string $content): int
    {
        $sql = "INSERT INTO messages (room_id, user_id, content) VALUES (:room_id, :user_id, :content)";
        $stmt = $this->connection->getConnection()->prepare($sql);
        $stmt->bindParam(':room_id', $roomId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':content', $content);
        $stmt->execute();

        $messageId = $this->connection->getConnection()->lastInsertId();

        if ($messageId !== false) {
            $messageId = (int) $messageId;
        }

        return $messageId;
    }

    public function getAllMessagesFromRoom(int $roomId): array
    {
        $sql = "SELECT m.id message_id, m.content, m.room_id, u.id user_id, u.username FROM messages m
                INNER JOIN users u on m.user_id = u.id
                WHERE m.room_id = :room_id
                ORDER BY m.id DESC";
        $stmt = $this->connection->getConnection()->prepare($sql);
        $stmt->bindParam(':room_id', $roomId);
        $stmt->execute();

        $messages = $stmt->fetchAll();

        return $messages;
    }
}

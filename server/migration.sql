PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE `rooms` (`id` INTEGER PRIMARY KEY AUTOINCREMENT, `title` TEXT UNIQUE, `user_id` INTEGER REFERENCES `users`(`id`));
CREATE TABLE `room_user` (`user_id` INTEGER REFERENCES `users`(`id`), `room_id` INTEGER REFERENCES `rooms`(`id`));
CREATE TABLE IF NOT EXISTS "users" (`id` INTEGER PRIMARY KEY AUTOINCREMENT, `username` TEXT UNIQUE, token TEXT UNIQUE);
CREATE TABLE `messages` (`id` INTEGER PRIMARY KEY AUTOINCREMENT, `content` TEXT, `user_id` INTEGER REFERENCES `users`(`id`), `room_id` INTEGER REFERENCES `rooms`(`id`));
COMMIT;

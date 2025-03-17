PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE `rooms` (`id` INTEGER PRIMARY KEY AUTOINCREMENT, `title` TEXT UNIQUE, `user_id` INTEGER REFERENCES `users`(`id`));
CREATE TABLE `room_user` (`user_id` INTEGER REFERENCES `users`(`id`), `room_id` INTEGER REFERENCES `rooms`(`id`));
CREATE TABLE IF NOT EXISTS "users" (`id` INTEGER PRIMARY KEY AUTOINCREMENT, `username` TEXT UNIQUE, token TEXT UNIQUE);
DELETE FROM sqlite_sequence;
INSERT INTO sqlite_sequence VALUES('users',0);
INSERT INTO sqlite_sequence VALUES('rooms',4);
COMMIT;

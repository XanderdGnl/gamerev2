DROP DATABASE IF EXISTS `gamerev`;

CREATE DATABASE `gamerev`;

USE `gamerev`;

CREATE TABLE `users` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
);

INSERT INTO `users` (`username`, `password`) VALUES ('bobby', 'password');

CREATE TABLE `posts` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category ENUM('Action', 'Adventure', 'RPG', 'Simulation', 'Strategy', 'Sports', 'Indie', 'Horror') NOT NULL,
    post_title VARCHAR(255) NOT NULL,
    rating TINYINT UNSIGNED,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `post_likes` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    post_id INT NOT NULL,
    UNIQUE KEY `unique_like` (`user_id`, `post_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`) ON DELETE CASCADE
);

CREATE USER 'username'@'localhost' IDENTIFIED BY 'password';
GRANT ALL ON app.* TO 'username'@'localhost';
CREATE DATABASE app;

CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    birth_day DATE NOT NULL,
    name VARCHAR(255),
    lastname VARCHAR(255),
    address VARCHAR(255),
    gender VARCHAR(255),
    email VARCHAR(255)
);
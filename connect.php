<?php

define('MYSQL_USER', 'username');

define('MYSQL_PASSWORD', 'password');

define('MYSQL_HOST', 'localhost');

define('MYSQL_DATABASE', 'app');
 

$pdo = new PDO(
    "mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DATABASE,
    MYSQL_USER,
    MYSQL_PASSWORD
);
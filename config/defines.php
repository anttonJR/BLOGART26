<?php
// Debug - À retirer après test
// var_dump([
//     'DB_HOST env' => getenv('DB_HOST'),
//     'DB_USER env' => getenv('DB_USER'),
//     'DB_PASSWORD env' => getenv('DB_PASSWORD'),
//     'DB_DATABASE env' => getenv('DB_DATABASE')
// ]);

define('SQL_HOST', getenv('DB_HOST') ?: 'localhost');
define('SQL_USER', getenv('DB_USER') ?: 'root');
define('SQL_PWD', getenv('DB_PASSWORD') ?: '');
define('SQL_DB', getenv('DB_DATABASE') ?: 'blogart26');

// Debug - À retirer après test
// var_dump([
//     'SQL_HOST' => SQL_HOST,
//     'SQL_USER' => SQL_USER,
//     'SQL_PWD' => SQL_PWD,
//     'SQL_DB' => SQL_DB
// ]);
?>
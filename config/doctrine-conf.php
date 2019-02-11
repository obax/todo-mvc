
<?php
    return [
    'driver' => 'pdo_mysql',
    'host' => 'todo-db-host',
    'port' => getenv('MYSQL_PORT'),
    'dbname' => getenv('MYSQL_DATABASE'),
    'user' => getenv('MYSQL_USER'),
    'password' => getenv('MYSQL_PASSWORD')
];

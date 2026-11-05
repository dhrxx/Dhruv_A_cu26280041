<?php
// Save as db_connect.php. Change credentials to match your local MariaDB/MySQL account.
$host = 'localhost';
$dbname = 'college_db';
$username = 'college_app';
$password = 'CHANGE_ME';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
    echo 'Database connection successful.';
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo 'Connection failed. Check MySQL/MariaDB, database name, and credentials.';
}

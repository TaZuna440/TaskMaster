<?php
// db_connect.php
// Secure PDO connection. Edit the values below if you change DB credentials.

declare(strict_types=1);

$DB_HOST = 'localhost';
$DB_NAME = 'test';
$DB_USER = 'root';
$DB_PASS = ''; 

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
    // In production, log the error and show a generic message.
    http_response_code(500);
    error_log('DB Connection error: ' . $e->getMessage());
    echo 'Database connection error.';
    exit;
}

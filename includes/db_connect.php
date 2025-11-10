<?php
// db_connect.php
// Secure PDO connection with schema validation

declare(strict_types=1);

$DB_HOST = 'localhost';
$DB_NAME = 'lalala';
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
    
    // ✅ FIX #2: Validate critical tables exist
    $requiredTables = ['users', 'tasks', 'contacts'];
    foreach ($requiredTables as $table) {
        $check = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($check->rowCount() === 0) {
            throw new Exception("Missing required table: $table. Please run the database setup script.");
        }
    }
    
    // ✅ Validate task_date column exists
    $columns = $pdo->query("SHOW COLUMNS FROM tasks LIKE 'task_date'")->fetchAll();
    if (empty($columns)) {
        throw new Exception("Missing task_date column. Please run: ALTER TABLE tasks ADD COLUMN task_date DATE NOT NULL;");
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    error_log('DB Connection error: ' . $e->getMessage());
    die('Database connection error. Please contact administrator.');
} catch (Exception $e) {
    http_response_code(500);
    error_log('DB Schema error: ' . $e->getMessage());
    die($e->getMessage());
}
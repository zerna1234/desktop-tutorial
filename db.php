<?php
// Prevent direct script access
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    exit('Direct access not allowed.');
}

// Database Credentials
$host    = getenv('DB_HOST') ?: 'localhost';
$port    = getenv('DB_PORT') ?: '3306';
$dbname  = getenv('DB_NAME') ?: 'mztech_db';
$user    = getenv('DB_USER') ?: 'root';
$pass    = getenv('DB_PASS') ?: '';
$charset = 'utf8mb4';

$dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$charset} COLLATE utf8mb4_unicode_ci"
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // If the database does not exist, automatically attempt to create it
    if ($e->getCode() === 1049) {
        try {
            $pdoRoot = new PDO("mysql:host={$host};port={$port};charset={$charset}", $user, $pass, $options);
            $pdoRoot->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $createEx) {
            error_log("Database Auto-Creation Error: " . $createEx->getMessage());
            die("Database initialization failed. Please verify MySQL configuration.");
        }
    } else {
        // Log the exact error internally and display a clean message to visitors
        error_log("Database Connection Error: " . $e->getMessage());
        die("Database Connection Failed. Please try again later or contact administrator.");
    }
}
?>
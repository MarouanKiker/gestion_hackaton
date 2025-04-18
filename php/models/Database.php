<?php
function connectDatabase() {
    $host = '127.0.0.1';
    $dbName = 'hackaton';
    $username = 'root';
    $password = '';

    $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8";

    try {
        $conn = new PDO($dsn, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $conn;
    } catch (PDOException $e) {
        error_log("Database connection error: " . $e->getMessage());
        return null;
    }
}
?>

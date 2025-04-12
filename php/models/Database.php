<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'hackaton';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function __construct() {
        $this->connect();
    }

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            error_log("Test log: La connexion a échoué.", 3, "c:/xampp/php/logs/error.log");
            error_log("Connection error: " . $exception->getMessage(), 3, "c:/xampp/php/logs/error.log");
        }

        return $this->conn;
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>
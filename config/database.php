<?php
class Database {
    // DB Params
    private $host = 'MYSQL5047.site4now.net';
    private $db_name = 'db_a3b3e3_atl';
    private $username = 'a3b3e3_atl';
    private $password = 'atl123456';
    private $conn;

    // DB Connect
    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }

        return $this->conn;
    }
}
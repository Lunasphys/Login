<?php

namespace Database;

use Exception;
use PDO;
use PDOException;


class database
{
    private ?PDO $connection = null;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {


        try {
            $this->connection = new PDO('mysql:host=;dbname=;charset=utf8', '', '');
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        if ($this->connection === null) {
            $this->connect();
        }

        return $this->connection;
    }

    public function testConnection()
    {
        try {
            $this->getConnection();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
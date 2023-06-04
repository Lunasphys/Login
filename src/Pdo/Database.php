<?php

    namespace Database;

    use Log\Log;
    use Exception;
    use PDO;
    use PDOException;


    class Database
    {
        private ?PDO $connection = null;

        /**
         * @throws Exception
         */
        public function __construct()
    {
        $this->connect();
    }

        /**
         * @throws Exception
         */
        private function connect()
    {
        new Log("Connexion à la base de données");

        try {
            $this->connection = new PDO('mysql:host=localhost;dbname=login;charset=utf8', 'root', '');
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

        /**
         * @throws Exception
         */
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
<?php
// Configuration UTF-8 prioritaire
header('Content-Type: text/html; charset=utf-8');
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');

// Chargement de la configuration
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/../models/Model.php';

// Classe PDO wrapper
class Database {
    private $connection;
    private $stmt;

    public function __construct() {
        try {
            $this->connection = new PDO(
                'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER,
                DB_PASSWORD,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
            // Forcer UTF-8 pour la communication avec MySQL
            $this->connection->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
        } catch (PDOException $e) {
            die('Erreur de connexion à la base de données: ' . $e->getMessage());
        }
    }

    public function query($sql) {
        $this->stmt = $this->connection->prepare($sql);
        return $this;
    }

    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            $type = match (true) {
                is_int($value) => PDO::PARAM_INT,
                is_bool($value) => PDO::PARAM_BOOL,
                is_null($value) => PDO::PARAM_NULL,
                default => PDO::PARAM_STR,
            };
        }
        $this->stmt->bindValue($param, $value, $type);
        return $this;
    }

    public function execute() {
        return $this->stmt->execute();
    }

    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }

    public function rowCount() {
        return $this->stmt->rowCount();
    }

    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
}

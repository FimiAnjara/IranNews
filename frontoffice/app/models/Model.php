<?php
/**
 * Classe de base pour tous les modèles
 * Gère la connexion PDO et les méthodes communes
 */
abstract class Model {
    protected $db;
    protected static $table;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Récupère une entrée par ID
     */
    public function find($id) {
        $sql = "SELECT * FROM " . static::$table . " WHERE id = :id LIMIT 1";
        $this->db->query($sql);
        $this->db->bind(':id', $id, PDO::PARAM_INT);
        return $this->db->single();
    }

    /**
     * Récupère toutes les entrées
     */
    public function all($limit = null, $offset = 0) {
        $sql = "SELECT * FROM " . static::$table;
        if ($limit) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        $this->db->query($sql);
        if ($limit) {
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
            $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        }
        return $this->db->resultSet();
    }

    /**
     * Recherche avec condition WHERE
     */
    public function where($column, $value, $operator = '=') {
        $sql = "SELECT * FROM " . static::$table . " WHERE " . $column . " " . $operator . " :value";
        $this->db->query($sql);
        $this->db->bind(':value', $value);
        return $this->db->resultSet();
    }

    /**
     * Compte les enregistrements
     */
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM " . static::$table;
        $this->db->query($sql);
        $result = $this->db->single();
        return $result['total'] ?? 0;
    }

    /**
     * Exécute insert/update/delete
     */
    protected function execute($sql, $bindings = []) {
        $this->db->query($sql);
        foreach ($bindings as $key => $value) {
            $type = PDO::PARAM_STR;
            if (is_int($value)) {
                $type = PDO::PARAM_INT;
            } elseif (is_bool($value)) {
                $type = PDO::PARAM_BOOL;
            } elseif (is_null($value)) {
                $type = PDO::PARAM_NULL;
            }
            $this->db->bind($key, $value, $type);
        }
        return $this->db->execute();
    }
}

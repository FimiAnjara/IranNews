<?php
class User extends Model {
    protected static $table = 'users';

    /**
     * Récupère tous les utilisateurs (alias pour all())
     */
    public function getAll() {
        $this->db->query("SELECT id, name, email, created_at FROM " . static::$table);
        return $this->db->resultSet();
    }

    /**
     * Récupère les utilisateurs avec pagination
     */
    public function getAllPaginated($limit = 10, $offset = 0) {
        $this->db->query("SELECT id, name, email, created_at FROM " . static::$table . " ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    /**
     * Compter le nombre total d'utilisateurs
     */
    public function count() {
        $this->db->query("SELECT COUNT(*) as total FROM " . static::$table);
        $result = $this->db->single();
        return (int)($result['total'] ?? 0);
    }

    /**
     * Récupère un utilisateur par ID (alias pour find())
     */
    public function getById($id) {
        $this->db->query("SELECT id, name, email, created_at FROM " . static::$table . " WHERE id = :id");
        $this->db->bind(':id', $id, PDO::PARAM_INT);
        return $this->db->single();
    }

    /**
     * Récupère un utilisateur par email
     */
    public function getByEmail($email) {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    /**
     * Récupère un utilisateur par nom
     */
    public function getByName($name) {
        $this->db->query("SELECT * FROM users WHERE name = :name");
        $this->db->bind(':name', $name);
        return $this->db->single();
    }

    public function create($name, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $this->db->query("
            INSERT INTO users (name, email, password_hash)
            VALUES (:name, :email, :password_hash)
        ");
        $this->db->bind(':name', $name);
        $this->db->bind(':email', $email);
        $this->db->bind(':password_hash', $hashedPassword);
        return $this->db->execute();
    }

    public function update($id, $name, $email) {
        $this->db->query("
            UPDATE users 
            SET name = :name, email = :email 
            WHERE id = :id
        ");
        $this->db->bind(':name', $name);
        $this->db->bind(':email', $email);
        $this->db->bind(':id', $id, PDO::PARAM_INT);
        return $this->db->execute();
    }

    public function updatePassword($id, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $this->db->query("UPDATE users SET password_hash = :password_hash WHERE id = :id");
        $this->db->bind(':password_hash', $hashedPassword);
        $this->db->bind(':id', $id, PDO::PARAM_INT);
        return $this->db->execute();
    }

    public function delete($id) {
        $this->db->query("DELETE FROM users WHERE id = :id");
        $this->db->bind(':id', $id, PDO::PARAM_INT);
        return $this->db->execute();
    }

    public function authenticate($name, $password) {
        $user = $this->getByName($name);
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return null;
    }
}


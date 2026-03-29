<?php
class User extends Model {
    protected static $table = 'users';

    public function getAll() {
        $this->db->query("SELECT id, name, email, created_at FROM users");
        return $this->db->resultSet();
    }

    public function getById($id) {
        $this->db->query("SELECT id, name, email, created_at FROM users WHERE id = :id");
        $this->db->bind(':id', $id, PDO::PARAM_INT);
        return $this->db->single();
    }

    public function getByName($name) {
        $this->db->query("SELECT * FROM users WHERE name = :name");
        $this->db->bind(':name', $name);
        return $this->db->single();
    }

    public function getByEmail($email) {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
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


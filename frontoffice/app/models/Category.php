<?php
class Category extends Model {
    protected static $table = 'categories';

    /**
     * Récupère une catégorie par ID (alias pour find())
     */
    public function getById($id) {
        return $this->find($id);
    }

    /**
     * Récupère toutes les catégories (alias pour all())
     */
    public function getAll() {
        return $this->all();
    }

    /**
     * Récupère toutes les catégories avec le compte d'articles (option limitée pour performance)
     */
    public function getAllWithArticleCount($limit = null) {
        $sql = "
            SELECT c.*,
                   COUNT(a.id) as article_count
            FROM categories c
            LEFT JOIN articles a ON a.category_id = c.id AND a.etat = 1
            GROUP BY c.id
            ORDER BY c.name ASC
        ";

        if ($limit !== null && is_int($limit) && $limit > 0) {
            $sql .= " LIMIT :limit";
        }

        $this->db->query($sql);

        if ($limit !== null && is_int($limit) && $limit > 0) {
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        }

        return $this->db->resultSet();
    }

    /**
     * Récupère une catégorie par slug
     */
    public function getBySlug($slug) {
        $this->db->query("
            SELECT * FROM categories
            WHERE slug = :slug
        ");
        $this->db->bind(':slug', $slug);
        return $this->db->single();
    }

    /**
     * Récupère une catégorie par nom
     */
    public function getByName($name) {
        $this->db->query("
            SELECT * FROM categories
            WHERE name = :name
        ");
        $this->db->bind(':name', $name);
        return $this->db->single();
    }

    /**
     * Créer une nouvelle catégorie
     */
    public function create($name, $description = null) {
        $slug = $this->slugify($name);
        $this->db->query("
            INSERT INTO categories (name, slug, description, created_at)
            VALUES (:name, :slug, :description, NOW())
        ");
        $this->db->bind(':name', $name);
        $this->db->bind(':slug', $slug);
        $this->db->bind(':description', $description);
        return $this->db->execute();
    }

    /**
     * Mettre à jour une catégorie
     */
    public function update($id, $name, $description = null) {
        $slug = $this->slugify($name);
        $this->db->query("
            UPDATE categories 
            SET name = :name, slug = :slug, description = :description, updated_at = NOW()
            WHERE id = :id
        ");
        $this->db->bind(':id', $id, PDO::PARAM_INT);
        $this->db->bind(':name', $name);
        $this->db->bind(':slug', $slug);
        $this->db->bind(':description', $description);
        return $this->db->execute();
    }

    /**
     * Supprimer une catégorie
     */
    public function delete($id) {
        $this->db->query("DELETE FROM categories WHERE id = :id");
        $this->db->bind(':id', $id, PDO::PARAM_INT);
        return $this->db->execute();
    }

    /**
     * Convertir un titre en slug
     */
    private function slugify($str) {
        $str = strtolower($str);
        $str = preg_replace('/[^a-z0-9-]+/', '-', $str);
        $str = preg_replace('/-+/', '-', $str);
        return trim($str, '-');
    }

    /**
     * Compter les articles d'une catégorie
     */
    public function countArticles($categoryId) {
        $this->db->query("
            SELECT COUNT(*) as count FROM articles
            WHERE category_id = :id AND etat = 1
        ");
        $this->db->bind(':id', $categoryId, PDO::PARAM_INT);
        $result = $this->db->single();
        return $result['count'] ?? 0;
    }
}

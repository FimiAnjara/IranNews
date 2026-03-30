<?php
class News extends Model {
    protected static $table = 'articles';

    /**
     * Récupère tous les articles publiés avec pagination
     */
    public function getAll($limit = 10, $offset = 0) {
        $this->db->query("
            SELECT a.*, c.name as category_name
            FROM articles a 
            LEFT JOIN categories c ON a.category_id = c.id
            WHERE a.etat = 1 AND a.delete_at IS NULL
            ORDER BY a.published_at DESC 
            LIMIT :limit OFFSET :offset
        ");
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    /**
     * Récupère tous les articles (pour l'admin) avec pagination
     */
    public function getAllForAdmin($limit = 10, $offset = 0) {
        $this->db->query("
            SELECT a.*, c.name as category_name
            FROM articles a 
            LEFT JOIN categories c ON a.category_id = c.id
            WHERE a.delete_at IS NULL
            ORDER BY a.published_at DESC 
            LIMIT :limit OFFSET :offset
        ");
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    /**
     * Récupère un article par ID (publié seulement)
     */
    public function getById($id) {
        $this->db->query("
            SELECT a.*, c.name as category_name, c.slug as category_slug
            FROM articles a 
            LEFT JOIN categories c ON a.category_id = c.id
            WHERE a.id = :id AND a.etat = 1 AND a.delete_at IS NULL
        ");
        $this->db->bind(':id', $id, PDO::PARAM_INT);
        return $this->db->single();
    }

    /**
     * Récupère un article par ID (pour l'admin, y compris brouillons)
     */
    public function getByIdForAdmin($id) {
        $this->db->query("
            SELECT a.*, c.name as category_name, c.slug as category_slug
            FROM articles a 
            LEFT JOIN categories c ON a.category_id = c.id
            WHERE a.id = :id AND a.delete_at IS NULL
        ");
        $this->db->bind(':id', $id, PDO::PARAM_INT);
        return $this->db->single();
    }

    /**
     * Récupère un article par slug (publié seulement)
     */
    public function getBySlug($slug) {
        $this->db->query("
            SELECT a.*, c.name as category_name, c.slug as category_slug
            FROM articles a 
            LEFT JOIN categories c ON a.category_id = c.id
            WHERE a.slug = :slug AND a.etat = 1 AND a.delete_at IS NULL
        ");
        $this->db->bind(':slug', $slug);
        return $this->db->single();
    }

    /**
     * Récupère les articles d'une catégorie
     */
    public function getByCategory($category, $limit = 10, $offset = 0) {
        $this->db->query("
            SELECT a.*, c.name as category_name, c.slug as category_slug
            FROM articles a 
            LEFT JOIN categories c ON a.category_id = c.id
            WHERE (c.name = :category OR c.slug = :category) AND a.etat = 1 AND a.delete_at IS NULL
            ORDER BY a.published_at DESC
            LIMIT :limit OFFSET :offset
        ");
        $this->db->bind(':category', $category);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    /**
     * Recherche d'articles par titre, description ou contenu
     */
    public function search($query, $limit = 10, $offset = 0) {
        $searchTerm = '%' . $query . '%';
        $this->db->query("
            SELECT a.*, c.name as category_name
            FROM articles a 
            LEFT JOIN categories c ON a.category_id = c.id
            WHERE (a.title LIKE :query OR a.description LIKE :query OR a.content LIKE :query)
            AND a.etat = 1 AND a.delete_at IS NULL
            ORDER BY a.published_at DESC
            LIMIT :limit OFFSET :offset
        ");
        $this->db->bind(':query', $searchTerm);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    /**
     * Récupère tous les articles publiés (pour le dashboard)
     */
    public function getAllPublished($limit = null) {
        $sql = "
            SELECT a.*, c.name as category_name
            FROM articles a 
            LEFT JOIN categories c ON a.category_id = c.id
            WHERE a.etat = 1 AND a.delete_at IS NULL
            ORDER BY a.published_at DESC
        ";
        if ($limit) {
            $sql .= " LIMIT :limit";
        }
        $this->db->query($sql);
        if ($limit) {
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        }
        return $this->db->resultSet();
    }

    /**
     * Récupère tous les articles en brouillon (pour le dashboard)
     */
    public function getAllDrafts($limit = null) {
        $sql = "
            SELECT a.*, c.name as category_name
            FROM articles a 
            LEFT JOIN categories c ON a.category_id = c.id
            WHERE a.etat = 0 AND a.delete_at IS NULL
            ORDER BY a.created_at DESC
        ";
        if ($limit) {
            $sql .= " LIMIT :limit";
        }
        $this->db->query($sql);
        if ($limit) {
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        }
        return $this->db->resultSet();
    }

    /**
     * Incrémenter les vues (la table n'a pas de colonne views)
     */
    public function incrementViews($id) {
        return true;
    }

    /**
     * Compter tous les articles publiés
     */
    public function countAll() {
        $this->db->query("SELECT COUNT(*) as total FROM articles WHERE etat = 1");
        $result = $this->db->single();
        return (int)$result['total'];
    }

    /**
     * Créer un nouvel article
     */
    public function create($title, $content, $userId, $categoryId = null, $description = null, $etat = 1, $autor = null) {
        $slug = $this->slugify($title);
        if (empty($autor)) {
            $autor = 'Admin';
        }
        $this->db->query("
            INSERT INTO articles (title, slug, content, description, autor, category_id, etat, published_at, created_at)
            VALUES (:title, :slug, :content, :description, :autor, :category_id, :etat, NOW(), NOW())
        ");
        $this->db->bind(':title', $title);
        $this->db->bind(':slug', $slug);
        $this->db->bind(':content', $content);
        $this->db->bind(':description', $description);
        $this->db->bind(':autor', $autor);
        $this->db->bind(':category_id', $categoryId, PDO::PARAM_INT);
        $this->db->bind(':etat', $etat, PDO::PARAM_INT);
        return $this->db->execute();
    }

    /**
     * Mettre à jour un article
     */
    public function update($id, $title, $content, $categoryId = null, $description = null, $etat = 1, $autor = null) {
        $slug = $this->slugify($title);
        $this->db->query("
            UPDATE articles 
            SET title = :title, slug = :slug, content = :content, description = :description,
                category_id = :category_id, etat = :etat, autor = :autor, updated_at = NOW()
            WHERE id = :id
        ");
        $this->db->bind(':id', $id, PDO::PARAM_INT);
        $this->db->bind(':title', $title);
        $this->db->bind(':slug', $slug);
        $this->db->bind(':content', $content);
        $this->db->bind(':description', $description);
        $this->db->bind(':category_id', $categoryId, PDO::PARAM_INT);
        $this->db->bind(':etat', $etat, PDO::PARAM_INT);
        $this->db->bind(':autor', $autor);
        return $this->db->execute();
    }

    /**
     * Supprimer un article
     */
    public function delete($id) {
        $this->db->query("UPDATE articles SET delete_at = NOW(), etat = 0, updated_at = NOW() WHERE id = :id AND delete_at IS NULL");
        $this->db->bind(':id', $id, PDO::PARAM_INT);
        $this->db->execute();

        $this->db->query("UPDATE media SET delete_at = NOW() WHERE article_id = :id AND delete_at IS NULL");
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
     * Récupérer la première image d'un article
     */
    public function getFirstImage($articleId) {
        $this->db->query("
            SELECT url, alt_text FROM media 
            WHERE article_id = :article_id AND delete_at IS NULL
            ORDER BY created_at ASC 
            LIMIT 1
        ");
        $this->db->bind(':article_id', $articleId, PDO::PARAM_INT);
        return $this->db->single();
    }

    /**
     * Récupérer les articles avec filtres (admin)
     */
    public function getFiltered($search = '', $status = '', $categoryId = '', $dateFrom = '', $dateTo = '', $limit = 10, $offset = 0) {
        $sql = "
            SELECT a.*, c.name as category_name
            FROM articles a 
            LEFT JOIN categories c ON a.category_id = c.id
            WHERE 1=1
            AND a.delete_at IS NULL
        ";
        
        // Filtre recherche (titre, id, description)
        if (!empty($search)) {
            $search = '%' . $search . '%';
            $sql .= " AND (a.title LIKE :search OR a.id LIKE :search OR a.description LIKE :search)";
        }
        
        // Filtre statut
        if ($status !== '') {
            $sql .= " AND a.etat = :status";
        }
        
        // Filtre catégorie
        if (!empty($categoryId)) {
            $sql .= " AND a.category_id = :category_id";
        }
        
        // Filtre date début
        if (!empty($dateFrom)) {
            $sql .= " AND DATE(a.created_at) >= :date_from";
        }
        
        // Filtre date fin
        if (!empty($dateTo)) {
            $sql .= " AND DATE(a.created_at) <= :date_to";
        }
        
        $sql .= " ORDER BY a.created_at DESC LIMIT :limit OFFSET :offset";
        
        $this->db->query($sql);
        
        if (!empty($search)) {
            $this->db->bind(':search', $search);
        }
        if ($status !== '') {
            $this->db->bind(':status', (int)$status, PDO::PARAM_INT);
        }
        if (!empty($categoryId)) {
            $this->db->bind(':category_id', (int)$categoryId, PDO::PARAM_INT);
        }
        if (!empty($dateFrom)) {
            $this->db->bind(':date_from', $dateFrom);
        }
        if (!empty($dateTo)) {
            $this->db->bind(':date_to', $dateTo);
        }
        
        $this->db->bind(':limit', (int)$limit, PDO::PARAM_INT);
        $this->db->bind(':offset', (int)$offset, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }


    /**
     * Récupérer toutes les images d'un article
     */
    public function getAllImages($articleId) {
        $this->db->query("
            SELECT id, url, alt_text FROM media 
            WHERE article_id = :article_id AND delete_at IS NULL
            ORDER BY created_at ASC
        ");
        $this->db->bind(':article_id', (int)$articleId, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

}

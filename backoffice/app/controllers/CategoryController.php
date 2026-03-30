<?php
require_once __DIR__ . '/../models/Category.php';

class CategoryController {
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new Category();
        $this->checkAuth();
    }

    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . backUrl('connexion'));
            exit;
        }
    }

    /**
     * Affiche la liste des catégories
     */
    public function list() {
        $categories = $this->categoryModel->getAllWithArticleCount();
        
        return [
            'view' => 'back/categories/list.php',
            'data' => [
                'categories' => $categories
            ]
        ];
    }

    /**
     * Affiche le formulaire de création
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            if (empty($name)) {
                return [
                    'view' => 'back/categories/create.php',
                    'data' => ['error' => 'Le nom de la catégorie est requis']
                ];
            }

            if ($this->categoryModel->getByName($name)) {
                return [
                    'view' => 'back/categories/create.php',
                    'data' => ['error' => 'Cette catégorie existe déjà']
                ];
            }

            $this->categoryModel->create($name, $description);

            return [
                'view' => 'back/categories/list.php',
                'data' => ['success' => 'Catégorie créée avec succès']
            ];
        }

        return [
            'view' => 'back/categories/create.php',
            'data' => []
        ];
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            if (empty($name)) {
                return [
                    'view' => 'back/categories/edit.php',
                    'data' => ['error' => 'Le nom de la catégorie est requis', 'id' => $id]
                ];
            }

            // Vérifier que le nom n'existe pas ailleurs
            $existing = $this->categoryModel->getByName($name);
            if ($existing && $existing['id'] != $id) {
                return [
                    'view' => 'back/categories/edit.php',
                    'data' => ['error' => 'Cette catégorie existe déjà', 'id' => $id]
                ];
            }

            $this->categoryModel->update($id, $name, $description);

            return [
                'view' => 'back/categories/list.php',
                'data' => ['success' => 'Catégorie mise à jour avec succès']
            ];
        }

        $category = $this->categoryModel->getById($id);
        
        if (!$category) {
            return [
                'view' => 'errors/404.php',
                'data' => ['message' => 'Catégorie non trouvée']
            ];
        }

        return [
            'view' => 'back/categories/edit.php',
            'data' => ['category' => $category, 'id' => $id]
        ];
    }

    /**
     * Supprime une catégorie
     */
    public function delete($id) {
        $this->categoryModel->delete($id);
        header('Location: ' . adminUrl('categories-list'));
        exit;
    }
}

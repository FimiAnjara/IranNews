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
        $pageNum = (int)($_GET['p'] ?? 1);
        $limit = 5;
        $offset = ($pageNum - 1) * $limit;
        
        $categories = $this->categoryModel->getAllWithArticleCountPaginated($limit, $offset);
        $totalCategories = $this->categoryModel->count();
        $totalPages = ceil($totalCategories / $limit);
        
        return [
            'view' => 'back/categories/list.php',
            'data' => [
                'categories' => $categories,
                'page' => $pageNum,
                'limit' => $limit,
                'totalPages' => $totalPages,
                'totalCategories' => $totalCategories
            ]
        ];
    }

    /**
     * Affiche le formulaire de création
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';

            if (empty($name)) {
                return [
                    'view' => 'back/categories/create.php',
                    'data' => [
                        'error' => 'Le nom de la catégorie est requis',
                        'suppress_global_alert' => true
                    ]
                ];
            }

            if ($this->categoryModel->getByName($name)) {
                return [
                    'view' => 'back/categories/create.php',
                    'data' => [
                        'error' => 'Cette catégorie existe déjà',
                        'suppress_global_alert' => true
                    ]
                ];
            }

            $this->categoryModel->create($name);

            return [
                'view' => 'back/categories/list.php',
                'data' => ['success' => 'Catégorie créée avec succès']
            ];
        }

        return [
            'view' => 'back/categories/create.php',
            'data' => ['suppress_global_alert' => true]
        ];
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';

            if (empty($name)) {
                return [
                    'view' => 'back/categories/edit.php',
                    'data' => [
                        'error' => 'Le nom de la catégorie est requis',
                        'id' => $id,
                        'suppress_global_alert' => true
                    ]
                ];
            }

            // Vérifier que le nom n'existe pas ailleurs
            $existing = $this->categoryModel->getByName($name);
            if ($existing && $existing['id'] != $id) {
                return [
                    'view' => 'back/categories/edit.php',
                    'data' => [
                        'error' => 'Cette catégorie existe déjà',
                        'id' => $id,
                        'suppress_global_alert' => true
                    ]
                ];
            }

            $this->categoryModel->update($id, $name);

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
            'data' => [
                'category' => $category,
                'id' => $id,
                'suppress_global_alert' => true
            ]
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

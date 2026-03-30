<?php
require_once __DIR__ . '/../models/News.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Category.php';

class BackController {
    private $newsModel;
    private $userModel;
    private $categoryModel;

    public function __construct() {
        $this->newsModel = new News();
        $this->userModel = new User();
        $this->categoryModel = new Category();
        $this->checkAuth();
    }

    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . url('connexion'));
            exit;
        }
    }

    public function dashboard() {
        $allNews = $this->newsModel->getAll(20);
        $publishedNews = $this->newsModel->getAllPublished(5);
        $draftNews = $this->newsModel->getAllDrafts(5);
        
        return [
            'view' => 'back/dashboard.php',
            'data' => [
                'allNews' => $allNews,
                'publishedNews' => $publishedNews,
                'draftNews' => $draftNews,
                'totalArticles' => count($allNews),
            ]
        ];
    }

    public function newsList() {
        $pageNum = (int)($_GET['p'] ?? 1);
        $limit = 10;
        $offset = ($pageNum - 1) * $limit;
        
        $news = $this->newsModel->getAllForAdmin($limit, $offset);
        
        return [
            'view' => 'back/news/list.php',
            'data' => [
                'news' => $news,
                'page' => $pageNum,
                'limit' => $limit
            ]
        ];
    }

    public function newsCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $content = $_POST['content'] ?? '';
            $categoryId = $_POST['category_id'] ?? null;
            $description = $_POST['description'] ?? '';
            $autor = $_POST['autor'] ?? '';
            $etat = isset($_POST['published']) ? 1 : 0;

            if (empty($title) || empty($content)) {
                return [
                    'view' => 'back/news/create.php',
                    'data' => ['error' => 'Titre et contenu requis']
                ];
            }

            // Créer l'article
            $newsId = $this->newsModel->create($title, $content, $_SESSION['user_id'], $categoryId, $description, $etat, $autor);
            
            // Gérer les uploads d'images
            if (!empty($_FILES['images']['name'][0])) {
                $this->handleImageUpload($newsId, $_FILES['images']);
            }

            return [
                'view' => 'back/news/list.php',
                'data' => ['success' => 'Article créé avec succès']
            ];
        }

        $categories = $this->categoryModel->getAll();
        
        return [
            'view' => 'back/news/create.php',
            'data' => ['categories' => $categories]
        ];
    }

    public function newsEdit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $content = $_POST['content'] ?? '';
            $categoryId = $_POST['category_id'] ?? null;
            $description = $_POST['description'] ?? '';
            $autor = $_POST['autor'] ?? '';
            $etat = isset($_POST['published']) ? 1 : 0;

            if (empty($title) || empty($content)) {
                return [
                    'view' => 'back/news/edit.php',
                    'data' => ['error' => 'Titre et contenu requis', 'id' => $id]
                ];
            }

            $this->newsModel->update($id, $title, $content, $categoryId, $description, $etat, $autor);

            return [
                'view' => 'back/news/list.php',
                'data' => ['success' => 'Article mis à jour avec succès']
            ];
        }

        $news = $this->newsModel->getByIdForAdmin($id);
        
        if (!$news) {
            return [
                'view' => 'errors/404.php',
                'data' => ['message' => 'Article non trouvé']
            ];
        }
        
        $categories = $this->categoryModel->getAll();

        return [
            'view' => 'back/news/edit.php',
            'data' => ['news' => $news, 'id' => $id, 'categories' => $categories]
        ];
    }

    public function newsDelete($id) {
        $this->newsModel->delete($id);
        header('Location: ' . adminUrl('news-list'));
        exit;
    }

    public function usersList() {
        $users = $this->userModel->getAll();
        return [
            'view' => 'back/users/list.php',
            'data' => ['users' => $users]
        ];
    }

    private function handleImageUpload($articleId, $files) {
        // Créer le dossier uploads s'il n'existe pas
        $uploadDir = __DIR__ . '/../../public/uploads/articles/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxFileSize = 5 * 1024 * 1024; // 5MB

        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $fileSize = $files['size'][$i];
                $fileType = $files['type'][$i];
                $tmpName = $files['tmp_name'][$i];
                $fileName = $files['name'][$i];

                // Valider le fichier
                if (!in_array($fileType, $allowedTypes)) {
                    continue;
                }
                if ($fileSize > $maxFileSize) {
                    continue;
                }

                // Générer un nom unique
                $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                $uniqueName = 'article-' . $articleId . '-' . time() . '-' . uniqid() . '.' . $ext;
                $uploadPath = $uploadDir . $uniqueName;

                // Déplacer le fichier
                if (move_uploaded_file($tmpName, $uploadPath)) {
                    // Sauvegarder le lien dans la base de données via le modèle Media
                    // Pour l'instant, on stocke juste le fichier
                }
            }
        }
    }
}


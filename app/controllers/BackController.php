<?php
require_once __DIR__ . '/../models/News.php';
require_once __DIR__ . '/../models/User.php';

class BackController {
    private $newsModel;
    private $userModel;

    public function __construct() {
        $this->newsModel = new News();
        $this->userModel = new User();
        $this->checkAuth();
    }

    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=connexion');
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
        $page = $_GET['page'] ?? 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $news = $this->newsModel->getAll($limit, $offset);
        
        return [
            'view' => 'back/news/list.php',
            'data' => [
                'news' => $news,
                'page' => $page,
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

            if (empty($title) || empty($content)) {
                return [
                    'view' => 'back/news/create.php',
                    'data' => ['error' => 'Titre et contenu requis']
                ];
            }

            $this->newsModel->create($title, $content, $_SESSION['user_id'], $categoryId);

            return [
                'view' => 'back/news/create.php',
                'data' => ['success' => 'Article créé avec succès']
            ];
        }

        return [
            'view' => 'back/news/create.php',
            'data' => []
        ];
    }

    public function newsEdit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $content = $_POST['content'] ?? '';
            $categoryId = $_POST['category_id'] ?? null;
            $description = $_POST['description'] ?? '';

            if (empty($title) || empty($content)) {
                return [
                    'view' => 'back/news/edit.php',
                    'data' => ['error' => 'Titre et contenu requis', 'id' => $id]
                ];
            }

            $this->newsModel->update($id, $title, $content, $categoryId, $description);

            return [
                'view' => 'back/news/edit.php',
                'data' => ['success' => 'Article mis à jour avec succès', 'id' => $id]
            ];
        }

        $news = $this->newsModel->getById($id);
        
        if (!$news) {
            return [
                'view' => 'errors/404.php',
                'data' => ['message' => 'Article non trouvé']
            ];
        }

        return [
            'view' => 'back/news/edit.php',
            'data' => ['news' => $news, 'id' => $id]
        ];
    }

    public function newsDelete($id) {
        $this->newsModel->delete($id);
        header('Location: index.php?page=admin&action=news-list');
        exit;
    }

    public function usersList() {
        $users = $this->userModel->getAll();
        return [
            'view' => 'back/users/list.php',
            'data' => ['users' => $users]
        ];
    }
}


<?php
require_once __DIR__ . '/../models/News.php';
require_once __DIR__ . '/../models/User.php';

class FrontController {
    private $newsModel;
    private $userModel;

    public function __construct() {
        $this->newsModel = new News();
        $this->userModel = new User();
    }

    public function home() {
        $pageNum = (int)($_GET['p'] ?? 1);  // Utilisez 'p' pour la pagination, pas 'page'
        $limit = 10;
        $offset = ($pageNum - 1) * $limit;
        
        $news = $this->newsModel->getAll($limit, $offset);
        
        return [
            'view' => 'front/home.php',
            'data' => ['news' => $news, 'page' => $pageNum]
        ];
    }

    public function showNews($id) {
        $news = $this->newsModel->getById($id);
        
        if (!$news) {
            return [
                'view' => 'errors/404.php',
                'data' => ['message' => 'Article non trouvé']
            ];
        }
        
        $this->newsModel->incrementViews($id);
        
        return [
            'view' => 'front/news/show.php',
            'data' => ['news' => $news]
        ];
    }

    public function newsByCategory($category) {
        $pageNum = (int)($_GET['p'] ?? 1);
        $limit = 10;
        $offset = ($pageNum - 1) * $limit;
        
        $news = $this->newsModel->getByCategory($category, $limit, $offset);
        
        return [
            'view' => 'front/category.php',
            'data' => ['news' => $news, 'category' => $category, 'page' => $pageNum]
        ];
    }

    public function search() {
        $query = $_GET['q'] ?? '';
        
        if (strlen($query) < 2) {
            return [
                'view' => 'front/search.php',
                'data' => ['news' => [], 'query' => $query, 'error' => 'Minimum 2 caractères']
            ];
        }
        
        $pageNum = (int)($_GET['p'] ?? 1);
        $limit = 10;
        $offset = ($pageNum - 1) * $limit;
        
        $news = $this->newsModel->search($query, $limit, $offset);
        
        return [
            'view' => 'front/search.php',
            'data' => ['news' => $news, 'query' => $query, 'page' => $pageNum]
        ];
    }

    public function about() {
        return [
            'view' => 'front/about.php',
            'data' => []
        ];
    }

    public function contact() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $message = $_POST['message'] ?? '';

            if (empty($name) || empty($email) || empty($message)) {
                return [
                    'view' => 'front/contact.php',
                    'data' => ['error' => 'Tous les champs sont requis']
                ];
            }

            // Envoyer l'email ici
            $to = 'contact@irannews.local';
            $subject = 'Nouveau message de: ' . $name;
            $headers = "From: " . $email . "\r\n";
            mail($to, $subject, $message, $headers);

            return [
                'view' => 'front/contact.php',
                'data' => ['success' => 'Message envoyé avec succès']
            ];
        }

        return [
            'view' => 'front/contact.php',
            'data' => []
        ];
    }
}

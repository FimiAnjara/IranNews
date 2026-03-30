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
            'data' => [
                'news' => $news,
                'page' => $pageNum,
                'page_title' => 'Actualités Iran - IranNews',
                'meta_description' => 'Actualités en temps réel sur la situation en Iran. Analyses, reportages et chronologies détaillées des événements géopolitiques.'
            ]
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
        
        // Extraire un résumé du contenu pour la meta description
        $excerpt = strip_tags($news['description'] ?? $news['content']);
        $excerpt = substr($excerpt, 0, 160);
        
        return [
            'view' => 'front/news/show.php',
            'data' => [
                'news' => $news,
                'page_title' => htmlspecialchars($news['title']) . ' - IranNews',
                'meta_description' => htmlspecialchars($excerpt)
            ]
        ];
    }

    public function newsByCategory($category) {
        $pageNum = (int)($_GET['p'] ?? 1);
        $limit = 10;
        $offset = ($pageNum - 1) * $limit;
        
        $news = $this->newsModel->getByCategory($category, $limit, $offset);
        
        return [
            'view' => 'front/category.php',
            'data' => [
                'news' => $news,
                'category' => $category,
                'page' => $pageNum,
                'page_title' => 'Catégorie: ' . htmlspecialchars($category) . ' - IranNews',
                'meta_description' => 'Articles et actualités dans la catégorie ' . htmlspecialchars($category) . ' sur la situation en Iran.'
            ]
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
            'data' => [
                'news' => $news,
                'query' => $query,
                'page' => $pageNum,
                'page_title' => 'Recherche: ' . htmlspecialchars($query) . ' - IranNews',
                'meta_description' => 'Résultats de recherche pour "' . htmlspecialchars($query) . '" sur IranNews.'
            ]
        ];
    }

    public function about() {
        return [
            'view' => 'front/about.php',
            'data' => [
                'page_title' => 'À propos - IranNews',
                'meta_description' => 'En savoir plus sur IranNews, notre mission, notre équipe et nos valeurs éditoriales.'
            ]
        ];
    }

    public function contact() {
        $defaultMeta = [
            'page_title' => 'Contact - IranNews',
            'meta_description' => 'Contactez-nous pour toute question, suggestion ou collaboration.'
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $message = $_POST['message'] ?? '';

            if (empty($name) || empty($email) || empty($message)) {
                return [
                    'view' => 'front/contact.php',
                    'data' => array_merge($defaultMeta, [
                        'error' => 'Tous les champs sont requis',
                        'suppress_global_alert' => true
                    ])
                ];
            }

            // Envoyer l'email ici
            $to = 'contact@irannews.local';
            $subject = 'Nouveau message de: ' . $name;
            $headers = "From: " . $email . "\r\n";
            mail($to, $subject, $message, $headers);

            return [
                'view' => 'front/contact.php',
                'data' => array_merge($defaultMeta, [
                    'success' => 'Message envoyé avec succès',
                    'suppress_global_alert' => true
                ])
            ];
        }

        return [
            'view' => 'front/contact.php',
            'data' => array_merge($defaultMeta, ['suppress_global_alert' => true])
        ];
    }
}

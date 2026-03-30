<?php
require_once __DIR__ . '/../models/News.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Category.php';

class FrontController {
    private $newsModel;
    private $userModel;

    public function __construct() {
        $this->newsModel = new News();
        $this->userModel = new User();
    }

    public function home() {
        $categoryModel = new Category();
        $categories = $categoryModel->getMenuCategories();
        $categorySections = [];

        foreach ($categories as $category) {
            $categoryKey = $category['slug'] ?? $category['name'] ?? '';
            if ($categoryKey === '') {
                continue;
            }

            $articles = $this->newsModel->getByCategory($categoryKey, 5, 0);
            if (empty($articles)) {
                continue;
            }

            foreach ($articles as &$article) {
                $article['image'] = $this->newsModel->getFirstImage($article['id']);
            }
            unset($article);

            $categorySections[] = [
                'category' => $category,
                'articles' => $articles,
            ];
        }

        return [
            'view' => 'front/home.php',
            'data' => [
                'category_sections' => $categorySections,
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
        
        $recentPosts = $this->newsModel->getRecent(5, $id);
        foreach ($recentPosts as &$recent) {
            $recent['image'] = $this->newsModel->getFirstImage($recent['id']);
        }
        unset($recent);

        $relatedNews = [];
        if (!empty($news['category_id'])) {
            $relatedNews = $this->newsModel->getRecentByCategoryId($news['category_id'], 6, $id);
            foreach ($relatedNews as &$related) {
                $related['image'] = $this->newsModel->getFirstImage($related['id']);
            }
            unset($related);
        }

        // Extraire un résumé du contenu pour la meta description
        $excerpt = strip_tags($news['description'] ?? $news['content']);
        $excerpt = substr($excerpt, 0, 160);
        
        return [
            'view' => 'front/news/show.php',
            'data' => [
                'news' => $news,
                'recent_posts' => $recentPosts,
                'related_news' => $relatedNews,
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
                'page_title' =>  htmlspecialchars($category) . ' - IranNews',
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

        foreach ($news as &$article) {
            $article['image'] = $this->newsModel->getFirstImage($article['id']);
        }
        unset($article);
        
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

    /**
     * API AJAX : Récupère les articles filtrés par catégorie avec pagination
     */
    public function getFilteredNews() {
        header('Content-Type: application/json; charset=utf-8');

        $categoryId = $_GET['category'] ?? null;
        $page = (int)($_GET['p'] ?? 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $news = [];

        // Si pas de catégorie spécifiée ou catégorie = "all", récupérer tous les articles
        if (empty($categoryId) || $categoryId === 'all') {
            $news = $this->newsModel->getAll($limit, $offset);
        } else {
            // Sinon, récupérer par catégorie
            $news = $this->newsModel->getByCategory($categoryId, $limit, $offset);
        }

        // Récupérer les images pour chaque article
        foreach ($news as &$article) {
            $article['image'] = $this->newsModel->getFirstImage($article['id']);
        }

        return [
            'success' => true,
            'data' => $news
        ];
    }

    /**
     * API AJAX : Récupère toutes les catégories avec le nombre d'articles
     */
    public function getCategories() {
        require_once __DIR__ . '/../models/Category.php';
        header('Content-Type: application/json; charset=utf-8');

        $categoryModel = new Category();

        // Limiter à 10 catégories pour l'affichage initial (optimisation)
        $categories = $categoryModel->getAllWithArticleCount(10);

        // Nombre total d'articles publiés
        $this->newsModel = new News();
        $globalCount = $this->newsModel->countAll();

        return [
            'success' => true,
            'data' => [
                'categories' => $categories,
                'total_count' => $globalCount,
                'category_count' => count($categories)
            ]
        ];
    }
}

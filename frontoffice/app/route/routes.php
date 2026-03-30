<?php

require_once __DIR__ . '/../controllers/FrontController.php';
require_once __DIR__ . '/../models/Category.php';

// Les parametres GET sont geres par .htaccess
$page = $_GET['page'] ?? 'accueil';
$id = $_GET['id'] ?? null;
$category = $_GET['category'] ?? null;
$q = $_GET['q'] ?? null;

try {
    $view = null;
    $data = [];

    switch ($page) {
        // Pages publiques
        case 'accueil':
        case 'home':
            $controller = new FrontController();
            $result = $controller->home();
            $view = $result['view'];
            $data = $result['data'];
            break;

        case 'article':
        case 'news':
            if (!$id) {
                throw new Exception('ID article manquant');
            }
            $controller = new FrontController();
            $result = $controller->showNews($id);
            $view = $result['view'];
            $data = $result['data'];
            break;

        case 'recherche':
        case 'search':
            $controller = new FrontController();
            $result = $controller->search();
            $view = $result['view'];
            $data = $result['data'];
            break;

        case 'categorie':
        case 'category':
            if (!$category) {
                throw new Exception('Categorie manquante');
            }
            $controller = new FrontController();
            $result = $controller->newsByCategory($category);
            $view = $result['view'];
            $data = $result['data'];
            break;

        case 'a-propos':
        case 'about':
            $controller = new FrontController();
            $result = $controller->about();
            $view = $result['view'];
            $data = $result['data'];
            break;

        case 'contact':
            $controller = new FrontController();
            $result = $controller->contact();
            $view = $result['view'];
            $data = $result['data'];
            break;

        // API endpoints
        case 'api-news':
            header('Content-Type: application/json; charset=utf-8');
            $controller = new FrontController();
            $result = $controller->getFilteredNews();
            echo json_encode($result);
            exit;

        case 'api-categories':
            header('Content-Type: application/json; charset=utf-8');
            $controller = new FrontController();
            $result = $controller->getCategories();
            echo json_encode($result);
            exit;

        // Auth (rediriger vers le backoffice)
        case 'connexion':
        case 'login':
        case 'inscription':
        case 'register':
            header('Location: ' . backUrl('connexion'));
            exit;

        case 'deconnexion':
        case 'logout':
            header('Location: ' . backUrl('deconnexion'));
            exit;

        // SEO
        case 'sitemap':
        case 'sitemap.xml':
            header('Location: /sitemap.php');
            exit;

        case 'robots':
        case 'robots.txt':
            break;

        // Page non trouvee
        default:
            throw new Exception('Page non trouvee: ' . htmlspecialchars($page));
    }

    // Charger la vue
    if ($view && file_exists(__DIR__ . '/../views/' . $view)) {
        if (!is_array($data)) {
            $data = [];
        }

        $categoryModel = new Category();
        $data['nav_categories'] = $categoryModel->getMenuCategories();

        extract($data);

        // Charger la vue dans un buffer
        ob_start();
        require __DIR__ . '/../views/' . $view;
        $viewContent = ob_get_clean();

        // Charger le layout principal avec le contenu de la vue
        include __DIR__ . '/../views/layouts/app.php';
    } else {
        throw new Exception('Vue non trouvee: ' . $view);
    }

} catch (Exception $e) {
    $error = $e->getMessage();
    require __DIR__ . '/../views/errors/500.php';
}

?>

<?php

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/BackController.php';
require_once __DIR__ . '/../controllers/CategoryController.php';

// Les parametres GET sont geres par .htaccess
$page = $_GET['page'] ?? 'admin';
$action = $_GET['action'] ?? null;
$id = $_GET['id'] ?? null;
$category = $_GET['category'] ?? null;
$q = $_GET['q'] ?? null;

try {
    $view = null;
    $data = [];

    switch ($page) {
        // Rediriger vers le site public
        case 'accueil':
        case 'home':
            header('Location: ' . frontUrl('accueil'));
            exit;

        // Authentification
        case 'connexion':
        case 'login':
            $controller = new AuthController();
            $result = $controller->login();
            $view = $result['view'];
            $data = $result['data'];
            break;

        case 'inscription':
        case 'register':
            $controller = new AuthController();
            $result = $controller->register();
            $view = $result['view'];
            $data = $result['data'];
            break;

        case 'deconnexion':
        case 'logout':
            $controller = new AuthController();
            $controller->logout();
            exit;

        // Administration
        case 'admin':
            $controller = new BackController();

            if ($action === 'dashboard' || empty($action)) {
                $result = $controller->dashboard();
            } elseif ($action === 'news-list') {
                $result = $controller->newsList();
            } elseif (strpos($action, 'news-show-') === 0) {
                $newsId = (int)str_replace('news-show-', '', $action);
                $result = $controller->newsShow($newsId);
            } elseif (strpos($action, 'media-update-') === 0) {
                $mediaId = (int)str_replace('media-update-', '', $action);
                $result = $controller->mediaUpdate($mediaId);
            } elseif (strpos($action, 'media-delete-') === 0) {
                $mediaId = (int)str_replace('media-delete-', '', $action);
                $result = $controller->mediaDelete($mediaId);
            } elseif ($action === 'news-create') {
                $result = $controller->newsCreate();
            } elseif (strpos($action, 'news-edit-') === 0) {
                $newsId = (int)str_replace('news-edit-', '', $action);
                $result = $controller->newsEdit($newsId);
            } elseif (strpos($action, 'news-delete-') === 0) {
                $newsId = (int)str_replace('news-delete-', '', $action);
                $result = $controller->newsDelete($newsId);
            } elseif (strpos($action, 'news-toggle-publish-') === 0) {
                $newsId = (int)str_replace('news-toggle-publish-', '', $action);
                $result = $controller->newsTogglePublish($newsId);
            } elseif ($action === 'users-list') {
                $result = $controller->usersList();
            } elseif ($action === 'categories-list') {
                $catController = new CategoryController();
                $result = $catController->list();
            } elseif ($action === 'categories-create') {
                $catController = new CategoryController();
                $result = $catController->create();
            } elseif (strpos($action, 'categories-edit-') === 0) {
                $catId = (int)str_replace('categories-edit-', '', $action);
                $catController = new CategoryController();
                $result = $catController->edit($catId);
            } elseif (strpos($action, 'categories-delete-') === 0) {
                $catId = (int)str_replace('categories-delete-', '', $action);
                $catController = new CategoryController();
                $result = $catController->delete($catId);
            } else {
                $result = $controller->dashboard();
            }
            $view = $result['view'];
            $data = $result['data'];
            break;

        // Page non trouvee
        default:
            throw new Exception('Page non trouvee: ' . htmlspecialchars($page));
    }

    // Charger la vue
    if ($view && file_exists(__DIR__ . '/../views/' . $view)) {
        extract($data);

        // Charger la vue dans un buffer
        ob_start();
        require __DIR__ . '/../views/' . $view;
        $viewContent = ob_get_clean();

        // Charger le layout backoffice avec le contenu de la vue
        include __DIR__ . '/../views/layouts/back.php';
    } else {
        throw new Exception('Vue non trouvee: ' . $view);
    }

} catch (Exception $e) {
    $error = $e->getMessage();
    require __DIR__ . '/../views/errors/500.php';
}

?>

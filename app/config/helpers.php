<?php
/**
 * Fonctions d'aide globales
 * Chargées au démarrage de l'application
 */

/**
 * Génère une URL reécrite par .htaccess
 * 
 * @param string $page La page (accueil, article, connexion, etc.)
 * @param array $params Paramètres supplémentaires
 * @return string URL complète
 */
function url($page = '', $params = []) {
    if (empty($page)) {
        return '/';
    }
    
    // URLs reécrites par .htaccess (sans index.php)
    return '/' . urlencode($page);
}

/**
 * Génère une URL pour un article
 * URL: /article-{id} ou /article-{id}-{slug}
 * 
 * @param int $id L'ID de l'article
 * @param string|null $slug Le slug de l'article (optionnel)
 * @return string URL vers l'article
 */
function articleUrl($id, $slug = null) {
    if (!empty($slug)) {
        return '/article-' . (int)$id . '-' . slugify($slug);
    }
    return '/article-' . (int)$id;
}

/**
 * Génère une URL pour une catégorie
 * URL: /categorie-{category}
 * 
 * @param string $category Le nom ou slug de la catégorie
 * @return string URL vers la catégorie
 */
function categoryUrl($category) {
    return '/categorie-' . slugify($category);
}

/**
 * Génère une URL pour la recherche
 * URL: /recherche-{query}
 * 
 * @param string $query Le terme de recherche
 * @return string URL de recherche
 */
function searchUrl($query) {
    return '/recherche-' . urlencode($query);
}

/**
 * Génère une URL pour l'admin
 * URL: /admin-{action} ou /admin-{action}-{id}
 * 
 * @param string $action L'action admin (dashboard, news-list, news-edit, etc.)
 * @param int|null $id L'ID optionnel
 * @return string URL admin
 */
function adminUrl($action = 'dashboard', $id = null) {
    $url = '/admin-' . slugify($action);
    if ($id !== null) {
        $url .= '-' . (int)$id;
    }
    return $url;
}

/**
 * Effectue une redirection
 * 
 * @param string $url L'URL de redirection
 * @return void
 */
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

/**
 * Échappe une chaîne HTML
 * 
 * @param string $str La chaîne à échapper
 * @return string La chaîne échappée
 */
function e($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

/**
 * Alias pour e() - Raccourci pour htmlspecialchars
 * 
 * @param string $str La chaîne à échapper
 * @return string La chaîne échappée
 */
function h($str) {
    return e($str);
}

/**
 * Raccourci pour htmlspecialchars
 * 
 * @param string $str La chaîne à échapper
 * @return string La chaîne échappée
 */
function escapeHtml($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

/**
 * Formate une date en français
 * 
 * @param string $date La date au format MySQL
 * @param string $format Le format de sortie
 * @return string La date formatée
 */
function formatDate($date, $format = 'd M Y') {
    return date($format, strtotime($date));
}

/**
 * Retourne le temps écoulé depuis une date (ex: "Il y a 2 jours")
 * 
 * @param string $date La date au format MySQL
 * @return string Le temps écoulé en français
 */
function timeAgo($date) {
    $timestamp = strtotime($date);
    $diff = time() - $timestamp;
    
    if ($diff < 60) {
        return "À l'instant";
    } elseif ($diff < 3600) {
        $mins = floor($diff / 60);
        return "Il y a " . $mins . " minute" . ($mins > 1 ? "s" : "");
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return "Il y a " . $hours . " heure" . ($hours > 1 ? "s" : "");
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return "Il y a " . $days . " jour" . ($days > 1 ? "s" : "");
    } else {
        return formatDate($date);
    }
}

/**
 * Limite une chaîne à un nombre de caractères
 * 
 * @param string $str La chaîne à limiter
 * @param int $length Le nombre de caractères max
 * @param string $suffix Le suffixe (ex: "...")
 * @return string La chaîne limitée
 */
function truncate($str, $length = 100, $suffix = '...') {
    if (strlen($str) <= $length) {
        return $str;
    }
    
    return substr($str, 0, $length) . $suffix;
}

/**
 * Génère un slug à partir d'une chaîne
 * 
 * @param string $str La chaîne
 * @return string Le slug
 */
function slugify($str) {
    $str = strtolower($str);
    $str = preg_replace('/[^a-z0-9-]+/', '-', $str);
    $str = preg_replace('/-+/', '-', $str);
    return trim($str, '-');
}

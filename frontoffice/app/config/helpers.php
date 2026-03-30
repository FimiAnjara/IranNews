<?php
/**
 * Fonctions d'aide globales
 * Chargees au demarrage de l'application
 */

/**
 * Construit une URL complete a partir d'une base et d'un chemin.
 *
 * @param string $base Base URL (ex: https://example.com:8050)
 * @param string $path Chemin (ex: /accueil)
 * @return string
 */
function buildUrl($base, $path) {
    $base = rtrim((string)$base, '/');
    if ($base === '') {
        return $path;
    }
    return $base . $path;
}

/**
 * Genere une URL reecrite par .htaccess
 *
 * @param string $page La page (accueil, article, connexion, etc.)
 * @param array $params Parametres supplementaires
 * @return string URL complete
 */
function url($page = '', $params = []) {
    $base = defined('APP_BASE_URL') ? APP_BASE_URL : '';
    $path = empty($page) ? '/' : '/' . urlencode($page);

    return buildUrl($base, $path);
}

/**
 * Genere une URL pour un article
 * URL: /article-{id} ou /article-{id}-{slug}
 *
 * @param int $id L'ID de l'article
 * @param string|null $slug Le slug de l'article (optionnel)
 * @return string URL vers l'article
 */
function articleUrl($id, $slug = null) {
    $base = defined('APP_BASE_URL') ? APP_BASE_URL : '';
    if (!empty($slug)) {
        return buildUrl($base, '/article-' . (int)$id . '-' . slugify($slug));
    }
    return buildUrl($base, '/article-' . (int)$id);
}

/**
 * Genere une URL pour une categorie
 * URL: /categorie-{category}
 *
 * @param string $category Le nom ou slug de la categorie
 * @return string URL vers la categorie
 */
function categoryUrl($category) {
    $base = defined('APP_BASE_URL') ? APP_BASE_URL : '';
    return buildUrl($base, '/' . slugify($category));
}

/**
 * Genere une URL pour la recherche
 * URL: /recherche-{query}
 *
 * @param string $query Le terme de recherche
 * @return string URL de recherche
 */
function searchUrl($query) {
    $base = defined('APP_BASE_URL') ? APP_BASE_URL : '';
    return buildUrl($base, '/recherche-' . urlencode($query));
}

/**
 * Genere une URL pour l'admin
 * URL: /admin-{action} ou /admin-{action}-{id}
 *
 * @param string $action L'action admin (dashboard, news-list, news-edit, etc.)
 * @param int|null $id L'ID optionnel
 * @return string URL admin
 */
function adminUrl($action = 'dashboard', $id = null) {
    $base = defined('ADMIN_BASE_URL') ? ADMIN_BASE_URL : (defined('BACK_BASE_URL') ? BACK_BASE_URL : '');
    $url = '/admin-' . slugify($action);
    if ($id !== null) {
        $url .= '-' . (int)$id;
    }
    return buildUrl($base, $url);
}

/**
 * Genere une URL vers le frontoffice
 *
 * @param string $page La page (accueil, article, etc.)
 * @return string URL complete
 */
function frontUrl($page = '') {
    $base = defined('FRONT_BASE_URL') ? FRONT_BASE_URL : '';
    $path = empty($page) ? '/' : '/' . urlencode($page);
    return buildUrl($base, $path);
}

/**
 * Genere une URL vers le backoffice
 *
 * @param string $page La page (connexion, deconnexion, etc.)
 * @return string URL complete
 */
function backUrl($page = '') {
    $base = defined('BACK_BASE_URL') ? BACK_BASE_URL : '';
    $path = empty($page) ? '/' : '/' . urlencode($page);
    return buildUrl($base, $path);
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
 * Echappe une chaine HTML
 *
 * @param string $str La chaine a echapper
 * @return string La chaine echappee
 */
function e($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

/**
 * Alias pour e() - Raccourci pour htmlspecialchars
 *
 * @param string $str La chaine a echapper
 * @return string La chaine echappee
 */
function h($str) {
    return e($str);
}

/**
 * Raccourci pour htmlspecialchars
 *
 * @param string $str La chaine a echapper
 * @return string La chaine echappee
 */
function escapeHtml($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

/**
 * Formate une date en francais
 *
 * @param string $date La date au format MySQL
 * @param string $format Le format de sortie
 * @return string La date formatee
 */
function formatDate($date, $format = 'd M Y') {
    return date($format, strtotime($date));
}

/**
 * Retourne le temps ecoule depuis une date (ex: "Il y a 2 jours")
 *
 * @param string $date La date au format MySQL
 * @return string Le temps ecoule en francais
 */
function timeAgo($date) {
    $timestamp = strtotime($date);
    $diff = time() - $timestamp;

    if ($diff < 60) {
        return "A l'instant";
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
 * Limite une chaine a un nombre de caracteres
 *
 * @param string $str La chaine a limiter
 * @param int $length Le nombre de caracteres max
 * @param string $suffix Le suffixe (ex: "...")
 * @return string La chaine limitee
 */
function truncate($str, $length = 100, $suffix = '...') {
    if (strlen($str) <= $length) {
        return $str;
    }

    return substr($str, 0, $length) . $suffix;
}

/**
 * Genere un slug a partir d'une chaine
 *
 * @param string $str La chaine
 * @return string Le slug
 */
function slugify($str) {
    $str = strtolower($str);
    $str = preg_replace('/[^a-z0-9-]+/', '-', $str);
    $str = preg_replace('/-+/', '-', $str);
    return trim($str, '-');
}

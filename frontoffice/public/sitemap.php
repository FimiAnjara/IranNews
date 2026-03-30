<?php
// Générer sitemap.xml dynamiquement

require_once __DIR__ . '/../app/config/bootstrap.php';
require_once __DIR__ . '/../app/models/News.php';
require_once __DIR__ . '/../app/models/Category.php';

header('Content-Type: application/xml; charset=utf-8');
header('Cache-Control: public, max-age=86400');

$newsModel = new News();
$categoryModel = new Category();

// Récupérer les articles publiés
$articles = $newsModel->getAll(1000);

// Récupérer les catégories
$categories = $categoryModel->getAll();

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// URL de base
$frontBaseUrl = getenv('FRONT_BASE_URL') ?: 'http://localhost:8050';
$baseUrl = rtrim($frontBaseUrl, '/');

// Page d'accueil
echo '  <url>' . "\n";
echo '    <loc>' . htmlspecialchars($baseUrl . '/accueil') . '</loc>' . "\n";
echo '    <lastmod>' . date('Y-m-d') . '</lastmod>' . "\n";
echo '    <changefreq>daily</changefreq>' . "\n";
echo '    <priority>1.0</priority>' . "\n";
echo '  </url>' . "\n";

// Pages statiques
$staticPages = [
    '/a-propos' => ['changefreq' => 'monthly', 'priority' => '0.6'],
    '/contact' => ['changefreq' => 'monthly', 'priority' => '0.5'],
];

foreach ($staticPages as $page => $meta) {
    echo '  <url>' . "\n";
    echo '    <loc>' . htmlspecialchars($baseUrl . $page) . '</loc>' . "\n";
    echo '    <changefreq>' . $meta['changefreq'] . '</changefreq>' . "\n";
    echo '    <priority>' . $meta['priority'] . '</priority>' . "\n";
    echo '  </url>' . "\n";
}

// Articles
if (!empty($articles)) {
    foreach ($articles as $article) {
        $url = htmlspecialchars($baseUrl . '/article-' . $article['id'] . '-' . $article['slug']);
        $lastmod = date('Y-m-d', strtotime($article['updated_at'] ?? $article['published_at'] ?? $article['created_at']));
        $priority = ($article['etat'] == 1) ? '0.8' : '0'; // Exclure les brouillons
        
        if ($article['etat'] == 1) {
            echo '  <url>' . "\n";
            echo '    <loc>' . $url . '</loc>' . "\n";
            echo '    <lastmod>' . $lastmod . '</lastmod>' . "\n";
            echo '    <changefreq>weekly</changefreq>' . "\n";
            echo '    <priority>' . $priority . '</priority>' . "\n";
            echo '  </url>' . "\n";
        }
    }
}

// Catégories
if (!empty($categories)) {
    foreach ($categories as $category) {
        echo '  <url>' . "\n";
        echo '    <loc>' . htmlspecialchars($baseUrl . '/categorie-' . $category['slug']) . '</loc>' . "\n";
        echo '    <changefreq>weekly</changefreq>' . "\n";
        echo '    <priority>0.7</priority>' . "\n";
        echo '  </url>' . "\n";
    }
}

echo '</urlset>' . "\n";
?>
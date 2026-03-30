<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($data['meta_description'] ?? 'Actualités en temps réel sur la situation en Iran. Analyses, reportages et chronologies détaillées.'); ?>">
    <meta name="keywords" content="iran, actualités, géopolitique, moyen-orient">
    <meta name="author" content="IranNews">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo htmlspecialchars($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>">
    
    <title><?php echo htmlspecialchars($data['page_title'] ?? APP_NAME . ' - Actualités Iran'); ?></title>
    
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <a href="<?php echo url('accueil'); ?>"><?php echo APP_NAME; ?></a>
            </div>
            <ul class="navbar-menu">
                <li><a href="<?php echo url('accueil'); ?>">Accueil</a></li>
                <li><a href="<?php echo url('a-propos'); ?>">À propos</a></li>
                <li><a href="<?php echo url('contact'); ?>">Contact</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Dropdown Gestion des Articles -->
                    <li class="dropdown">
                        <span class="dropdown-toggle">Articles</span>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo adminUrl('news-list'); ?>">Gestion des articles</a></li>
                            <li><a href="<?php echo adminUrl('news-create'); ?>">Créer un article</a></li>
                        </ul>
                    </li>
                    
                    <!-- Dropdown Gestion des Catégories -->
                    <li class="dropdown">
                        <span class="dropdown-toggle">Catégories</span>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo adminUrl('categories-list'); ?>">Gestion des catégories</a></li>
                            <li><a href="<?php echo adminUrl('categories-create'); ?>">Créer une catégorie</a></li>
                        </ul>
                    </li>
                    
                    <li><a href="<?php echo backUrl('deconnexion'); ?>">Déconnexion</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Search Bar -->
    <div class="search-bar">
        <div class="container">
            <form method="GET" action="/recherche" onsubmit="this.action = '/recherche-' + document.querySelector('input[name=q]').value; return true;">
                <input type="text" name="q" placeholder="Rechercher un article..." required>
                <button type="submit">Rechercher</button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container">
        <?php if (isset($data['error'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($data['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($data['success'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($data['success']); ?>
            </div>
        <?php endif; ?>

        <?php echo $viewContent ?? ''; ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="/assets/js/script.js"></script>
</body>
</html>

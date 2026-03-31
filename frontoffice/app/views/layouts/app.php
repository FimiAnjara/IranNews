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
    <nav class="navbar" role="navigation" aria-label="Navigation principale">
        <div class="container">
            <div class="navbar-brand">
                <a href="<?php echo url(''); ?>"><?php echo APP_NAME; ?></a>
            </div>

            <!-- Desktop Menu -->
            <ul class="navbar-menu">
                <li><a href="<?php echo url(''); ?>">Accueil</a></li>
                <?php if (!empty($data['nav_categories']) && is_array($data['nav_categories'])): ?>
                    <?php
                        $visibleCategories = array_slice($data['nav_categories'], 0, 3);
                        $hiddenCategories = array_slice($data['nav_categories'], 3);
                    ?>
                    <?php foreach ($visibleCategories as $category): ?>
                        <li>
                            <a href="<?php echo categoryUrl($category['slug'] ?? $category['name'] ?? ''); ?>">
                                <?php echo htmlspecialchars($category['name'] ?? ''); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>

                    <?php if (!empty($hiddenCategories)): ?>
                        <li class="dropdown">
                            <button class="dropdown-toggle" type="button" aria-label="Plus de catégories">
                                <span class="menu-burger"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <?php foreach ($hiddenCategories as $category): ?>
                                    <li>
                                        <a href="<?php echo categoryUrl($category['slug'] ?? $category['name'] ?? ''); ?>">
                                            <?php echo htmlspecialchars($category['name'] ?? ''); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <li>
                    <button class="search-toggle" type="button" aria-controls="searchBar" aria-expanded="false">
                        <span class="search-icon">🔍</span>
                    </button>
                </li>
            </ul>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" type="button" aria-label="Menu mobile" aria-expanded="false">
                <span class="menu-burger"></span>
            </button>

            <!-- Mobile Search Icon -->
            <button class="mobile-search-toggle" type="button" aria-controls="searchBar" aria-expanded="false" aria-label="Recherche">
                <span class="search-icon">🔍</span>
            </button>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <ul class="mobile-menu-list">
            <li><a href="<?php echo url(''); ?>">Accueil</a></li>
            <?php if (!empty($data['nav_categories']) && is_array($data['nav_categories'])): ?>
                <?php foreach ($data['nav_categories'] as $category): ?>
                    <li>
                        <a href="<?php echo categoryUrl($category['slug'] ?? $category['name'] ?? ''); ?>">
                            <?php echo htmlspecialchars($category['name'] ?? ''); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Search Bar -->
    <div class="search-bar" id="searchBar">
        <div class="container">
            <form method="GET" action="/recherche" onsubmit="this.action = '/recherche-' + document.querySelector('input[name=q]').value; return true;">
                <input type="text" name="q" placeholder="Rechercher un article..." required>
                <button type="submit">Rechercher</button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container" class="container" role="main">
        <?php if (empty($data['suppress_global_alert'])): ?>
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
        <?php endif; ?>

        <?php echo $viewContent ?? ''; ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="/assets/js/script.js" defer></script>
</body>
</html>

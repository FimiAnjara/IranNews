<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($data['meta_description'] ?? 'Actualités en temps réel sur la situation en Iran. Analyses, reportages et chronologies détaillées.'); ?>">
    <meta name="keywords" content="iran, actualités, géopolitique, moyen-orient">
    <meta name="author" content="WarNews">
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
                    <button class="search-toggle" type="button" aria-controls="searchBar" aria-expanded="false" aria-label="Rechercher">
                        <span class="search-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg></span>
                    </button>
                </li>
            </ul>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" type="button" aria-label="Menu mobile" aria-expanded="false">
                <span class="menu-burger"></span>
            </button>

            <!-- Mobile Search Icon -->
            <button class="mobile-search-toggle" type="button" aria-controls="searchBar" aria-expanded="false" aria-label="Recherche">
                <span class="search-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg></span>
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
            <form method="GET" action="/recherche" id="searchForm" onsubmit="return validateSearch();">
                <input type="text" name="q" id="searchInput" placeholder="Rechercher un article..." required>
                <button type="submit">Rechercher</button>
            </form>
        </div>
    </div>

    <script>
        function validateSearch() {
            const query = document.getElementById('searchInput').value.trim();
            if (query.length > 100) {
                alert('Attention : La recherche ne doit pas dépasser 100 caractères.');
                return false;
            }
            document.getElementById('searchForm').action = '/recherche-' + query;
            return true;
        }
    </script>

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
            <div class="footer-grid">
                <!-- Colonne 1: À propos -->
                <div class="footer-column">
                    <h4 class="footer-title"><?php echo APP_NAME; ?></h4>
                    <p class="footer-description">Votre source fiable pour les actualités, analyses et reportages sur la situation en Iran et le Moyen-Orient.</p>
                    <div class="footer-socials">
                        <a href="#" class="social-link" aria-label="Facebook" title="Facebook">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-link" aria-label="Twitter" title="Twitter">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M23.953 4.57a10 10 0 002.856-3.915 10 10 0 01-2.837.856c1.012-.604 1.785-1.559 2.15-2.703-.949.564-2.002.974-3.127 1.195a4.948 4.948 0 00-8.506 4.513A14.025 14.025 0 011.671 3.149a4.947 4.947 0 001.523 6.573 4.914 4.914 0 01-2.24-.616v.06a4.95 4.95 0 003.97 4.847 4.996 4.996 0 01-2.212.085 4.95 4.95 0 004.604 3.417A9.868 9.868 0 010 21.54a13.994 13.994 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-link" aria-label="LinkedIn" title="LinkedIn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.225 0z"/>
                            </svg>
                        </a>
                    </div>
                </div>


                <!-- Colonne 3: Catégories -->
                <div class="footer-column">
                    <h4 class="footer-title">Catégories</h4>
                    <ul class="footer-links">
                        <?php 
                        $categories = $data['nav_categories'] ?? [];
                        $limitedCategories = array_slice($categories, 0, 5);
                        foreach ($limitedCategories as $cat): 
                        ?>
                            <li>
                                <a href="<?php echo categoryUrl($cat['slug'] ?? $cat['name'] ?? ''); ?>">
                                    <?php echo htmlspecialchars($cat['name'] ?? ''); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Colonne 4: Légal -->
                <div class="footer-column">
                    <h4 class="footer-title">Légal</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo url(''); ?>?page=privacy">Confidentialité</a></li>
                        <li><a href="<?php echo url(''); ?>?page=terms">Conditions d'utilisation</a></li>
                        <li><a href="<?php echo url(''); ?>?page=cookies">Cookies</a></li>
                        <li><a href="<?php echo url(''); ?>?page=sitemap">Plan du site</a></li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <p class="footer-copyright">&copy; <?php echo date('Y'); ?> <strong><?php echo APP_NAME; ?></strong>. Tous droits réservés.</p>
                <p class="footer-tagline">Les actualités, analyses et reportages de référence sur le Moyen-Orient</p>
            </div>
        </div>
    </footer>

    <script src="/assets/js/script.js" defer></script>
</body>
</html>

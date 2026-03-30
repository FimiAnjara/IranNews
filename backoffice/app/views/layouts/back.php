<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <link rel="canonical" href="<?php echo htmlspecialchars($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>">

    <title><?php echo htmlspecialchars($data['page_title'] ?? APP_NAME . ' - Administration'); ?></title>

    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <div class="admin-brand">
                <a href="<?php echo adminUrl('dashboard'); ?>"><?php echo APP_NAME; ?> Admin</a>
            </div>
            <nav class="admin-nav">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo adminUrl('dashboard'); ?>">Dashboard</a>

                    <div class="admin-nav-group">
                        <button class="admin-nav-toggle" type="button">Articles</button>
                        <div class="admin-submenu">
                            <a href="<?php echo adminUrl('news-list'); ?>">Liste des articles</a>
                            <a href="<?php echo adminUrl('news-create'); ?>">Ajouter un article</a>
                        </div>
                    </div>

                    <div class="admin-nav-group">
                        <button class="admin-nav-toggle" type="button">Categories</button>
                        <div class="admin-submenu">
                            <a href="<?php echo adminUrl('categories-list'); ?>">Liste des categories</a>
                            <a href="<?php echo adminUrl('categories-create'); ?>">Ajouter une categorie</a>
                        </div>
                    </div>

                    <a href="<?php echo adminUrl('users-list'); ?>">Utilisateurs</a>
                    <a href="<?php echo backUrl('deconnexion'); ?>">Deconnexion</a>
                <?php else: ?>
                    <a href="<?php echo backUrl('connexion'); ?>">Connexion</a>
                <?php endif; ?>
                <a href="<?php echo frontUrl('accueil'); ?>">Retour au site</a>
            </nav>
        </aside>

        <div class="admin-main">
            <header class="admin-topbar">
                <div class="container">
                    <span class="admin-title">Administration</span>
                </div>
            </header>

            <main class="admin-content">
                <div class="container">
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
                </div>
            </main>

            <footer class="admin-footer">
                <div class="container">
                    <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. Administration.</p>
                </div>
            </footer>
        </div>
    </div>

    <script src="/assets/js/script.js"></script>
</body>
</html>

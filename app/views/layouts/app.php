<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
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
                <li><a href="<?php echo categoryUrl('Général'); ?>">Général</a></li>
                <li><a href="<?php echo categoryUrl('Politique'); ?>">Politique</a></li>
                <li><a href="<?php echo url('a-propos'); ?>">À propos</a></li>
                <li><a href="<?php echo url('contact'); ?>">Contact</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="<?php echo adminUrl('dashboard'); ?>">Tableau de bord</a></li>
                    <li><a href="<?php echo url('deconnexion'); ?>">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="<?php echo url('connexion'); ?>">Connexion</a></li>
                    <li><a href="<?php echo url('inscription'); ?>">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Search Bar -->
    <div class="search-bar">
        <div class="container">
            <form method="GET" action="index.php">
                <input type="hidden" name="page" value="recherche">
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

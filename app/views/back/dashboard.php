<div class="admin-dashboard">
    <h1>Tableau de bord</h1>
    
    <div class="admin-menu">
        <ul>
            <li><a href="<?php echo adminUrl('news-list'); ?>">Gestion des articles</a></li>
            <li><a href="<?php echo adminUrl('news-create'); ?>">Créer un article</a></li>
            <li><a href="<?php echo adminUrl('users-list'); ?>">Gestion des utilisateurs</a></li>
        </ul>
    </div>

    <div class="dashboard-content">
        <h2>Bienvenue, <?php echo htmlspecialchars($_SESSION['name'] ?? 'Admin'); ?>!</h2>
        <p>Utilisez le menu pour gérer votre contenu.</p>
    </div>
</div>

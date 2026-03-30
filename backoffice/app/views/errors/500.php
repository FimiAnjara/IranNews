<div class="error-page">
    <h1>Erreur 500</h1>
    <p>Une erreur serveur est survenue.</p>
    <?php if (APP_DEBUG && isset($error)): ?>
        <p class="error-details">
            <strong>Détails:</strong> <?php echo htmlspecialchars($error); ?>
        </p>
    <?php endif; ?>
    <a href="/index.php" class="btn btn-primary">Retour à l'accueil</a>
</div>

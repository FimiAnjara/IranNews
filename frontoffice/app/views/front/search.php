<section class="search-results">
    <h2>Résultats de recherche pour "<?php echo htmlspecialchars($query ?? ''); ?>"</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-warning">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php elseif (empty($news)): ?>
        <p>Aucun article trouvé.</p>
    <?php else: ?>
        <div class="news-grid">
            <?php foreach ($news as $article): ?>
                <article class="news-card">
                    <div class="news-content">
                        <h3>
                            <a href="<?php echo articleUrl($article['id'], $article['slug']); ?>">
                                <?php echo htmlspecialchars($article['title']); ?>
                            </a>
                        </h3>
                        <p class="news-excerpt">
                            <?php echo htmlspecialchars(substr($article['description'] ?? $article['content'], 0, 200) . '...'); ?>
                        </p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="back-link">
        <a href="<?php echo url('accueil'); ?>">← Retour à l'accueil</a>
    </div>
</section>

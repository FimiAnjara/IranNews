<div class="hero">
    <h1>Bienvenue sur <?php echo APP_NAME; ?></h1>
    <p>Actualités de l'Iran en temps réel</p>
</div>

<section class="news-section">
    <h2>Dernières actualités</h2>
    
    <?php if (empty($news)): ?>
        <p>Aucun article disponible pour le moment.</p>
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
                        <p class="news-meta">
                            Par <strong><?php echo htmlspecialchars($article['autor'] ?? 'Admin'); ?></strong>
                            - <?php echo date('d/m/Y', strtotime($article['published_at'] ?? $article['created_at'])); ?>
                        </p>
                        <p class="news-excerpt">
                            <?php echo htmlspecialchars(substr($article['description'] ?? $article['content'], 0, 200) . '...'); ?>
                        </p>
                        <p class="news-stats">
                            <?php if ($article['category_name']): ?>
                                <span class="category"><?php echo htmlspecialchars($article['category_name']); ?></span>
                            <?php endif; ?>
                        </p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="<?php echo url('accueil', ['page' => $page - 1]); ?>">« Précédent</a>
            <?php endif; ?>
            
            <span>Page <?php echo $page; ?></span>
            
            <a href="<?php echo url('accueil', ['page' => $page + 1]); ?>">Suivant »</a>
        </div>
    <?php endif; ?>
</section>

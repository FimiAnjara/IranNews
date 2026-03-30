<section class="category-view">
    <h1> <?php echo htmlspecialchars($category ?? ''); ?></h1>

    <?php if (empty($news)): ?>
        <p>Aucun article dans cette catégorie.</p>
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
</section>

<section class="search-results">
    <div class="search-header">
        <h2>Résultats de recherche pour "<?php echo htmlspecialchars($query ?? ''); ?>"</h2>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-warning">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php elseif (empty($news)): ?>
        <p class="no-articles">Aucun article trouvé.</p>
    <?php else: ?>
        <div class="category-grid">
            <?php foreach ($news as $article): ?>
                <?php
                $img = $article['image'] ?? null;
                $hasImage = !empty($img['url']);
                $articleSlug = !empty($article['slug']) ? '-' . htmlspecialchars($article['slug']) : '';
                $articleUpdated = $article['updated_at'] ?? null;
                $articleUpdateTime = !empty($articleUpdated) ? date('H:i', strtotime($articleUpdated)) : null;
                $articleCategory = htmlspecialchars($article['category_name'] ?? '');
                ?>
                <article class="stream-card<?php echo $hasImage ? '' : ' no-media'; ?>">
                    <div class="stream-content">
                        <p class="kicker"><?php echo $articleCategory; ?></p>
                        <h3><a href="/article-<?php echo htmlspecialchars($article['id']); ?><?php echo $articleSlug; ?>"><?php echo htmlspecialchars($article['title']); ?></a></h3>
                        <p class="story-meta">
                            <span class="author">Par <?php echo htmlspecialchars($article['autor'] ?? 'Admin'); ?></span>
                            <span class="separator">•</span>
                            <time datetime="<?php echo htmlspecialchars($article['published_at'] ?? $article['created_at'] ?? ''); ?>"><?php echo htmlspecialchars(date('d M Y', strtotime($article['published_at'] ?? $article['created_at'] ?? ''))); ?></time>
                            <?php if (!empty($articleUpdateTime)): ?>
                                <span class="separator">•</span>
                                <span class="update-time">Maj <?php echo htmlspecialchars($articleUpdateTime); ?></span>
                            <?php endif; ?>
                        </p>
                        <p class="story-excerpt"><?php echo htmlspecialchars(substr($article['description'] ?? $article['content'] ?? '', 0, 120)); ?>...</p>
                    </div>
                    <?php if ($hasImage): ?>
                        <div class="stream-media">
                            <img src="<?php echo htmlspecialchars($img['url'] ?? ''); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
                        </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

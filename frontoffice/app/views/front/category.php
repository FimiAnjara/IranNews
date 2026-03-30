<section class="category-view">
    <?php
    $categoryTitle = htmlspecialchars($category ?? '');
    if (!empty($news) && !empty($news[0]['category_name'])) {
        $categoryTitle = htmlspecialchars($news[0]['category_name']);
    }
    ?>
    <div class="category-header">
        <h1><?php echo $categoryTitle; ?></h1>
    </div>

    <?php if (empty($news)): ?>
        <p class="no-articles">Aucun article dans cette catégorie.</p>
    <?php else: ?>
        <div class="category-grid">
            <?php foreach ($news as $article): ?>
                <?php
                $img = $article['image'] ?? null;
                $hasImage = !empty($img['url']);
                $articleSlug = !empty($article['slug']) ? '-' . htmlspecialchars($article['slug']) : '';
                $articleUpdated = $article['updated_at'] ?? null;
                $articleUpdateTime = !empty($articleUpdated) ? date('H:i', strtotime($articleUpdated)) : null;
                $articleCategory = htmlspecialchars($article['category_name'] ?? $categoryTitle);
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

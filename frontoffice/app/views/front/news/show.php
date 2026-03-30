<div class="article-layout">
    <?php if (isset($news) && $news): 
        $newsModel = new News();
        $images = $newsModel->getAllImages($news['id']);
    ?>
        <div class="article-main">
            <div class="article-view">
                <!-- Fil d'Ariane -->
                <nav class="breadcrumb">
                    <a href="<?php echo url('accueil'); ?>">Accueil</a>
                    <?php if ($news['category_name'] ?? null): ?>
                        <span>/</span>
                        <a href="<?php echo categoryUrl($news['category_name']); ?>">
                            <?php echo htmlspecialchars($news['category_name']); ?>
                        </a>
                    <?php endif; ?>
                    <span>/</span>
                    <span><?php echo htmlspecialchars($news['title']); ?></span>
                </nav>

                <article class="article-full">
                    <header class="article-header">
                        <h1><?php echo htmlspecialchars($news['title']); ?></h1>
                        
                        <div class="article-meta">
                            <p class="meta-line">
                                <span class="author">Par <strong><?php echo htmlspecialchars($news['autor'] ?? 'Admin'); ?></strong></span>
                                <span class="separator">•</span>
                                <time class="published-date" datetime="<?php echo $news['published_at'] ?? $news['created_at']; ?>">
                                    <?php echo date('d F Y à H:i', strtotime($news['published_at'] ?? $news['created_at'])); ?>
                                </time>
                            </p>
                            <?php if ($news['category_name'] ?? null): ?>
                                <p class="meta-line">
                                    <span class="category-badge"><?php echo htmlspecialchars($news['category_name']); ?></span>
                                </p>
                            <?php endif; ?>
                        </div>
                    </header>

                    <!-- Description comme accroche -->
                    <?php if ($news['description'] ?? null): ?>
                        <div class="article-excerpt">
                            <p class="lead"><?php echo htmlspecialchars($news['description']); ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Galerie d'images si présentes -->
                    <?php if (!empty($images)): ?>
                        <div class="article-gallery">
                            <?php foreach ($images as $image): ?>
                                <div class="gallery-item">
                                    <img src="<?php echo htmlspecialchars($image['url']); ?>" 
                                         alt="<?php echo htmlspecialchars($image['alt_text'] ?? 'Image article'); ?>"
                                         class="gallery-image">
                                    <?php if ($image['alt_text']): ?>
                                        <p class="gallery-caption"><?php echo htmlspecialchars($image['alt_text']); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Contenu HTML rendu (TinyMCE) -->
                    <div class="article-content">
                        <?php echo $news['content']; ?>
                    </div>

                    <footer class="article-footer">
                        <a href="<?php echo url('accueil'); ?>" class="btn btn-secondary">← Retour aux actualités</a>
                    </footer>
                </article>
            </div>

            <?php if (!empty($related_news)): ?>
                <section class="related-section" aria-label="Articles de la meme categorie">
                    <div class="category-header">
                        <h2>Derniers articles de la meme categorie</h2>
                    </div>
                    <div class="category-grid">
                        <?php foreach ($related_news as $article): ?>
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
                </section>
            <?php endif; ?>
        </div>

        <aside class="article-sidebar" aria-label="Articles recents">
            <?php if (!empty($recent_posts)): ?>
                <div class="sidebar-card">
                    <h3>Articles recents</h3>
                    <ul class="recent-list">
                        <?php foreach ($recent_posts as $recent): ?>
                            <?php
                            $recentSlug = !empty($recent['slug']) ? '-' . htmlspecialchars($recent['slug']) : '';
                            $recentImage = $recent['image'] ?? null;
                            ?>
                            <li class="recent-item">
                                <?php if (!empty($recentImage['url'])): ?>
                                    <a class="recent-thumb" href="/article-<?php echo htmlspecialchars($recent['id']); ?><?php echo $recentSlug; ?>">
                                        <img src="<?php echo htmlspecialchars($recentImage['url']); ?>" alt="<?php echo htmlspecialchars($recent['title']); ?>">
                                    </a>
                                <?php endif; ?>
                                <div class="recent-content">
                                    <a href="/article-<?php echo htmlspecialchars($recent['id']); ?><?php echo $recentSlug; ?>">
                                        <?php echo htmlspecialchars($recent['title']); ?>
                                    </a>
                                    <time datetime="<?php echo htmlspecialchars($recent['published_at'] ?? $recent['created_at'] ?? ''); ?>">
                                        <?php echo htmlspecialchars(date('d M Y', strtotime($recent['published_at'] ?? $recent['created_at'] ?? ''))); ?>
                                    </time>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </aside>
    <?php else: ?>
        <p>Article non trouvé.</p>
    <?php endif; ?>
</div>

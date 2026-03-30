<div class="home-wrapper">
    <section class="home-main">
        <div class="home-header-row">
            <div class="hero" role="banner">
                <h1>Actualités sur l'Iran</h1>
                <p>Les dernières informations et analyses en temps réel</p>
            </div>
        </div>

        <section class="news-container" id="newsContainer" aria-live="polite" aria-atomic="true">
            <?php if (!empty($category_sections) && is_array($category_sections)): ?>
                <?php foreach ($category_sections as $section): ?>
                    <?php
                    $category = $section['category'] ?? [];
                    $categoryName = htmlspecialchars($category['name'] ?? '');
                    $categorySlug = $category['slug'] ?? ($category['name'] ?? '');
                    $categoryId = (int)($category['id'] ?? 0);
                    $articles = $section['articles'] ?? [];
                    ?>
                    <?php if (!empty($articles)): ?>
                        <?php if ($categoryId === 1): ?>
                            <?php
                            $featured = $articles[0];
                            $featuredImage = $featured['image'] ?? null;
                            $featuredSlug = !empty($featured['slug']) ? '-' . htmlspecialchars($featured['slug']) : '';
                            $featuredUpdated = $featured['updated_at'] ?? null;
                            $featuredUpdateTime = !empty($featuredUpdated) ? date('H:i', strtotime($featuredUpdated)) : null;
                            $secondary = array_slice($articles, 1);
                            ?>
                            <section class="category-section category-featured" aria-label="Categorie <?php echo $categoryName; ?>">
                                <div class="home-top">
                                    <article class="lead-story">
                                        <div class="lead-content">
                                            <p class="kicker"><?php echo $categoryName; ?></p>
                                            <h2><a href="/article-<?php echo htmlspecialchars($featured['id']); ?><?php echo $featuredSlug; ?>"><?php echo htmlspecialchars($featured['title']); ?></a></h2>
                                            <p class="story-meta">
                                                <span class="author">Par <?php echo htmlspecialchars($featured['autor'] ?? 'Admin'); ?></span>
                                                <span class="separator">•</span>
                                                <time datetime="<?php echo htmlspecialchars($featured['published_at'] ?? $featured['created_at'] ?? ''); ?>"><?php echo htmlspecialchars(date('d F Y', strtotime($featured['published_at'] ?? $featured['created_at'] ?? ''))); ?></time>
                                                <?php if (!empty($featuredUpdateTime)): ?>
                                                    <span class="separator">•</span>
                                                    <span class="update-time">Maj <?php echo htmlspecialchars($featuredUpdateTime); ?></span>
                                                <?php endif; ?>
                                            </p>
                                            <p class="lead-excerpt"><?php echo htmlspecialchars(substr($featured['description'] ?? $featured['content'] ?? '', 0, 260)); ?>...</p>
                                            <a href="/article-<?php echo htmlspecialchars($featured['id']); ?><?php echo $featuredSlug; ?>" class="text-link">Lire l'article</a>
                                        </div>
                                        <?php if (!empty($featuredImage['url'])): ?>
                                            <div class="lead-media">
                                                <img src="<?php echo htmlspecialchars($featuredImage['url']); ?>" alt="<?php echo htmlspecialchars($featured['title']); ?>">
                                            </div>
                                        <?php endif; ?>
                                    </article>

                                    <?php if (!empty($secondary)): ?>
                                        <div class="story-stack">
                                            <p class="section-title">A lire aussi</p>
                                            <?php foreach ($secondary as $article): ?>
                                                <?php $articleSlug = !empty($article['slug']) ? '-' . htmlspecialchars($article['slug']) : ''; ?>
                                                <?php $articleUpdated = $article['updated_at'] ?? null; ?>
                                                <?php $articleUpdateTime = !empty($articleUpdated) ? date('H:i', strtotime($articleUpdated)) : null; ?>
                                                <article class="story-item">
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
                                                </article>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </section>
                        <?php else: ?>
                            <section class="category-section" aria-label="Categorie <?php echo $categoryName; ?>">
                                <div class="category-header">
                                    <h2><?php echo $categoryName; ?></h2>
                                    <a class="text-link" href="<?php echo categoryUrl($categorySlug); ?>">Voir tout</a>
                                </div>
                                <div class="category-grid">
                                    <?php foreach ($articles as $article): ?>
                                        <?php
                                        $img = $article['image'] ?? null;
                                        $hasImage = !empty($img['url']);
                                        $articleSlug = !empty($article['slug']) ? '-' . htmlspecialchars($article['slug']) : '';
                                        $articleUpdated = $article['updated_at'] ?? null;
                                        $articleUpdateTime = !empty($articleUpdated) ? date('H:i', strtotime($articleUpdated)) : null;
                                        $articleCategory = htmlspecialchars($article['category_name'] ?? $categoryName);
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
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-articles">Aucun article disponible pour le moment.</div>
            <?php endif; ?>
        </section>
    </section>
</div>

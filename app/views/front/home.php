<div class="hero">
    <h1>Actualités sur l'Iran</h1>
    <p>Les dernières informations et analyses en temps réel</p>
</div>

<main class="news-container">
    <?php if (empty($news)): ?>
        <p class="no-articles">Aucun article disponible pour le moment.</p>
    <?php else: ?>
        <!-- Article principal (premier de la liste) -->
        <?php if (isset($news[0])): ?>
            <section class="featured-article">
                <article class="featured-card">
                    <div class="featured-content">
                        <h2><a href="<?php echo articleUrl($news[0]['id'], $news[0]['slug']); ?>">
                            <?php echo htmlspecialchars($news[0]['title']); ?>
                        </a></h2>
                        
                        <p class="featured-meta">
                            <span class="author">Par <?php echo htmlspecialchars($news[0]['autor'] ?? 'Admin'); ?></span>
                            <span class="separator">•</span>
                            <time datetime="<?php echo $news[0]['published_at'] ?? $news[0]['created_at']; ?>">
                                <?php echo date('d F Y', strtotime($news[0]['published_at'] ?? $news[0]['created_at'])); ?>
                            </time>
                        </p>

                        <p class="featured-excerpt">
                            <?php echo htmlspecialchars(substr($news[0]['description'] ?? $news[0]['content'], 0, 300)); ?> ...
                        </p>

                        <a href="<?php echo articleUrl($news[0]['id'], $news[0]['slug']); ?>" class="btn btn-primary">Lire l'article</a>
                    </div>
                </article>
            </section>
        <?php endif; ?>

        <!-- Articles secondaires en grille -->
        <?php if (count($news) > 1): ?>
            <section class="news-section">
                <h2>Dernières actualités</h2>
                
                <div class="news-grid">
                    <?php for ($i = 1; $i < count($news); $i++): 
                        $article = $news[$i];
                    ?>
                        <article class="news-card">
                            <h3>
                                <a href="<?php echo articleUrl($article['id'], $article['slug']); ?>">
                                    <?php echo htmlspecialchars($article['title']); ?>
                                </a>
                            </h3>
                            
                            <p class="news-meta">
                                <span class="author">Par <?php echo htmlspecialchars($article['autor'] ?? 'Admin'); ?></span>
                                <span class="separator">•</span>
                                <time datetime="<?php echo $article['published_at'] ?? $article['created_at']; ?>">
                                    <?php echo date('d M Y', strtotime($article['published_at'] ?? $article['created_at'])); ?>
                                </time>
                            </p>
                            
                            <p class="news-excerpt">
                                <?php echo htmlspecialchars(substr($article['description'] ?? $article['content'], 0, 150)); ?>...
                            </p>
                            
                            <?php if ($article['category_name']): ?>
                                <p class="category-link">
                                    <a href="<?php echo categoryUrl($article['category_name']); ?>">
                                        <span class="category"><?php echo htmlspecialchars($article['category_name']); ?></span>
                                    </a>
                                </p>
                            <?php endif; ?>
                        </article>
                    <?php endfor; ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="<?php echo url('accueil') . '?p=' . ($page - 1); ?>" class="btn-nav">« Précédent</a>
            <?php endif; ?>
            
            <span class="page-info">Page <?php echo $page; ?></span>
            
            <a href="<?php echo url('accueil') . '?p=' . ($page + 1); ?>" class="btn-nav">Suivant »</a>
        </div>
    <?php endif; ?>
</main>

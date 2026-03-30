<div class="article-view">
    <?php if (isset($news) && $news): 
        $newsModel = new News();
        $images = $newsModel->getAllImages($news['id']);
    ?>
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
    <?php else: ?>
        <p>Article non trouvé.</p>
    <?php endif; ?>
</div>

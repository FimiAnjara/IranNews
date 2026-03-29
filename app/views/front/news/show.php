<div class="article-view">
    <?php if (isset($news) && $news): ?>
        <article>
            <h1><?php echo htmlspecialchars($news['title']); ?></h1>
            
            <div class="article-meta">
                <p>
                    Par <strong><?php echo htmlspecialchars($news['autor'] ?? 'Admin'); ?></strong>
                    - <?php echo date('d/m/Y H:i', strtotime($news['published_at'] ?? $news['created_at'])); ?>
                </p>
                <?php if ($news['category_name'] ?? null): ?>
                    <p>Catégorie: <span class="category"><?php echo htmlspecialchars($news['category_name']); ?></span></p>
                <?php endif; ?>
            </div>

            <div class="article-content">
                <?php echo nl2br(htmlspecialchars($news['content'])); ?>
            </div>

            <div class="article-footer">
                <a href="<?php echo url('accueil'); ?>" class="btn btn-secondary">← Retour aux actualités</a>
            </div>
        </article>
    <?php else: ?>
        <p>Article non trouvé.</p>
    <?php endif; ?>
</div>

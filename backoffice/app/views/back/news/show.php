<div class="admin-news admin-page">
    <div class="admin-actions">
        <a href="<?php echo adminUrl('news-list'); ?>" class="btn btn-sm btn-secondary">Retour a la liste</a>
        <a href="<?php echo adminUrl('news-edit', $news['id']); ?>" class="btn btn-sm btn-primary">Editer</a>
        <a href="<?php echo adminUrl('news-delete', $news['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Etes-vous sur?');">Supprimer</a>
    </div>

    <h1>Detail article</h1>

    <div class="admin-detail-grid">
        <div class="admin-detail-row">
            <span class="admin-detail-label">ID</span>
            <span class="admin-detail-value">#<?php echo htmlspecialchars($news['id']); ?></span>
        </div>
        <div class="admin-detail-row">
            <span class="admin-detail-label">Titre</span>
            <span class="admin-detail-value"><?php echo htmlspecialchars($news['title']); ?></span>
        </div>
        <div class="admin-detail-row">
            <span class="admin-detail-label">Categorie</span>
            <span class="admin-detail-value"><?php echo htmlspecialchars($news['category_name'] ?? 'Non assigne'); ?></span>
        </div>
        <div class="admin-detail-row">
            <span class="admin-detail-label">Statut</span>
            <span class="badge badge-<?php echo $news['etat'] ? 'success' : 'warning'; ?>">
                <?php echo $news['etat'] ? 'Publie' : 'Brouillon'; ?>
            </span>
        </div>
        <div class="admin-detail-row">
            <span class="admin-detail-label">Auteur</span>
            <span class="admin-detail-value"><?php echo htmlspecialchars($news['autor'] ?? 'Admin'); ?></span>
        </div>
        <div class="admin-detail-row">
            <span class="admin-detail-label">Publication</span>
            <span class="admin-detail-value"><?php echo date('d/m/Y H:i', strtotime($news['published_at'] ?? $news['created_at'])); ?></span>
        </div>
    </div>

    <?php if (!empty($news['description'])): ?>
        <div class="admin-detail-section">
            <h2>Description</h2>
            <p><?php echo htmlspecialchars($news['description']); ?></p>
        </div>
    <?php endif; ?>

    <div class="admin-detail-section">
        <h2>Contenu</h2>
        <div class="admin-detail-content">
            <?php echo $news['content']; ?>
        </div>
    </div>

    <?php if (!empty($images)): ?>
        <div class="admin-detail-section">
            <h2>Images</h2>
            <div class="admin-image-grid">
                <?php foreach ($images as $image): ?>
                    <figure class="admin-image-item">
                        <img src="<?php echo htmlspecialchars($image['url']); ?>" alt="<?php echo htmlspecialchars($image['alt_text'] ?? 'Image article'); ?>">
                        <?php if (!empty($image['alt_text'])): ?>
                            <figcaption><?php echo htmlspecialchars($image['alt_text']); ?></figcaption>
                        <?php endif; ?>
                    </figure>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

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
        <?php
            $imagePayload = [];
            foreach ($images as $image) {
                $imagePayload[] = [
                    'id' => (int)$image['id'],
                    'url' => $image['url'],
                    'alt' => $image['alt_text'] ?? '',
                    'updateUrl' => adminUrl('media-update', $image['id']),
                    'deleteUrl' => adminUrl('media-delete', $image['id'])
                ];
            }
            $firstImage = $imagePayload[0];
        ?>
        <div class="admin-detail-section">
            <h2>Images</h2>
            <div class="admin-image-viewer">
                <button class="admin-image-nav" id="admin-image-prev" type="button" aria-label="Image precedente">‹</button>
                <div class="admin-image-frame">
                    <img id="admin-image-display" src="<?php echo htmlspecialchars($firstImage['url']); ?>" alt="<?php echo htmlspecialchars($firstImage['alt'] ?: 'Image article'); ?>">
                </div>
                <button class="admin-image-nav" id="admin-image-next" type="button" aria-label="Image suivante">›</button>
            </div>
            <p id="admin-image-caption" class="admin-image-caption"><?php echo htmlspecialchars($firstImage['alt']); ?></p>

            <form class="admin-image-form" id="admin-image-form" method="POST" action="<?php echo htmlspecialchars($firstImage['updateUrl']); ?>" enctype="multipart/form-data">
                <label class="admin-image-label" for="admin-alt-text">Texte alternatif</label>
                <input id="admin-alt-text" type="text" name="alt_text" value="<?php echo htmlspecialchars($firstImage['alt']); ?>">

                <label class="admin-image-label" for="admin-image-file">Remplacer l'image</label>
                <input id="admin-image-file" type="file" name="image" accept="image/*">

                <div class="admin-image-actions">
                    <button type="submit" class="btn btn-sm btn-primary">Mettre a jour</button>
                    <a href="<?php echo htmlspecialchars($firstImage['deleteUrl']); ?>" id="admin-image-delete" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette image?');">Supprimer</a>
                </div>
            </form>
        </div>

        <script>
            (function() {
                const images = <?php echo json_encode($imagePayload, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
                const display = document.getElementById('admin-image-display');
                const caption = document.getElementById('admin-image-caption');
                const altInput = document.getElementById('admin-alt-text');
                const form = document.getElementById('admin-image-form');
                const deleteLink = document.getElementById('admin-image-delete');
                const prevBtn = document.getElementById('admin-image-prev');
                const nextBtn = document.getElementById('admin-image-next');

                if (!images.length) {
                    return;
                }

                let index = 0;

                function render() {
                    const current = images[index];
                    display.src = current.url;
                    display.alt = current.alt || 'Image article';
                    caption.textContent = current.alt || '';
                    altInput.value = current.alt || '';
                    form.action = current.updateUrl;
                    deleteLink.href = current.deleteUrl;
                    prevBtn.disabled = images.length <= 1;
                    nextBtn.disabled = images.length <= 1;
                }

                prevBtn.addEventListener('click', () => {
                    index = (index - 1 + images.length) % images.length;
                    render();
                });

                nextBtn.addEventListener('click', () => {
                    index = (index + 1) % images.length;
                    render();
                });

                render();
            })();
        </script>
    <?php endif; ?>
</div>

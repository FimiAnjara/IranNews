<div class="admin-news admin-page">
    <h1>Gestion des articles</h1>

    <!-- Filtres -->
    <div class="filter-section">
        <form method="GET" class="filter-form">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="search">Recherche (titre, ID)</label>
                    <input type="text" id="search" name="search" placeholder="Rechercher..." value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
                </div>
                
                <div class="filter-group">
                    <label for="status">Statut</label>
                    <select id="status" name="status">
                        <option value="">Tous</option>
                        <option value="1" <?php echo ($filters['status'] === '1') ? 'selected' : ''; ?>>Publié</option>
                        <option value="0" <?php echo ($filters['status'] === '0') ? 'selected' : ''; ?>>Brouillon</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="category_id">Catégorie</label>
                    <select id="category_id" name="category_id">
                        <option value="">Toutes</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo ($filters['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="filter-row">
                <div class="filter-group">
                    <label for="date_from">De</label>
                    <input type="date" id="date_from" name="date_from" value="<?php echo htmlspecialchars($filters['date_from'] ?? ''); ?>">
                </div>
                
                <div class="filter-group">
                    <label for="date_to">À</label>
                    <input type="date" id="date_to" name="date_to" value="<?php echo htmlspecialchars($filters['date_to'] ?? ''); ?>">
                </div>
                
                <div class="filter-group filter-actions">
                    <button type="submit" class="btn btn-sm btn-primary">Filtrer</button>
                    <a href="<?php echo adminUrl('news-list'); ?>" class="btn btn-sm btn-secondary">Réinitialiser</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tableau des articles -->
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Catégorie</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($news)): ?>
                <?php foreach ($news as $article): ?>
                    <tr>
                        <td>
                            <a href="<?php echo adminUrl('news-show', $article['id']); ?>">
                                #<?php echo htmlspecialchars($article['id']); ?>
                            </a>
                        </td>
                        <td>
                            <a href="<?php echo adminUrl('news-show', $article['id']); ?>">
                                <?php echo htmlspecialchars($article['title']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($article['category_name'] ?? 'Non assigné'); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $article['etat'] ? 'success' : 'warning'; ?>">
                                <?php echo $article['etat'] ? 'Publié' : 'Brouillon'; ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($article['published_at'] ?? $article['created_at'])); ?></td>
                        <td>
                            <a href="<?php echo adminUrl('news-edit', $article['id']); ?>" class="btn btn-sm btn-primary">Éditer</a>
                            <a href="<?php echo adminUrl('news-toggle-publish', $article['id']); ?>" class="btn btn-sm btn-<?php echo $article['etat'] ? 'warning' : 'success'; ?>">
                                <?php echo $article['etat'] ? 'Dépublier' : 'Publier'; ?>
                            </a>
                            <a href="<?php echo adminUrl('news-delete', $article['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="empty-state">
                        Aucun article trouvé. <a href="<?php echo adminUrl('news-create'); ?>">Créer un article</a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <?php if (!empty($news) && $totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="<?php echo adminUrl('news-list') . '?p=' . ($page - 1) . buildQueryString($filters); ?>" class="btn btn-sm">← Précédent</a>
            <?php endif; ?>
            
            <div class="pagination-pages">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i === $page): ?>
                        <span class="pagination-current"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="<?php echo adminUrl('news-list') . '?p=' . $i . buildQueryString($filters); ?>" class="pagination-link"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
            
            <?php if ($page < $totalPages): ?>
                <a href="<?php echo adminUrl('news-list') . '?p=' . ($page + 1) . buildQueryString($filters); ?>" class="btn btn-sm">Suivant →</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php
// Fonction helper pour construire les paramètres de query
function buildQueryString($filters) {
    $qs = '';
    if (!empty($filters['search'])) {
        $qs .= '&search=' . urlencode($filters['search']);
    }
    if ($filters['status'] !== '') {
        $qs .= '&status=' . urlencode($filters['status']);
    }
    if (!empty($filters['category_id'])) {
        $qs .= '&category_id=' . urlencode($filters['category_id']);
    }
    if (!empty($filters['date_from'])) {
        $qs .= '&date_from=' . urlencode($filters['date_from']);
    }
    if (!empty($filters['date_to'])) {
        $qs .= '&date_to=' . urlencode($filters['date_to']);
    }
    return $qs;
}
?>

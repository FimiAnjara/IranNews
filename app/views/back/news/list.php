<div class="admin-news">
    <h1>Gestion des articles</h1>
    
    <div class="admin-actions">
        <a href="<?php echo adminUrl('news-create'); ?>" class="btn btn-primary">+ Créer un article</a>
    </div>

    <!-- Tableau des articles -->
    <table class="admin-table">
        <thead>
            <tr>
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
                        <td><?php echo htmlspecialchars($article['title']); ?></td>
                        <td><?php echo htmlspecialchars($article['category_name'] ?? 'Non assigné'); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $article['etat'] ? 'success' : 'warning'; ?>">
                                <?php echo $article['etat'] ? 'Publié' : 'Brouillon'; ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($article['published_at'] ?? $article['created_at'])); ?></td>
                        <td>
                            <a href="<?php echo adminUrl('news-edit', $article['id']); ?>" class="btn btn-sm btn-primary">Éditer</a>
                            <a href="<?php echo adminUrl('news-delete', $article['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">
                        Aucun article trouvé. <a href="<?php echo adminUrl('news-create'); ?>">Créer un article</a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
.admin-news {
    background: white;
    padding: 2rem;
    border-radius: 8px;
}

.admin-actions {
    margin-bottom: 2rem;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}

.admin-table thead {
    background-color: #f8f9fa;
}

.admin-table th,
.admin-table td {
    padding: 0.75rem;
    border: 1px solid #dee2e6;
    text-align: left;
}

.admin-table tr:hover {
    background-color: #f9f9f9;
}

.badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: bold;
}

.badge-success {
    background-color: #28a745;
    color: white;
}

.badge-warning {
    background-color: #ffc107;
    color: #333;
}

.btn-sm {
    padding: 0.35rem 0.75rem !important;
    font-size: 0.875rem !important;
}

.btn-danger {
    background-color: #dc3545 !important;
}

.btn-danger:hover {
    background-color: #c82333 !important;
}
</style>

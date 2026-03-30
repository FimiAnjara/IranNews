<div class="admin-categories">
    <h1>Gestion des catégories</h1>
    
    <div class="admin-actions">
        <a href="<?php echo adminUrl('categories-create'); ?>" class="btn btn-primary">+ Créer une catégorie</a>
    </div>

    <!-- Tableau des catégories -->
    <table class="admin-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Slug</th>
                <th>Description</th>
                <th>Articles</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                        <td><code><?php echo htmlspecialchars($category['slug']); ?></code></td>
                        <td><?php echo htmlspecialchars(substr($category['description'] ?? '', 0, 50)); ?></td>
                        <td><?php echo $category['article_count'] ?? 0; ?></td>
                        <td>
                            <a href="<?php echo adminUrl('categories-edit', $category['id']); ?>" class="btn btn-sm btn-primary">Éditer</a>
                            <a href="<?php echo adminUrl('categories-delete', $category['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">
                        Aucune catégorie trouvée. <a href="<?php echo adminUrl('categories-create'); ?>">Créer une catégorie</a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
.admin-categories {
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

.admin-table code {
    background-color: #f4f4f4;
    padding: 0.2rem 0.4rem;
    border-radius: 3px;
    font-family: monospace;
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

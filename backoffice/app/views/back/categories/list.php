<div class="admin-categories admin-page">
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
                    <td colspan="5" class="empty-state">
                        Aucune catégorie trouvée. <a href="<?php echo adminUrl('categories-create'); ?>">Créer une catégorie</a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

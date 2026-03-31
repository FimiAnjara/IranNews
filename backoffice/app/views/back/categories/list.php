<div class="admin-categories admin-page">
    <h1>Gestion des catégories</h1>


    <!-- Tableau des catégories -->
    <table class="admin-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Slug</th>
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
                        <td><?php echo $category['article_count'] ?? 0; ?></td>
                        <td>
                            <a href="<?php echo adminUrl('categories-edit', $category['id']); ?>" class="btn btn-sm btn-primary">Éditer</a>
                            <a href="<?php echo adminUrl('categories-delete', $category['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="empty-state">
                        Aucune catégorie trouvée. <a href="<?php echo adminUrl('categories-create'); ?>">Créer une catégorie</a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <?php if (!empty($categories) && $totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="<?php echo adminUrl('categories-list') . '?p=' . ($page - 1); ?>" class="btn btn-sm">← Précédent</a>
            <?php endif; ?>
            
            <div class="pagination-pages">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i === $page): ?>
                        <span class="pagination-current"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="<?php echo adminUrl('categories-list') . '?p=' . $i; ?>" class="pagination-link"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
            
            <?php if ($page < $totalPages): ?>
                <a href="<?php echo adminUrl('categories-list') . '?p=' . ($page + 1); ?>" class="btn btn-sm">Suivant →</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

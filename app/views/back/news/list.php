<div class="admin-news">
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
                
                <div class="filter-group" style="display: flex; align-items: flex-end; gap: 0.5rem;">
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
                        <td>#<?php echo htmlspecialchars($article['id']); ?></td>
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
                            <a href="<?php echo adminUrl('news-toggle-publish', $article['id']); ?>" class="btn btn-sm btn-<?php echo $article['etat'] ? 'warning' : 'success'; ?>">
                                <?php echo $article['etat'] ? 'Dépublier' : 'Publier'; ?>
                            </a>
                            <a href="<?php echo adminUrl('news-delete', $article['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">
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

.filter-section {
    background-color: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    border-left: 4px solid #007bff;
}

.filter-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.filter-row {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    align-items: flex-start;
}

.filter-group {
    flex: 1;
    min-width: 200px;
    display: flex;
    flex-direction: column;
}

.filter-group label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
    color: #333;
}

.filter-group input,
.filter-group select {
    padding: 0.5rem;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    font-size: 0.875rem;
    font-family: inherit;
}

.filter-group input:focus,
.filter-group select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
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

.admin-table th {
    font-weight: 600;
    color: #333;
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

.btn-primary {
    background-color: #007bff !important;
    color: white !important;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: background-color 0.2s;
}

.btn-primary:hover {
    background-color: #0056b3 !important;
}

.btn-secondary {
    background-color: #6c757d !important;
    color: white !important;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: background-color 0.2s;
}

.btn-secondary:hover {
    background-color: #545b62 !important;
}

.btn-success {
    background-color: #28a745 !important;
    color: white !important;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: background-color 0.2s;
}

.btn-success:hover {
    background-color: #218838 !important;
}

.btn-warning {
    background-color: #ffc107 !important;
    color: #333 !important;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: background-color 0.2s;
}

.btn-warning:hover {
    background-color: #e0a800 !important;
}

.btn-danger {
    background-color: #dc3545 !important;
    color: white !important;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: background-color 0.2s;
}

.btn-danger:hover {
    background-color: #c82333 !important;
}

@media (max-width: 768px) {
    .filter-row {
        flex-direction: column;
    }
    
    .filter-group {
        min-width: 100%;
    }
    
    .admin-table th,
    .admin-table td {
        padding: 0.5rem;
        font-size: 0.875rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem !important;
        font-size: 0.75rem !important;
        margin-bottom: 0.25rem;
        display: block;
        width: 100%;
        text-align: center;
    }
}
</style>

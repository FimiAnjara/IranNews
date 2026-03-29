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
                <th>Vues</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="6" style="text-align: center;">
                    Implémentation en attente...
                </td>
            </tr>
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
</style>

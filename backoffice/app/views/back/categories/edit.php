<div class="admin-edit">
    <h1>Éditer la catégorie</h1>
    
    <form method="POST" class="category-form" accept-charset="UTF-8">
        <div class="form-group">
            <label for="name">Nom:</label>
            <input type="text" id="name" name="name" required maxlength="100" value="<?php echo htmlspecialchars($category['name'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" placeholder="Description de la catégorie"><?php echo htmlspecialchars($category['description'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label>Slug:</label>
            <code><?php echo htmlspecialchars($category['slug'] ?? ''); ?></code>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="<?php echo adminUrl('categories-list'); ?>" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<style>
.admin-edit {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    max-width: 600px;
    margin: 0 auto;
}

.category-form .form-group {
    margin-bottom: 1.5rem;
}

.category-form input,
.category-form textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #bdc3c7;
    border-radius: 4px;
    font-family: inherit;
}

.category-form textarea {
    resize: vertical;
}

.category-form code {
    background-color: #f4f4f4;
    padding: 0.5rem;
    border-radius: 3px;
    display: block;
    font-family: monospace;
    word-break: break-all;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}

.form-actions button,
.form-actions a {
    flex: 1;
    text-align: center;
}
</style>

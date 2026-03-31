<div class="admin-create">
    <h1>Créer une nouvelle catégorie</h1>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger form-alert">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <div class="alert alert-success form-alert">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" class="category-form" accept-charset="UTF-8">
        <div class="form-group">
            <label for="name">Nom:</label>
            <input type="text" id="name" name="name" required maxlength="100">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Créer la catégorie</button>
            <a href="<?php echo adminUrl('categories-list'); ?>" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<style>
.admin-create {
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

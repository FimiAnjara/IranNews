<div class="admin-create">
    <h1>Créer un nouvel article</h1>
    
    <form method="POST" class="article-form">
        <div class="form-group">
            <label for="title">Titre:</label>
            <input type="text" id="title" name="title" required maxlength="255">
        </div>

        <div class="form-group">
            <label for="category">Catégorie:</label>
            <select id="category" name="category">
                <option value="">-- Sélectionner une catégorie --</option>
                <option value="Général">Général</option>
                <option value="Politique">Politique</option>
                <option value="Économie">Économie</option>
                <option value="Culture">Culture</option>
                <option value="Sport">Sport</option>
                <option value="Science">Science</option>
            </select>
        </div>

        <div class="form-group">
            <label for="content">Contenu:</label>
            <textarea id="content" name="content" rows="12" required></textarea>
        </div>

        <div class="form-group">
            <label for="published">
                <input type="checkbox" id="published" name="published" value="1">
                Publier maintenant
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Créer l'article</button>
            <a href="<?php echo adminUrl('dashboard'); ?>" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<style>
.admin-create {
    background: white;
    padding: 2rem;
    border-radius: 8px;
}

.article-form .form-group {
    margin-bottom: 1.5rem;
}

.article-form select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #bdc3c7;
    border-radius: 4px;
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

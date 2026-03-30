<div class="admin-create">
    <h1>Créer un nouvel article</h1>
    
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#content',
            license_key: 'gpl',
            height: 400,
            plugins: ['link', 'image', 'lists', 'code', 'preview'],
            toolbar: 'formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image | code preview'
        });
    </script>
    
    <form method="POST" class="article-form">
        <div class="form-group">
            <label for="title">Titre:</label>
            <input type="text" id="title" name="title" required maxlength="255">
        </div>

        <div class="form-group">
            <label for="category_id">Catégorie:</label>
            <select id="category_id" name="category_id">
                <option value="">-- Sélectionner une catégorie --</option>
                <option value="1">Général</option>
                <option value="2">Politique</option>
                <option value="3">Économie</option>
                <option value="4">Culture</option>
                <option value="5">Sport</option>
                <option value="6">Science</option>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="3" placeholder="Courte description de l'article"></textarea>
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

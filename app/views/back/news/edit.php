<div class="admin-edit">
    <h1>Éditer l'article</h1>
    
    <script src="/assets/js/tinymce/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#content',
            license_key: 'gpl',
            height: 400,
            plugins: ['link', 'image', 'lists', 'code', 'preview'],
            toolbar: 'formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image | code preview'
        });
    </script>
    
    <form method="POST" class="article-form" accept-charset="UTF-8" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Titre:</label>
            <input type="text" id="title" name="title" required maxlength="255" value="<?php echo htmlspecialchars($news['title'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="category_id">Catégorie:</label>
            <select id="category_id" name="category_id">
                <option value="">-- Sélectionner une catégorie --</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>" <?php echo ($news['category_id'] ?? null) == $category['id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="3" placeholder="Courte description de l'article"><?php echo $news['description'] ?? ''; ?></textarea>
        </div>

        <div class="form-group">
            <label for="autor">Auteur:</label>
            <input type="text" id="autor" name="autor" maxlength="100" value="<?php echo htmlspecialchars($news['autor'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="content">Contenu:</label>
            <textarea id="content" name="content" required><?php echo $news['content'] ?? ''; ?></textarea>
        </div>

        <div class="form-group">
            <label for="published">
                <input type="checkbox" id="published" name="published" value="1" <?php echo ($news['etat'] ?? 0) ? 'checked' : ''; ?>>
                Publier maintenant
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Mettre à jour l'article</button>
            <a href="<?php echo adminUrl('news-list'); ?>" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<style>
.admin-edit {
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

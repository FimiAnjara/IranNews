<div class="admin-create">
    <h1>Créer un nouvel article</h1>

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
            <input type="text" id="title" name="title" required maxlength="255">
        </div>

        <div class="form-group">
            <label for="category_id">Catégorie:</label>
            <select id="category_id" name="category_id">
                <option value="">-- Sélectionner une catégorie --</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="3" placeholder="Courte description de l'article"></textarea>
        </div>

        <div class="form-group">
            <label for="autor">Auteur:</label>
            <input type="text" id="autor" name="autor" maxlength="100" placeholder="Nom de l'auteur">
        </div>

        <div class="form-group">
            <label for="content">Contenu:</label>
            <textarea id="content" name="content" rows="12"></textarea>
        </div>

        <div class="form-group">
            <label for="images_alt_text">Texte alternatif des images:</label>
            <input type="text" id="images_alt_text" name="images_alt_text" maxlength="255" placeholder="Ex: Vue generale de la manifestation">
            <small>Ce texte sera applique a toutes les images ajoutees.</small>
        </div>

        <div class="form-group">
            <label for="images">Images:</label>
            <input type="file" id="images" name="images[]" multiple accept="image/*">
            <small>Sélectionnez une ou plusieurs images (JPG, PNG, GIF)</small>
            <div id="image-preview" class="image-preview"></div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Créer l'article</button>
            <a href="<?php echo adminUrl('news-list'); ?>" class="btn btn-secondary">Annuler</a>
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

.article-form select,
.article-form input[type="file"] {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #bdc3c7;
    border-radius: 4px;
}

.article-form input[type="file"] {
    padding: 0.5rem;
}

.article-form small {
    display: block;
    color: #7f8c8d;
    margin-top: 0.5rem;
    font-size: 0.9rem;
}

.image-preview {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.image-preview-item {
    position: relative;
    border: 1px solid #bdc3c7;
    border-radius: 4px;
    overflow: hidden;
    background-color: #f5f5f5;
}

.image-preview-item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.image-preview-item .remove-image {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background-color: #e74c3c;
    color: white;
    border: none;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    cursor: pointer;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.2s;
}

.image-preview-item .remove-image:hover {
    background-color: #c0392b;
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

<script>
document.getElementById('images').addEventListener('change', function(e) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    
    const files = e.target.files;
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();
        
        reader.onload = function(event) {
            const item = document.createElement('div');
            item.className = 'image-preview-item';
            item.innerHTML = `
                <img src="${event.target.result}" alt="Aperçu image">
                <button type="button" class="remove-image" data-index="${i}">✕</button>
            `;
            preview.appendChild(item);
        };
        
        reader.readAsDataURL(file);
    }
});

// Gestion de la suppression d'images du preview
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-image')) {
        e.preventDefault();
        const items = document.getElementById('image-preview').querySelectorAll('.image-preview-item');
        items.forEach(item => item.style.display = 'none');
        document.getElementById('images').value = '';
        document.getElementById('image-preview').innerHTML = '';
    }
});

// Validation du contenu TinyMCE avant soumission
document.querySelector('.article-form').addEventListener('submit', function(e) {
    const editorContent = tinymce.get('content').getContent();
    if (!editorContent || editorContent.trim() === '') {
        e.preventDefault();
        alert('Le contenu de l\'article est requis.');
        tinymce.get('content').focus();
        return false;
    }
    // Mettre à jour le textarea avant soumission
    tinymce.get('content').save();
});
</script>

<div class="home-wrapper">
    <!-- Sidebar Gauche - Filtres Catégories -->
    <aside class="categories-sidebar" id="categoriesSidebar">
        <div class="sidebar-header">
            <h3>Filtres</h3>
            <button class="drawer-close" id="filterClose" aria-label="Fermer les filtres">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <nav class="categories-list">
            <a href="#" class="category-filter active" data-category="all">
                <span class="category-name">Tous les articles</span>
                <span class="category-count" id="count-all">0</span>
            </a>
            <div id="categoriesContainer">
                <div class="loading">Chargement des catégories...</div>
            </div>
        </nav>
    </aside>
    <div class="drawer-overlay" id="drawerOverlay"></div>

    <section class="home-main">
        <div class="home-header-row">
            <div class="hero">
                <h1>Actualités sur l'Iran</h1>
                <p>Les dernières informations et analyses en temps réel</p>
            </div>
            <button class="drawer-toggle" id="filterToggle" aria-label="Ouvrir les filtres">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="4" y1="6" x2="20" y2="6"></line>
                    <line x1="4" y1="12" x2="20" y2="12"></line>
                    <line x1="4" y1="18" x2="20" y2="18"></line>
                </svg>
                Filtrer par catégorie
            </button>
        </div>

        <main class="news-container" id="newsContainer">
            <div class="articles-loading">Chargement des articles...</div>
        </main>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterToggle = document.getElementById('filterToggle');
    const filterClose = document.getElementById('filterClose');
    const drawerOverlay = document.getElementById('drawerOverlay');
    const sidebar = document.getElementById('categoriesSidebar');
    const newsContainer = document.getElementById('newsContainer');
    const categoriesContainer = document.getElementById('categoriesContainer');

    // Drawer toggle
    filterToggle.addEventListener('click', () => {
        sidebar.classList.add('open');
        drawerOverlay.classList.add('visible');
    });

    filterClose.addEventListener('click', () => {
        sidebar.classList.remove('open');
        drawerOverlay.classList.remove('visible');
    });

    drawerOverlay.addEventListener('click', () => {
        sidebar.classList.remove('open');
        drawerOverlay.classList.remove('visible');
    });

    // Charger les catégories
    function loadCategories() {
        fetch('/index.php?page=api-categories')
            .then(res => res.json())
            .then(data => {
                if (data.success && data.data && Array.isArray(data.data.categories)) {
                    let html = '';
                    data.data.categories.forEach(cat => {
                        html += `<a href="#" class="category-filter" data-category="${cat.slug}">
                            <span class="category-name">${cat.name}</span>
                            <span class="category-count">${cat.article_count}</span>
                        </a>`;
                    });
                    categoriesContainer.innerHTML = html;
                    document.getElementById('count-all').innerText = data.data.total_count || 0;
                    attachCategoryListeners();

                    // Charger les articles
                    loadNews('all');
                } else {
                    document.getElementById('count-all').innerText = '0';
                    categoriesContainer.innerHTML = '<p>Aucune catégorie disponible.</p>';
                    loadNews('all');
                }
            })
            .catch(err => {
                console.error('Erreur chargement catégories:', err);
                categoriesContainer.innerHTML = '<p style="color: #e94b3c;">Erreur de chargement</p>';
                document.getElementById('count-all').innerText = '0';
                loadNews('all');
            });
    }

    // Charger les articles filtrés
    function loadNews(category = 'all', page = 1) {
        const url = `/index.php?page=api-news&category=${category}&p=${page}`;

        newsContainer.innerHTML = '<div class="articles-loading">Chargement des articles...</div>';

        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (data.success && data.data) {
                    renderNews(data.data);

                    // Mettre à jour l'URL
                    if (category !== 'all') {
                        history.pushState(null, '', `/?category=${category}&p=${page}`);
                    } else {
                        history.pushState(null, '', `/?p=${page}`);
                    }
                } else {
                    newsContainer.innerHTML = '<p class="no-articles">Aucun article disponible pour le moment.</p>';
                }
            })
            .catch(err => {
                console.error('Erreur chargement articles:', err);
                newsContainer.innerHTML = '<p style="color: #e94b3c;">Erreur de chargement des articles</p>';
            });
    }

    // Afficher les articles
    function renderNews(articles) {
        if (!articles || articles.length === 0) {
            newsContainer.innerHTML = '<p class="no-articles">Aucun article disponible pour le moment.</p>';
            return;
        }

        let html = '';

        // Article en vedette (premier)
        const featured = articles[0];
        const featImage = featured.image ? featured.image.url : '';
        html += `
            <section class="featured-article">
                <article class="featured-card">
                    ${featImage ? `<div class="featured-image"><img src="${featImage}" alt="${featured.title}" class="featured-img"></div>` : ''}
                    <div class="featured-content">
                        <h2><a href="/index.php?page=article&id=${featured.id}">${featured.title}</a></h2>
                        <p class="featured-meta">
                            <span class="author">Par ${featured.autor || 'Admin'}</span>
                            <span class="separator">•</span>
                            <time>${new Date(featured.published_at || featured.created_at).toLocaleDateString('fr-FR', {year: 'numeric', month: 'long', day: 'numeric'})}</time>
                        </p>
                        <p class="featured-excerpt">${(featured.description || featured.content).substring(0, 300)}...</p>
                        <a href="/index.php?page=article&id=${featured.id}" class="btn btn-primary">Lire l'article</a>
                    </div>
                </article>
            </section>
        `;

        // Articles secondaires
        if (articles.length > 1) {
            html += '<section class="news-section"><h2>Articles</h2><div class="news-grid">';

            for (let i = 1; i < articles.length; i++) {
                const article = articles[i];
                const img = article.image ? article.image.url : '';
                html += `
                    <article class="news-card">
                        ${img ? `<div class="news-card-image"><img src="${img}" alt="${article.title}" class="card-img"></div>` : ''}
                        <div class="news-card-content">
                            <h3><a href="/index.php?page=article&id=${article.id}">${article.title}</a></h3>
                            <p class="news-meta">
                                <span class="author">Par ${article.autor || 'Admin'}</span>
                                <span class="separator">•</span>
                                <time>${new Date(article.published_at || article.created_at).toLocaleDateString('fr-FR', {year: 'numeric', month: 'short', day: 'numeric'})}</time>
                            </p>
                            <p class="news-excerpt">${(article.description || article.content).substring(0, 150)}...</p>
                            ${article.category_name ? `<p class="category-link"><a href="#"><span class="category">${article.category_name}</span></a></p>` : ''}
                        </div>
                    </article>
                `;
            }
            html += '</div></section>';
        }

        // Pagination
        html += '<div class="pagination"><span class="page-info">Page 1</span></div>';

        newsContainer.innerHTML = html;
    }

    // Gérer les clics sur les catégories
    function attachCategoryListeners() {
        document.querySelectorAll('.category-filter').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();

                // Mettre à jour l'UI
                document.querySelectorAll('.category-filter').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                // Charger les articles
                const category = btn.dataset.category;
                loadNews(category, 1);

                // Fermer le drawer sur mobile
                if (window.innerWidth < 768) {
                    sidebar.classList.remove('open');
                    drawerOverlay.classList.remove('visible');
                }
            });
        });
    }

    // Charger au démarrage
    loadCategories();

    // Gérer le retour en arrière/avant
    window.addEventListener('popstate', () => {
        const url = new URLSearchParams(window.location.search);
        const category = url.get('category') || 'all';
        const page = url.get('p') || 1;

        document.querySelectorAll('.category-filter').forEach(b => b.classList.remove('active'));
        document.querySelector(`[data-category="${category}"]`)?.classList.add('active');

        loadNews(category, page);
    });
});
</script>

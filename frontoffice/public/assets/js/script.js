// JavaScript simple pour le site

document.addEventListener('DOMContentLoaded', function() {
    const searchToggle = document.querySelector('.search-toggle');
    const searchBar = document.getElementById('searchBar');

    // Confirmation de suppression
    const deleteLinks = document.querySelectorAll('.delete-link');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('Êtes-vous sûr?')) {
                e.preventDefault();
            }
        });
    });

    if (searchToggle && searchBar) {
        const searchInput = searchBar.querySelector('input[name="q"]');

        const closeSearch = () => {
            searchBar.classList.remove('is-open');
            searchBar.style.display = 'none';
            searchToggle.setAttribute('aria-expanded', 'false');
        };

        searchToggle.addEventListener('click', (event) => {
            event.stopPropagation();
            const isOpen = searchBar.classList.toggle('is-open');
            searchBar.style.display = isOpen ? 'block' : 'none';
            searchToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            if (isOpen && searchInput) {
                searchInput.focus();
            }
        });

        document.addEventListener('click', (event) => {
            if (!searchBar.contains(event.target) && !searchToggle.contains(event.target)) {
                closeSearch();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeSearch();
            }
        });
    }

    const galleries = document.querySelectorAll('.article-gallery[data-gallery="slider"]');
    galleries.forEach((gallery) => {
        const track = gallery.querySelector('.gallery-track');
        const items = track ? Array.from(track.children) : [];
        const prevButton = gallery.querySelector('.gallery-nav.prev');
        const nextButton = gallery.querySelector('.gallery-nav.next');

        if (!track || items.length < 2) {
            return;
        }

        let index = 0;

        items.forEach((item) => {
            item.style.flex = '0 0 100%';
        });

        const update = () => {
            const slideWidth = gallery.clientWidth;
            track.style.transform = `translateX(-${index * slideWidth}px)`;
        };

        prevButton?.addEventListener('click', (event) => {
            event.preventDefault();
            index = (index - 1 + items.length) % items.length;
            update();
        });

        nextButton?.addEventListener('click', (event) => {
            event.preventDefault();
            index = (index + 1) % items.length;
            update();
        });

        update();
        window.addEventListener('resize', update);
    });

    // Validation des formulaires
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const inputs = form.querySelectorAll('input[required], textarea[required]');
            let valid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.style.borderColor = 'red';
                    valid = false;
                } else {
                    input.style.borderColor = '';
                }
            });
            
            if (!valid) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs requis');
            }
        });
    });
});

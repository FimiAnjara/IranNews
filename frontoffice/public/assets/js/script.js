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

document.addEventListener('DOMContentLoaded', () => {

    const searchInput = document.getElementById('adminNewsSearch');
    const sortSelect  = document.getElementById('adminSortSelect');
    const cards       = Array.from(document.querySelectorAll('.program-card-admin'));
    const container   = document.querySelector('.news-card-grid');

    if (!searchInput || !sortSelect) return;

    /* SEARCH */
    searchInput.addEventListener('input', () => {
        const query = searchInput.value.toLowerCase().trim();

        cards.forEach(card => {
            const text = `
                ${card.dataset.title}
                ${card.dataset.org}
                ${card.dataset.author}
                ${card.dataset.categories}
                ${card.innerText}
            `.toLowerCase();

            card.style.display = text.includes(query) ? '' : 'none';
        });
    });

    /* SORT */
    sortSelect.addEventListener('change', () => {
        const type = sortSelect.value;

        const sorted = [...cards].sort((a, b) => {
            switch (type) {
                case 'oldest':
                    return new Date(a.dataset.date) - new Date(b.dataset.date);
                case 'newest':
                    return new Date(b.dataset.date) - new Date(a.dataset.date);
                case 'title-az':
                    return a.dataset.title.localeCompare(b.dataset.title, undefined, { numeric:true });
                case 'org-az':
                    return a.dataset.org.localeCompare(b.dataset.org, undefined, { numeric:true });
                case 'author-az':
                    return a.dataset.author.localeCompare(b.dataset.author, undefined, { numeric:true });
                default:
                    return 0;
            }
        });

        sorted.forEach(card => container.appendChild(card));
    });

    /* ===== CATEGORY FILTERING (same as news.js) ===== */

let activeCategory = null;

const adminCards = document.querySelectorAll('.program-card-admin');
const categoryPills = document.querySelectorAll('.category-pill');

categoryPills.forEach(pill => {
    pill.addEventListener('click', function () {
        const selectedCategory = this.textContent.toLowerCase().trim();

        if (activeCategory === selectedCategory) {
            activeCategory = null;
            clearActivePills();
            showAllCards();
            return;
        }

        activeCategory = selectedCategory;
        setActivePill(this);

        adminCards.forEach(card => {
            const cardCategories = card.dataset.categories || '';
            if (cardCategories.includes(activeCategory)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
});

function clearActivePills() {
    categoryPills.forEach(p => p.classList.remove('active'));
}

function setActivePill(activePill) {
    clearActivePills();

    const category = activePill.textContent.toLowerCase().trim();

    categoryPills.forEach(pill => {
        if (pill.textContent.toLowerCase().trim() === category) {
            pill.classList.add('active');
        }
    });
}

function showAllCards() {
    adminCards.forEach(card => card.style.display = '');
}


});


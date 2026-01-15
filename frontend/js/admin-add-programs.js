function toggleDJFields() {
    const typeSelect = document.getElementById('type-selector');
    const djContainer = document.getElementById('dj-selection-container');
    const checkboxes = document.querySelectorAll('.dj-checkbox');
    
    if (typeSelect.value === "WITH DJ/HOST") {
        djContainer.classList.remove('disabled-dj');
        checkboxes.forEach(cb => cb.disabled = false);
    } else {
        djContainer.classList.add('disabled-dj');
        checkboxes.forEach(cb => {
            cb.disabled = true;
            cb.checked = false; // clear selection if switched back
        });
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const typeSelect = document.getElementById('type-selector');
    if (typeSelect) {
        toggleDJFields();
    }

    const searchInput = document.getElementById('adminProgramSearch');
    const filterSelect = document.getElementById('adminProgramFilter');
    const list = document.querySelector('.program-list');
    const cards = Array.from(document.querySelectorAll('.program-card'));

    function renderPrograms(filter = 'title', search = '') {
        let filtered = [...cards];

        // SEARCH
        if (search) {
            filtered = filtered.filter(card =>
                card.dataset.title.includes(search)
            );
        }

        // FILTER / SORT (based on programs.js)
        if (['weekdays', 'sat', 'sun'].includes(filter)) {
            const map = {
                weekdays: 'WEEKDAYS',
                sat: 'SAT',
                sun: 'SUN'
            };

            filtered = filtered.filter(card =>
                (card.dataset.days || '').includes(map[filter])
            );

            filtered.sort((a, b) =>
                a.dataset.start.localeCompare(b.dataset.start)
            );

        } else if (filter === 'time') {
            filtered.sort((a, b) =>
                a.dataset.start.localeCompare(b.dataset.start)
            );
        } else {
            // title A–Z
            filtered.sort((a, b) =>
                a.dataset.title.localeCompare(b.dataset.title)
            );
        }

        list.innerHTML = '';
        filtered.forEach(card => list.appendChild(card));
    }

    // EVENTS
    searchInput.addEventListener('input', e => {
        renderPrograms(filterSelect.value, e.target.value.toLowerCase());
    });

    filterSelect.addEventListener('change', e => {
        renderPrograms(e.target.value, searchInput.value.toLowerCase());
    });

    // INITIAL LOAD
    renderPrograms();
});

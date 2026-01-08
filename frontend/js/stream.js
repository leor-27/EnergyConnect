/* for playing the audio broadcasts */
let currentAudio = null;
let currentIcon = null;

function togglePlay(id) {
    const audio = document.getElementById('audio-' + id);
    const icon = document.getElementById('icon-' + id);

    // if another audio is playing, stop it and reset icon
    if (currentAudio && currentAudio !== audio) {
        currentAudio.pause();
        currentAudio.currentTime = 0;
        if (currentIcon) currentIcon.textContent = '▶';
    }

    if (audio.paused) {
        audio.play();
        icon.textContent = '❚❚';
        currentAudio = audio;
        currentIcon = icon;
    } else {
        audio.pause();
        icon.textContent = '▶';
        currentAudio = null;
        currentIcon = null;
    }

    // when audio finishes, reset icon
    audio.onended = () => {
        icon.textContent = '▶';
        currentAudio = null;
        currentIcon = null;
    };
}

// SEARCH AND FILTER FUNCTION
function filterTable() {
    const searchInput = document.getElementById('streamSearch').value.toLowerCase();
    const programFilter = document.getElementById('programFilter').value.toLowerCase();
    const table = document.getElementById('broadcastTable');
    const rows = table.getElementsByTagName('tr');

    // loop through all table rows
    for (let i = 1; i < rows.length; i++) {
        const nameCol = rows[i].getElementsByTagName('td')[0];
        const dateCol = rows[i].getElementsByTagName('td')[1];

        if (nameCol && dateCol) {
            const nameText = nameCol.textContent || nameCol.innerText;
            const dateText = dateCol.textContent || dateCol.innerText;

            // checks if row matches search text
            const matchesSearch = nameText.toLowerCase().indexOf(searchInput) > -1 || 
                                  dateText.toLowerCase().indexOf(searchInput) > -1;

            // checks if row matches the selected program
            const matchesFilter = programFilter === "" || nameText.toLowerCase().indexOf(programFilter) > -1;

            if (matchesSearch && matchesFilter) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }
}

// SORTING FUNCTION
let sortDirections = { 0: 'asc', 1: 'asc', 2: 'asc' }; 

function sortTable(n) {
    const table = document.getElementById("broadcastTable");
    const tbody = table.querySelector("tbody");

    // Convert HTMLCollection to array for sorting 
    const rows = Array.from(tbody.querySelectorAll("tr"));
    if(rows.length === 1 && rows[0].cells.length < 2) return;
    let dir = sortDirections[n] === 'asc' ? 'desc' : 'asc';
    sortDirections[n] = dir;

    rows.sort((rowA, rowB) => {
        // Name
        const nameA = rowA.cells[0].innerText.trim().toLowerCase();
        const nameB = rowB.cells[0].innerText.trim().toLowerCase();
        // Date
        const dateA = new Date(rowA.cells[1].innerText.trim());
        const dateB = new Date(rowB.cells[1].innerText.trim());
        // Time
        const timeStrA = rowA.cells[2].innerText.split('–')[0].trim();
        const timeStrB = rowB.cells[2].innerText.split('–')[0].trim();
        const timeA = new Date("2025/01/01 " + timeStrA);
        const timeB = new Date("2025/01/01 " + timeStrB);

        // sorting by name
        if (n === 0) {
            if (nameA < nameB) return dir === 'asc' ? -1 : 1;
            if (nameA > nameB) return dir === 'asc' ? 1 : -1;
            return 0;
        }

        // sorting by date
        else if (n === 1) {
            return dir === 'asc' ? (dateA - dateB) : (dateB - dateA);
        }

        // sorting by time (keeps newest date at the top)
        else if (n === 2) {
            if (dateA > dateB) return -1;
            if (dateA < dateB) return 1;
            return dir === 'asc' ? (timeA - timeB) : (timeB - timeA);
        }
    });

    rows.forEach(row => tbody.appendChild(row));
    updateSortIcons(n, dir);
}

function updateSortIcons(activeIndex, direction) {
    const headers = document.querySelectorAll("#broadcastTable th .sort-icon");
    headers.forEach((icon, index) => {
        if (index === activeIndex) {
            icon.innerText = direction === 'asc' ? "▲" : "▼";
        } else {
            icon.innerText = "⇅"; 
        }
    });
}

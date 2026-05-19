document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("search-input");
    const sortSelect = document.getElementById("sort-select");
    const mineCheckbox = document.getElementById("mine-checkbox"); // Vaut null si déconnecté
    const container = document.getElementById("container");
    const noMatchState = document.getElementById("no-match-state");

    const originalCards = Array.from(document.querySelectorAll(".quiz-card"));

    function filterQuizs() {
        const searchValue = searchInput.value.toLowerCase().trim();

        const showOnlyMine = mineCheckbox ? mineCheckbox.checked : false;
        let visibleCount = 0;

        originalCards.forEach(card => {
            const name = card.getAttribute("data-name");
            const author = card.getAttribute("data-author");
            const isMine = card.getAttribute("data-mine") === "true";

            const matchesSearch = name.includes(searchValue) || author.includes(searchValue);
            const matchesMine = !showOnlyMine || isMine;

            if (matchesSearch && matchesMine) {
                card.style.display = "flex";
                visibleCount++;
            } else {
                card.style.display = "none";
            }
        });

        if (visibleCount === 0 && originalCards.length > 0) {
            noMatchState.style.display = "block";
        } else {
            noMatchState.style.display = "none";
        }
    }

    function sortQuizs() {
        const sortBy = sortSelect.value;

        if (sortBy === "default") {
            originalCards.forEach(card => container.appendChild(card));
            return;
        }

        const sortedCards = [...originalCards];

        sortedCards.sort((cardA, cardB) => {
            let valA, valB;

            if (sortBy.startsWith("alpha")) {
                valA = cardA.getAttribute("data-name");
                valB = cardB.getAttribute("data-name");
            } else if (sortBy.startsWith("author")) {
                valA = cardA.getAttribute("data-author");
                valB = cardB.getAttribute("data-author");
            }

            if (sortBy.endsWith("desc")) {
                return valB.localeCompare(valA);
            } else {
                return valA.localeCompare(valB);
            }
        });

        sortedCards.forEach(card => container.appendChild(card));
    }

    if (searchInput) searchInput.addEventListener("input", filterQuizs);
    if (sortSelect) sortSelect.addEventListener("change", sortQuizs);

    if (mineCheckbox) {
        mineCheckbox.addEventListener("change", filterQuizs);
    }
});
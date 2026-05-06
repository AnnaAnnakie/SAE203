let currentIndex = 0;

function renderQuestion() {
    const question = dataset[currentIndex];

    document.getElementById('question-title').textContent = question.intitule;

    const container = document.getElementById('reponses');
    container.innerHTML = "";

    question.reponses.forEach(reponse => {
        const div = document.createElement('div');
        div.classList.add("question");
        div.innerHTML = `
                    <label for="${reponse.id}">${reponse.content}</label>
                    <input type="checkbox" id="${reponse.id}" name="answer" value="${reponse.id}" class="checkbox" >
                    <img src="assets/check-mark.svg" alt="check">`;
        container.appendChild(div);
    });

    document.getElementById('submit-btn').style.display = "block";

}

document.getElementById('submit-btn').addEventListener('click', () => {
    const inputs = document.querySelectorAll('input[name="answer"]');
    let isCorrect = true;

    inputs.forEach(input => {
        const wasChecked = input.checked;
        const shouldBeChecked = input.dataset.correct === "true";
        if (wasChecked !== shouldBeChecked) isCorrect = false;
    });

    document.getElementById('submit-btn').style.display = "none";
    document.getElementById("next-btn").hidden = false;
});

document.getElementById('next-btn').addEventListener('click', () => {
    if (currentIndex < dataset.length-1) {
        currentIndex++;
        document.getElementById("next-btn").hidden = true;
        renderQuestion();
    }
});

document.getElementById('prev-btn').addEventListener('click', () => {
    if (currentIndex > 0 ) {
        currentIndex--;
        renderQuestion();
    }
});

// Lancement initial
renderQuestion();
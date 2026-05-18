let currentIndex = 0;
let totalScore = 0;
let userSelections = {};

const nextBtn = document.getElementById("next-btn");
const prevBtn = document.getElementById("prev-btn");
const submitBtn = document.getElementById("submit-btn");
const scoreDisplay = document.getElementById("current-score");

function renderQuestion() {
    const question = dataset[currentIndex];
    document.getElementById('question-title').textContent = question.intitule;

    const container = document.getElementById('reponses');
    container.innerHTML = "";

    question.reponses.forEach(reponse => {
        const div = document.createElement('div');
        div.classList.add("question");

        const isChecked = userSelections[question.id] && userSelections[question.id].includes(String(reponse.id));

        div.innerHTML = `
            <label for="${reponse.id}">${reponse.content}</label>
            <input type="checkbox" id="${reponse.id}" name="answer" value="${reponse.id}" class="checkbox" ${isChecked ? 'checked' : ''}>
            <img src="assets/check-mark.svg" alt="check">`;
        container.appendChild(div);
    });

    submitBtn.style.display = "block";
    nextBtn.hidden = true;

    if (userSelections[question.id] !== undefined) {
        lockInputsAndShowCorrection();
    }
}

function lockInputsAndShowCorrection() {
    const inputs = document.querySelectorAll("input[name='answer']");
    const question = dataset[currentIndex];

    inputs.forEach(input => {
        let questionBlock = input.closest('.question');
        let checkbox = questionBlock.querySelector(".checkbox");
        let label = questionBlock.querySelector('label');

        checkbox.disabled = true;
        label.style.setProperty("cursor", "not-allowed");

        const wasChecked = input.checked;
        const shouldBeChecked = question.reponses.find(r => r.id == input.id)["bonne_reponse"] === 1;

        if (wasChecked !== shouldBeChecked && !shouldBeChecked) {
            questionBlock.classList.add("wrong-answer");
            questionBlock.querySelector("img").src = "assets/cross-mark.svg";
        } else if (wasChecked === shouldBeChecked && shouldBeChecked) {
            questionBlock.classList.add("correct-answer");
        } else if (wasChecked !== shouldBeChecked && shouldBeChecked) {
            questionBlock.classList.add("expected-answer");
        }
    });

    submitBtn.style.display = "none";
    nextBtn.hidden = false;

    // Changement de texte dynamique pour le dernier bouton
    if (currentIndex === dataset.length - 1) {
        nextBtn.textContent = "VOIR MON RÉSULTAT";
    } else {
        nextBtn.textContent = "SUIVANTE";
    }
}

submitBtn.addEventListener('click', () => {
    const inputs = document.querySelectorAll("input[name='answer']");
    const question = dataset[currentIndex];

    let selectedIds = [];
    inputs.forEach(input => { if(input.checked) selectedIds.push(input.value); });
    userSelections[question.id] = selectedIds;

    let questionScore = 0;
    const bonnesReponses = question.reponses.filter(r => r.bonne_reponse === 1);
    const totalBonnes = bonnesReponses.length;

    let checkedBonnes = 0;
    let checkedMauvaises = 0;

    inputs.forEach(input => {
        const isGood = question.reponses.find(r => r.id == input.id)["bonne_reponse"] === 1;
        if (input.checked) {
            if (isGood) checkedBonnes++;
            else checkedMauvaises++;
        }
    });

    if (totalBonnes === 1) {
        if (checkedBonnes === 1 && checkedMauvaises === 0) {
            questionScore = 1;
        }
    } else if (totalBonnes > 1) {
        let valeurParReponse = 1 / totalBonnes;
        questionScore = (checkedBonnes * valeurParReponse) - (checkedMauvaises * valeurParReponse);
        if (questionScore < 0) questionScore = 0;
    }

    totalScore += questionScore;
    scoreDisplay.textContent = totalScore.toFixed(2);

    lockInputsAndShowCorrection();
});

nextBtn.addEventListener('click', () => {
    if (currentIndex < dataset.length - 1) {
        currentIndex++;
        prevBtn.disabled = false;
        renderQuestion();
    } else if (currentIndex === dataset.length - 1) {
        // Fin du quiz : Envoi des données en POST sécurisé vers le PHP
        document.getElementById("user-answers-input").value = JSON.stringify(userSelections);
        document.getElementById("finish-form").submit();
    }
});

prevBtn.addEventListener('click', () => {
    if (currentIndex > 0) {
        currentIndex--;
        if (currentIndex === 0) prevBtn.disabled = true;
        renderQuestion();
    }
});

// Lancement initial
renderQuestion();
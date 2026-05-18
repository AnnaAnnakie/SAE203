let currentIndex = 0;
let nextBtn = document.getElementById("next-btn");
let prevBtn = document.getElementById("prev-btn");
let submitBtn = document.getElementById("submit-btn");

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

    submitBtn.style.display = "block";

}

submitBtn.addEventListener('click', () => {
    const inputs = document.querySelectorAll("input[name='answer']");
    let shouldBeChecked = false;

    inputs.forEach(input => {
        let questionBlock = input.closest('.question');
        let checkbox = questionBlock.querySelector(".checkbox");
        let label = questionBlock.querySelector('label');

        checkbox.disabled = true;
        label.style.setProperty("cursor", "not-allowed");

        const wasChecked = input.checked;
        shouldBeChecked = dataset[currentIndex]['reponses'].find(reponse => reponse.id == input.id)["bonne_reponse"] === 1;

        // Si la réponse est fausse
        if (wasChecked !== shouldBeChecked && !shouldBeChecked){
            questionBlock.classList.add("wrong-answer");
            questionBlock.querySelector("img").src = "assets/cross-mark.svg";
        }
        // Si la réponse est bonne
        else if(wasChecked === shouldBeChecked && shouldBeChecked){
            questionBlock.classList.add("correct-answer");
        }
        // Si c'était la réponse attendue
        else if(wasChecked !== shouldBeChecked && shouldBeChecked){
            questionBlock.classList.add("expected-answer");
        }
    });

    submitBtn.style.display = "none";
    nextBtn.hidden = false;
});

nextBtn.addEventListener('click', () => {
    if (currentIndex < dataset.length-1) {
        currentIndex++;
        nextBtn.hidden = true;
        prevBtn.disabled = false;
        renderQuestion();
    }else if (currentIndex === dataset.length - 1){
        window.location.replace("resultat.php");
    }
});

prevBtn.addEventListener('click', () => {
    if (currentIndex > 0 ) {
        currentIndex--;
        if (currentIndex === 0){
            prevBtn.disabled = true;
        }
        nextBtn.hidden = true;
        renderQuestion();
    }
});

// Lancement initial
renderQuestion();
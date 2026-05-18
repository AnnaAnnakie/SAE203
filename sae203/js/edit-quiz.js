const addQuestionBtn = document.getElementById('add-question-btn');
const container = document.getElementById('questions-container');

// Ajout d'une nouvelle question
addQuestionBtn.addEventListener('click', () => {
    const questionCount = container.querySelectorAll('.question-card').length;
    const nextQId = questionCount + 1;

    const newQuestion = document.createElement("div");
    newQuestion.classList.add("question-card");
    newQuestion.id = `q${nextQId}`;
    newQuestion.setAttribute('data-qindex', nextQId);

    newQuestion.innerHTML = `
        <div class="question-header">
            <h3>Question N°${nextQId}</h3>
            <button type="button" class="delete-question-btn">Supprimer la question</button>
        </div>
        <div class="form-group">
            <input type="text" name="questions[${nextQId}][text]" class="question-input" placeholder="Votre question..." required>
        </div>
        <div class="answers-section">
            <h4>Réponses possibles</h4>
            <div class="answers-list">
                <div class="answer-item">
                    <input type="text" name="questions[${nextQId}][answers][1][text]" placeholder="Réponse 1" required>
                    <label class="checkbox-container">
                        <input type="checkbox" name="questions[${nextQId}][answers][1][correct]" value="1">
                        <span class="checkmark"></span> Bonne réponse
                    </label>
                    <button type="button" class="delete-answer-btn">&times;</button>
                </div>
            </div>
            <button type="button" class="add-answer-btn">+ Ajouter une réponse</button>
        </div>
    `;
    container.appendChild(newQuestion);
});

// Check tous les event au sein du container (plutôt que de faire 3 event listener)
container.addEventListener('click', (event) => {

    // Pour ajouter une réponse
    if (event.target.classList.contains('add-answer-btn')) {
        const questionCard = event.target.closest('.question-card');
        const qIndex = questionCard.getAttribute('data-qindex');
        const answersList = questionCard.querySelector('.answers-list');
        const nextAIndex = answersList.querySelectorAll('.answer-item').length + 1;

        const newAnswer = document.createElement('div');
        newAnswer.classList.add('answer-item');
        newAnswer.innerHTML = `
            <input type="text" name="questions[${qIndex}][answers][${nextAIndex}][text]" placeholder="Nouvelle réponse" required>
            <label class="checkbox-container">
                <input type="checkbox" name="questions[${qIndex}][answers][${nextAIndex}][correct]" value="1">
                <span class="checkmark"></span> Bonne réponse
            </label>
            <button type="button" class="delete-answer-btn">&times;</button>
        `;
        answersList.appendChild(newAnswer);
    }

    // Pour supprimer une réponse
    if (event.target.classList.contains('delete-answer-btn')) {
        event.target.closest('.answer-item').remove();
        updateFormIndexes();
    }

    // Pour supprimer la question
    if (event.target.closest('.delete-question-btn')) {
        event.target.closest('.question-card').remove();
        updateFormIndexes();
    }
});

// Fonction magique pour réindexé si on delete une question ou une réponse
function updateFormIndexes() {
    const questionCards = container.querySelectorAll('.question-card');

    questionCards.forEach((card, qIndex) => {
        const newQNum = qIndex + 1;
        card.id = `q${newQNum}`;
        card.setAttribute('data-qindex', newQNum);

        // Update titre question
        card.querySelector('.question-header h3').innerText = `Question N°${newQNum}`;

        // Update input principal question
        card.querySelector('.question-input').name = `questions[${newQNum}][text]`;

        // Si l'input ID caché de la question existe, on met à jour son name
        const qIdInput = card.querySelector('input[type="hidden"][name^="questions"][name$="[id]"]');
        if (qIdInput) qIdInput.name = `questions[${newQNum}][id]`;

        // Update toutes les réponses de cette question
        const answerItems = card.querySelectorAll('.answer-item');
        answerItems.forEach((answer, aIndex) => {
            const newANum = aIndex + 1;
            answer.querySelector('input[type="text"]').name = `questions[${newQNum}][answers][${newANum}][text]`;
            answer.querySelector('input[type="checkbox"]').name = `questions[${newQNum}][answers][${newANum}][correct]`;

            // Si l'input ID de la réponse existe, on gère son name aussi
            const aIdInput = answer.querySelector('input[type="hidden"]');
            if (aIdInput) aIdInput.name = `questions[${newQNum}][answers][${newANum}][id]`;
        });
    });
}
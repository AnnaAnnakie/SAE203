document.addEventListener("DOMContentLoaded", () => {

    // Suppression d'un compte
    const deleteAccountForm = document.getElementById("deleteAccountForm");
    if (deleteAccountForm) {
        deleteAccountForm.addEventListener("submit", function(e) {
            const confirmed = confirm("⚠️ ATTENTION ! Êtes-vous certain de vouloir supprimer définitivement votre compte ainsi que tous vos scores enregistrés ? Cette opération est totalement irréversible.");
            if (!confirmed) e.preventDefault();
        });
    }

    // --- GESTION DU RECADRAGE PHOTO (CROPPER.JS) ---
    const inputAvatar = document.getElementById("input-avatar");
    const cropperModal = document.getElementById("cropper-modal");
    const imageToCrop = document.getElementById("image-to-crop");
    const btnCancel = document.getElementById("btn-cancel-crop");
    const btnValidate = document.getElementById("btn-validate-crop");
    const avatarForm = document.getElementById("avatarForm");
    const base64Input = document.getElementById("avatar_base64_input");

    let cropperInstance = null;

    if (inputAvatar) {
        // Déclenché quand l'utilisateur choisit un fichier image
        inputAvatar.addEventListener("change", function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const file = files[0];

                // Fichier de 4Mo max (limite du format BLOB)
                if (file.size > 4 * 1024 * 1024) {
                    alert("L'image est trop lourde (Max 4 Mo).");
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    // Injecte la source de l'image lue dans la zone de découpe
                    imageToCrop.src = event.target.result;

                    // Affiche la fenêtre de découpe
                    cropperModal.classList.remove("hidden");

                    // Initialise l'instance du plugin Cropper
                    if (cropperInstance) cropperInstance.destroy(); // Nettoie l'ancien résidu s'il y en a un

                    cropperInstance = new Cropper(imageToCrop, {
                        aspectRatio: 1, // Ratio 1:1 parfait (Carré)
                        viewMode: 1,    // Empêche la boîte de sélection de déborder de l'image
                        background: false,
                        movable: true,
                        zoomable: true,
                        rotatable: false,
                        scalable: false
                    });
                };
                reader.readAsDataURL(file);
            }
        });

        btnCancel.addEventListener("click", () => {
            cropperModal.classList.add("hidden");
            if (cropperInstance) cropperInstance.destroy();
            inputAvatar.value = "";
        });

        btnValidate.addEventListener("click", () => {
            if (cropperInstance) {
                const canvas = cropperInstance.getCroppedCanvas({
                    width: 300,
                    height: 300,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high'
                });

                const dataURL = canvas.toDataURL("image/jpeg", 0.85);

                // Placement du résultat dans l'input masqué et envoi du formulaire en POST vers PHP
                base64Input.value = dataURL;
                avatarForm.submit();
            }
        });
    }
});
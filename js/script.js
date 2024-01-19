
function afficherFormulaire(formulaire) {
    var formulaireInscription = document.getElementById("register");
    var formulaireConnexion = document.getElementById("connexion");

    if (formulaire === "inscription") {
        formulaireInscription.style.display = "block";
        formulaireConnexion.style.display = "none";
    } else if (formulaire === "connexion") {
        formulaireInscription.style.display = "none";
        formulaireConnexion.style.display = "block";
    }
}

function togglePasswordVisibility(id) {
    var input = document.getElementById(id);
    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }
}




document.addEventListener('DOMContentLoaded', function () {
    const likeButtons = document.querySelectorAll('.post-action.like');

    likeButtons.forEach(likeButton => {
        likeButton.addEventListener('click', function () {
            const postId = likeButton.getAttribute('data-id_post');

            // Vérifiez si l'utilisateur a déjà aimé ce post
            const hasLiked = likeButton.classList.contains('liked');

            // Envoyez une demande AJAX pour ajouter ou supprimer un like
            const action = hasLiked ? 'remove' : 'add';

            sendLikeRequest(postId, action, (response) => {
                if (response.success) {
                    // Mettez à jour l'apparence du bouton "like"
                    if (action === 'add') {
                        likeButton.classList.add('liked');
                    } else {
                        likeButton.classList.remove('liked');
                    }

                    // Mettez à jour le compteur de likes
                    const likeCountElement = likeButton.querySelector('.like-count');
                    likeCountElement.textContent = response.likeCount;
                }
            });
        });
    });
});

function sendLikeRequest(postId, action, callback) {
    // Envoyez une demande AJAX au serveur pour gérer les likes
    // Assurez-vous de gérer cette demande côté serveur (PHP) pour ajouter ou supprimer le like et mettre à jour la base de données.
    // Le serveur doit renvoyer une réponse JSON au script JavaScript pour indiquer si l'opération a réussi et le nombre de likes actuel pour le post.
}

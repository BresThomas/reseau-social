document.addEventListener("DOMContentLoaded", () => {
    const ouvrirPublicationBtn = document.getElementById("ouvrirPublication");
    const modal = document.getElementById("modal");
    const effacerContenuBtn = document.getElementById("effacerContenu");
    const effacerImageBtn = document.getElementById("effacerImage");
    const imageInput = document.getElementById("image");
    const contenuTextarea = document.getElementById("contenu");

    ouvrirPublicationBtn.addEventListener("click", () => {
        modal.style.display = "block";
    });

    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
});

document.getElementById('copy-link').addEventListener('click', function() {
    var postLink = document.getElementById('post-link');
    
    if (postLink) {
      var textArea = document.createElement("textarea");
      textArea.value = postLink.href;
      document.body.appendChild(textArea);
      textArea.select();
      document.execCommand('copy');
      document.body.removeChild(textArea);
      alert("L'URL du post viens d'ètre copié dans votre presse papier");
    }
  });
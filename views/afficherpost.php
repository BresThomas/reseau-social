<link rel="stylesheet" href="../css/afficherpost.css">



<?php
foreach ($posts as $post): ?>

    <div class="afficherpost">
        <a href="profilViewer.php?user=<?php echo $post['user']; ?>">
            <div class="post">
                <div class="post-header">
                    <img src="../<?php echo $post['user_pp']; ?>" alt="Photo de profil de l'utilisateur"
                         class="post-pp post-pp-hover">
                    <h3 class="post-username">
                        <?php echo $post['user']; ?>
                    </h3>
                </div>
                <p>
                    <?php echo $post['Time']; ?>
                </p>
        </a>
        <hr class="post-divider">
        <p class="post-content">
            <?php echo $post['contenu']; ?>
        </p>
        <?php if (!empty($post['image'])): ?>
            <img src="../<?php echo $post['image']; ?>" alt="Photo de profil" class="post-image" />
        <?php endif; ?>
        <div class="post-actions">
            <button class="post-action like" data-id_post="<?php echo $post['id_post']; ?>"
                    data-liked="<?php echo $post['user_liked'] > 0 ? 'true' : 'false'; ?>">
                ❤️ <?php echo $post['LikeCount']; ?>
            </button>


            <a id="post-link" class="post-action comment" href="post.php?id=<?php echo $post['id_post']; ?>">💬</a>
            <button class="post-action share" id="copy-link">🔗</button>
            <?php if ($is_moderator == 1 || $post['user'] == $user): ?>
                <a class="post-action delete" href="supprimerPost.php?id=<?php echo $post['id_post']; ?>">🗑</a>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>

<script>
    const likeButtons = document.querySelectorAll('.like');

    likeButtons.forEach(button => {
        button.addEventListener('click', () => {
            const id_post = button.dataset.id_post;
            const liked = button.getAttribute('data-liked'); // Récupérer la valeur de l'attribut data-liked
            const formData = new FormData();
            formData.append('id_post', id_post);
            formData.append('liked', liked); // Envoyer l'état actuel du like

            fetch('../Post/likePost', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const currentLikeCount = parseInt(button.textContent.split(' ')[1]);
                        if(data.action === 'liked') {
                            button.textContent = `❤️ ${currentLikeCount + 1}`;
                            button.classList.add('liked');
                        } else if(data.action === 'unliked') {
                            button.textContent = `❤️ ${currentLikeCount - 1}`;
                            button.classList.remove('liked');
                        }
                        // Mettre à jour l'attribut data-liked en fonction de la réponse du serveur
                        if (data.LikeCount > 0) {
                            button.setAttribute('data-liked', 'true');
                        } else {
                            button.setAttribute('data-liked', 'false');
                        }
                    }
                })
                .catch(error => console.log(error));
        });
    });






</script>

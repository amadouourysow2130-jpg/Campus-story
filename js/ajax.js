function chargerStories() {
    const categorie = document.getElementById("categorie")?.value || "";
    const typeExperience = document.getElementById("type_experience")?.value || "";

    fetch("api/get_stories.php?categorie=" + encodeURIComponent(categorie) + "&type_experience=" + encodeURIComponent(typeExperience))
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById("stories-container");

            if (!container) {
                return;
            }

            container.innerHTML = "";

            if (data.length === 0) {
                container.innerHTML = "<p>Aucune story trouvée.</p>";
                return;
            }

            data.forEach(story => {
                const div = document.createElement("div");
                div.style.border = "1px solid #ccc";
                div.style.padding = "15px";
                div.style.marginBottom = "15px";

                let actions = `<a href="story.php?id=${story.id}">Voir plus</a>`;

                if (utilisateurConnecte && nomUtilisateur === story.auteur) {
                    actions += ` | <a href="edit_story.php?id=${story.id}">Modifier</a>`;
                    actions += ` | <a href="delete_story.php?id=${story.id}">Supprimer</a>`;
                }

                div.innerHTML = `
                    <h3>${story.titre}</h3>

                    <p>
                        Auteur : ${story.auteur}<br>
                        Catégorie : ${story.categorie}<br>
                        Type : ${story.type_experience}<br>
                        Date : ${story.date}
                    </p>

                    <p>${story.contenu}</p>

                    <p>
                        Réactions :
                        Utile (${story.reactions.utile}) |
                        Inspirant (${story.reactions.inspirant}) |
                        Pareil (${story.reactions.vecu_pareil}) |
                        Bon conseil (${story.reactions.bon_conseil}) |
                        À éviter (${story.reactions.a_eviter})
                    </p>

                    ${actions}
                `;

                container.appendChild(div);
            });
        })
        .catch(error => {
            console.error("Erreur AJAX :", error);
        });
}

document.addEventListener("DOMContentLoaded", function () {
    chargerStories();

    const filtreForm = document.getElementById("filtre-form");

    if (filtreForm) {
        filtreForm.addEventListener("submit", function(e) {
            e.preventDefault();
            chargerStories();
        });
    }

    const resetFiltre = document.getElementById("reset-filtre");

    if (resetFiltre) {
        resetFiltre.addEventListener("click", function() {
            document.getElementById("categorie").value = "";
            document.getElementById("type_experience").value = "";
            chargerStories();
        });
    }

    setInterval(chargerStories, 5000);
});
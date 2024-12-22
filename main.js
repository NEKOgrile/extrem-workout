// main.js

// === Variables globales ===
let cartCount = 0; // Compteur d'articles dans le panier
const bodyElement = document.body; // Référence au body

// === Fonctions ===

// Fonction pour attendre qu'un élément soit présent dans le DOM
function waitForElement(selector, callback) {
    const interval = setInterval(() => {
        const element = document.querySelector(selector);
        if (element) {
            clearInterval(interval);
            callback(element);
        }
    }, 100); // Vérifie toutes les 100ms
}

function toggleDarkMode() {
    const isDarkMode = bodyElement.classList.toggle('dark-mode');
    
    // Afficher les étoiles et masquer les autres éléments si en mode sombre
    const starElements = document.querySelectorAll('.star-element');
    if (isDarkMode) {
        starElements.forEach(star => star.style.display = 'block'); // Afficher les étoiles
        // Vous pouvez masquer d'autres éléments si nécessaire
    } else {
        starElements.forEach(star => star.style.display = 'none'); // Masquer les étoiles
    }

    // Autres changements relatifs au mode sombre, comme le logo, etc.
    const logoElement = document.getElementById('logo');
    if (logoElement) {
        logoElement.src = isDarkMode ? 'image/grand-logo-sombre.jpg' : 'image/grand-logo.jpg';
    }

    const toggleDarkModeBtn = document.getElementById('toggle-dark-mode');
    if (toggleDarkModeBtn) {
        toggleDarkModeBtn.textContent = isDarkMode
            ? 'Désactiver le mode sombre'
            : 'Activer le mode sombre';
    }
}

// Charger et appliquer les traductions selon la langue
function setLanguage(lang) {
    fetch('./langue/lang.json')
        .then(response => response.json())
        .then(data => {
            if (data[lang]) {
                document.getElementById('title').textContent = data[lang].title;
                document.getElementById('description').textContent = data[lang].description;
            } else {
                console.error('Langue non disponible dans le JSON');
            }
        })
        .catch(error => console.error('Erreur lors du chargement du JSON:', error));
}

// Gérer les clics sur le bouton de recherche
function handleSearch() {
    const query = document.getElementById('search')?.value;
    alert(query ? `Vous avez recherché : ${query}` : 'Veuillez entrer un terme de recherche.');
}

// Ajouter un article au panier
function addToCart() {
    cartCount++;
    document.getElementById('cart-count').textContent = cartCount;
    alert('Ajout d’un article au panier.');
}

// Charger les articles depuis le JSON
function loadArticles(lang) {
    fetch('./langue/lang.json')
        .then(response => response.json())
        .then(data => {
            if (data[lang]?.articles) {
                const articlesContainer = document.querySelector('.articles');
                articlesContainer.innerHTML = '';

                data[lang].articles.forEach(article => {
                    const button = document.createElement('button');
                    button.classList.add('article-button');
                    button.textContent = article.name;
                    button.addEventListener('click', () => {
                        alert(`Vous avez sélectionné : ${article.name}`);
                    });
                    articlesContainer.appendChild(button);
                });
            } else {
                console.error('Aucun article trouvé pour cette langue.');
            }
        })
        .catch(error => console.error('Erreur lors du chargement des articles:', error));
}

// Charger dynamiquement le footer
function loadFooter(lang) {
    // Charger le HTML du footer
    fetch('footer/footer.html')
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erreur HTTP ${response.status} lors du chargement du HTML du footer.`);
            }
            return response.text();
        })
        .then(html => {
            // Insérer le HTML dans le conteneur
            const footerContainer = document.getElementById('footer-container');
            footerContainer.innerHTML = html;
            attachFooterEvents();

            // Charger les données de langue
            return fetch('./langue/lang.json');
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erreur HTTP ${response.status} lors du chargement du fichier JSON.`);
            }
            return response.json();
        })
        .then(data => {
            // Vérifier si les données pour la langue sont disponibles
            if (!data[lang]?.footer?.sections || data[lang].footer.sections.length === 0) {
                console.warn(`Aucune section de footer trouvée pour la langue "${lang}".`);
                return;
            }

            // Créer les sections du footer
            const footer = document.getElementById('footer');
            footer.innerHTML = ''; // Réinitialiser le contenu

            data[lang].footer.sections.forEach(section => {
                addFooterSection(section, footer);
            });
        })
        .catch(error => {
            console.error('Erreur lors du chargement du footer ou des données de langue :', error);
        });
}

/**
 * Ajoute une section au footer.
 * @param {Object} section - La section à ajouter.
 * @param {HTMLElement} footer - L'élément footer cible.
 */
function addFooterSection(section, footer) {
    if (!section.title || !Array.isArray(section.links)) {
        console.warn('Section de footer invalide :', section);
        return;
    }

    // Créer une div pour la section
    const sectionDiv = document.createElement('div');
    sectionDiv.classList.add('footer-section');

    // Ajouter le titre de la section
    const title = document.createElement('h4');
    title.textContent = section.title;
    sectionDiv.appendChild(title);

    // Ajouter la liste de liens
    const list = document.createElement('ul');
    section.links.forEach(link => {
        if (!link.name || !link.url) {
            console.warn('Lien invalide dans la section :', link);
            return;
        }

        const item = document.createElement('li');
        const anchor = document.createElement('a');
        anchor.href = link.url;
        anchor.textContent = link.name;
        item.appendChild(anchor);
        list.appendChild(item);
    });

    sectionDiv.appendChild(list);
    footer.appendChild(sectionDiv);
}
function attachFooterEvents() {
    // Exemple : Ajouter un événement au bouton "open-story" du footer
    const boutonHistoire = document.getElementById('open-story');
    if (boutonHistoire) {
        boutonHistoire.addEventListener('click', openStory);
    } else {
        console.warn('Le bouton "open-story" est introuvable dans le footer.');
    }
}

/**
 * Fonction exécutée lorsqu'on clique sur le bouton d'histoire
 */
function openStory() {
    console.log("Bouton cliqué, histoire ouverte !");
    const storyContainer = document.getElementById('story-container');
    if (storyContainer) {
        storyContainer.style.display = 'flex'; // Affiche l'histoire
    } else {
        console.warn("Le conteneur de l'histoire est introuvable.");
    }
}


// Charger dynamiquement le header
function loadHeader() {
    fetch('header/header.html')
        .then(response => response.text())
        .then(html => {
            document.getElementById('header-container').innerHTML = html;

            // Ajouter les événements pour le header
            const toggleDarkModeBtn = document.getElementById('toggle-dark-mode');
            if (toggleDarkModeBtn) {
                toggleDarkModeBtn.addEventListener('click', toggleDarkMode);
            }
        })
        .catch(error => console.error('Erreur lors du chargement du header:', error));
}

// Charger l'histoire
function histoire(lang) {
    fetch('./langue/lang.json')
        .then(response => response.json())
        .then(data => {
            if (data[lang]?.histoire) {
                document.getElementById('histoire-titre').textContent = data[lang].histoire.titre;
                document.getElementById('histoire-texte').textContent = data[lang].histoire.texte;
            } else {
                console.error('Données d\'histoire non trouvées');
            }
        })
        .catch(error => console.error('Erreur lors du chargement de l\'histoire:', error));
}

// === Initialisation ===
document.addEventListener('DOMContentLoaded', () => {
    loadHeader(); // Charger le header
    setLanguage('fr'); // Charger la langue par défaut
    loadArticles('fr'); // Charger les articles
    loadFooter('fr'); // Charger le footer
    histoire('fr'); // Charger l'histoire

    // Gérer les événements pour les stories
    waitForElement('#open-story', storyButton => {
        waitForElement('.container-black', containerBlack => {
            waitForElement('.story-card', storyCard => {
                storyButton.addEventListener('click', e => {
                    containerBlack.classList.add('active');
                    storyCard.classList.add('active');
                    e.stopPropagation();
                });

                containerBlack.addEventListener('click', () => {
                    containerBlack.classList.remove('active');
                    storyCard.classList.remove('active');
                });

                document.addEventListener('click', e => {
                    if (
                        storyCard.classList.contains('active') &&
                        !storyCard.contains(e.target) &&
                        !storyButton.contains(e.target)
                    ) {
                        containerBlack.classList.remove('active');
                        storyCard.classList.remove('active');
                    }
                });
            });
        });
    });
});
const canvas = document.getElementById("star-element");
const ctx = canvas.getContext("2d");

canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

const stars = [];
const starCount = 200;

// Création des étoiles avec des propriétés aléatoires
for (let i = 0; i < starCount; i++) {
  stars.push({
    x: Math.random() * canvas.width,
    y: Math.random() * canvas.height,
    size: Math.random() * 2 + 1,
    speed: Math.random() * 1, // Réduit la plage de vitesse
});
}

// Fonction pour dessiner une étoile
function drawStar(star) {
  ctx.beginPath();
  ctx.arc(star.x, star.y, star.size, 0, Math.PI * 2);
  ctx.fillStyle = "white";
  ctx.fill();
}

// Animation des étoiles
function animate() {
  // Efface une couche translucide pour un effet fluide
  ctx.fillStyle = "rgba(0, 0, 0, 0.2)";
  ctx.fillRect(0, 0, canvas.width, canvas.height);

  for (const star of stars) {
    drawStar(star);

    // Fait descendre les étoiles
    star.y += star.speed;

    // Recycle les étoiles qui sortent de l'écran
    if (star.y > canvas.height) {
      star.y = -star.size; // Remonte en haut
      star.x = Math.random() * canvas.width; // Position aléatoire
    }
  }

  requestAnimationFrame(animate);
}

// Ajuste la taille du canvas quand la fenêtre est redimensionnée
window.addEventListener("resize", () => {
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
});

animate();

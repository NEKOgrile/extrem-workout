// main.js

// === Variables globales ===
let cartCount = 0; // Compteur d'articles dans le panier
const bodyElement = document.body; // Référence au body
const logoElement = document.getElementById('logo'); // Référence au logo
const toggleDarkModeBtn = document.getElementById('toggle-dark-mode'); // Bouton mode sombre
const languageSelect = document.getElementById('language-select'); // Sélecteur de langue

// === Fonctions ===

// Basculer le mode sombre
function toggleDarkMode() {
    const isDarkMode = bodyElement.classList.toggle('dark-mode'); // Ajoute ou enlève la classe dark-mode
    logoElement.src = isDarkMode
        ? 'image/grand-logo-sombre.jpg' // Logo sombre
        : 'image/grand-logo.jpg'; // Logo clair
    toggleDarkModeBtn.textContent = isDarkMode
        ? 'Désactiver le mode sombre'
        : 'Activer le mode sombre';
}

// Charger et appliquer les traductions selon la langue
function setLanguage(lang) {
    fetch('./langue/lang.json')
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erreur HTTP ! statut : ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data[lang]) {
                document.getElementById('title').textContent = data[lang].title;
                document.getElementById('description').textContent = data[lang].description;
            } else {
                console.error('Langue non disponible dans le JSON');
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement du JSON:', error);
        });
}

// Gérer les clics sur le bouton de recherche
function handleSearch() {
    const query = document.getElementById('search').value;
    if (query) {
        alert(`Vous avez recherché : ${query}`);
    } else {
        alert('Veuillez entrer un terme de recherche.');
    }
}

// Ajouter un article au panier
function addToCart() {
    cartCount++;
    document.getElementById('cart-count').textContent = cartCount;
    alert('Ajout d’un article au panier.');
}

// === Écouteurs d'événements ===

// Basculer le mode sombre
toggleDarkModeBtn.addEventListener('click', toggleDarkMode);

// Gestion du changement de langue
languageSelect.addEventListener('change', (event) => {
    setLanguage(event.target.value);
    loadArticles(event.target.value);

});

// Gestion de la recherche
document.getElementById('search-button').addEventListener('click', handleSearch);

// Ajouter au panier
document.querySelector('.cart').addEventListener('click', addToCart);

// === Initialisation ===
setLanguage('fr'); // Langue par défaut
// Fonction pour charger les articles depuis le fichier JSON
function loadArticles(lang) {
    console.log('test')
    fetch('./langue/lang.json')
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erreur HTTP ! statut : ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data[lang] && data[lang].articles) {
                const articlesContainer = document.querySelector('.articles'); // Conteneur des articles
                articlesContainer.innerHTML = ''; // Vider le conteneur avant d'ajouter des articles

                data[lang].articles.forEach(article => {
                    const button = document.createElement('button'); // Crée un bouton
                    button.classList.add('article-button'); // Ajoute la classe au bouton
                    button.textContent = article.name; // Définit le texte du bouton
                    button.addEventListener('click', () => {
                        alert(`Vous avez sélectionné : ${article.name}`);
                    });
                    articlesContainer.appendChild(button); // Ajoute le bouton dans le conteneur
                });
            } else {
                console.error('Aucun article trouvé pour cette langue.');
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des articles depuis le JSON:', error);
        });
}


// Initialiser les articles avec la langue par défaut
loadArticles('fr');

console.log("rest 2")
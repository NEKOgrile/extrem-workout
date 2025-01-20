<?php
require 'connection_bdd.php'; // Inclusion de la connexion à la base de données

// Récupération des articles depuis la base de données
$query = "SELECT * FROM articles ORDER BY created_at DESC";
$result = $conn->query($query);
$articles = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $articles[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flexbox Layout</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div id="header-container"></div>
    </header>

    <div class="main-container">
        <!-- Articles Section -->
        <div class="articles">
            <!-- Les boutons seront ajoutés dynamiquement ici -->
        </div>

        <!-- Flexbox Section -->
        <div class="flexbox-container">
            <img src="image/grand-logo.jpg" alt="Logo par défaut" class="logo" id="logo">
        </div>
    </div>

    <!-- Section Nouveauté -->
    <div class="articles-nouveaute">
    <?php if (!empty($articles)): ?>
        <?php foreach ($articles as $article): ?>
            <div class="article-item">
                <div class="article-content">
                    <h3 class="article-title"><?= htmlspecialchars($article['title']) ?></h3>
                </div>
                <!-- Appel au script display_image.php -->
                <img src="externe/display_image.php?id=<?= htmlspecialchars($article['id']) ?>" 
                     alt="Image de l'article" 
                     class="article-image" />
                <div class="article-content">
                    <p class="article-description"><?= htmlspecialchars($article['description']) ?></p>
                    <p class="article-price">Prix : <?= number_format($article['price'], 2, ',', ' ') ?> €</p>
                    <p class="article-quantity">Quantité disponible : <?= (int)$article['quantity'] ?></p>
                    <button class="article-action">En savoir plus</button>
                </div>
                <div class="article-footer">
                    <p class="article-date">Ajouté le : <?= date("d/m/Y à H:i", strtotime($article['created_at'])) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun article disponible pour le moment.</p>
    <?php endif; ?>
</div>



    <div id="footer-container"></div>

    <canvas id="star-element" class="star-element"></canvas>

    <script src="main.js"></script>
</body>
</html>

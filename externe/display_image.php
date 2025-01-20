<?php
// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=extrem_workout', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérification et récupération de l'image
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Préparation de la requête
    $stmt = $pdo->prepare("SELECT image FROM articles WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $image = $stmt->fetch(PDO::FETCH_ASSOC)['image'];

        // Définir l'en-tête MIME pour l'image
        header("Content-Type: image/jpeg");
        echo $image;
        exit;
    }
}

// Si aucune image trouvée ou en cas d'erreur, retourner une image par défaut
header("Content-Type: image/jpeg");
readfile('default-image.jpg');

<?php
require '../connection_bdd.php'; // Inclusion de la connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars(trim($_POST['title']));
    $description = htmlspecialchars(trim($_POST['description']));
    $price = (float) $_POST['price'];
    $quantity = (int) $_POST['quantity'];
    $image_data = null;

    // Vérification des champs obligatoires
    if (empty($title) || empty($description) || empty($price) || empty($quantity)) {
        $error = "Tous les champs obligatoires doivent être remplis.";
    } else {
        // Gestion de l'image
        if (!empty($_FILES['image']['name'])) {
            $image_tmp = $_FILES['image']['tmp_name'];
            $image_type = mime_content_type($image_tmp);

            // Vérification du type de fichier
            $valid_extensions = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($image_type, $valid_extensions)) {
                $error = "Seules les images de type JPG, PNG et GIF sont autorisées.";
            } else {
                // Récupérer les données de l'image
                $image_data = file_get_contents($image_tmp);
            }
        }

        // Si tout est valide, insertion dans la base de données
        if (!isset($error)) {
            $stmt = $conn->prepare(
                "INSERT INTO articles (title, description, image, price, quantity) 
                 VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("ssbdi", $title, $description, $null, $price, $quantity);

            if ($image_data !== null) {
                $stmt->send_long_data(2, $image_data);
            }

            if ($stmt->execute()) {
                $success = "Article ajouté avec succès.";
            } else {
                $error = "Erreur lors de l'ajout de l'article : " . $conn->error;
            }

            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un article</title>
    <link rel="stylesheet" href="add_article.css">
</head>
<body>
    <h1>Ajouter un nouvel article</h1>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php elseif (isset($success)): ?>
        <p style="color: green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form action="add_article.php" method="POST" enctype="multipart/form-data">
        <label for="title">Titre de l'article :</label>
        <input type="text" id="title" name="title" required><br><br>

        <label for="description">Description :</label><br>
        <textarea id="description" name="description" rows="5" cols="40" required></textarea><br><br>

        <label for="price">Prix :</label>
        <input type="number" step="0.01" id="price" name="price" required><br><br>

        <label for="quantity">Quantité :</label>
        <input type="number" id="quantity" name="quantity" required><br><br>

        <label for="image">Image :</label>
        <input type="file" id="image" name="image" accept="image/*"><br><br>

        <button type="submit">Ajouter l'article</button>
    </form>
</body>
</html>

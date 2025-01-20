
<?php
require '../connection_bdd.php'; // Inclusion de la connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $date_of_birth = $_POST['date_of_birth'];
    $phone_number = htmlspecialchars(trim($_POST['phone_number']));
    $address = htmlspecialchars(trim($_POST['address']));
    $city = htmlspecialchars(trim($_POST['city']));
    $postal_code = htmlspecialchars(trim($_POST['postal_code']));
    $country = htmlspecialchars(trim($_POST['country']));

    // Vérification des champs obligatoires
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password) || empty($date_of_birth)) {
        die("Tous les champs obligatoires doivent être remplis.");
    }

    // Vérification du mot de passe
    if ($password !== $confirm_password) {
        die("Les mots de passe ne correspondent pas.");
    }

    // Vérification si l'adresse e-mail est déjà utilisée
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        die("Cette adresse e-mail est déjà utilisée.");
    }
    $stmt->close();

    // Vérification si l'adresse physique est déjà utilisée (optionnel)
    if (!empty($address)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE address = ?");
        $stmt->bind_param("s", $address);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            die("Cette adresse est déjà utilisée.");
        }
        $stmt->close();
    }

    // Hashage du mot de passe
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insertion dans la base de données
    $stmt = $conn->prepare(
        "INSERT INTO users (first_name, last_name, email, password_hash, date_of_birth, phone_number, address, city, postal_code, country)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param(
        "ssssssssss",
        $first_name,
        $last_name,
        $email,
        $password_hash,
        $date_of_birth,
        $phone_number,
        $address,
        $city,
        $postal_code,
        $country
    );

    if ($stmt->execute()) {
        echo "Inscription réussie.";
    } else {
        echo "Erreur lors de l'inscription : " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <header>
        <div id="header-container"></div>
    </header>
    <main class="login-container">
        <div class="login-box">
            <h2>Créer un compte</h2>
            <form action="register.php" method="POST">
    <input type="text" name="first_name" placeholder="Prénom" class="input-field" required>
    <input type="text" name="last_name" placeholder="Nom" class="input-field" required>
    <input type="email" name="email" placeholder="Adresse Email" class="input-field" required>
    <input type="password" name="password" placeholder="Mot de passe" class="input-field" required>
    <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" class="input-field" required>
    <input type="tel" name="phone_number" placeholder="Numéro de téléphone" class="input-field">
    <input type="text" name="address" placeholder="Adresse" class="input-field">
    <input type="text" name="city" placeholder="Ville" class="input-field">
    <input type="text" name="postal_code" placeholder="Code Postal" class="input-field">
    <input type="text" name="country" placeholder="Pays" class="input-field">
    <label class="date-of-birth">
        Date de naissance :
        <input type="date" name="date_of_birth" class="input-field" required>
    </label>
    <button type="submit" class="login-button">S'inscrire</button>
</form>

            <p class="register">Déjà un compte ? <a href="#">Se connecter</a></p>
        </div>
    </main>
    <footer>
        <div id="footer-container"></div>
    </footer>
    <canvas id="star-element" class="star-element"></canvas>
    <script src="register.js"></script>
</body>
</html>

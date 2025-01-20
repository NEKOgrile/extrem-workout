<?php
require '../connection_bdd.php'; // Inclusion de la connexion à la base de données

$errors = [];
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
    if (empty($first_name)) $errors['first_name'] = "Le prénom est obligatoire.";
    if (empty($last_name)) $errors['last_name'] = "Le nom est obligatoire.";
    if (empty($email)) $errors['email'] = "L'email est obligatoire.";
    if (empty($password)) $errors['password'] = "Le mot de passe est obligatoire.";
    if (empty($confirm_password)) $errors['confirm_password'] = "La confirmation du mot de passe est obligatoire.";
    if (empty($date_of_birth)) $errors['date_of_birth'] = "La date de naissance est obligatoire.";

    // Vérification des mots de passe
    if ($password !== $confirm_password) {
        $errors['password'] = "Les mots de passe ne correspondent pas.";
        $errors['confirm_password'] = "Les mots de passe ne correspondent pas.";
    }

    // Vérification si l'adresse e-mail est déjà utilisée
    if (empty($errors['email'])) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors['email'] = "Cette adresse e-mail est déjà utilisée.";
        }
        $stmt->close();
    }

    // Hashage et insertion si aucune erreur
    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
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
            header("Location: ../login/login.php");
            exit;
        } else {
            $errors['general'] = "Erreur lors de l'inscription. Veuillez réessayer.";
        }

        $stmt->close();
    }
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
            <?php if (!empty($errors['general'])): ?>
                <p class="error-message"><?= htmlspecialchars($errors['general']) ?></p>
            <?php endif; ?>
            <form action="register.php" method="POST">
                <input type="text" name="first_name" placeholder="Prénom" class="input-field <?= isset($errors['first_name']) ? 'error' : '' ?>" value="<?= htmlspecialchars($first_name ?? '') ?>">
                <?php if (isset($errors['first_name'])): ?><p class="error-message"><?= $errors['first_name'] ?></p><?php endif; ?>
                
                <input type="text" name="last_name" placeholder="Nom" class="input-field <?= isset($errors['last_name']) ? 'error' : '' ?>" value="<?= htmlspecialchars($last_name ?? '') ?>">
                <?php if (isset($errors['last_name'])): ?><p class="error-message"><?= $errors['last_name'] ?></p><?php endif; ?>

                <input type="email" name="email" placeholder="Adresse Email" class="input-field <?= isset($errors['email']) ? 'error' : '' ?>" value="<?= htmlspecialchars($email ?? '') ?>">
                <?php if (isset($errors['email'])): ?><p class="error-message"><?= $errors['email'] ?></p><?php endif; ?>

                <div class="password-container">
                    <input type="password" name="password" placeholder="Mot de passe" class="input-field <?= isset($errors['password']) ? 'error' : '' ?>" id="password">
                    <span class="eye-icon" onclick="togglePassword('password')">👁️</span>
                </div>
                <?php if (isset($errors['password'])): ?><p class="error-message"><?= $errors['password'] ?></p><?php endif; ?>

                <div class="password-container">
                    <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" class="input-field <?= isset($errors['confirm_password']) ? 'error' : '' ?>" id="confirm_password">
                    <span class="eye-icon" onclick="togglePassword('confirm_password')">👁️</span>
                </div>
                <?php if (isset($errors['confirm_password'])): ?><p class="error-message"><?= $errors['confirm_password'] ?></p><?php endif; ?>

                <input type="tel" name="phone_number" placeholder="Numéro de téléphone" class="input-field" value="<?= htmlspecialchars($phone_number ?? '') ?>">
                <input type="text" name="address" placeholder="Adresse" class="input-field" value="<?= htmlspecialchars($address ?? '') ?>">
                <input type="text" name="city" placeholder="Ville" class="input-field" value="<?= htmlspecialchars($city ?? '') ?>">
                <input type="text" name="postal_code" placeholder="Code Postal" class="input-field" value="<?= htmlspecialchars($postal_code ?? '') ?>">
                <input type="text" name="country" placeholder="Pays" class="input-field" value="<?= htmlspecialchars($country ?? '') ?>">
                <label class="date-of-birth">
                    Date de naissance :
                    <input type="date" name="date_of_birth" class="input-field <?= isset($errors['date_of_birth']) ? 'error' : '' ?>" value="<?= htmlspecialchars($date_of_birth ?? '') ?>">
                </label>
                <?php if (isset($errors['date_of_birth'])): ?><p class="error-message"><?= $errors['date_of_birth'] ?></p><?php endif; ?>

                <button type="submit" class="login-button">S'inscrire</button>
            </form>
            <p class="register">Déjà un compte ? <a href="../login/login.php">Se connecter</a></p>
        </div>
    </main>
    <footer>
        <div id="footer-container"></div>
    </footer>
    <canvas id="star-element" class="star-element"></canvas>
    <script src="register.js"></script>
</body>
</html>

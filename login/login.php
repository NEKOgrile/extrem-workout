<?php
require '../connection_bdd.php'; // Inclusion de la connexion à la base de données
session_start();

$message = ""; // Message pour afficher les erreurs

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['password']);

    // Vérifier si l'email existe dans la base de données
    $stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $message = "Email ou mot de passe incorrect.";
    } else {
        $user = $result->fetch_assoc();

        // Vérification du mot de passe
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id']; // Stocke l'ID utilisateur dans la session
            header("Location: ../main.php"); // Redirection vers la page principale
            exit;
        } else {
            $message = "Email ou mot de passe incorrect.";
        }
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Connexion</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <header>
        <div id="header-container"></div>
    </header>
    <main class="login-container">
        <div class="login-box">
            <h2>Connexion</h2>
            <?php if (!empty($message)): ?>
                <p class="error-message"><?= htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <input type="email" name="email" placeholder="Adresse e-mail" class="input-field" required>
                <input type="password" name="password" placeholder="Mot de passe" class="input-field" required>
                <div class="options">
                    <label>
                        <input type="checkbox"> Se souvenir de moi
                    </label>
                    <a href="#" class="forgot-password">Mot de passe oublié ?</a>
                </div>
                <button type="submit" class="login-button">Se connecter</button>
            </form>
            <p class="register">Pas encore de compte ? <a href="../register/register.php">S'inscrire</a></p>
        </div>
    </main>
    <footer>
        <div id="footer-container"></div>
    </footer>
    <canvas id="star-element" class="star-element"></canvas>
</body>
</html>

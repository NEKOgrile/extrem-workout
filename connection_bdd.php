<?php
// Paramètres de connexion
$host = 'localhost'; // Serveur MySQL
$username = 'root';  // Nom d'utilisateur MySQL
$password = '';      // Mot de passe MySQL (laisser vide pour WAMP ou XAMPP par défaut)
$database = 'extrem_workout'; // Nom de la base de données

// Création de la connexion
$conn = new mysqli($host, $username, $password, $database);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}
?>

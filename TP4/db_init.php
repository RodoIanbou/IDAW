<?php

// Configuration de la base de données
$db_host = 'localhost';
$db_name = 'users';
$db_user = 'root';
$db_pass = '';

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
} catch(PDOException $e) {
    die("Impossible de se connecter à la base de données : " . $e->getMessage());
}

// Suppression de toutes les tables existantes
$pdo->exec('SET foreign_key_checks = 0'); // désactiver la vérification des clés étrangères
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
foreach ($tables as $table) {
    $pdo->exec("DROP TABLE IF EXISTS `$table`");
}
$pdo->exec('SET foreign_key_checks = 1'); // réactiver la vérification des clés étrangères

// Importation de la structure de la base de données
$sql = file_get_contents('C:\wamp64\www\IDAW\TP4\users.sql');
$pdo->exec($sql);

// Importation des données de test
$sql = file_get_contents('C:\wamp64\www\IDAW\TP4\users.sql');
$pdo->exec($sql);

echo 'La base de données a été réinitialisée avec succès !';

?>
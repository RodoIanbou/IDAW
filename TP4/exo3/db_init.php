<!DOCTYPE html>
<html>
  <head>
    <title>Users</title>
    <meta charset='utf-8'>
  </head>
  <body>



<?php

// Configuration de la base de données
$db_host = 'localhost';
$db_name = 'dbtest';
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
$sql = file_get_contents('dbtest.sql');
$pdo->exec($sql);

// Importation des données de test
$sql = file_get_contents('dbtest.sql');
$pdo->exec($sql);

    echo 'La base de données a été réinitialisée avec succès !';

    
    require_once('config.php');

    // connexion à la base de données
    $connectionString = "mysql:host=". _MYSQL_HOST;
    if (defined('_MYSQL_PORT'))
      $connectionString .= ";port=". _MYSQL_PORT;
    $connectionString .= ";dbname=" . _MYSQL_DBNAME;
    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' );
    $pdo = NULL;
    try {
      $pdo = new PDO($connectionString,_MYSQL_USER,_MYSQL_PASSWORD,$options);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $erreur) {
      echo 'Erreur : '.$erreur->getMessage();
    }

    // requête SQL pour récupérer les utilisateurs
    $request = $pdo->prepare("select * from users");
    $request->execute();
    $users = $request->fetchAll(PDO::FETCH_OBJ);

    // affichage des utilisateurs dans un tableau HTML
    if (count($users) > 0) {
      echo "<table>";
      echo "<tr><th>ID</th><th>Nom</th><th>Adresse email</th></tr>";
      foreach ($users as $user) {
        echo "<tr>";
        echo "<td>".$user->id."</td>";
        echo "<td>".$user->name."</td>";
        echo "<td>".$user->email."</td>";
        
        echo "</tr>";
      }
      echo "</table>";
    } else {
      echo "Aucun utilisateur trouvé.";
    }

    // fermeture de la connexion à la base de données
    $pdo = null;
    ?>

  </body>
</html>



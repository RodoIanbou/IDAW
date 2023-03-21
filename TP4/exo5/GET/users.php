<!DOCTYPE html>
<html>
  <head>
    <title>Users</title>
    <meta charset='utf-8'>
  </head>
  <body>

    <?php
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


<!DOCTYPE html>
<html>
  <head>
    <title>Users</title>
    <meta charset='utf-8'>
  </head>
  <body>
    <header>
      <div id="header">
        <h1> Base de données USERS <h1>
      </div>
    </header>

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

    // traitement de l'ajout d'un utilisateur
    if (isset($_POST['valider'])) {
      //On récupère les valeurs entrées par l'utilisateur :
      $name = $_POST['name'];
      $email = $_POST['email'];
  


      // insertion des données dans la base de données
      try {
        $sql = "INSERT INTO users (name, email) VALUES (:name, :email )";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
       
        $stmt->execute();
        echo "Utilisateur ajouté avec succès !";
      } catch (PDOException $e) {
        echo "Erreur : ".$e->getMessage();
      }
    }
    ?>

    <br>  
    <form method="POST">
      <label>name</label>
      <input type="text" name="name">
      <br>
      <label>email</label>
      <input type="email" name="email">
      <br>
      <br>
      <input type="submit" name="valider" value="Ajouter">
    </form>
  </body>
</html>

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
    
    
    if (isset($_POST['valider'])) {
      //On récupère les valeurs entrées par l'utilisateur :
      $name = $_POST['name'];
      $email = $_POST['email'];
      // insertion des données dans la base de données
      try {
        $sql = "INSERT INTO users (name, email) VALUES (:name, :email )";
        $request = $pdo->prepare("select * from users");
        $request->execute();

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
       
        $stmt->execute();
        echo "Utilisateur ajouté avec succès !";
      } catch (PDOException $e) {
        echo "Erreur : ".$e->getMessage();
      }
    
    }

// traitement de la modification d'un utilisateur
if (isset($_POST['modifier'])) {
  $id = $_POST['id'];
  $name = $_POST['name'];
  $email = $_POST['email'];

  // modification des données dans la base de données
  try {
    $sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    echo "Utilisateur modifié avec succès !";
  } catch (PDOException $e) {
    echo "Erreur : ".$e->getMessage();
  }
}

    
     // supression des données dans la base de données 
    if (isset($_POST['delete'])) {
      $id = $_POST['id'];
      try {
        $sql = "DELETE FROM users WHERE id = :id";         // Cela va récupérer l'ID de l'utilisateur à supprimer à partir de la valeur de l'input caché "id" dans le formulaire, puis le supprimer
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();                                            //  redirection vers la page actuelle (pour actualiser le tableau des utilisateurs) après la suppression de l'utilisateur.
      } catch (PDOException $e) {
        echo "Erreur : ".$e->getMessage();
      }
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
        echo '<td>
            <form action="" method="post">
              <button type="submit" name="edit" value="'.$user->id.'">Modifier</button>
              <input type="hidden" name="id" value="'.$user->id.'">
            </form>
          </td>';
        echo '<td><form action="" method="post"><button type="submit" name="delete">Supprimer</button><input type="hidden" name="id" value="'.$user->id.'"></form></td>';
        echo "</tr>";
      }
     echo "</table>";
    } else {
      echo "Aucun utilisateur trouvé.";
    }
    // formulaire de modification
  if (isset($_POST['edit'])) {
  $id = $_POST['edit'];
  $request = $pdo->prepare("SELECT * FROM users WHERE id = :id");
  $request->bindParam(':id', $id);
  $request->execute();
  $user = $request->fetch(PDO::FETCH_OBJ);
  ?>

  <h2>Modifier un utilisateur</h2>
  <form method="POST">
    <label>Nom</label>
    <input type="text" name="name" value="<?php echo $user->name; ?>">
    <br>
    <label>Adresse email</label>
    <input type="email" name="email" value="<?php echo $user->email; ?>">
    <br>
    <br>
    <input type="hidden" name="id" value="<?php echo $user->id; ?>">
    <input type="submit" name="modifier" value="Modifier">
  </form>.


  <?php
  }
  ?>
  

 
    
  

    

    <br> 
    <h2>Ajouter un utilisateur</h2> 
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


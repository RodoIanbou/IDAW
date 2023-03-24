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
define('_MYSQL_HOST','127.0.0.1');
define('_MYSQL_PORT',3306);
define('_MYSQL_DBNAME','dbtest');
define('_MYSQL_USER','root');
define('_MYSQL_PASSWORD','');
?>

    <?php
    require_once('config.php');

    // connexion à la base de données
    $connectionString = "mysql:host="._MYSQL_HOST;
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
      
      // insertion des données dans la base de données
      try {
        $sql = "INSERT INTO users (name) VALUES (:name,)";
        $request = $pdo->prepare("select * from users");
        $request->execute();

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
  
       
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


  // modification des données dans la base de données
  try {
    $sql = "UPDATE users SET name = :name WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
   
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
      echo "<tr><th>ID</th><th>Nom</th><th>CRUD</th></tr>";
      foreach ($users as $user) {
        echo "<tr>";
        echo "<td>".$user->id."</td>";
        echo "<td>".$user->name."</td>";
        
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
    
    <br>
    <input type="hidden" name="id" value="<?php echo $user->id; ?>">
    <input type="submit" name="modifier" value="Modifier">
  </form>.


  <?php
  }
  ?>
  
  <script>
           function onFormSubmit() {
                // prevent the form to be sent to the server
                event.preventDefault();

                let nom = $("#inputNom").val();

                // vérifier si le champ nom est vide
                if (nom.trim() === '') {
                    // vérifier si le message d'erreur existe déjà
                    if ($('#inputNom').siblings('.text-danger').length == 0) {
                        // afficher le message d'erreur à côté du champ nom
                        $('#inputNom').parent().append('<div class="text-danger">This field is required</div>');
                    }
                    return;
                }

                // supprimer le message d'erreur s'il existe déjà
                $('#inputNom').siblings('.text-danger').remove();

                let prenom = $("#inputPrenom").val();


                $("#studentsTableBody").append(`
                    <tr>
                        <td>${nom}</td>

                            <button class="btn btn-primary editBtn">Edit</button>
                        </td>
                        <td>
                            <button class="btn btn-danger deleteBtn">Delete</button>
                        </td>
                    </tr>
                `);
                
                $(document).on('click', '.editBtn', function() {
                    // récupérer les données de la ligne
                    let row = $(this).closest('tr');
                    let nom = row.find('td:eq(0)').text();


                    // remplir le formulaire avec les données
                    $('#inputNom').val(nom);


                    // supprimer la ligne
                    row.remove();
                });

                $(document).on('click', '.deleteBtn', function() {
                    $(this).closest('tr').remove();
                });

                document.getElementById("addStudentForm").reset();

            }
            
            $(document).ready(function(){
    $('#ajouter-utilisateur').on('submit', function(e){
        e.preventDefault(); // Empêche le formulaire de se soumettre normalement
        var nom = $('#nom').val(); // Récupère la valeur du champ nom
        var prenom = $('#prenom').val(); // Récupère la valeur du champ prénom
        var url = 'http://localhost/IDAW/TP4/exo5/xxx'; // URL e API

        // Envoie la requête AJAX avec jQuery
        $.ajax({
            type: 'POST',
            url: ur
            data: {nom: nom},
            success: function(response){
                // Si la requête réussit, affiche un message de confirmation
                alert('Utilisateur ajouté avec succès !');
            },
            error: function(xhr, status, error){
                // Si la requête échoue, affiche un message d'erreur
                alert('Erreur : ' + xhr.responseText);
            }
        });
    });
});

        </script>

        


<br> 
    <h2>Ajouter un utilisateur</h2> 
    <form method="POST" id="addStudentForm" action="" onsubmit="onFormSubmit();">
    <label for="inputNom" class="col-sm-2 col-form-label">Nom</label>
                <div class="col-sm-3">
                <input type="text" class="form-control" id="inputNom" >
      <br>
      
      <br>
      <br>
      <input type="submit" name="valider" value="Ajouter">
    </form>
  </body>
</html>
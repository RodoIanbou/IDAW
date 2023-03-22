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

    
    // Requête GET pour récupérer tous les utilisateurs de la base
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $sql = "SELECT * FROM users";
      try{ 
        $request = $pdo->prepare("select * from users");
        $request->execute();
        $users = $request->fetchAll(PDO::FETCH_OBJ);

        $pdo=NULL;

    header('Content-Type: application/json');
    echo json_encode($users);
    // affichage des utilisateurs dans un tableau HTML
    }
    catch (PDOException $e) {
      header("HTTP/1.1 500 Internal Server Error");
      echo "Erreur de connexion à la base de données : " . $e->getMessage();
  }
}
    
    // Ajout d'un utilsateur avec la requête POST
    
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // vérification que les paramètres ont été fournis
      if (!isset($_POST['name']) || !isset($_POST['email'])) {
          header("HTTP/1.1 400 Bad Request");
          echo "Veuillez fournir un nom et une adresse e-mail pour créer un utilisateur.";
          exit();
      }
  
      //Création de l'utilisateur dans la base de données
      try {
          $pdo = new PDO($connectionString, _MYSQL_USER, _MYSQL_PASSWORD, $options);
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
          // récupération des données depuis la requête cURL POST
          $data = json_decode(file_get_contents('php://input'), true);
          $name = $data['name'];
          $email = $data['email'];
          $sql = "INSERT INTO users (name, email) VALUES (:name, :email)";
          $stmt = $pdo->prepare($sql);
          $stmt->bindParam(':name', $name);
          $stmt->bindParam(':email', $email);
          $stmt->execute();
          $userId = $pdo->lastInsertId();
          $pdo = null;
  
          // envoi de la réponse avec le code HTTP 201 Created
          header("HTTP/1.1 201 Created");
          header("Location: /users.php/$userId");
          header("Content-Type: application/json");
          $user = array(
              "id" => $userId,
              "name" => $name,
              "email" => $email
          );
          echo json_encode($user);
      } catch (PDOException $e) {
          header("HTTP/1.1 500 Internal Server Error");
          echo "Erreur lors de la création de l'utilisateur : " . $e->getMessage();
      }
  }
  
    




  // Requête DELETE pour supprimer un utilisateur de la base
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
  // Vérification si l'ID de l'utilisateur à supprimer a été fourni
  if (!isset($_GET['id'])) {
    header("HTTP/1.1 400 Bad Request");
    echo "L'ID de l'utilisateur à supprimer n'a pas été fourni.";
    exit();
  }
  
  $userId = $_GET['id'];
  
  try {
    $request = $pdo->prepare("DELETE FROM users WHERE id = :userId");
    $request->bindParam(':userId', $userId, PDO::PARAM_INT);
    $request->execute();
    
    // Vérification si l'utilisateur a été supprimé
    if ($request->rowCount() === 0) {
      header("HTTP/1.1 404 Not Found");
      echo "L'utilisateur avec l'ID $userId n'a pas été trouvé dans la base de données.";
      exit();
    }
    
    header("HTTP/1.1 204 No Content");
  }
  catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
  }
}





// Requête PUT pour mettre à jour un utilisateur de la base
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
  // Vérification si l'ID de l'utilisateur à mettre à jour a été fourni
  if (!isset($_GET['id'])) {
  header("HTTP/1.1 400 Bad Request");
  echo "L'ID de l'utilisateur à mettre à jour n'a pas été fourni.";
  exit();
  }
  
  $userId = $_GET['id'];
  
  // Vérification si l'utilisateur existe
  $request = $pdo->prepare("SELECT * FROM users WHERE id = :userId");
  $request->bindParam(':userId', $userId, PDO::PARAM_INT);
  $request->execute();
  $user = $request->fetch(PDO::FETCH_OBJ);
  
  if (!$user) {
  header("HTTP/1.1 404 Not Found");
  echo "L'utilisateur avec l'ID $userId n'a pas été trouvé dans la base de données.";
  exit();
  }
  
  // Récupération des données depuis la requête cURL PUT
  $data = json_decode(file_get_contents('php://input'), true);
  $name = isset($data['name']) ? $data['name'] : $user->name;
  $email = isset($data['email']) ? $data['email'] : $user->email;
  
  try {
  $request = $pdo->prepare("UPDATE users SET name = :name, email = :email WHERE id = :userId");
  $request->bindParam(':name', $name);
  $request->bindParam(':email', $email);
  $request->bindParam(':userId', $userId, PDO::PARAM_INT);
  $request->execute();
  
  
  // Récupération de l'utilisateur mis à jour
  $request = $pdo->prepare("SELECT * FROM users WHERE id = :userId");
  $request->bindParam(':userId', $userId, PDO::PARAM_INT);
  $request->execute();
  $user = $request->fetch(PDO::FETCH_OBJ);
  
  // Envoi de la réponse avec le code HTTP 200 OK
  header("HTTP/1.1 200 OK");
  header("Content-Type: application/json");
  echo json_encode($user);
  }
  catch (PDOException $e) {
  header("HTTP/1.1 500 Internal Server Error");
  echo "Erreur de connexion à la base de données : " . $e->getMessage();
  }
  }


// Vérification si l'ID de l'utilisateur à mettre à jour a été fourni
if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
  
  if (!isset($_GET['id'])) {
    header("HTTP/1.1 400 Bad Request");
    echo "L'ID de l'utilisateur à mettre à jour n'a pas été fourni.";
    exit();
  }
  
  $userId = $_GET['id'];
  
  // Vérification si l'utilisateur existe
  $request = $pdo->prepare("SELECT * FROM users WHERE id = :userId");
  $request->bindParam(':userId', $userId, PDO::PARAM_INT);
  $request->execute();
  
  if ($request->rowCount() === 0) {
    header("HTTP/1.1 404 Not Found");
    echo "L'utilisateur avec l'ID $userId n'a pas été trouvé dans la base de données.";
    exit();
  }
  
  // récupération des données depuis la requête cURL PATCH
  $data = json_decode(file_get_contents('php://input'), true);
  $name = isset($data['name']) ? $data['name'] : null;
  $email = isset($data['email']) ? $data['email'] : null;
  
  // Vérification si des données ont été fournies pour la mise à jour
  if ($name === null && $email === null) {
    header("HTTP/1.1 400 Bad Request");
    echo "Veuillez fournir des données valides pour la mise à jour de l'utilisateur.";
    exit();
  }
  
  // Mise à jour de l'utilisateur dans la base de données
  try {
    $sql = "UPDATE users SET";
    if ($name !== null) {
      $sql .= " name = :name";
    }
    if ($email !== null) {
      $sql .= ($name !== null ? "," : "") . " email = :email";
    }
    $sql .= " WHERE id = :userId";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    if ($name !== null) {
      $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    }
    if ($email !== null) {
      $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    }
    $stmt->execute();
    
    // Récupération des données mises à jour de l'utilisateur
    $request = $pdo->prepare("SELECT * FROM users WHERE id = :userId");
    $request->bindParam(':userId', $userId, PDO::PARAM_INT);
    $request->execute();
    $user = $request->fetch(PDO::FETCH_ASSOC);
    
    // Envoi de la réponse avec le code HTTP 200 OK
    header("HTTP/1.1 200 OK");
    header("Content-Type: application/json");
    echo json_encode($user);
  } catch (PDOException $e) {
    header("HTTP/1.1 500 Internal Server Error");
    echo "Erreur lors de la mise à jour de l'utilisateur : " . $e->getMessage();
  }
}




  
    

   



    ?>
Création d'une API REST

 GET
— GET /users.php
— paramètre : aucun
— résultat : retourne tous les utilisateurs de la base (JSON)
Commande à rentrer dans l'invit de commande Windows: curl -X GET http://localhost/IDAW/TP4/exo5/POST_GET/users.php

POST
— POST /users.php
— paramètre : tous les champs de l’utilsateur à créer (name, email) sauf le champ id qui
sera automatique affecté par le serveur
— résultat : retourne le code de statut HTTP 201 (Created) et le champ Location de l’entête HTTP doit contenir l’URL de la nouvelle ressource donc son identifiant, exemple :
Location: /user.php/5 et le corps de la réponse, toutes les infos de l’utilisateur créé au
format JSON
curl -X POST -H "Content-Type: application/json" -d "{\"id\":\"208\", \"name\":\"jean\", \"email\":\"jean@gmail.com\"}" http://localhost/IDAW/TP4/exo5/POST_GET/users.php


DELETE
exemple pour suppprimer l'utilsateur id= 123
curl -X DELETE http://localhost/IDAW/TP4/exo5/POST_GET/users.php?id=123



PATCH

curl -X PUT -H "Content-Type: application/json" -d "{\"name\":\"nouveau_nom\", \"email\":\"nouvel_email@gmail.com\"}" http://localhost/IDAW/TP4/exo5/POST_GET/users.php?id=35


PUT
Dans cet exemple, nous mettons à jour l'utilisateur avec l'ID 208 en spécifiant le nouveau nom et l'adresse e-mail dans le corps de la requête. Nous utilisons également le paramètre id dans l'URL pour spécifier l'ID de l'utilisateur à mettre à jour.

curl -X PUT -H "Content-Type: application/json" -d "{\"name\":\"nouveau_nom\", \"email\":\"nouvel_email@gmail.com\"}" http://localhost/IDAW/TP4/exo5/POST_GET/users.php?id=35

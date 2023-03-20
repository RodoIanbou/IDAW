<!DOCTYPE html>
    <?php
        if(isset($_GET['css'])) {
        $style = $_GET['css'];
        setcookie('style', $style);
}
?>

<?php
// définition des styles disponibles
$styles = ['style1', 'style2'];

// définition du style par défaut
$default_style = 'style1';

// lecture de l'identifiant de style dans les cookies
if(isset($_COOKIE['style']) && in_array($_COOKIE['style'], $styles)) {
  $style = $_COOKIE['style'];
} else {
  $style = $default_style;
}

// inclusion de la feuille de style correspondante
echo '<link rel="stylesheet" href="' . $style . '.css" />';
?>

<html>


<form id="style_form" action="index.php" method="GET">
<select name="css">
<option value="style1">style1</option>
<option value="style2">style2</option>
<link href="styles1.css" rel="stylesheet" />
<link href="styles2.css" rel="stylesheet" />





</select>
<input type="submit" value="Appliquer" />
<table>
<tr>
<th>Login :</th>
<td><input type="text" name="login"></td>
</tr>
<tr>
<th>Mot de passe :</th>
<td><input type="password" name="password"></td>
</tr>
<tr>
<th></th>
<td><input type="submit" value="Se connecter..." /></td>
</tr>
</table>
</form>





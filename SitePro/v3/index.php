<?php
// Inclusion des fichiers nécessaires
require_once("template_header.php");
require_once("template_menu.php");

// Définition de la langue par défaut (français)
$defaultLang = 'fr';

// Récupération de la langue courante depuis le paramètre GET lang
$currentLang = isset($_GET['lang']) ? $_GET['lang'] : $defaultLang;

// Définition de la page courante par défaut
$currentPageId = 'accueil';

// Récupération de la page courante depuis le paramètre GET page
if(isset($_GET['page'])) {
    $currentPageId = $_GET['page'];
}

// Affichage du menu pour la langue courante
renderMenuToHTML($currentPageId, $currentLang);

// Inclusion du contenu de la page courante pour la langue courante

$pageToInclude = $currentLang . '/' . $currentPageId . '.php';

if(is_readable($pageToInclude)) {
    require_once($pageToInclude);
} else {
    
    // Si la page courante n'existe pas dans la langue courante, on affiche un message d'erreur
    require_once("error.php");
}

// Inclusion du footer
require_once("template_footer.php");
?>



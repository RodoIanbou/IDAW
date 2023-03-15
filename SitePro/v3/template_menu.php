<?php
function renderMenuToHTML($currentPageId, $currentLang) {
    // un tableau qui définit la structure du site
    $mymenu = array(
        // idPage => [titre_fr, titre_en]
        'accueil' => array('Accueil', 'Home Page'),
        'cv' => array('Cv', 'CV'),
        'projets' => array('Mes Projets', 'Projects'),
        'infos_techniques' => array('Informations techniques', 'Technical Information'),
        'contact' => array('Contact', 'Contact'),
    );

    echo '<nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">';
    echo '<div class="container">';
    echo '<a class="navbar-brand" href="#page-top">Rodolphe IANBOUKHTINE</a>';
    echo '<button class="navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">';
    echo 'Menu';
    echo '<i class="fas fa-bars"></i>';
    echo '</button>';
    echo '<div class="collapse navbar-collapse" id="navbarResponsive">';
    echo '<ul class="navbar-nav ms-auto">';

    // parcours du tableau $mymenu pour afficher les liens du menu
    foreach ($mymenu as $pageId => $pageParameters) {
        $title = $pageParameters[$currentLang == 'fr' ? 0 : 1]; // utilise le titre correspondant à la langue courante
        $activeClass = ($pageId == $currentPageId) ? 'active' : '';
        echo '<li class="nav-item mx-0 mx-lg-1">';
        echo '<a class="nav-link py-3 px-0 px-lg-3 ' . $activeClass . '" href="index.php?page=' . $pageId . '&lang=' . $currentLang . '">' . $title . '</a>';
        echo '</li>';
    }

    // Ajouter des liens pour passer d'une langue à une autre
    echo '<li class="nav-item mx-0 mx-lg-1" mr-8>';
    echo '<a class="nav-link py-3 px-0 px-lg-3" href="index.php?page=' . $currentPageId . '&lang=fr">Français</a>';
    echo '</li>';
    echo '<li class="nav-item mx-0 mx-lg-1" mr-10>';
    echo '<a class="nav-link py-3 px-0 px-lg-3" href="index.php?page=' . $currentPageId . '&lang=en">English</a>';
    echo '</li>';

    echo '</ul>';
    echo '</div>';
    echo '</div>';
    echo '</nav>';
}
?>


<?php
function renderMenuToHTML($currentPageId) {
    // un tableau qui définit la structure du site
    $mymenu = array(
        // idPage => [titre]
        'index' => array('Accueil', 'index.php'),
        'cv' => array('Cv'),
        'projets' => array('Mes Projets'),
        'infos_techniques' => array('Informations techniques'),

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

    foreach ($mymenu as $pageId => $pageParameters) {
        $title = $pageParameters[0];
        $activeClass = ($pageId == $currentPageId) ? 'active' : '';
        echo '<li class="nav-item mx-0 mx-lg-1">';
        echo '<a class="nav-link py-3 px-0 px-lg-3 ' . $activeClass . '" href="' . $pageId . '.php">' . $title . '</a>';
        echo '</li>';
    }

    echo '</ul>';
    echo '</div>';
    echo '</div>';
    echo '</nav>';
}
?>


<?php

session_start();

function connecter_utilisateur($utilisateur) {
    $_SESSION['utilisateur'] = $utilisateur;
}

function utilisateur_connecte() {
    return isset($_SESSION['utilisateur']);
}

function obtenir_utilisateur() {
    if (utilisateur_connecte()) {
        return $_SESSION['utilisateur'];
    }
    return null;
}

function deconnecter_utilisateur() {
    session_unset();
    session_destroy();
}

function proteger_page() {
    if (!utilisateur_connecte()) {
        header("Location: login.php");
        exit();
    }
}

?>
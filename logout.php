<?php
// ============================
// BACKEND AMADOU - LOGOUT
// ============================

require_once 'includes/session.php';

// Déconnexion
deconnecter_utilisateur();

// Redirection vers login
header("Location: login.php");
exit();
?>
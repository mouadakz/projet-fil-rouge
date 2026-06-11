<?php
// bda la session
session_start();

// connexion l base de données
require '../config/db.php';

// wach l'admin connecté? ila la, reja3o l login
if (!isset($_SESSION['admin'])) {
 header('Location: ../login.php');
 exit(); 
 }

// 3ed total dyel les voitures
$total_voitures    = $pdo->query("SELECT COUNT(*) FROM voiture")->fetchColumn();
// 3ed total dyel les demandes
$total_demandes    = $pdo->query("SELECT COUNT(*) FROM demande")->fetchColumn();
// 3ed les voitures lli mréservées
$voitures_reservees = $pdo->query("SELECT COUNT(*) FROM voiture WHERE statut='reservee'")->fetchColumn();
?>



<!DOCTYPE html><html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - AutoVent</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>
<!-- nav bar -->
<?php include 'nav.php'; ?>
<div class="dashboard">
    <h1>Tableau de bord</h1>
    <!-- les cartes dyel les stats -->
    <div class="stats">
        <div class="stat-card"><h3>Total Véhicules</h3><p><?= $total_voitures ?></p></div>
        <div class="stat-card"><h3>Total Demandes</h3><p><?= $total_demandes ?></p></div>
        <div class="stat-card"><h3>Véhicules Réservés</h3><p><?= $voitures_reservees ?></p></div>
    </div>
</div>
</body></html>
<?php
session_start(); require '../config/db.php';
if (!isset($_SESSION['client'])) { header('Location: ../login.php'); exit(); }

$stmt = $pdo->prepare("
    SELECT d.commentaire, d.dateDemande, v.marque, v.modele, v.prix, v.carburant, v.couleur, v.statut
    FROM demande d
    JOIN voiture v ON d.idVoiture = v.idVoiture
    WHERE d.idClient = ?
    ORDER BY d.dateDemande DESC
");
$stmt->execute([$_SESSION['client']]);
$demandes = $stmt->fetchAll();
?>
<!DOCTYPE html><html lang="fr"><head>
    <meta charset="UTF-8">
    <title>Mes Demandes - AutoVent</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include 'includes/nav.php'; ?>

<div class="container">
    <h1>Mes Demandes</h1>
    <?php if (!$demandes): ?>
        <div class="empty">
            <p>Vous n'avez aucune demande pour le moment.</p>
            <a href="home.php" style="color:#D60000;">Voir les véhicules</a>
        </div>
    <?php else: ?>
        <?php foreach ($demandes as $d): ?>
        <div class="demande-card">
            <div class="demande-info">
                <h3><?= htmlspecialchars($d['marque'].' '.$d['modele']) ?></h3>
                <p>Prix: <?= number_format($d['prix'], 0, ',', ' ') ?> DH</p>
                <p>Carburant: <?= htmlspecialchars($d['carburant']) ?> | Couleur: <?= htmlspecialchars($d['couleur']) ?></p>
                <p>Date: <?= $d['dateDemande'] ?></p>
                <?php if ($d['commentaire']): ?><p>Commentaire: <?= htmlspecialchars($d['commentaire']) ?></p><?php endif; ?>
            </div>
            <span class="badge <?= $d['statut'] ?>"><?= ucfirst($d['statut']) ?></span>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>

</body></html>
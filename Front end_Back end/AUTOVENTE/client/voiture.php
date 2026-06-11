<?php
session_start(); require '../config/db.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM voiture WHERE idVoiture = ?");
$stmt->execute([$id]);
$voiture = $stmt->fetch();
if (!$voiture) { header('Location: home.php'); exit(); }
?>
<!DOCTYPE html><html lang="fr"><head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($voiture['marque'].' '.$voiture['modele']) ?> - AutoVent</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include 'includes/nav.php'; ?>

<div class="voiture-detail">
    <h1><?= htmlspecialchars($voiture['marque'].' '.$voiture['modele']) ?></h1>
    <div class="info-grid">
        <div class="info-item"><label>Prix</label><span class="prix"><?= number_format($voiture['prix'], 0, ',', ' ') ?> DH</span></div>
        <div class="info-item"><label>Carburant</label><span><?= htmlspecialchars($voiture['carburant']) ?></span></div>
        <div class="info-item"><label>Couleur</label><span><?= htmlspecialchars($voiture['couleur']) ?></span></div>
        <div class="info-item"><label>Statut</label><span><?= htmlspecialchars($voiture['statut']) ?></span></div>
    </div>

    <?php if ($voiture['statut'] === 'disponible'): ?>
        <?php if (isset($_SESSION['client'])): ?>
            <a href="envoyer_demande.php?id=<?= $voiture['idVoiture'] ?>" class="btn-reserver">🚗 Réserver ce véhicule</a>
        <?php else: ?>
            <a href="../login.php" class="btn-reserver">🔑 Connectez-vous pour réserver</a>
        <?php endif; ?>
    <?php else: ?>
        <p style="color:#999; text-align:center;">Ce véhicule n'est plus disponible.</p>
    <?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>


</body></html>
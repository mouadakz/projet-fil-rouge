<?php
session_start(); require '../config/db.php';
if (!isset($_SESSION['client'])) { header('Location: ../login.php'); exit(); }

$idVoiture = (int)($_GET['id'] ?? 0);
$idClient  = $_SESSION['client'];

$stmt = $pdo->prepare("SELECT * FROM voiture WHERE idVoiture = ? AND statut = 'disponible'");
$stmt->execute([$idVoiture]);
$voiture = $stmt->fetch();
if (!$voiture) { header('Location: home.php'); exit(); }

$success = $erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentaire = htmlspecialchars(trim($_POST['commentaire'] ?? ''), ENT_QUOTES, 'UTF-8');
    $stmt = $pdo->prepare("SELECT idDemande FROM demande WHERE idClient = ? AND idVoiture = ?");
    $stmt->execute([$idClient, $idVoiture]);
    if ($stmt->fetch()) {
        $erreur = "Vous avez déjà envoyé une demande pour ce véhicule.";
    } else {
        $pdo->prepare("INSERT INTO demande (commentaire, idClient, idVoiture) VALUES (?, ?, ?)")
            ->execute([$commentaire, $idClient, $idVoiture]);
        $success = "Votre demande a été envoyée avec succès!";
    }
}
?>
<!DOCTYPE html><html lang="fr"><head>
    <meta charset="UTF-8">
    <title>Réservation - AutoVent</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include 'includes/nav.php'; ?>

<div class="reservation-form">
    <h1>Demande de Réservation</h1>
    <div class="voiture-info">
        <h3><?= htmlspecialchars($voiture['marque'].' '.$voiture['modele']) ?></h3>
        <p>Prix: <?= number_format($voiture['prix'], 0, ',', ' ') ?> DH</p>
        <p>Carburant: <?= htmlspecialchars($voiture['carburant']) ?> | Couleur: <?= htmlspecialchars($voiture['couleur']) ?></p>
    </div>

    <?php if ($success): ?>
        <p class="success"><?= $success ?></p>
        <a href="home.php" style="color:#001E50;">← Retour aux véhicules</a>
    <?php else: ?>
        <?php if ($erreur): ?><p class="erreur"><?= $erreur ?></p><?php endif; ?>
        <form method="POST">
            <label style="color:#333; font-size:14px; display:block; margin-bottom:8px;">Commentaire (optionnel)</label>
            <textarea name="commentaire" placeholder="Ajoutez un message..."></textarea>
            <button type="submit" class="btn-submit">Envoyer la demande 🚗</button>
        </form>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
</body></html>
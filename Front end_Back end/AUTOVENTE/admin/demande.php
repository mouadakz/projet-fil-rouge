<?php
// bda la session w connexion l base
session_start(); require '../config/db.php';
// wach l'admin connecté? ila la, reja3o l login
if (!isset($_SESSION['admin'])) { header('Location: ../login.php'); exit(); }

// ila cliqé 3la "annuler", rje3 statut dyel voiture l disponible
if (isset($_GET['annuler'])) {
    $pdo->prepare("UPDATE voiture SET statut='disponible' WHERE idVoiture=?")->execute([(int)$_GET['annuler']]);
    header('Location: demande.php'); exit();
}
// ila cliqé 3la "supprimer", mcha demande men table
if (isset($_GET['supprimer'])) {
    $pdo->prepare("DELETE FROM demande WHERE idDemande=?")->execute([(int)$_GET['supprimer']]);
    header('Location: demande.php'); exit();
}

// jib toutes les demandes m3a info dyel client w voiture
$demandes = $pdo->query("
    SELECT d.idDemande, d.dateDemande, d.commentaire,
           c.nom, c.prenom, c.email, c.telephone,
           v.idVoiture, v.marque, v.modele, v.prix, v.statut
    FROM demande d
    JOIN client c  ON d.idClient  = c.idClient
    JOIN voiture v ON d.idVoiture = v.idVoiture
    ORDER BY d.dateDemande DESC
")->fetchAll();
?>
<!DOCTYPE html><html lang="fr"><head>
    <meta charset="UTF-8"><title>Demandes - AutoVent Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head><body>
<?php include 'nav.php'; ?>
<div class="container">
    <h1>Gestion des Demandes</h1>
    <?php if (!$demandes): ?>
        <!-- ma kayn walu, wri message -->
        <div class="empty"><p>Aucune demande pour le moment.</p></div>
    <?php else: ?>
        <!-- tabla dyel les demandes -->
        <table>
            <tr><th>#</th><th>Client</th><th>Email</th><th>Téléphone</th><th>Voiture</th><th>Prix</th><th>Statut</th><th>Commentaire</th><th>Date</th><th>Actions</th></tr>
            <?php foreach ($demandes as $d): ?>
            <tr>
                <td><?= $d['idDemande'] ?></td>
                <!-- protect men XSS f nom w prenom -->
                <td><?= htmlspecialchars($d['nom'].' '.$d['prenom']) ?></td>
                <td><?= htmlspecialchars($d['email']) ?></td>
                <td><?= htmlspecialchars($d['telephone']) ?></td>
                <td><?= htmlspecialchars($d['marque'].' '.$d['modele']) ?></td>
                <!-- format lprix b espace -->
                <td><?= number_format($d['prix'], 0, ',', ' ') ?> DH</td>
                <!-- badge dyel statut -->
                <td><span class="badge badge-<?= $d['statut'] ?>"><?= ucfirst($d['statut']) ?></span></td>
                <!-- wri commentaire ila kayn, ila la wri tiret -->
                <td><?= $d['commentaire'] ? htmlspecialchars($d['commentaire']) : '-' ?></td>
                <td><?= $d['dateDemande'] ?></td>
                <td>
                    <!-- bouton "disponible" kaytban ghir ila voiture mréservée -->
                    <?php if ($d['statut'] === 'reservee'): ?>
                        <a href="?annuler=<?= $d['idVoiture'] ?>" class="btn-edit" onclick="return confirm('Remettre disponible?')">Disponible</a>
                    <?php endif; ?>
                    <!-- bouton supprimer demande -->
                    <a href="?supprimer=<?= $d['idDemande'] ?>" class="btn-del" onclick="return confirm('Supprimer?')">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>
</body></html>
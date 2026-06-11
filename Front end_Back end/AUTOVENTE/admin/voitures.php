<?php
// bda la session w connexion l base
session_start(); require '../config/db.php';
// wach l'admin connecté? ila la, reja3o l login
if (!isset($_SESSION['admin'])) { header('Location: ../login.php'); exit(); }

// fonction bach upload image dyel voiture
function uploadImg($file) {
    // ila kayn error, rja3 string khawya
    if ($file['error'] !== 0) return '';
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    // wach extension msmou7a?
    if (!in_array($ext, ['jpg','jpeg','png','webp'])) return '';
    // smo b timestamp bach ma ytkararch
    $nom = 'voiture_'.time().'.'.$ext;
    move_uploaded_file($file['tmp_name'], '../assets/image/'.$nom);
    return $nom;
}

// ila soumit formulaire "ajouter"
if (isset($_POST['ajouter'])) {
    // upload image w insert voiture f base
    $img = uploadImg($_FILES['image']);
    $pdo->prepare("INSERT INTO voiture (marque,modele,prix,statut,carburant,couleur,image) VALUES (?,?,?,?,?,?,?)")
        ->execute([$_POST['marque'],$_POST['modele'],$_POST['prix'],$_POST['statut'],$_POST['carburant'],$_POST['couleur'],$img]);
    header('Location: voitures.php'); exit();
}
// ila cliqé 3la supprimer, m7i voiture men base
if (isset($_GET['supprimer'])) {
    $pdo->prepare("DELETE FROM voiture WHERE idVoiture=?")->execute([(int)$_GET['supprimer']]);
    header('Location: voitures.php'); exit();
}

// jib toutes les voitures, les jdad l fo9
$voitures = $pdo->query("SELECT * FROM voiture ORDER BY idVoiture DESC")->fetchAll();
?>
<!DOCTYPE html><html lang="fr"><head>
    <meta charset="UTF-8"><title>Voitures - AutoVent Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head><body>
<?php include 'nav.php'; ?>
<div class="container">
    <h1>Gestion des Voitures</h1>
    <!-- formulaire dyel ajout voiture jdida -->
    <div class="form-card">
        <h2>Ajouter une voiture</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-grid">
                <input type="text"   name="marque"   placeholder="Marque"    required>
                <input type="text"   name="modele"   placeholder="Modèle"    required>
                <input type="number" name="prix"     placeholder="Prix (DH)" required>
                <!-- select dyel statut -->
                <select name="statut">
                    <option value="disponible">Disponible</option>
                    <option value="reservee">Réservée</option>
                </select>
                <!-- select dyel carburant -->
                <select name="carburant">
                    <option value="essence">Essence</option>
                    <option value="diesel">Diesel</option>
                    <option value="electrique">Electrique</option>
                    <option value="hybride">Hybride</option>
                </select>
                <input type="text" name="couleur" placeholder="Couleur" required>
            </div>
            <!-- input dyel image -->
            <input type="file" name="image" accept="image/*" style="margin-bottom:15px;">
            <button type="submit" name="ajouter" class="btn btn-add">Ajouter</button>
        </form>
    </div>

    <!-- tabla dyel toutes les voitures -->
    <h2>Liste des voitures</h2>
    <table>
        <tr><th>Image</th><th>Marque</th><th>Modèle</th><th>Prix</th><th>Statut</th><th>Carburant</th><th>Couleur</th><th>Actions</th></tr>
        <?php foreach ($voitures as $v): ?>
        <tr>
            <!-- wri image, ila ma kaynach wri logo VW -->
            <td><img src="../assets/image/<?= $v['image'] ?: 'vw-logo.png' ?>" class="car-img"></td>
            <!-- protect men XSS -->
            <td><?= htmlspecialchars($v['marque']) ?></td>
            <td><?= htmlspecialchars($v['modele']) ?></td>
            <!-- format lprix b espace -->
            <td><?= number_format($v['prix'], 0, ',', ' ') ?> DH</td>
            <!-- badge dyel statut -->
            <td><span class="badge badge-<?= $v['statut'] ?>"><?= $v['statut'] ?></span></td>
            <td><?= htmlspecialchars($v['carburant']) ?></td>
            <td><?= htmlspecialchars($v['couleur']) ?></td>
            <!-- boutons modifier w supprimer -->
            <td>
                <a href="modifier_voiture.php?id=<?= $v['idVoiture'] ?>" class="btn-edit">Modifier</a>
                <a href="?supprimer=<?= $v['idVoiture'] ?>" class="btn-del" onclick="return confirm('Supprimer?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body></html>
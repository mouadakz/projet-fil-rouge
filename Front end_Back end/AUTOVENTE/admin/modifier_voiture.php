<?php
// bda la session w connexion l base
session_start(); require '../config/db.php';
// wach l'admin connecté? ila la, reja3o l login
if (!isset($_SESSION['admin'])) { header('Location: ../login.php'); exit(); }

// jib id dyel voiture men URL, ila ma kaynch reja3 l liste
$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: voitures.php'); exit(); }

// ila soumit le formulaire
if (isset($_POST['modifier'])) {
    // 7fed image l7aliya bdefault
    $img = $_POST['image_actuelle'];
    // wach upload image jdida?
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        // wach extension msmou7a?
        if (in_array($ext, ['jpg','jpeg','png','webp'])) {
            // smo l fichier b timestamp bach ma ytkararch
            $img = 'voiture_'.time().'.'.$ext;
            move_uploaded_file($_FILES['image']['tmp_name'], '../assets/image/'.$img);
        }
    }
    // update dyel voiture f base
    $pdo->prepare("UPDATE voiture SET marque=?,modele=?,prix=?,statut=?,carburant=?,couleur=?,image=? WHERE idVoiture=?")
        ->execute([$_POST['marque'],$_POST['modele'],$_POST['prix'],$_POST['statut'],$_POST['carburant'],$_POST['couleur'],$img,$id]);
    header('Location: voitures.php'); exit();
}

// jib données dyel voiture b id dyalha
$stmt = $pdo->prepare("SELECT * FROM voiture WHERE idVoiture=?");
$stmt->execute([$id]);
$v = $stmt->fetch();
// ila ma lqatch voiture, reja3 l liste
if (!$v) { header('Location: voitures.php'); exit(); }
?>
<!DOCTYPE html><html lang="fr"><head>
    <meta charset="UTF-8"><title>Modifier Voiture - AutoVent</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head><body>
<?php include 'nav.php'; ?>
<div class="container">
    <h1>Modifier Voiture</h1>
    <div class="form-card">
        <form method="POST" enctype="multipart/form-data">
            <!-- 7fed nom image l7aliya f hidden input -->
            <input type="hidden" name="image_actuelle" value="<?= htmlspecialchars($v['image']) ?>">
            <div class="form-grid">
                <!-- champs dyel infos voiture, protégés men XSS -->
                <div><label>Marque</label><input type="text" name="marque" value="<?= htmlspecialchars($v['marque']) ?>" required></div>
                <div><label>Modèle</label><input type="text" name="modele" value="<?= htmlspecialchars($v['modele']) ?>" required></div>
                <div><label>Prix (DH)</label><input type="number" name="prix" value="<?= $v['prix'] ?>" required></div>
                <!-- select dyel statut, l option l7aliya selected -->
                <div><label>Statut</label>
                    <select name="statut">
                        <option value="disponible" <?= $v['statut']==='disponible'?'selected':'' ?>>Disponible</option>
                        <option value="reservee"   <?= $v['statut']==='reservee'  ?'selected':'' ?>>Réservée</option>
                        <option value="vendue"     <?= $v['statut']==='vendue'    ?'selected':'' ?>>Vendue</option>
                    </select>
                </div>
                <!-- select dyel carburant, l option l7aliya selected -->
                <div><label>Carburant</label>
                    <select name="carburant">
                        <option value="essence"    <?= $v['carburant']==='essence'   ?'selected':'' ?>>Essence</option>
                        <option value="diesel"     <?= $v['carburant']==='diesel'    ?'selected':'' ?>>Diesel</option>
                        <option value="electrique" <?= $v['carburant']==='electrique'?'selected':'' ?>>Electrique</option>
                        <option value="hybride"    <?= $v['carburant']==='hybride'   ?'selected':'' ?>>Hybride</option>
                    </select>
                </div>
                <div><label>Couleur</label><input type="text" name="couleur" value="<?= htmlspecialchars($v['couleur']) ?>" required></div>
            </div>
            <div style="margin-bottom:18px;">
                <label>Image</label>
                <!-- wri image l7aliya ila kayna -->
                <?php if ($v['image']): ?>
                    <img src="../assets/image/<?= htmlspecialchars($v['image']) ?>" style="width:120px;height:80px;object-fit:cover;border-radius:8px;margin:8px 0;display:block;">
                <?php endif; ?>
                <!-- input bach tbdl image -->
                <input type="file" name="image" accept="image/*">
            </div>
            <div style="display:flex;gap:12px;">
                <!-- bouton save w bouton annuler -->
                <button type="submit" name="modifier" class="btn-add">Enregistrer</button>
                <a href="voitures.php" class="btn-edit" style="padding:9px 20px;font-size:14px;">Annuler</a>
            </div>
        </form>
    </div>
</div>
</body></html>
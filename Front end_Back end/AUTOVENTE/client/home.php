<?php
session_start();
require '../config/db.php';

$modele = isset($_GET['modele']) ? $_GET['modele'] : '';
$prix = isset($_GET['prix']) ? $_GET['prix'] : '';
$carburant = isset($_GET['carburant']) ? $_GET['carburant'] : '';

$query = "SELECT * FROM voiture WHERE statut='disponible'";
$params = [];

if ($modele != '') {
    $query .= " AND modele LIKE ?";
    $params[] = "%$modele%";
}
if ($prix != '') {
    $query .= " AND prix <= ?";
    $params[] = $prix;
}
if ($carburant != '') {
    $query .= " AND carburant = ?";
    $params[] = $carburant;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$voitures = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoVent - Home</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include 'includes/nav.php'; ?>

<section class="hero">
    <img src="../assets/image/volksw.png" alt="VW">
    <div class="hero-text">
        <h1>Drive the Future of Volkswagen.</h1>
        <p>Discover the perfect blend of electric innovation and cutting-edge technology at AutoVent.</p>
        <a href="#voitures">Voir les véhicules</a>
    </div>
</section>

<section class="search">
    <form method="GET">
        <input type="text" name="modele" placeholder="Modèle" value="<?php echo $modele; ?>">
        <input type="number" name="prix" placeholder="Prix max" value="<?php echo $prix; ?>">
        <select name="carburant">
            <option value="">Tous</option>
            <option value="electrique" <?php echo $carburant=='electrique'?'selected':''; ?>>Electrique</option>
            <option value="diesel" <?php echo $carburant=='diesel'?'selected':''; ?>>Diesel</option>
            <option value="essence" <?php echo $carburant=='essence'?'selected':''; ?>>Essence</option>
            <option value="hybride" <?php echo $carburant=='hybride'?'selected':''; ?>>Hybride</option>
        </select>
        <button type="submit">Rechercher</button>
    </form>
</section>

<section class="voitures" id="voitures">
    <h2>Featured <span>Vehicles</span></h2>
    <div class="cards-grid">
        <?php if (count($voitures) == 0): ?>
            <p style="color:#999;">Aucun véhicule trouvé.</p>
        <?php else: ?>
            <?php foreach ($voitures as $v): ?>
                <div class="card">
                    <?php if ($v['image']): ?>
                        <img src="../assets/image/<?php echo $v['image']; ?>" class="card-img" alt="<?php echo $v['marque']; ?>">
                    <?php else: ?>
                        <img src="../assets/image/vw-logo.png" class="card-img" alt="VW">
                    <?php endif; ?>
                    <div class="card-body">
                        <h3><?php echo $v['marque'].' '.$v['modele']; ?></h3>
                        <p class="prix"><?php echo number_format($v['prix'], 0, ',', ' '); ?> DH</p>
                        <p>Carburant: <?php echo $v['carburant']; ?></p>
                        <p>Couleur: <?php echo $v['couleur']; ?></p>
                        <a href="voiture.php?id=<?php echo $v['idVoiture']; ?>">Voir détails</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<section class="stats">
    <div class="stat"><h3>1500+</h3><p>CARS SOLD</p></div>
    <div class="stat"><h3>50+</h3><p>LOCATIONS</p></div>
    <div class="stat"><h3>4.9/5</h3><p>CUSTOMER RATING</p></div>
</section>

<?php include 'includes/footer.php'; ?>

</body>
</html>
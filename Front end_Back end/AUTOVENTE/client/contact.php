<?php session_start(); require '../config/db.php'; ?>
<!DOCTYPE html><html lang="fr"><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - AutoVent</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head><body>
<?php include 'includes/nav.php'; ?>

<div class="contact-page">
    <h1>Contactez-nous</h1>
    <p class="subtitle">Notre équipe est disponible pour répondre à toutes vos questions.</p>
    <div class="contact-grid">
        <div class="contact-card">
            <div class="icon"></div><h3>Téléphone</h3>
            <p><a href="tel:+212691055180">+212 691055180</a></p>
        </div>
        <div class="contact-card">
            <div class="icon"></div><h3>Adresse</h3>
            <p>Badriouene Guzenaya V</p><p>Tanger, Maroc</p>
        </div>
        <div class="contact-card">
            <div class="icon"></div><h3>Email</h3>
            <p><a href="mailto:jordanytff@gmail.com">volkswagen@gmail.com</a></p>
        </div>
        <div class="contact-card">
            <div class="icon"></div><h3>Heures d'ouverture</h3>
            <table class="hours-table">
                <tr><td>Lundi - Vendredi</td><td>9h - 18h</td></tr>
                <tr><td>Samedi</td><td>9h - 13h</td></tr>
                <tr><td>Dimanche</td><td>Fermé</td></tr>
            </table>
        </div>
    </div>
    <div class="map-container">
        <iframe src="https://maps.google.com/maps?q=Gzenaya%20Tanger&t=&z=13&ie=UTF8&iwloc=&output=embed" allowfullscreen="" loading="lazy"></iframe>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body></html>
<nav>
    <div class="logo">
        <img src="../assets/image/logo.png" alt="VW">
        <span>Auto<b>Vente</b></span>
    </div>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="contact.php">Contact</a></li>
        <?php if (isset($_SESSION['client'])): ?>
            <li><a href="mes_demandes.php">Mes Demandes</a></li>
            <li><a href="../logout.php" class="btn-nav">Déconnexion</a></li>
        <?php else: ?>
            <li><a href="../login.php">Login</a></li>
            <li><a href="../signup.php" class="btn-nav">Signup</a></li>
        <?php endif; ?>
    </ul>
</nav>
<?php
session_start();

if (isset($_SESSION['client'])) {
    header('Location: client/home.php');
} elseif (isset($_SESSION['admin'])) {
    header('Location: admin/dashboard.php');
} else {
    header('Location: client/home.php');
}
exit();
?>
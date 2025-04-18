<?php
// filepath: c:\xampp\htdocs\3GID\hackaton\php\controllers\logout.php
session_start();
session_destroy();
header('Location: ../../index.php');
exit;
?>
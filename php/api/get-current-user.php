<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['user']) && isset($_SESSION['user']['role'])) {
    echo json_encode(['role' => $_SESSION['user']['role']]);
} else {
    echo json_encode(['role' => null]);
}
?>

<?php
session_start();

function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}

function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

?>
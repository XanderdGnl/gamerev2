<?php
require_once 'connection.php';
require_once 'session.php';

requireLogin();

// 1. Initialize the base redirect path
$redirectUrl = "index.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $user_id = getUserId();
    $post_id = (int) $_POST['post_id'];
    
    $category = $_POST['category'] ?? '';

    $stmt = $pdo->prepare("SELECT 1 FROM post_likes WHERE user_id = ? AND post_id = ?");
    $stmt->execute([$user_id, $post_id]);

    if ($stmt->rowCount() === 0) {
        $insert = $pdo->prepare("INSERT INTO post_likes (user_id, post_id) VALUES (?, ?)");
        $insert->execute([$user_id, $post_id]);
    }

    if (!empty($category)) {
        $redirectUrl .= "?category=" . urlencode($category);
    }
}

header("Location: " . $redirectUrl);
exit;

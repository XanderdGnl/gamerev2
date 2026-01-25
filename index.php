<?php
require_once 'connection.php';
require_once 'session.php'; 

requireLogin();  

$user_id = getUserId();

$categoryStmt = $pdo->query("SELECT DISTINCT category FROM posts");
$categories = $categoryStmt->fetchAll(PDO::FETCH_COLUMN);

$selectedCategory = $_GET['category'] ?? '';

if (!empty($selectedCategory)) {
    $stmt = $pdo->prepare("
        SELECT
            posts.id,
            posts.post_title,
            posts.content,
            posts.category,
            posts.rating,
            posts.created_at,
            users.username,
            (SELECT COUNT(*) FROM post_likes WHERE post_id = posts.id) AS likes_count
        FROM posts
        JOIN users ON posts.user_id = users.id
        WHERE posts.category = ?
        ORDER BY posts.created_at DESC
    ");
    $stmt->execute([$selectedCategory]);
} else {
    $stmt = $pdo->query("
        SELECT
            posts.id,
            posts.post_title,
            posts.content,
            posts.category,
            posts.rating,
            posts.created_at,
            users.username,
            (SELECT COUNT(*) FROM post_likes WHERE post_id = posts.id) AS likes_count
        FROM posts
        JOIN users ON posts.user_id = users.id
        ORDER BY posts.created_at DESC
    ");
}

$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Posts - GameRev</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1e1e2f;
            color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .header-title {
            font-weight: bold;
            color: #0d6efd;
        }
        .card {
            border: none;
            border-radius: 1rem;
            background-color: #2c2f4a;
        }
        .card-title {
            color: #ffffff;
            font-size: 1.5rem;
        }
        .card-subtitle {
            color: #adb5bd;
        }
        .card-text {
            color: #e9ecef;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-outline-danger {
            border-color: #dc3545;
            color: #dc3545;
        }
        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: white;
        }
        .btn-success {
            font-weight: 600;
        }
        .alert-info {
            background-color: #0dcaf0;
            color: #0a0a0a;
        }
        .filter-form select {
            width: 200px;
        }
        .form-select {
            background-color: #1c1f2e;
            border: 1px solid #444;
            color: #f8f9fa;
        }
        .form-select:focus {
            background-color: #1c1f2e;
            border-color: #007bff;
            color: #f8f9fa;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <header class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="add_post.php" class="btn btn-success me-2">➕ Maak post</a>
            <a href="logout.php" class="btn btn-outline-danger">Uitloggen</a>
        </div>
        <h1 class="header-title h3">Alle Game Posts</h1>
    </header>

    <form method="get" class="mb-4 filter-form">
        <label for="category" class="form-label">Filter op categorie:</label>
        <select name="category" id="category" class="form-select d-inline w-auto me-2" onchange="this.form.submit()">
            <option value="">-- Alle categorieën --</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category); ?>"<?php echo $category === $selectedCategory ? ' selected' : ''; ?>>
                    <?php echo htmlspecialchars($category); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <noscript><button type="submit" class="btn btn-primary">Filter</button></noscript>
    </form>

    <?php if (empty($posts)): ?>
        <div class="alert alert-info text-center">Geen posts gevonden in deze categorie.</div>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <article class="card mb-4 shadow-lg">
                <div class="card-body">
                    <h2 class="card-title"><?php echo htmlspecialchars($post['post_title']); ?></h2>
                    <p class="card-subtitle mb-3">
                        <strong><?php echo htmlspecialchars($post['username']); ?></strong> |
                        Categorie: <span class="text-info"><?php echo htmlspecialchars($post['category']); ?></span> |
                        Rating: <span class="text-info"><?php echo htmlspecialchars($post['rating']); ?></span>/5 ⭐ |
                        <small><?php echo date("F j, Y, g:i a", strtotime($post['created_at'])); ?></small>
                    </p>
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>

                    <form method="post" action="like_post.php" class="mt-3 d-inline">
                        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                        <button type="submit" class="btn btn-primary">👍 Like (<?php echo $post['likes_count']; ?>)</button>
                    </form>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>

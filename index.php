<?php
require_once 'connection.php';
require_once 'session.php'; 

requireLogin();  

$user_id = getUserId();

$categoryStmt = $pdo->query("SELECT DISTINCT category FROM posts WHERE category IS NOT NULL AND category != ''");
$categories = $categoryStmt->fetchAll(PDO::FETCH_COLUMN);

$selectedCategory = $_GET['category'] ?? '';

$sql = "
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
    JOIN users ON posts.user_id = users.id";

$params = [];
if (!empty($selectedCategory)) {
    $sql .= " WHERE posts.category = ?";
    $params[] = $selectedCategory;
}

$sql .= " ORDER BY posts.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
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
        body { background-color: #1e1e2f; color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .header-title { font-weight: bold; color: #0d6efd; }
        .card { border: none; border-radius: 1rem; background-color: #2c2f4a; transition: transform 0.2s; }
        .card:hover { transform: translateY(-5px); }
        .card-title { color: #ffffff; font-size: 1.5rem; }
        .card-subtitle { color: #adb5bd; }
        .card-text { color: #e9ecef; }
        .btn-primary { background-color: #007bff; border: none; font-weight: 600; }
        .btn-success { font-weight: 600; }
        .form-select { background-color: #1c1f2e; border: 1px solid #444; color: #f8f9fa; }
        .form-select:focus { background-color: #1c1f2e; color: #fff; border-color: #0d6efd; box-shadow: none; }
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

    <form method="get" class="mb-4 d-flex align-items-center">
        <label for="category" class="me-2">Filter op categorie:</label>
        <select name="category" id="category" class="form-select w-auto" onchange="this.form.submit()">
            <option value="">-- Alle categorieën --</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category); ?>" 
                    <?php echo $category === $selectedCategory ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($selectedCategory)): ?>
            <a href="index.php" class="ms-2 btn btn-sm btn-link text-decoration-none text-secondary">Reset filter</a>
        <?php endif; ?>
    </form>

    <?php if (empty($posts)): ?>
        <div class="alert alert-info text-center border-0" style="background: #2c2f4a; color: #0dcaf0;">
            Geen posts gevonden in deze categorie.
        </div>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <article class="card mb-4 shadow-lg">
                <div class="card-body p-4">
                    <h2 class="card-title"><?php echo htmlspecialchars($post['post_title']); ?></h2>
                    <p class="card-subtitle mb-3 small">
                        <strong><?php echo htmlspecialchars($post['username']); ?></strong> | 
                        <span class="badge bg-primary"><?php echo htmlspecialchars($post['category']); ?></span> | 
                        Rating: <span class="text-warning"><?php echo htmlspecialchars($post['rating']); ?>/5 ⭐</span> | 
                        <?php echo date("d M Y", strtotime($post['created_at'])); ?>
                    </p>
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>

                    <form method="post" action="like_post.php" class="mt-3">
                        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                        <input type="hidden" name="category" value="<?php echo htmlspecialchars($selectedCategory); ?>">
                        <button type="submit" class="btn btn-primary btn-sm">
                            👍 Like (<?php echo $post['likes_count']; ?>)
                        </button>
                    </form>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>

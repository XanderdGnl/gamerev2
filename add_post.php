<?php
require_once 'connection.php';
require_once 'session.php'; 

requireLogin(); 

$user_id = getUserId();

$category = $post_title = $content = $rating = '';
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = trim($_POST['category'] ?? '');
    $post_title = trim($_POST['post_title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $rating = trim($_POST['rating'] ?? '');

    if (!empty($category) && !empty($post_title) && !empty($content) && is_numeric($rating) && $rating >= 1 && $rating <= 5) {
        try {
            $stmt = $pdo->prepare("INSERT INTO posts (user_id, category, rating, post_title, content) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $category, $rating, $post_title, $content]);

            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $error = "Database error: " . htmlspecialchars($e->getMessage());
        }
    } else {
        $error = "Vul alle velden in en zorg dat de beoordeling tussen 1 en 5 ligt.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Post - GameRev</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1e1e2f;
            color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            border: none;
            border-radius: 1rem;
            background-color: #2c2f4a;
        }
        .card-header {
            background: linear-gradient(135deg, #6f42c1, #007bff);
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }
        .card-header h2 {
            margin: 0;
        }
        .form-control {
            background-color: #1c1f2e;
            border: 1px solid #444;
            color: #f8f9fa;
        }
        .form-control:focus {
            background-color: #1c1f2e;
            border-color: #007bff;
            color: #f8f9fa;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .alert-danger {
            background-color: #ff4d4d;
            border: none;
            color: white;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg">
                <div class="card-header text-white text-center py-3">
                    <h2>Maak een nieuwe post</h2>
                </div>
                <div class="card-body text-white ">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select name="category" id="category" class="form-control" required>
                                <option value="">-- Selecteer categorie --</option>
                                <?php
                                $categories = ['Action', 'Adventure', 'RPG', 'Simulation', 'Strategy', 'Sports', 'Indie', 'Horror'];
                                foreach ($categories as $cat) {
                                    $selected = (isset($category) && $category === $cat) ? 'selected' : '';
                                    echo "<option value=\"$cat\" $selected>$cat</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="post_title" class="form-label">Beoordeling(1-5)</label>
                            <input type="number" id="rating" name="rating" min="1" max="5" step="0.1"
                                value="<?= htmlspecialchars($rating ?? '') ?>" required>
                        </div>



                        <div class="mb-3">
                            <label for="post_title" class="form-label">Post Title</label>
                            <input type="text" name="post_title" id="post_title" class="form-control"
                                value="<?= htmlspecialchars($post_title ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea name="content" id="content" rows="6" class="form-control" required><?= htmlspecialchars($content ?? '') ?></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Submit Post</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>


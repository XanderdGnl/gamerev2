<?php
include_once('connection.php');
session_start();

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $check->execute([$username]);

        if ($check->fetch()) {
            $error = "Deze gebruikersnaam bestaat al.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hashedPassword]);
            header("Location: login.php");
            exit;
        }
    } else {
        $error = "Vul alle velden in.";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maak Account - GameRev</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1e1e2f;
            color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            background-color: #2c2f4a;
            border: none;
            border-radius: 1rem;
        }
        .card-header {
            background: linear-gradient(135deg, #198754, #20c997);
            border-radius: 1rem 1rem 0 0;
        }
        .card-header h1 {
            margin: 0;
            font-weight: bold;
        }
        .form-control {
            background-color: #1c1f2e;
            border: 1px solid #444;
            color: #f8f9fa;
        }
        .form-control:focus {
            border-color: #20c997;
            box-shadow: none;
            background-color: #1c1f2e;
            color: #f8f9fa;
        }
        .btn-success {
            background-color: #20c997;
            border: none;
        }
        .btn-success:hover {
            background-color: #159b84;
        }
        a {
            color: #0d6efd;
        }
        a:hover {
            text-decoration: underline;
        }
        .alert-danger {
            background-color: #dc3545;
            border: none;
            color: white;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="card shadow mx-auto" style="max-width: 420px;">
        <div class="card-header text-white text-center py-3">
            <h1 class="h4">Maak een Account</h1>
        </div>
        <div class="card-body px-4 py-4 text-white">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">Gebruikersnaam</label>
                    <input type="text" name="username" class="form-control" id="username" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Wachtwoord</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                </div>

                <button type="submit" class="btn btn-success w-100">Account aanmaken</button>
            </form>

            <div class="text-center mt-3">
                <a href="login.php">⬅ Terug naar login</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

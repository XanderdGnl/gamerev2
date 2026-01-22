<?php
include_once('connection.php');
session_start();

unset($_SESSION['error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['error'] = "Gebruikersnaam of wachtwoord is ongeldig.";
        }
    } else {
        $_SESSION['error'] = "Vul zowel gebruikersnaam als wachtwoord in.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GameRev</title>
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
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            background: linear-gradient(135deg, #6f42c1, #0d6efd);
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }
        .card-header h4 {
            margin: 0;
            font-weight: bold;
        }
        .form-control {
            background-color: #1c1f2e;
            border: 1px solid #444;
            color: #f8f9fa;
        }
        .form-control:focus {
            background-color: #1c1f2e;
            border-color: #0d6efd;
            box-shadow: none;
            color: #f8f9fa;
        }
        .btn-primary {
            background-color: #0d6efd;
            border: none;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #0a58ca;
        }
        .alert-danger {
            background-color: #dc3545;
            border: none;
            color: white;
        }
        a {
            color: #0d6efd;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="card shadow mx-auto" style="max-width: 400px;">
        <div class="card-header text-white text-center py-3">
            <h4>GameRev Login</h4>
        </div>
        <div class="card-body px-4 py-4 text-white">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Gebruikersnaam</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Wachtwoord</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Inloggen</button>
            </form>

            <div class="text-center mt-3">
                <a href="make_account.php">Maak een nieuw account</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
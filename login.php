<?php
session_start();
if (isset($_SESSION['loggedin'])) {
    header("location: books.php");
    exit;
}

$error_message = '';
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 1: $error_message = 'Invalid username or password'; break;
        case 2: $error_message = 'Invalid CAPTCHA answer'; break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Books Collection</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h1 class="display-5 fw-bold text-primary">
                                <i class="bi bi-shield-lock"></i> Login
                            </h1>
                            <p class="text-muted">Access your book collection</p>
                        </div>

                        <?php if ($error_message): ?>
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-circle-fill"></i> <?= $error_message ?>
                            </div>
                        <?php endif; ?>

                        <form action="login-process.php" method="post">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Username</label>
                                <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold text-primary">Security: What is 3 + 4?</label>
                                <input type="number" name="captcha" class="form-control" placeholder="Enter answer" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Login</button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <p class="text-muted small">
                                Demo: admin / password
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
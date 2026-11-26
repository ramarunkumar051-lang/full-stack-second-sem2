<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: books.php');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $captcha_answer = $_POST['captcha_answer'] ?? '';
    
    if ($captcha_answer != '7') {
        $error = 'Security check failed. Please answer the math question correctly.';
    } 
    elseif ($username === 'admin' && $password === 'password') {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = 'admin';
        $_SESSION['user_id'] = 1;
        header('Location: books.php');
        exit();
    } else {
        $error = 'Invalid username or password';
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
    <style>
        body {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            color: #000000;
        }
        .card {
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            border: 1px solid #dee2e6;
            color: #000000;
        }
        .card-header {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: white;
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #6c757d, #495057);
            border: none;
            color: white;
        }
        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #495057, #6c757d);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0 text-center text-white"><i class="bi bi-lock"></i> Login</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Security Check</label>
                                <div class="captcha-container p-3 rounded text-center border">
                                    <strong>What is 3 + 4?</strong>
                                    <input type="number" name="captcha_answer" class="form-control mt-2" required>
                                    <small class="text-muted">Simple math question to verify you're human</small>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary-custom w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
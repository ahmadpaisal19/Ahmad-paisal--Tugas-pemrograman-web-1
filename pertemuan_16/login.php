<?php
session_start();
include 'koneksi.php';

// Jika pengguna sudah login, langsung alihkan ke halaman utama (index.php)
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

// Proses pengecekan akun saat tombol login ditekan
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validasi akun statis sesuai standar admin/admin
    if ($username == 'admin' && $password == 'admin') {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit;
    } else {
        $error = true;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .login-container { max-width: 400px; margin: 100px auto; }
    </style>
</head>
<body>

<div class="container login-container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h4>Login Sistem</h4>
        </div>
        <div class="card-body">
            
            <?php if (isset($error)) : ?>
                <div class="alert alert-danger text-center" role="alert">
                    Username atau Password Salah!
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" id="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>
                <button type="submit" name="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
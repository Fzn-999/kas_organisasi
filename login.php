<?php
include 'koneksi.php';

if (isset($_SESSION['login'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        // Untuk kemudahan testing sesuai Blackbox Testing: admin / admin123
        if ($password === $row['password']) {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
            header("Location: dashboard.php");
            exit;
        }
    }
    $error = "Username atau password salah!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistem Pengelolaan Kas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #e0f2fe, #f0f9ff); height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 400px; background: white; padding: 30px; }
    </style>
</head>
<body>
<div class="login-card text-center">
    <div class="mb-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#0d6efd" class="bi bi-wallet2" viewBox="0 0 16 16">
          <path d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499L12.136.326zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484L5.562 3zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-13z"/>
        </svg>
    </div>
    <h5 class="fw-bold text-uppercase">Sistem Pengelolaan Kas<br><span class="text-primary" style="font-size: 14px;">Organisasi Mahasiswa</span></h5>
    <p class="text-muted small">Silakan login untuk melanjutkan</p>
    
    <?php if ($error): ?>
        <div class="alert alert-danger p-2 small"><?= $error; ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="mb-3 text-start">
            <div class="input-group">
                <span class="input-group-text">👤</span>
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
        </div>
        <div class="mb-4 text-start">
            <div class="input-group">
                <span class="input-group-text">🔒</span>
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
        </div>
        <button type="submit" name="login" class="btn btn-primary w-100 py-2 fw-semibold">LOGIN</button>
    </form>
</div>
</body>
</html>
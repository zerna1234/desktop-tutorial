<?php
// Prevent duplicate session start notices
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Check if already logged in - using the exact session key checked by admin_dashboard.php
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin/admin_dashboard.php");
    exit;
}

// 2. Database path resolution
if (file_exists(__DIR__ . '/db.php')) {
    require_once __DIR__ . '/db.php';
} elseif (file_exists(__DIR__ . '/../db.php')) {
    require_once __DIR__ . '/../db.php';
}

$error_message = '';

// 3. Handle login submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        // Simple credential check (Adjust 'admin123' if needed)
        if ($username === 'admin' && $password === 'admin123') {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;

            // Redirect to dashboard using relative path
            header("Location: admin/admin_dashboard.php");
            exit;
        } else {
            $error_message = "Invalid username or password.";
        }
    } else {
        $error_message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #121212; color: #fff; font-family: 'Poppins', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: #1a1a1a; border: 1px solid #333; border-radius: 10px; padding: 40px; width: 100%; max-width: 400px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); box-sizing: border-box; }
        h2 { color: #fff; margin-top: 0; margin-bottom: 25px; text-align: center; font-size: 22px; }
        .form-group { margin-bottom: 20px; }
        label { color: #8b949e; font-size: 12px; display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 12px; background: #0d1117; border: 1px solid #30363d; color: #fff; border-radius: 4px; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        .btn-login { background: #4facfe; color: #fff; border: none; padding: 12px; width: 100%; border-radius: 4px; cursor: pointer; font-weight: 600; font-size: 14px; margin-top: 10px; }
        .btn-login:hover { background: #00f2fe; }
        .error { background: rgba(220, 53, 69, 0.1); border: 1px solid #dc3545; color: #dc3545; padding: 10px; border-radius: 4px; font-size: 13px; margin-bottom: 20px; text-align: center; }
    </style>
</head>
<body>

<div class="login-card">
    <h2>Admin Portal Login</h2>

    <?php if (!empty($error_message)): ?>
        <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required placeholder="Enter username">
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required placeholder="Enter password">
        </div>

        <button type="submit" class="btn-login">Login to Dashboard</button>
    </form>
</div>

</body>
</html>
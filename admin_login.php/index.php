<?php
session_start();
require_once __DIR__ . '/../db.php';

if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id']       = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: admin_dashboard.php");
            exit;
        } else {
            $error = "Invalid admin username or password.";
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - MZ Tech Solution</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background: #0a0a0c;
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow: hidden;
        }
        .login-card {
            background: #141419;
            border: 1px solid #2a2a35;
            border-radius: 12px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6);
            position: relative;
        }
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .brand-title {
            text-align: center;
            font-size: 1.6rem;
            font-weight: 700;
            color: #4facfe;
            margin-bottom: 5px;
        }
        .brand-sub {
            text-align: center;
            font-size: 0.85rem;
            color: #8888a0;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .error-alert {
            background: rgba(220, 53, 69, 0.15);
            border: 1px solid #dc3545;
            color: #ff6b6b;
            padding: 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            color: #b0b0c0;
            font-size: 0.85rem;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            background: #1c1c24;
            border: 1px solid #333345;
            border-radius: 6px;
            color: #ffffff;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.3s ease;
        }
        .form-group input:focus {
            border-color: #4facfe;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            border-radius: 6px;
            color: #000;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: opacity 0.3s ease;
            margin-top: 10px;
        }
        .btn-login:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h2 class="brand-title">MZ Tech Solution</h2>
        <p class="brand-sub">Admin Authentication</p>

        <?php if ($error): ?>
            <div class="error-alert"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Admin Username</label>
                <input type="text" id="username" name="username" placeholder="Enter username" required autocomplete="off">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter password" required>
            </div>

            <button type="submit" class="btn-login">Sign In to Dashboard</button>
        </form>
    </div>

</body>
</html>
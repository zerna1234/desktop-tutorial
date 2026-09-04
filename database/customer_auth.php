<?php
session_start();
require_once __DIR__ . '/db.php';

// Redirect if already logged in
if (isset($_SESSION['customer_id'])) {
    header("Location: customer_dashboard.php");
    exit;
}

$error = '';
$success = '';

// 1. REGISTRATION HANDLER
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($fullname) || empty($email) || empty($password)) {
        $error = "All registration fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        try {
            $stmt = $pdo->prepare("INSERT INTO customers (fullname, email, password) VALUES (:name, :email, :pass)");
            $stmt->execute([
                ':name'  => htmlspecialchars($fullname),
                ':email' => $email,
                ':pass'  => $hashedPassword
            ]);
            $success = "Registration successful! You can now log in.";
        } catch (PDOException $e) {
            $error = ($e->getCode() == 23000) ? "This email is already registered." : "Database error: " . $e->getMessage();
        }
    }
}

// 2. LOGIN HANDLER (From Modal or Page)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please fill in all login fields.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM customers WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['customer_id']   = $user['id'];
            $_SESSION['customer_name'] = $user['fullname'];
            header("Location: customer_dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    }
}

$pageTitle = "Customer Portal - MZ Tech Solution";
if (file_exists(__DIR__ . '/header.php')) include __DIR__ . '/header.php';
?>

<section style="max-width: 900px; margin: 60px auto; color: #fff; padding: 20px; font-family: 'Poppins', sans-serif;">
    <h2 style="text-align: center; color: #4facfe;">Customer Access</h2>

    <?php if ($error): ?>
        <div style="background: #5c1d24; border: 1px solid #842029; color: #f8d7da; padding: 12px; border-radius: 6px; margin-bottom: 20px; text-align: center;">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div style="background: #0f5132; border: 1px solid #0f5132; color: #d1e7dd; padding: 12px; border-radius: 6px; margin-bottom: 20px; text-align: center;">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <div style="display: flex; gap: 30px; flex-wrap: wrap; margin-top: 30px;">
        <!-- LOGIN CARD -->
        <div style="flex: 1; min-width: 280px; background: #1a1a1a; padding: 30px; border-radius: 8px; border: 1px solid #333;">
            <h3 style="color: #4facfe; margin-top: 0;">Login</h3>
            <form method="POST" action="customer_auth.php">
                <input type="hidden" name="action" value="login">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; color: #ccc; margin-bottom: 5px;">Email Address</label>
                    <input type="email" name="email" required style="width: 100%; padding: 10px; background: #222; border: 1px solid #444; color: #fff; border-radius: 5px; box-sizing: border-box;">
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; color: #ccc; margin-bottom: 5px;">Password</label>
                    <input type="password" name="password" required style="width: 100%; padding: 10px; background: #222; border: 1px solid #444; color: #fff; border-radius: 5px; box-sizing: border-box;">
                </div>
                <button type="submit" style="width: 100%; padding: 12px; background: #4facfe; color: #fff; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">Login</button>
            </form>
        </div>

        <!-- REGISTRATION CARD -->
        <div style="flex: 1; min-width: 280px; background: #1a1a1a; padding: 30px; border-radius: 8px; border: 1px solid #333;">
            <h3 style="color: #00f2fe; margin-top: 0;">Create Account</h3>
            <form method="POST" action="customer_auth.php">
                <input type="hidden" name="action" value="register">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; color: #ccc; margin-bottom: 5px;">Full Name</label>
                    <input type="text" name="fullname" required style="width: 100%; padding: 10px; background: #222; border: 1px solid #444; color: #fff; border-radius: 5px; box-sizing: border-box;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; color: #ccc; margin-bottom: 5px;">Email Address</label>
                    <input type="email" name="email" required style="width: 100%; padding: 10px; background: #222; border: 1px solid #444; color: #fff; border-radius: 5px; box-sizing: border-box;">
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; color: #ccc; margin-bottom: 5px;">Password</label>
                    <input type="password" name="password" required style="width: 100%; padding: 10px; background: #222; border: 1px solid #444; color: #fff; border-radius: 5px; box-sizing: border-box;">
                </div>
                <button type="submit" style="width: 100%; padding: 12px; background: #00f2fe; color: #000; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">Register</button>
            </form>
        </div>
    </div>
</section>

<?php if (file_exists(__DIR__ . '/footer.php')) include __DIR__ . '/footer.php'; ?>
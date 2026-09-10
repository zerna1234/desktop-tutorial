<?php
session_start();
require_once __DIR__ . '/db.php';

// Disable pop-up modal rendering
$hideModal = true;

// Redirect if logged in
if (isset($_SESSION['customer_id'])) {
    header("Location: customer_dashboard.php");
    exit;
}

$error = '';

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
            // Set created_at AND last_login at registration time
            $stmt = $pdo->prepare("INSERT INTO customers (fullname, email, password, created_at, last_login) VALUES (:name, :email, :pass, NOW(), NOW())");
            $stmt->execute([
                ':name'  => htmlspecialchars($fullname),
                ':email' => $email,
                ':pass'  => $hashedPassword
            ]);

            // Auto-login newly created account
            $_SESSION['customer_id']   = $pdo->lastInsertId();
            $_SESSION['customer_name'] = htmlspecialchars($fullname);

            header("Location: customer_dashboard.php");
            exit;

        } catch (PDOException $e) {
            $error = ($e->getCode() == 23000) ? "This email is already registered." : "Database error: " . $e->getMessage();
        }
    }
}

// 2. LOGIN HANDLER
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
            
            // --- UPDATE LAST_LOGIN TIMESTAMP ---
            $updateStmt = $pdo->prepare("UPDATE customers SET last_login = NOW() WHERE id = :id");
            $updateStmt->execute([':id' => $user['id']]);
            // -----------------------------------

            header("Location: customer_dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    }
}

$pageTitle = "Customer Registration - MZ Tech Solution";
if (file_exists(__DIR__ . '/header.php')) include __DIR__ . '/header.php';
?>

<section style="max-width: 500px; margin: 0 auto; padding: 120px 20px 60px 20px; color: #fff; font-family: 'Poppins', sans-serif;">
    <h2 style="text-align: center; color: #4facfe; margin-bottom: 25px; font-size: 2rem;">Customer Registration</h2>

    <?php if ($error): ?>
        <div style="background: #5c1d24; border: 1px solid #842029; color: #f8d7da; padding: 12px; border-radius: 6px; margin-bottom: 25px; text-align: center;">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <!-- REGISTRATION CARD ONLY -->
    <div style="background: #1a1a1a; padding: 35px; border-radius: 8px; border: 1px solid #333;">
        <h3 style="color: #00f2fe; margin-top: 0; margin-bottom: 20px; text-align: center;">Create Account</h3>
        <form method="POST" action="">
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
</section>

<?php if (file_exists(__DIR__ . '/footer.php')) include __DIR__ . '/footer.php'; ?>
<?php
session_start();

// Protection Guard: Block logged-out visitors
if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_auth.php");
    exit;
}

// Logout Action
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['customer_id']);
    unset($_SESSION['customer_name']);
    header("Location: customer_auth.php");
    exit;
}

$pageTitle = "Customer Dashboard - MZ Tech Solution";
if (file_exists(__DIR__ . '/header.php')) include __DIR__ . '/header.php';
?>

<section style="max-width: 800px; margin: 80px auto; color: #fff; text-align: center; padding: 20px; font-family: 'Poppins', sans-serif;">
    <div style="background: #1a1a1a; padding: 50px 30px; border-radius: 10px; border: 1px solid #333; box-shadow: 0 4px 15px rgba(0,0,0,0.5);">
        <h1 style="color: #4facfe; margin-top: 0; font-size: 32px;">Welcome, <?php echo htmlspecialchars($_SESSION['customer_name']); ?>!</h1>
        <p style="color: #ccc; font-size: 16px; margin-top: 10px;">You are authenticated in your MZ Tech Solution customer account area.</p>
        
        <div style="margin-top: 35px; display: flex; justify-content: center; gap: 15px;">
            <a href="services.php" style="background: #4facfe; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 14px;">Browse Services</a>
            <a href="customer_dashboard.php?action=logout" style="background: #dc3545; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 14px;">Logout</a>
        </div>
    </div>
</section>

<?php if (file_exists(__DIR__ . '/footer.php')) include __DIR__ . '/footer.php'; ?>
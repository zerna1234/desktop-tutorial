<?php
session_start();

// Point one directory up to access db.php
require_once __DIR__ . '/../db.php';

// 1. Check session auth (Redirect to index.php if not logged in)
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

// 2. Customer deletion logic
if (isset($_POST['delete_customer_id'])) {
    $deleteId = (int)$_POST['delete_customer_id'];
    $deleteStmt = $pdo->prepare("DELETE FROM customers WHERE id = :id");
    $deleteStmt->execute([':id' => $deleteId]);
    header("Location: admin_dashboard.php");
    exit;
}

// 3. Contact message deletion logic
if (isset($_POST['delete_message_id'])) {
    $deleteMsgId = (int)$_POST['delete_message_id'];
    $deleteMsgStmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = :id");
    $deleteMsgStmt->execute([':id' => $deleteMsgId]);
    header("Location: admin_dashboard.php");
    exit;
}

// Fetch Customers Data
$stmt = $pdo->query("SELECT id, fullname, email, created_at, last_login FROM customers ORDER BY id ASC");
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Contact Messages Data
try {
    $stmtMessages = $pdo->query("SELECT * FROM contact_messages ORDER BY submitted_at ASC");
    $messages = $stmtMessages->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $messages = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { background: #111; color: #fff; font-family: 'Poppins', sans-serif; padding: 40px; }
        .container { max-width: 1000px; margin: 0 auto; background: #1a1a1a; padding: 30px; border-radius: 10px; border: 1px solid #333; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; margin-bottom: 40px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #333; font-size: 14px; }
        th { background: #222; color: #4facfe; }
        .badge-active { color: #28a745; font-weight: bold; }
        .badge-never { color: #888; }
        .btn-delete { background: #dc3545; color: #fff; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 12px; }
        .section-title { color: #4facfe; margin-top: 30px; border-bottom: 1px solid #333; padding-bottom: 10px; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Admin Management Dashboard</h2>
        <div>
            <span>Admin: <strong><?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></strong></span> | 
            <a href="logout.php" style="color: #dc3545; text-decoration: none; font-weight: bold; margin-left: 10px;">Logout</a>
        </div>
    </div>

    <!-- CUSTOMERS SECTION -->
    <h3 class="section-title">Customer Registrations & Login Activity</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Registered Date</th>
                <th>Last Login Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($customers) > 0): ?>
                <?php $custIndex = 1; ?>
                <?php foreach ($customers as $c): ?>
                    <tr>
                        <td><?php echo $custIndex++; ?></td>
                        <td><?php echo htmlspecialchars($c['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($c['email']); ?></td>
                        <td><?php echo date("M d, Y g:i A", strtotime($c['created_at'])); ?></td>
                        <td>
                            <?php if ($c['last_login']): ?>
                                <span class="badge-active"><?php echo date("M d, Y g:i A", strtotime($c['last_login'])); ?></span>
                            <?php else: ?>
                                <span class="badge-never">Never Logged In</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" action="admin_dashboard.php" onsubmit="return confirm('Delete this customer account?');">
                                <input type="hidden" name="delete_customer_id" value="<?php echo $c['id']; ?>">
                                <button type="submit" class="btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #aaa;">No customer accounts found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- INQUIRIES & CONTACT MESSAGES SECTION -->
    <h3 class="section-title">Inquiries & Contact Messages</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Date Sent</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($messages)): ?>
                <?php $msgIndex = 1; ?>
                <?php foreach ($messages as $msg): ?>
                    <tr>
                        <td><?php echo $msgIndex++; ?></td>
                        <td><?php echo htmlspecialchars($msg['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($msg['email']); ?></td>
                        <td><?php echo htmlspecialchars($msg['subject']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($msg['message'])); ?></td>
                        <td><?php echo date("M d, Y g:i A", strtotime($msg['submitted_at'])); ?></td>
                        <td>
                            <form method="POST" action="admin_dashboard.php" onsubmit="return confirm('Delete this message?');">
                                <input type="hidden" name="delete_message_id" value="<?php echo $msg['id']; ?>">
                                <button type="submit" class="btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #aaa;">No contact messages found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

</body>
</html>
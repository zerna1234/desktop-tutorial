<?php
// Prevent duplicate session start notices
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Robust database path resolution relative to current file directory
if (file_exists(__DIR__ . '/db.php')) {
    require_once __DIR__ . '/db.php';
} elseif (file_exists(__DIR__ . '/../db.php')) {
    require_once __DIR__ . '/../db.php';
} else {
    die("Database connection file (db.php) not found.");
}

// Ensure the admin session is active
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: /mywebsites/admin_login.php");
    exit;
}

// ---------------------------------------------------------
// 1. DATABASE & FORM PROCESSING (POST / GET)
// ---------------------------------------------------------

// Create blogs table automatically if it doesn't exist
$pdo->exec("CREATE TABLE IF NOT EXISTS blogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    excerpt TEXT NOT NULL,
    youtube_id VARCHAR(50) NOT NULL,
    full_content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Process Adding a New Blog Post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_blog'])) {
    $category   = trim($_POST['category'] ?? '');
    $title      = trim($_POST['title'] ?? '');
    $excerpt    = trim($_POST['excerpt'] ?? '');
    $youtube_id = trim($_POST['youtube_id'] ?? '');
    $content    = trim($_POST['full_content'] ?? '');

    if (!empty($category) && !empty($title) && !empty($youtube_id)) {
        $stmt = $pdo->prepare("INSERT INTO blogs (category, title, excerpt, youtube_id, full_content) VALUES (:cat, :title, :exc, :yt, :content)");
        $stmt->execute([
            ':cat'     => $category,
            ':title'   => $title,
            ':exc'     => $excerpt,
            ':yt'      => $youtube_id,
            ':content' => $content
        ]);
        header("Location: " . $_SERVER['PHP_SELF'] . "?status=blog_added");
        exit;
    }
}

// Process Deleting a Customer
if (isset($_GET['delete_customer'])) {
    $customerId = intval($_GET['delete_customer']);
    $stmt = $pdo->prepare("DELETE FROM customers WHERE id = :id");
    $stmt->execute([':id' => $customerId]);
    header("Location: " . $_SERVER['PHP_SELF'] . "?status=customer_deleted");
    exit;
}

// Process Deleting an Inquiry Message
if (isset($_GET['delete_inquiry'])) {
    $inquiryId = intval($_GET['delete_inquiry']);
    $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = :id");
    $stmt->execute([':id' => $inquiryId]);
    header("Location: " . $_SERVER['PHP_SELF'] . "?status=inquiry_deleted");
    exit;
}

// Process Deleting a Blog Post
if (isset($_GET['delete_blog'])) {
    $blogId = intval($_GET['delete_blog']);
    $stmt = $pdo->prepare("DELETE FROM blogs WHERE id = :id");
    $stmt->execute([':id' => $blogId]);
    header("Location: " . $_SERVER['PHP_SELF'] . "?status=blog_deleted");
    exit;
}

// ---------------------------------------------------------
// 2. FETCH DATA FROM DATABASE
// ---------------------------------------------------------
$customers = $pdo->query("SELECT * FROM customers ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$inquiries = $pdo->query("SELECT * FROM contact_messages ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$blogs     = $pdo->query("SELECT * FROM blogs ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #121212; color: #fff; font-family: 'Poppins', sans-serif; padding: 30px; }
        .dashboard-card { background: #1a1a1a; border: 1px solid #333; border-radius: 10px; padding: 30px; max-width: 1200px; margin: 0 auto; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
        h2 { color: #fff; margin: 0; font-size: 24px; }
        h3 { color: #4facfe; margin-top: 35px; margin-bottom: 15px; font-size: 18px; }
        table { width: 100%; border-collapse: collapse; background: #161b22; border: 1px solid #21262d; border-radius: 6px; overflow: hidden; font-size: 14px; margin-bottom: 20px; }
        th { background: #21262d; color: #4facfe; text-align: left; padding: 12px; font-weight: 600; }
        td { padding: 12px; border-top: 1px solid #21262d; color: #c9d1d9; }
        .btn-delete { background: #dc3545; color: #fff; text-decoration: none; padding: 5px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .form-grid { background: #161b22; padding: 20px; border: 1px solid #21262d; border-radius: 8px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; }
        input, textarea { width: 100%; padding: 10px; background: #0d1117; border: 1px solid #30363d; color: #fff; border-radius: 4px; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        label { color: #8b949e; font-size: 12px; display: block; margin-bottom: 5px; }
        .btn-submit { background: #4facfe; color: #fff; border: none; padding: 10px 24px; border-radius: 4px; cursor: pointer; font-weight: 600; font-size: 14px; }
    </style>
</head>
<body>

<div class="dashboard-card">
    <div class="header">
        <h2>Admin Management Dashboard</h2>
        <div>Admin: <strong style="color: #4facfe;"><?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'admin'); ?></strong> | <a href="../logout.php" style="color: #dc3545; text-decoration: none; font-weight: 600;">Logout</a></div>
    </div>

    <!-- 1. CUSTOMER REGISTRATIONS & LOGIN ACTIVITY -->
    <h3>Customer Registrations & Login Activity</h3>
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
            <?php if (empty($customers)): ?>
                <tr><td colspan="6" style="text-align: center; color: #888;">No customers registered yet.</td></tr>
            <?php else: ?>
                <?php foreach ($customers as $idx => $c): ?>
                    <tr>
                        <td><?php echo $idx + 1; ?></td>
                        <td><?php echo htmlspecialchars($c['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($c['email']); ?></td>
                        <td><?php echo date('M d, Y g:i A', strtotime($c['created_at'])); ?></td>
                        <td style="color: <?php echo !empty($c['last_login']) ? '#28a745' : '#888'; ?>;"><?php echo !empty($c['last_login']) ? date('M d, Y g:i A', strtotime($c['last_login'])) : 'Never Logged In'; ?></td>
                        <td><a href="?delete_customer=<?php echo $c['id']; ?>" onclick="return confirm('Delete this customer?');" class="btn-delete">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- 2. INQUIRIES & CONTACT MESSAGES -->
    <h3>Inquiries & Contact Messages</h3>
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
            <?php if (empty($inquiries)): ?>
                <tr><td colspan="7" style="text-align: center; color: #888;">No contact messages received.</td></tr>
            <?php else: ?>
                <?php foreach ($inquiries as $idx => $m): ?>
                    <?php 
                        // Safely handle different possible date column names in contact_messages table
                        $msgDate = $m['created_at'] ?? $m['submitted_at'] ?? $m['date'] ?? null;
                    ?>
                    <tr>
                        <td><?php echo $idx + 1; ?></td>
                        <td><?php echo htmlspecialchars($m['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($m['email']); ?></td>
                        <td><?php echo htmlspecialchars($m['subject']); ?></td>
                        <td><?php echo htmlspecialchars($m['message']); ?></td>
                        <td><?php echo $msgDate ? date('M d, Y g:i A', strtotime($msgDate)) : 'N/A'; ?></td>
                        <td><a href="?delete_inquiry=<?php echo $m['id']; ?>" onclick="return confirm('Delete this inquiry?');" class="btn-delete">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- 3. LATEST BLOGS & TECH INSIGHTS MANAGEMENT -->
    <h3>Latest Blogs & Tech Insights</h3>

    <!-- Create Blog Form -->
    <form method="POST" class="form-grid">
        <input type="hidden" name="add_blog" value="1">
        
        <div>
            <label>Category (e.g., CASE STUDY, TECH INSIGHT)</label>
            <input type="text" name="category" required placeholder="CASE STUDY">
        </div>

        <div>
            <label>YouTube Video ID (e.g., KkyCn3C2dTY)</label>
            <input type="text" name="youtube_id" required placeholder="KkyCn3C2dTY">
        </div>

        <div style="grid-column: span 2;">
            <label>Blog Title</label>
            <input type="text" name="title" required placeholder="Enter article title">
        </div>

        <div style="grid-column: span 2;">
            <label>Short Excerpt / Summary</label>
            <textarea name="excerpt" rows="2" required placeholder="Short preview text for front page..."></textarea>
        </div>

        <div style="grid-column: span 2;">
            <label>Full Content (Paragraphs/HTML tags permitted)</label>
            <textarea name="full_content" rows="4" required placeholder="<p>Full article body text goes here...</p>"></textarea>
        </div>

        <div style="grid-column: span 2; text-align: right;">
            <button type="submit" class="btn-submit">+ Add New Blog Post</button>
        </div>
    </form>

    <!-- Blogs List Table -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Category</th>
                <th>Title</th>
                <th>YouTube ID</th>
                <th>Date Added</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($blogs)): ?>
                <tr><td colspan="6" style="text-align: center; color: #888;">No blogs created yet. Fill out the form above to post one.</td></tr>
            <?php else: ?>
                <?php foreach ($blogs as $idx => $b): ?>
                    <tr>
                        <td><?php echo $idx + 1; ?></td>
                        <td style="color: #4facfe; font-weight: 600;"><?php echo htmlspecialchars($b['category']); ?></td>
                        <td><?php echo htmlspecialchars($b['title']); ?></td>
                        <td style="font-family: monospace; color: #00f2fe;"><?php echo htmlspecialchars($b['youtube_id']); ?></td>
                        <td style="font-size: 12px; color: #8b949e;"><?php echo date('M d, Y', strtotime($b['created_at'])); ?></td>
                        <td><a href="?delete_blog=<?php echo $b['id']; ?>" onclick="return confirm('Delete this blog post?');" class="btn-delete">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
<?php
$conn = new mysqli('localhost', 'root', '', 'mztech_db');
$result = $conn->query("SELECT * FROM messages ORDER BY submitted_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Messages - Admin</title>
    <style>
        body { font-family: Arial, sans-serif; background: #121212; color: #fff; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 12px; text-align: left; }
        th { background: #222; }
        tr:nth-child(even) { background: #1a1a1a; }
    </style>
</head>
<body>
    <h2>Submitted Contact Messages</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Date</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                <td><?php echo $row['submitted_at']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
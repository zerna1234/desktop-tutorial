<?php
if (isset($_POST['submit'])) {
    // 1. Sanitize input data
    $name    = filter_var(trim($_POST['name']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email   = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $subject = filter_var(trim($_POST['subject']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $message = filter_var(trim($_POST['message']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // 2. Recipient Email Address
    $to = "info@mztechsolution.com"; // Replace with your actual email

    // 3. Email Headers
    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: MZ Tech Website <noreply@mztechsolution.com>" . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";

    // 4. Construct Email Content
    $emailContent = "
    <html>
    <head>
        <title>New Contact Form Submission</title>
    </head>
    <body>
        <h2>New Inquiry from Website</h2>
        <p><strong>Name:</strong> {$name}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Subject:</strong> {$subject}</p>
        <p><strong>Message:</strong></p>
        <p>" . nl2br($message) . "</p>
    </body>
    </html>
    ";

// Start session for status messaging
session_start();

if (isset($_POST['submit'])) {

    // 1. Database Configuration (XAMPP Defaults)
    $dbHost = 'localhost';
    $dbUser = 'root';
    $dbPass = '';
    $dbName = 'mztech_db';

    // 2. Connect to Database
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    if ($conn->connect_error) {
        header("Location: index.php?status=error#contact");
        exit();
    }

    // 3. Sanitize Input Data
    $name    = trim(htmlspecialchars($_POST['name']));
    $email   = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $subject = trim(htmlspecialchars($_POST['subject']));
    $message = trim(htmlspecialchars($_POST['message']));

    // Basic Validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        header("Location: index.php?status=error#contact");
        exit();
    }

    // 4. Save Message to MySQL Database using Prepared Statements
    $stmt = $conn->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        // 5. Send Optional Email Notification
        $to      = "info@mztechsolution.com";
        $headers = "From: webmaster@mztechsolution.com\r\n" .
                   "Reply-To: " . $email . "\r\n" .
                   "X-Mailer: PHP/" . phpversion();

        $emailBody = "New message from website:\n\n" .
                     "Name: $name\n" .
                     "Email: $email\n" .
                     "Subject: $subject\n\n" .
                     "Message:\n$message";

        @mail($to, "Contact Form: $subject", $emailBody, $headers);

        // Redirect back with success message
        $stmt->close();
        $conn->close();
        header("Location: index.php?status=success#contact");
        exit();
    } else {
        $stmt->close();
        $conn->close();
        header("Location: index.php?status=error#contact");
        exit();
    }

} else {
    header("Location: index.php");
    exit();
}

    // 5. Send Mail
    if (mail($to, $subject, $emailContent, $headers)) {
        header("Location: index.php?status=success#contact");
    } else {
        header("Location: index.php?status=error#contact");
    }
    exit();
} else {
    header("Location: index.php");
    exit();
}
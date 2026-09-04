<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : "MZ Tech Solution - Home"; ?></title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <!-- NAVIGATION BAR -->
    <header class="navbar">
        <div class="logo-container">
            <img src="images/navbar-logo.png" alt="MZ Tech Logo" class="logo-img">
            <div class="logo-text">
                <span class="brand-title">MZ tech</span>
                <span class="brand-sub">Solution</span>
            </div>
        </div>
        <nav class="nav-links">
            <a href="#home" class="active">HOME</a>
            <a href="#about">ABOUT US</a>
            <a href="#services">SERVICE</a>
            <a href="#portfolio">PORTFOLIO</a>
            <a href="#blog">BLOG</a>
            <a href="#contact">CONTACT</a>
        </nav>
        <a href="#contact" class="btn-primary">GET IN TOUCH &rarr;</a>
    </header>
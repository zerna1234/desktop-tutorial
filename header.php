<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Detect current page filename
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : "MZ Tech Solution"; ?></title>
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
            <a href="index.php#home" class="<?php echo ($currentPage === 'index.php') ? 'active' : ''; ?>">HOME</a>
            
            <?php if (isset($_SESSION['customer_id'])): ?>
                <!-- LOGGED IN NAVIGATION -->
                <a href="index.php#about">ABOUT US</a>
                <a href="services.php" class="<?php echo ($currentPage === 'services.php') ? 'active' : ''; ?>">SERVICE</a>
                <a href="index.php#portfolio">PORTFOLIO</a>
                <a href="blogs.php" class="<?php echo ($currentPage === 'blogs.php') ? 'active' : ''; ?>">BLOG</a>
                <a href="index.php#contact">CONTACT</a>
                <a href="customer_dashboard.php" class="<?php echo ($currentPage === 'customer_dashboard.php') ? 'active' : ''; ?>" style="color: #4facfe; font-weight: 600;">
                    Hi, <?php echo htmlspecialchars($_SESSION['customer_name']); ?>
                </a>
                <a href="customer_dashboard.php?action=logout" style="color: #dc3545; font-size: 13px;">LOGOUT</a>
            <?php else: ?>
                <!-- LOGGED OUT NAVIGATION (Triggers Login Modal for restricted sections) -->
                <a href="#" onclick="document.getElementById('loginModal').style.display='flex'; return false;">ABOUT US</a>
                <a href="#" onclick="document.getElementById('loginModal').style.display='flex'; return false;">SERVICE</a>
                <a href="#" onclick="document.getElementById('loginModal').style.display='flex'; return false;">PORTFOLIO</a>
                <a href="#" onclick="document.getElementById('loginModal').style.display='flex'; return false;">BLOG</a>
                <a href="index.php#contact">CONTACT</a>
                
                <?php if ($currentPage === 'customer_auth.php'): ?>
                    <!-- Direct link on customer_auth.php -->
                    <a href="customer_auth.php" class="active" style="border: 1px solid #4facfe; color: #4facfe; padding: 6px 14px; border-radius: 4px; text-decoration: none; font-weight: 600; font-size: 14px; font-family: 'Poppins', sans-serif;">
                        LOGIN
                    </a>
                <?php else: ?>
                    <!-- Open modal button on all other pages -->
                    <button onclick="document.getElementById('loginModal').style.display='flex'" style="background: transparent; border: 1px solid #4facfe; color: #4facfe; padding: 6px 14px; border-radius: 4px; cursor: pointer; font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 14px; transition: 0.3s;">
                        LOGIN
                    </button>
                <?php endif; ?>
            <?php endif; ?>
        </nav>

        <!-- HEADER CTA BUTTON ("GET IN TOUCH") -->
        <?php if (isset($_SESSION['customer_id'])): ?>
            <?php if ($currentPage === 'index.php'): ?>
                <a href="#contact" class="btn-primary">GET IN TOUCH &rarr;</a>
            <?php else: ?>
                <a href="index.php#contact" class="btn-primary">GET IN TOUCH &rarr;</a>
            <?php endif; ?>
        <?php else: ?>
            <button onclick="document.getElementById('loginModal').style.display='flex'" class="btn-primary" style="border: none; cursor: pointer; font-family: 'Poppins', sans-serif;">
                GET IN TOUCH &rarr;
            </button>
        <?php endif; ?>
    </header> 

    <?php if (!isset($hideModal) || !$hideModal): ?>
    <!-- POP-UP CUSTOMER LOGIN MODAL -->
    <div id="loginModal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.85); justify-content: center; align-items: center; font-family: 'Poppins', sans-serif;">
        <div style="background: #1a1a1a; padding: 35px; border: 1px solid #333; border-radius: 10px; width: 90%; max-width: 400px; position: relative; color: #fff; box-shadow: 0 10px 25px rgba(0,0,0,0.7);">
            
            <!-- Close Button (X) -->
            <span onclick="document.getElementById('loginModal').style.display='none'" style="position: absolute; right: 20px; top: 15px; color: #aaa; font-size: 26px; cursor: pointer; font-weight: bold;">&times;</span>
            
            <h3 style="color: #4facfe; margin-top: 0; text-align: center; font-size: 22px; font-weight: 600;">Customer Login</h3>
            
            <form method="POST" action="customer_auth.php" style="margin-top: 20px;">
                <input type="hidden" name="action" value="login">
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 6px; color: #ccc; font-size: 14px;">Email Address</label>
                    <input type="email" name="email" required style="width: 100%; padding: 10px 12px; background: #222; border: 1px solid #444; color: #fff; border-radius: 5px; box-sizing: border-box; font-family: 'Poppins', sans-serif;">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 6px; color: #ccc; font-size: 14px;">Password</label>
                    <input type="password" name="password" required style="width: 100%; padding: 10px 12px; background: #222; border: 1px solid #444; color: #fff; border-radius: 5px; box-sizing: border-box; font-family: 'Poppins', sans-serif;">
                </div>
                
                <button type="submit" style="width: 100%; padding: 12px; background: #4facfe; color: #fff; border: none; border-radius: 5px; font-weight: 600; cursor: pointer; font-size: 15px; font-family: 'Poppins', sans-serif;">Login</button>
            </form>

            <p style="text-align: center; margin-top: 20px; font-size: 13px; color: #aaa;">
                Don't have an account? <a href="customer_auth.php" style="color: #00f2fe; text-decoration: none; font-weight: 600;">Register here</a>
            </p>
        </div>
    </div>

    <script>
    window.onclick = function(event) {
        var modal = document.getElementById('loginModal');
        if (modal && event.target == modal) {
            modal.style.display = "none";
        }
    }
    </script>
    <?php endif; ?>
<?php
session_start();
require_once 'db.php';

// Process form submission directly inside index.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    if (isset($_SESSION['customer_id'])) {
        $fullname = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $subject  = trim($_POST['subject'] ?? '');
        $message  = trim($_POST['message'] ?? '');

        if (!empty($fullname) && !empty($email) && !empty($subject) && !empty($message)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO contact_messages (fullname, email, subject, message) VALUES (:name, :email, :subject, :msg)");
                $stmt->execute([
                    ':name'    => $fullname,
                    ':email'   => $email,
                    ':subject' => $subject,
                    ':msg'     => $message
                ]);

                header("Location: index.php?status=success#contact");
                exit;
            } catch (PDOException $e) {
                header("Location: index.php?status=error#contact");
                exit;
            }
        } else {
            header("Location: index.php?status=error#contact");
            exit;
        }
    } else {
        header("Location: index.php#contact");
        exit;
    }
}

$pageTitle = "MZ Tech Solution - Home";
include 'header.php';

// Dynamic arrays for clean data maintenance
$features = [
    "INNOVATIVE SOLUTION",
    "RELIABLE SUPPORT",
    "SECURE SYSTEMS",
    "CLIENT FOCUSED"
];

$coreValues = [
    ["title" => "INNOVATION", "desc" => "Continuously creating smarter technology solutions."],
    ["title" => "INTEGRITY", "desc" => "Being honest, transparent, and dependable."],
    ["title" => "EXCELLENCE", "desc" => "Delivering quality in every project."],
    ["title" => "CLIENT FIRST", "desc" => "Building lasting relationships through exceptional service."]
];

$services = [
    ["title" => "Software Development", "slug" => "software-development", "desc" => "Custom software solutions built to meet your business needs."],
    ["title" => "System Integration", "slug" => "system-integration", "desc" => "Seamless integration of systems and technologies for better performance."],
    ["title" => "Technical Support", "slug" => "technical-support", "desc" => "Reliable 24/7 technical support to keep your business running."],
    ["title" => "Cloud Solutions", "slug" => "cloud-solutions", "desc" => "Scalable and secure cloud solutions for modern businesses."],
    ["title" => "Cybersecurity", "slug" => "cybersecurity", "desc" => "Protecting your data and systems with advanced security solutions."]
];

$solutions = [
    [
        "img" => "images/net-infra.jpg",
        "title" => "NETWORK INFRASTRUCTURE",
        "desc" => "Reliable and secure network infrastructure designed for fast, stable, and efficient connectivity."
    ],
    [
        "img" => "images/sys-integration.jpg",
        "title" => "SYSTEM INTEGRATION",
        "desc" => "Seamless integration of systems and technologies to improve performance, connectivity, and workflow."
    ],
    [
        "img" => "images/soft-dev.jpg",
        "title" => "SOFTWARE DEVELOPMENT",
        "desc" => "Custom software solutions designed to meet your business needs and provide efficient digital experiences."
    ],
    [
        "img" => "images/tech-support.jpg",
        "title" => "TECHNICAL SUPPORT",
        "desc" => "Professional technical assistance available to help troubleshoot issues and keep your systems running smoothly."
    ]
];

$stats = [
    ["number" => "50+", "label" => "HAPPY CLIENTS"],
    ["number" => "100+", "label" => "PROJECTS COMPLETED"],
    ["number" => "5+", "label" => "YEARS EXPERIENCE"],
    ["number" => "24/7+", "label" => "TECHNICAL SUPPORT"]
];
?>

    <!-- HERO SECTION -->
    <section id="home" class="hero-section">
        <div class="hero-container">
            <div class="hero-content">
                <p class="tagline">SMART SOLUTION. REAL IMPACT</p>
                <h1>MZ TECH SOLUTION</h1>
                <p class="hero-description">
                    We deliver innovative, secure, and reliable IT solutions that help businesses and individuals succeed through technology, digital transformation, and professional services.
                </p>
                <div class="hero-buttons">
                    <?php if (isset($_SESSION['customer_id'])): ?>
                        <a href="services.php" class="btn-primary">OUR SERVICE &rarr;</a>
                    <?php else: ?>
                        <a href="#" onclick="document.getElementById('loginModal').style.display='flex'; return false;" class="btn-primary">OUR SERVICE &rarr;</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="hero-logo-wrapper">
                <img src="images/hero-logo-m.png" alt="MZ Tech Big Logo" class="hero-big-logo">
            </div>
        </div>
    </section>

    <!-- HIGHLIGHT FEATURE BOXES -->
    <section class="features-grid">
        <?php foreach ($features as $feature): ?>
            <div class="feature-card">
                <h3><?php echo htmlspecialchars($feature); ?></h3>
            </div>
        <?php endforeach; ?>
    </section>

    <!-- ABOUT US SECTION -->
    <section id="about" class="about-section">
        <div class="section-title">
            <h2>ABOUT US</h2>
            <div class="divider"></div>
        </div>
        <div class="about-grid">
            <div class="about-box">
                <h3>ABOUT</h3>
                <p>MZ Tech Solution is a modern technology company that delivers innovative, secure, and reliable IT solutions. The brand focuses on helping businesses and individuals succeed through technology, digital transformation, and professional technical services.</p>
            </div>
            <div class="about-box">
                <h3>MISSION</h3>
                <p>To deliver innovative and reliable technology solutions that empower businesses to work smarter and achieve more.</p>
            </div>
            <div class="about-box">
                <h3>VISION</h3>
                <p>To become a trusted technology partner recognized for transforming ideas into impactful digital solutions.</p>
            </div>
        </div>
        <div class="center-btn">
            <a href="#about" class="btn-secondary">LEARN MORE &rarr;</a>
        </div>
    </section>

    <!-- CORE VALUES SECTION -->
    <section class="values-section">
        <div class="section-title">
            <h2>OUR CORE VALUES</h2>
            <div class="divider"></div>
        </div>
        <div class="values-grid">
            <?php foreach ($coreValues as $value): ?>
                <div class="value-card">
                    <h3><?php echo htmlspecialchars($value['title']); ?></h3>
                    <p><?php echo htmlspecialchars($value['desc']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- SERVICES OVERVIEW -->
    <section id="services" class="services-section">
        <div class="section-title">
            <h2>OUR SERVICE</h2>
            <p>Comprehensive IT solutions designed to improve your business efficiency</p>
            <div class="divider"></div>
        </div>
        <div class="services-grid">
            <?php foreach ($services as $service): ?>
                <div class="service-card">
                    <h3><?php echo htmlspecialchars($service['title']); ?></h3>
                    <p><?php echo htmlspecialchars($service['desc']); ?></p>
                    <?php if (isset($_SESSION['customer_id'])): ?>
                        <a href="services.php#<?php echo $service['slug']; ?>" class="link-btn">LEARN MORE &rarr;</a>
                    <?php else: ?>
                        <a href="#" onclick="document.getElementById('loginModal').style.display='flex'; return false;" class="link-btn">LEARN MORE &rarr;</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- SOLUTIONS THAT DRIVE SUCCESS -->
    <section class="solutions-section">
        <div class="section-title">
            <h2>SOLUTIONS THAT DRIVE SUCCESS</h2>
            <div class="divider"></div>
        </div>
        <div class="solutions-grid">
            <?php foreach ($solutions as $sol): ?>
                <div class="solution-card">
                    <div class="card-img-wrapper">
                        <img src="<?php echo htmlspecialchars($sol['img']); ?>" alt="<?php echo htmlspecialchars($sol['title']); ?>">
                    </div>
                    <div class="card-body">
                        <h3><?php echo htmlspecialchars($sol['title']); ?></h3>
                        <p><?php echo htmlspecialchars($sol['desc']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- STATS COUNTER SECTION -->
    <section class="stats-section">
        <?php foreach ($stats as $stat): ?>
            <div class="stat-item">
                <h2><?php echo htmlspecialchars($stat['number']); ?></h2>
                <p><?php echo htmlspecialchars($stat['label']); ?></p>
            </div>
        <?php endforeach; ?>
    </section>

    <!-- CALL TO ACTION -->
    <section class="cta-section">
        <h2>LET'S BUILD SOMETHING GREAT TOGETHER</h2>
        <p>We are ready to help your business grow with smart technology solutions</p>
        <?php if (isset($_SESSION['customer_id'])): ?>
            <a href="#contact" class="btn-primary">GET IN TOUCH &rarr;</a>
        <?php else: ?>
            <a href="#" onclick="document.getElementById('loginModal').style.display='flex'; return false;" class="btn-primary">GET IN TOUCH &rarr;</a>
        <?php endif; ?>
    </section>
    
    <!-- CONTACT SECTION (PROTECTED ACCESS) -->
    <section id="contact" style="
        position: relative;
        width: 100vw !important;
        left: 50% !important;
        right: 50% !important;
        margin-left: -50vw !important;
        margin-right: -50vw !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 60px 20px !important;
        box-sizing: border-box !important;
        clear: both !important;
        float: none !important;
    ">
        <div class="section-title" style="text-align: center; margin-bottom: 30px; width: 100%;">
            <h2 style="text-align: center;">GET IN TOUCH</h2>
            <div class="divider" style="margin: 10px auto;"></div>
        </div>

        <div style="width: 100%; max-width: 600px; margin: 0 auto; box-sizing: border-box;">
            <?php if (isset($_SESSION['customer_id'])): ?>
                <!-- LOGGED IN USER: SHOW CONTACT FORM -->
                <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
                    <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
                        Thank you! Your message has been sent successfully.
                    </div>
                <?php elseif (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
                    <div class="alert alert-error" style="background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
                        Sorry, something went wrong. Please try again.
                    </div>
                <?php endif; ?>

                <form action="index.php#contact" method="POST" style="display: flex; flex-direction: column; gap: 15px; width: 100%; box-sizing: border-box;">
                    <div style="width: 100%;">
                        <input type="text" name="name" placeholder="Your Full Name" required style="width: 100%; padding: 14px 16px; border: 1px solid #333; background-color: #1a1a1a; color: #ffffff; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 15px; box-sizing: border-box; display: block;">
                    </div>
                    <div style="width: 100%;">
                        <input type="email" name="email" placeholder="Your Email Address" required style="width: 100%; padding: 14px 16px; border: 1px solid #333; background-color: #1a1a1a; color: #ffffff; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 15px; box-sizing: border-box; display: block;">
                    </div>
                    <div style="width: 100%;">
                        <input type="text" name="subject" placeholder="Subject" required style="width: 100%; padding: 14px 16px; border: 1px solid #333; background-color: #1a1a1a; color: #ffffff; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 15px; box-sizing: border-box; display: block;">
                    </div>
                    <div style="width: 100%;">
                        <textarea name="message" rows="5" placeholder="Your Message" required style="width: 100%; padding: 14px 16px; border: 1px solid #333; background-color: #1a1a1a; color: #ffffff; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 15px; box-sizing: border-box; display: block; resize: vertical;"></textarea>
                    </div>
                    <button type="submit" name="submit" class="btn-primary" style="width: 100%; padding: 14px; cursor: pointer; border: none; border-radius: 6px; font-weight: 600;">Send Message &rarr;</button>
                </form>

            <?php else: ?>
                <!-- LOGGED OUT USER: SHOW LOGIN PROMPT -->
                <div style="text-align: center; padding: 40px 20px; background: #1a1a1a; border-radius: 8px; border: 1px solid #333; color: #fff;">
                    <h3 style="color: #4facfe; margin-top: 0; margin-bottom: 12px; font-size: 20px;">Please Log In to Contact Us</h3>
                    <p style="color: #aaa; margin-bottom: 25px; font-size: 14px;">You must have an active customer account to access our contact form and get in touch with our team.</p>
                    <button onclick="document.getElementById('loginModal').style.display='flex'" style="background: #4facfe; color: #fff; border: none; padding: 12px 28px; border-radius: 5px; font-weight: 600; cursor: pointer; font-size: 15px; font-family: 'Poppins', sans-serif; transition: 0.3s;">
                        Login / Register Now
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </section>

<?php include 'footer.php'; ?>
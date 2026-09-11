<?php
session_start();
require_once 'db.php';

// Process contact form submission directly inside index.php
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

// Interactive Category Filter Data
$caseStudies = [
    [
        "title" => "Apex Flux – Modular Biometric Wallet",
        "category" => "ux web",
        "tags" => ["PHP", "MySQL", "JavaScript", "UX Design"],
        "problem" => "Traditional wallets lacked security integration and custom physical modularity for modern users.",
        "solution" => "Built a digital web prototype showcasing real-time biometric access simulation and modular layout management."
    ],
    [
        "title" => "Local Retail POS & Inventory System",
        "category" => "system web",
        "tags" => ["PHP PDO", "MySQL", "Bootstrap"],
        "problem" => "Manual record-keeping caused stock miscounts and lost daily revenue data for retail stores.",
        "solution" => "Developed a real-time admin portal to track inventory stock, logins, sales reports, and customer transactions."
    ],
    [
        "title" => "Neon Class Corporate Platform",
        "category" => "web",
        "tags" => ["HTML5/CSS3", "PHP", "SEO"],
        "problem" => "Low online visibility and slow page performance on mobile devices across key client entry points.",
        "solution" => "Redesigned the platform with dark-mode responsiveness and optimized image/script assets."
    ]
];

// FETCH DYNAMIC BLOG POSTS FROM THE DATABASE
try {
    $stmt = $pdo->query("SELECT * FROM blogs ORDER BY id DESC");
    $recentBlogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $recentBlogs = [];
}

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
    <section class="values-section" style="margin-bottom: 0; padding-bottom: 40px;">
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

    <!-- INTERACTIVE FEATURED CASE STUDIES -->
    <section id="portfolio" class="case-studies-section" style="padding: 80px 20px 60px 20px; margin-top: 40px; background: #0a0a0a; border-top: 1px solid #1f293d;">
        <div class="section-title" style="text-align: center; margin-bottom: 30px;">
            <h2>FEATURED CASE STUDIES</h2>
            <p style="color: #aaa; margin-top: 8px;">Real problems, custom technology solutions, and proven business results</p>
            <div class="divider" style="margin: 15px auto 0 auto;"></div>
        </div>

        <!-- Filter Buttons -->
        <div class="filter-container" style="display: flex; justify-content: center; gap: 10px; margin-top: 30px; margin-bottom: 40px; flex-wrap: wrap;">
            <button class="filter-btn" onclick="filterCases('all', this)">All Projects</button>
            <button class="filter-btn" onclick="filterCases('web', this)">Web Apps</button>
            <button class="filter-btn" onclick="filterCases('ux', this)">UI/UX Design</button>
            <button class="filter-btn active" onclick="filterCases('system', this)">Systems & POS</button>
        </div>

        <!-- Case Cards Grid -->
        <div class="case-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; max-width: 1200px; margin: 0 auto;">
            <?php foreach ($caseStudies as $study): ?>
                <div class="case-card" data-category="<?php echo htmlspecialchars($study['category']); ?>" style="background: #1a1a1a; border: 1px solid #333; border-radius: 8px; padding: 25px; transition: all 0.3s ease;">
                    <h3 style="color: #fff; font-size: 20px; margin-bottom: 15px;"><?php echo htmlspecialchars($study['title']); ?></h3>
                    
                    <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 15px;">
                        <?php foreach ($study['tags'] as $tag): ?>
                            <span style="background: #252525; color: #4facfe; border: 1px solid #4facfe33; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500;"><?php echo htmlspecialchars($tag); ?></span>
                        <?php endforeach; ?>
                    </div>

                    <p style="color: #bbb; font-size: 14px; margin-bottom: 10px;">
                        <strong style="color: #4facfe;">Problem:</strong> <?php echo htmlspecialchars($study['problem']); ?>
                    </p>
                    <p style="color: #bbb; font-size: 14px;">
                        <strong style="color: #4facfe;">Solution:</strong> <?php echo htmlspecialchars($study['solution']); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- LATEST BLOGS & TECH INSIGHTS (DATABASE DRIVEN + FULL ARTICLE MODAL) -->
    <section id="blog" style="padding: 70px 20px; background: #11141a; border-top: 1px solid #1f293d;">
        <div class="section-title" style="text-align: center; margin-bottom: 40px;">
            <h2 style="color: #fff; font-size: 28px;">LATEST BLOGS & TECH INSIGHTS</h2>
            <p style="color: #8a99ad; margin-top: 8px;">Watch video breakdowns of our engineering process and smart tech solutions</p>
            <div class="divider" style="margin: 15px auto 0 auto;"></div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; max-width: 1200px; margin: 0 auto;">
            <?php if (empty($recentBlogs)): ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #888;">
                    No blog posts available right now. Visit the admin dashboard to add new posts!
                </div>
            <?php else: ?>
                <?php foreach ($recentBlogs as $blog): ?>
                    <div style="background: #161b22; border: 1px solid #21262d; border-radius: 8px; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between;">
                        
                        <!-- YouTube Video Embed -->
                        <?php if (!empty($blog['youtube_id'])): ?>
                            <div style="position: relative; width: 100%; padding-top: 56.25%; background: #000; overflow: hidden;">
                                <iframe 
                                    src="https://www.youtube.com/embed/<?php echo htmlspecialchars($blog['youtube_id']); ?>?autoplay=1&mute=1&loop=1&playlist=<?php echo htmlspecialchars($blog['youtube_id']); ?>&controls=1&modestbranding=1&rel=0" 
                                    title="<?php echo htmlspecialchars($blog['title']); ?>"
                                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                                </iframe>
                            </div>
                        <?php endif; ?>

                        <div style="padding: 20px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                <span style="color: #4facfe; font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; border: 1px solid #4facfe44; padding: 3px 8px; border-radius: 4px;"><?php echo htmlspecialchars($blog['category']); ?></span>
                                <span style="color: #6e7681; font-size: 12px;"><?php echo date('M d, Y', strtotime($blog['created_at'])); ?></span>
                            </div>
                            <h3 style="color: #ffffff; font-size: 18px; margin-bottom: 12px; line-height: 1.4;"><?php echo htmlspecialchars($blog['title']); ?></h3>
                            <p style="color: #8b949e; font-size: 13px; line-height: 1.6; margin-bottom: 20px;"><?php echo htmlspecialchars($blog['excerpt']); ?></p>
                            
                            <!-- Dynamic Read Article Button (passes database ID) -->
                            <button onclick="showArticleModal(<?php echo $blog['id']; ?>)" style="background: none; border: none; color: #4facfe; font-size: 13px; font-weight: 600; cursor: pointer; padding: 0; display: inline-flex; align-items: center; gap: 5px;">READ ARTICLE &rarr;</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="blogs.php" class="btn-secondary" style="display: inline-block; padding: 12px 28px; border: 1px solid #4facfe; color: #4facfe; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px;">VIEW ALL BLOG POSTS &rarr;</a>
        </div>

        <!-- VIDEO COPYRIGHT & PLATFORM DISCLAIMER BLOCK -->
        <div style="max-width: 1200px; margin: 40px auto 0 auto; padding: 16px 20px; background: rgba(22, 27, 34, 0.7); border: 1px dashed #21262d; border-radius: 6px; text-align: center; color: #8b949e; font-size: 12px; line-height: 1.5;">
            <p style="margin: 0;">
                <strong style="color: #c9d1d9;">Disclaimer & Copyright Notice:</strong> 
                Embedded video content is powered by YouTube. All video content, trademarks, logos, and copyrights belong to their respective owners and creators. MZ Tech Solution does not claim ownership over third-party multimedia assets embedded on this page. If you are a copyright owner and wish to request removal of embedded media, please contact us.
            </p>
        </div>
    </section>

    <!-- FULL ARTICLE DISPLAY MODAL (POPUP) -->
    <div id="articleModalOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.85); backdrop-filter: blur(5px); z-index: 9999; justify-content: center; align-items: center; padding: 20px; box-sizing: border-box;">
        <div style="background: #161b22; border: 1px solid #30363d; border-radius: 10px; max-width: 750px; width: 100%; max-height: 85vh; display: flex; flex-direction: column; overflow: hidden; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
            
            <!-- Modal Header -->
            <div style="padding: 20px 25px; border-bottom: 1px solid #21262d; display: flex; justify-content: space-between; align-items: center; background: #11141a;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span id="modalCategory" style="color: #4facfe; font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; border: 1px solid #4facfe44; padding: 3px 8px; border-radius: 4px;">CATEGORY</span>
                    <span id="modalDate" style="color: #6e7681; font-size: 12px;">DATE</span>
                </div>
                <button onclick="closeArticleModal()" style="background: none; border: none; color: #8b949e; font-size: 24px; cursor: pointer; line-height: 1;">&times;</button>
            </div>

            <!-- Modal Content Area -->
            <div style="padding: 30px; overflow-y: auto; color: #c9d1d9; font-size: 15px; line-height: 1.7;">
                <h2 id="modalTitle" style="color: #ffffff; font-size: 24px; margin-top: 0; margin-bottom: 20px; line-height: 1.3;"></h2>
                <div id="modalVideoWrapper" style="position: relative; width: 100%; padding-top: 56.25%; background: #000; overflow: hidden; border-radius: 6px; margin-bottom: 25px;">
                    <iframe id="modalIframe" src="" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;" allowfullscreen></iframe>
                </div>
                <div id="modalBody" style="color: #8b949e;"></div>
            </div>

            <!-- Modal Footer -->
            <div style="padding: 15px 25px; border-top: 1px solid #21262d; background: #11141a; text-align: right;">
                <button onclick="closeArticleModal()" style="background: #21262d; color: #c9d1d9; border: 1px solid #30363d; padding: 8px 18px; border-radius: 5px; cursor: pointer; font-size: 13px; font-weight: 600;">Close Article</button>
            </div>
        </div>
    </div>

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

<!-- Inline Styles for Category Filter Buttons -->
<style>
.filter-btn {
    background: #1a1a1a;
    color: #fff;
    border: 1px solid #333;
    padding: 8px 20px;
    border-radius: 25px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s ease;
}

.filter-btn:hover, .filter-btn.active {
    background: #4facfe;
    border-color: #4facfe;
    color: #fff;
    box-shadow: 0 0 12px rgba(79, 172, 254, 0.4);
}

#modalBody h3 {
    color: #ffffff;
    font-size: 18px;
    margin-top: 20px;
    margin-bottom: 8px;
}
#modalBody p {
    margin-bottom: 15px;
    color: #8b949e;
}
#modalBody ul {
    margin-bottom: 15px;
    padding-left: 20px;
    color: #8b949e;
}
#modalBody li {
    margin-bottom: 6px;
}
</style>

<!-- JavaScript Filter & Modal Logic -->
<script>
// Database Blog data encoded into JavaScript for modal popups
const blogArticles = <?php echo json_encode($recentBlogs); ?>;

function showArticleModal(blogId) {
    const blog = blogArticles.find(item => parseInt(item.id) === parseInt(blogId));
    if (!blog) return;

    document.getElementById('modalCategory').innerText = blog.category;
    document.getElementById('modalDate').innerText = new Date(blog.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    document.getElementById('modalTitle').innerText = blog.title;
    document.getElementById('modalIframe').src = 'https://www.youtube.com/embed/' + blog.youtube_id + '?autoplay=1&rel=0';
    document.getElementById('modalBody').innerHTML = blog.full_content;

    const modal = document.getElementById('articleModalOverlay');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeArticleModal() {
    const modal = document.getElementById('articleModalOverlay');
    modal.style.display = 'none';
    document.getElementById('modalIframe').src = '';
    document.body.style.overflow = 'auto';
}

// Close Modal when clicking outside the container box
window.addEventListener('click', function(event) {
    const modal = document.getElementById('articleModalOverlay');
    if (event.target === modal) {
        closeArticleModal();
    }
});

function filterCases(category, element) {
    const buttons = document.querySelectorAll('.filter-btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    element.classList.add('active');

    const cards = document.querySelectorAll('.case-card');
    cards.forEach(card => {
        const categories = card.getAttribute('data-category');
        if (category === 'all' || categories.includes(category)) {
            card.style.display = 'block';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'scale(1)';
            }, 10);
        } else {
            card.style.opacity = '0';
            card.style.transform = 'scale(0.95)';
            setTimeout(() => {
                card.style.display = 'none';
            }, 300);
        }
    });
}
</script>

<?php include 'footer.php'; ?>
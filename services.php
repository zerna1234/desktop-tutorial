<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect logged-out users to the registration/login portal
if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_auth.php");
    exit;
}

$pageTitle = "Our Services - MZ Tech Solution";
include 'header.php';

$detailedServices = [
    [
        "id" => "software-development",
        "title" => "Software Development",
        "desc" => "Custom software solutions built to meet your specific business requirements.",
        "details" => [
            "Custom Web & Mobile Application Development",
            "Enterprise Software Solutions & API Development",
            "Database Design & Management",
            "Legacy Code Refactoring & Maintenance"
        ]
    ],
    [
        "id" => "system-integration",
        "title" => "System Integration",
        "desc" => "Connecting isolated software and hardware into a single efficient ecosystem.",
        "details" => [
            "Third-Party API Integration & Automation",
            "ERP & CRM System Synchronization",
            "Data Pipeline & Database Integration",
            "Hardware & IoT Network Setup"
        ]
    ],
    [
        "id" => "technical-support",
        "title" => "Technical Support",
        "desc" => "Continuous technical assistance to keep your business operating without downtime.",
        "details" => [
            "24/7 IT Help Desk & Remote Support",
            "System Monitoring & Diagnostics",
            "Hardware Troubleshooting & Repairs",
            "Software Updates & Maintenance Packages"
        ]
    ],
    [
        "id" => "cloud-solutions",
        "title" => "Cloud Solutions",
        "desc" => "Flexible and scalable cloud infrastructure for modern digital operational workflows.",
        "details" => [
            "Cloud Migration (AWS, Google Cloud, Azure)",
            "Cloud Storage Setup & Management",
            "Automated Backup & Disaster Recovery",
            "Server Cost Optimization & Security"
        ]
    ],
    [
        "id" => "cybersecurity",
        "title" => "Cybersecurity",
        "desc" => "Proactive protection for your IT assets, user accounts, and confidential data.",
        "details" => [
            "Vulnerability & Security Risk Assessments",
            "Firewall Configuration & Network Protection",
            "Data Encryption & Secure Access Control",
            "Security Incident Response & Recovery"
        ]
    ]
];
?>

<section class="services-detail-page" style="padding: 60px 20px; max-width: 1100px; margin: 0 auto; color: #fff;">
    <div class="section-title" style="text-align: center; margin-bottom: 50px;">
        <h2>OUR COMPREHENSIVE SERVICES</h2>
        <p style="color: #aaa;">Explore full details of what we offer to scale your digital presence</p>
        <div class="divider" style="margin: 10px auto;"></div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 40px;">
        <?php foreach ($detailedServices as $s): ?>
            <div id="<?php echo $s['id']; ?>" style="background-color: #1a1a1a; border: 1px solid #333; padding: 30px; border-radius: 8px; scroll-margin-top: 100px;">
                <h3 style="font-size: 24px; margin-top: 0; color: #4facfe;"><?php echo htmlspecialchars($s['title']); ?></h3>
                <p style="color: #ccc; font-size: 16px; margin-bottom: 20px;"><?php echo htmlspecialchars($s['desc']); ?></p>
                
                <h4 style="margin-bottom: 10px; font-size: 16px;">Key Capabilities:</h4>
                <ul style="list-style: square; padding-left: 20px; color: #aaa; line-height: 1.8;">
                    <?php foreach ($s['details'] as $item): ?>
                        <li><?php echo htmlspecialchars($item); ?></li>
                    <?php endforeach; ?>
                </ul>
                <div style="margin-top: 20px;">
                    <a href="index.php#contact" class="btn-primary" style="display: inline-block; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Get Started &rarr;</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php include 'footer.php'; ?>
<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Require database connection
require_once __DIR__ . '/db.php';

// Fetch Latest Blogs & Tech Insights dynamically from database
try {
    $stmt = $pdo->query("SELECT id, category, title, excerpt, youtube_id, full_content, DATE_FORMAT(created_at, '%b %d, %Y') as formatted_date FROM blogs ORDER BY id DESC");
    $recentBlogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $recentBlogs = []; // Fallback array if table is empty or query fails
}
?>

<!-- BLOG / TECH INSIGHTS SECTION -->
<section id="blog" class="blog-section">
    <div class="container">
        <h2 class="section-title">LATEST BLOGS & TECH INSIGHTS</h2>
        <p class="section-subtitle">Stay updated with our latest news and technical insights.</p>

        <div class="blog-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 30px;">
            <?php if (!empty($recentBlogs)): ?>
                <?php foreach ($recentBlogs as $blog): ?>
                    <div class="blog-card" style="background: #141419; border: 1px solid #2a2a35; border-radius: 8px; overflow: hidden;">
                        
                        <!-- Embedded YouTube Video -->
                        <div class="blog-video" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                            <iframe 
                                src="https://www.youtube.com/embed/<?php echo htmlspecialchars($blog['youtube_id']); ?>" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen
                                style="position: absolute; top:0; left:0; width:100%; height:100%;">
                            </iframe>
                        </div>

                        <div class="blog-content" style="padding: 20px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                <span style="color: #4facfe; font-size: 12px; font-weight: 600; text-transform: uppercase;"><?php echo htmlspecialchars($blog['category']); ?></span>
                                <span style="color: #8888a0; font-size: 12px;"><?php echo htmlspecialchars($blog['formatted_date']); ?></span>
                            </div>

                            <h3 style="color: #fff; font-size: 18px; margin-bottom: 10px; font-weight: 600;"><?php echo htmlspecialchars($blog['title']); ?></h3>
                            <p style="color: #b0b0c0; font-size: 14px; line-height: 1.5; margin-bottom: 15px;"><?php echo htmlspecialchars($blog['excerpt']); ?></p>
                            
                            <!-- Access Control Link -->
                            <?php if (isset($_SESSION['customer_id'])): ?>
                                <a href="blogs.php?id=<?php echo $blog['id']; ?>" style="color: #00f2fe; text-decoration: none; font-weight: 600; font-size: 14px;">Read More &rarr;</a>
                            <?php else: ?>
                                <a href="#" onclick="document.getElementById('loginModal').style.display='flex'; return false;" style="color: #00f2fe; text-decoration: none; font-weight: 600; font-size: 14px;">Read More &rarr;</a>
                            <?php endif; ?>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #888; grid-column: 1 / -1; text-align: center;">No blog posts available at the moment.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
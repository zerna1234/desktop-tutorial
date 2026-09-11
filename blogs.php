<?php
session_start();
require_once 'db.php';

$pageTitle = "MZ Tech Solution - All Blogs & Tech Insights";
include 'header.php';

// FETCH ALL BLOG POSTS FROM THE DATABASE
try {
    $stmt = $pdo->query("SELECT * FROM blogs ORDER BY id DESC");
    $allBlogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $allBlogs = [];
}
?>

    <!-- ALL BLOGS HEADER SECTION -->
    <section style="padding: 100px 20px 40px 20px; text-align: center; background: #0a0a0a;">
        <div class="section-title">
            <h2 style="color: #fff; font-size: 32px;">ALL BLOGS & TECH INSIGHTS</h2>
            <p style="color: #8a99ad; margin-top: 10px; font-size: 15px;">Browse our full archive of engineering articles, video tutorials, and tech breakdowns</p>
            <div class="divider" style="margin: 15px auto 0 auto;"></div>
        </div>

        <!-- Optional Category Filter Buttons -->
        <div class="filter-container" style="display: flex; justify-content: center; gap: 10px; margin-top: 30px; flex-wrap: wrap;">
            <button class="filter-btn active" onclick="filterBlogCategory('all', this)">All Posts</button>
            <button class="filter-btn" onclick="filterBlogCategory('Tech', this)">Tech</button>
            <button class="filter-btn" onclick="filterBlogCategory('Development', this)">Development</button>
            <button class="filter-btn" onclick="filterBlogCategory('Security', this)">Security</button>
        </div>
    </section>

    <!-- BLOGS GRID SECTION -->
    <section style="padding: 20px 20px 80px 20px; background: #11141a; min-height: 50vh;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px; max-width: 1200px; margin: 0 auto;">
            <?php if (empty($allBlogs)): ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 60px; color: #8b949e; background: #161b22; border: 1px solid #21262d; border-radius: 8px;">
                    <h3 style="color: #fff; margin-bottom: 10px;">No Blog Posts Found</h3>
                    <p>There are no articles published in the database yet. Check back soon or add one from your admin panel!</p>
                </div>
            <?php else: ?>
                <?php foreach ($allBlogs as $blog): ?>
                    <div class="blog-card" data-category="<?php echo htmlspecialchars($blog['category']); ?>" style="background: #161b22; border: 1px solid #21262d; border-radius: 8px; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.3s ease, border-color 0.3s ease;">
                        
                        <!-- YouTube Video Embed Preview -->
                        <?php if (!empty($blog['youtube_id'])): ?>
                            <div style="position: relative; width: 100%; padding-top: 56.25%; background: #000; overflow: hidden;">
                                <iframe 
                                    src="https://www.youtube.com/embed/<?php echo htmlspecialchars($blog['youtube_id']); ?>?autoplay=0&controls=1&modestbranding=1&rel=0" 
                                    title="<?php echo htmlspecialchars($blog['title']); ?>"
                                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"
                                    allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                                </iframe>
                            </div>
                        <?php endif; ?>

                        <div style="padding: 24px; display: flex; flex-direction: column; flex-grow: 1; justify-content: space-between;">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                    <span style="color: #4facfe; font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; border: 1px solid #4facfe44; padding: 3px 8px; border-radius: 4px;"><?php echo htmlspecialchars($blog['category']); ?></span>
                                    <span style="color: #6e7681; font-size: 12px;"><?php echo date('M d, Y', strtotime($blog['created_at'])); ?></span>
                                </div>
                                <h3 style="color: #ffffff; font-size: 19px; margin-bottom: 12px; line-height: 1.4;"><?php echo htmlspecialchars($blog['title']); ?></h3>
                                <p style="color: #8b949e; font-size: 13px; line-height: 1.6; margin-bottom: 20px;"><?php echo htmlspecialchars($blog['excerpt']); ?></p>
                            </div>
                            
                            <!-- Read Full Article Button triggering the Modal -->
                            <button onclick="showArticleModal(<?php echo $blog['id']; ?>)" style="background: none; border: none; color: #4facfe; font-size: 13px; font-weight: 600; cursor: pointer; padding: 0; display: inline-flex; align-items: center; gap: 5px; text-align: left;">READ ARTICLE &rarr;</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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
                <div style="position: relative; width: 100%; padding-top: 56.25%; background: #000; overflow: hidden; border-radius: 6px; margin-bottom: 25px;">
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

<!-- Inline Styles for Filters and Cards -->
<style>
.filter-btn {
    background: #161b22;
    color: #c9d1d9;
    border: 1px solid #30363d;
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

.blog-card:hover {
    border-color: #4facfe55;
    transform: translateY(-3px);
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

<!-- JavaScript Modal & Filtering Logic -->
<script>
const allBlogsData = <?php echo json_encode($allBlogs); ?>;

function showArticleModal(blogId) {
    const blog = allBlogsData.find(item => parseInt(item.id) === parseInt(blogId));
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

window.addEventListener('click', function(event) {
    const modal = document.getElementById('articleModalOverlay');
    if (event.target === modal) {
        closeArticleModal();
    }
});

function filterBlogCategory(category, element) {
    const buttons = document.querySelectorAll('.filter-btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    element.classList.add('active');

    const cards = document.querySelectorAll('.blog-card');
    cards.forEach(card => {
        const cardCategory = card.getAttribute('data-category');
        if (category === 'all' || cardCategory.toLowerCase() === category.toLowerCase()) {
            card.style.display = 'flex';
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
<?php
require_once 'includes/config.php';

// Get post slug from URL
$slug = filter_input(INPUT_GET, 'slug', FILTER_SANITIZE_STRING);

if (!$slug) {
    header('Location: blog.php');
    exit;
}

try {
    // Get post details
    $stmt = $pdo->prepare("
        SELECT p.*, c.name as category_name, c.slug as category_slug, a.name as author_name
        FROM posts p
        JOIN categories c ON p.category_id = c.id
        JOIN authors a ON p.author_id = a.id
        WHERE p.slug = ? AND p.status = 'published'
    ");
    $stmt->execute([$slug]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        header('Location: blog.php');
        exit;
    }

    // Update post views
    $stmt = $pdo->prepare("UPDATE posts SET views = views + 1 WHERE id = ?");
    $stmt->execute([$post['id']]);

    // Get related posts
    $stmt = $pdo->prepare("
        SELECT p.*, c.name as category_name, a.name as author_name
        FROM posts p
        JOIN categories c ON p.category_id = c.id
        JOIN authors a ON p.author_id = a.id
        WHERE p.category_id = ? AND p.id != ? AND p.status = 'published'
        ORDER BY p.created_at DESC
        LIMIT 3
    ");
    $stmt->execute([$post['category_id'], $post['id']]);
    $related_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    header('Location: blog.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - <?php echo SITE_NAME; ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($post['excerpt']); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/images/favicon.png">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        .blog-post {
            margin: 50px 0;
        }
        .blog-post .featured-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .blog-post .post-meta {
            margin-bottom: 20px;
        }
        .blog-post .post-meta span {
            margin-left: 20px;
            color: #666;
        }
        .blog-post .post-meta i {
            margin-left: 5px;
        }
        .blog-post .post-content {
            line-height: 1.8;
            font-size: 18px;
        }
        .blog-post .post-content p {
            margin-bottom: 20px;
        }
        .blog-post .post-content img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 20px 0;
        }
        .blog-post .post-content h2, 
        .blog-post .post-content h3 {
            margin: 30px 0 20px;
        }
        .blog-post .post-content blockquote {
            border-right: 4px solid #007bff;
            padding: 20px;
            background: #f8f9fa;
            margin: 20px 0;
        }
        .related-posts {
            margin-top: 50px;
        }
        .related-posts .card {
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }
        .related-posts .card:hover {
            transform: translateY(-5px);
        }
        .related-posts .card img {
            height: 200px;
            object-fit: cover;
        }
        .share-buttons {
            margin: 30px 0;
        }
        .share-buttons a {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            background: #f8f9fa;
            border-radius: 50%;
            margin-left: 10px;
            color: #333;
            transition: all 0.3s ease;
        }
        .share-buttons a:hover {
            background: #007bff;
            color: #fff;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main>
        <div class="container">
            <article class="blog-post">
                <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="featured-image">
                
                <h1><?php echo htmlspecialchars($post['title']); ?></h1>
                
                <div class="post-meta">
                    <span><i class="far fa-calendar"></i> <?php echo date('d M Y', strtotime($post['created_at'])); ?></span>
                    <span><i class="far fa-user"></i> <?php echo htmlspecialchars($post['author_name']); ?></span>
                    <span><i class="far fa-folder"></i> <a href="blog.php?category=<?php echo $post['category_slug']; ?>"><?php echo htmlspecialchars($post['category_name']); ?></a></span>
                    <span><i class="far fa-eye"></i> <?php echo number_format($post['views']); ?> مشاهدة</span>
                </div>

                <div class="share-buttons">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(SITE_URL . '/blog-post.php?slug=' . $post['slug']); ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(SITE_URL . '/blog-post.php?slug=' . $post['slug']); ?>&text=<?php echo urlencode($post['title']); ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(SITE_URL . '/blog-post.php?slug=' . $post['slug']); ?>&title=<?php echo urlencode($post['title']); ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                    <a href="https://wa.me/?text=<?php echo urlencode($post['title'] . ' ' . SITE_URL . '/blog-post.php?slug=' . $post['slug']); ?>" target="_blank"><i class="fab fa-whatsapp"></i></a>
                </div>

                <div class="post-content">
                    <?php echo $post['content']; ?>
                </div>
            </article>

            <?php if (!empty($related_posts)): ?>
            <section class="related-posts">
                <h2>مقالات ذات صلة</h2>
                <div class="row">
                    <?php foreach ($related_posts as $related): ?>
                    <div class="col-md-4">
                        <div class="card">
                            <img src="<?php echo htmlspecialchars($related['featured_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($related['title']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($related['title']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($related['excerpt']); ?></p>
                                <div class="blog-meta">
                                    <span><i class="far fa-calendar"></i> <?php echo date('d M Y', strtotime($related['created_at'])); ?></span>
                                    <span><i class="far fa-user"></i> <?php echo htmlspecialchars($related['author_name']); ?></span>
                                </div>
                                <a href="blog-post.php?slug=<?php echo $related['slug']; ?>" class="btn btn-primary mt-3">اقرأ المزيد</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript Files -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html> 
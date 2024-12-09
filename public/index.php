<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: ' . __DIR__ . '/../public/home.php');
    exit;
}

include __DIR__ . '/../includes/header.php';
?>

<div class="snap-container">
    <section id="intro-section" class="snap-section">
        <div class="section-content">
            <h1>Welcome to TAGGY</h1>
            <p>Discover and share memories with ease.</p>
            <a href="signup.php" class="cta-button">Get Started</a>
        </div>
    </section>
    <section id="memory-section" class="snap-section">
        <div class="section-content">
            <h1>Create Memories</h1>
            <p>Tag your favorite moments and keep them forever.</p>
        </div>
    </section>
    <section id="sharing-section" class="snap-section">
        <div class="section-content">
            <h1>Easy Sharing</h1>
            <p>Share your memories with your loved ones effortlessly.</p>
        </div>
    </section>
    <section id="get-started-section" class="snap-section">
        <div class="section-content">
            <h1>Get Started</h1>
            <p><a href="signup.php">Sign Up</a> or <a href="login.php">Login</a> to begin your journey.</p>
        </div>
    </section>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

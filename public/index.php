<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// ログインユーザーがindex.phpにアセクスした時の処理（homeに飛ばすかどうか）
if (isset($_SESSION['user_id'])) {
    header('Location: /taggy/public/home.php');
    exit;
}


include __DIR__ . '/../includes/header.php';
?>
<!-- <div id="modal-overlay" class="modal-overlay hidden">
    <div class="modal-content">
        <button id="close-modal" class="close-button">&times;</button>
        <div id="modal-body"></div>
    </div>
</div> -->

<div class="snap-container">
    <section id="intro-section" class="snap-section">
        <div class="section-content">
            <h1>TAGGY</h1>
            <p>あなたの大切なモノの物語を紡ぐ、新しい思い出の形</p>
        </div>
        <a href="signup.php" class="cta-button btn-primary">登録して始める</a>
    </section>
    <section id="memory-section" class="snap-section">
        <div class="section-content">
            <h1>モノに想いを。想いを未来へ。</h1>
            <p>TAGGYはモノに思い出を残す、デジタルメモリープラットフォームです。</p>
        </div>
    </section>
    <section id="get-started-section" class="snap-section">
        <div class="section-content">
            <h1>さぁ、思い出の溢れる生活へ</h1>
            <p><a href="signup.php">新規登録</a> or <a href="login.php">ログイン</a></p>
        </div>
    </section>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
<!-- <script src="public/assets/js/main.js"></script> -->
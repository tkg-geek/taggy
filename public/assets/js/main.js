document.addEventListener("DOMContentLoaded", () => {
    const modalOverlay = document.getElementById("modal-overlay");
    const closeModal = document.getElementById("close-modal");

    // サインアップボタンをクリックしたらモーダル表示
    document.querySelectorAll(".cta-button, a[href='signup.php']").forEach(button => {
        button.addEventListener("click", (e) => {
            e.preventDefault();
            fetch('signup.php')
                .then(response => response.text())
                .then(html => {
                    document.getElementById("modal-body").innerHTML = html;
                    modalOverlay.classList.remove("hidden");
                    attachFormListeners();
                });
        });
    });

    // ログインボタンをクリックしたらモーダル表示
    document.querySelectorAll("a[href='login.php']").forEach(button => {
        button.addEventListener("click", (e) => {
            e.preventDefault();
            fetch('login.php')
                .then(response => response.text())
                .then(html => {
                    document.getElementById("modal-body").innerHTML = html;
                    modalOverlay.classList.remove("hidden");
                    attachFormListeners();
                });
        });
    });

    // モーダルを閉じる
    closeModal.addEventListener("click", () => {
        modalOverlay.classList.add("hidden");
    });

    // オーバーレイをクリックして閉じる
    modalOverlay.addEventListener("click", (e) => {
        if (e.target === modalOverlay) {
            modalOverlay.classList.add("hidden");
        }
    });

    // モーダル内のフォーム送信後にモーダルを閉じる
    function attachFormListeners() {
        const form = document.querySelector("#modal-body form");
        if (form) {
            form.addEventListener("submit", () => {
                modalOverlay.classList.add("hidden");
            });
        }
    }
});

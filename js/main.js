document.addEventListener("DOMContentLoaded", () => {

    // ===== MENU =====
    const menuBtn  = document.getElementById("menuBtn");
    const backBtn  = document.getElementById("backBtn");
    const mainView = document.getElementById("mainView");
    const menuView = document.getElementById("menuView");

    menuBtn?.addEventListener("click", () => {
        mainView.style.display = "none";
        menuView.style.display = "block";
    });

    backBtn?.addEventListener("click", () => {
        menuView.style.display = "none";
        mainView.style.display = "block";
    });

    // ===== COMPOSE =====
    const composeBtn = document.getElementById("composeBtn");
    const composeBox = document.getElementById("composeBox");
    const cancelBtn  = document.getElementById("cancelCompose");

    composeBtn?.addEventListener("click", () => {
        composeBox.style.display = "block";
    });

    cancelBtn?.addEventListener("click", () => {
        composeBox.style.display = "none";
    });

});


// =========================
// ADD COMMENT (AJAX)
// =========================
function addComment(e, id) {
    e.preventDefault();

    const form = e.target;
    const comment = form.comment.value;

    fetch('api/index.php?action=comment', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `message_id=${id}&comment=${encodeURIComponent(comment)}`
    })
    .then(res => res.text())
    .then(() => {

        const box = form.closest('.comment-box');

        const newComment = document.createElement('div');
        newComment.className = 'comment';

        newComment.innerHTML = `
            <div class="comment-user">You</div>
            <div class="comment-text">${comment}</div>
        `;

        box.insertBefore(newComment, form);
        form.reset();
    })
    .catch(err => console.error("Comment error:", err));

    return false;
}


// =========================
// GLOBAL CLICK HANDLER
// =========================
document.addEventListener("click", (e) => {

    // ONLY handle comment toggle
    if (e.target.closest(".comment-toggle")) {
        handleCommentToggle(e);
        return;
    }

    // ONLY handle reactions
    if (e.target.closest(".react-btn")) {
        handleReaction(e);
        return;
    }

});


// =========================
// COMMENT TOGGLE
// =========================

function handleCommentToggle(e) {
    const btn = e.target.closest(".comment-toggle");
    if (!btn) return;

    const id = btn.dataset.id;
    const box = document.getElementById("comments-" + id);

    if (!box) return;

    box.classList.toggle("show"); // ✅ THIS is the key
}

// =========================
// REACT BUTTON
// =========================
function handleReaction(e) {
    const btn = e.target.closest(".react-btn");
    if (!btn) return;

    const messageId = btn.dataset.id;
    const type = btn.dataset.type;

    sendReaction(messageId, type, btn);
}


// =========================
// API CALL
// =========================
function sendReaction(messageId, type, btn) {
    fetch('api/index.php?action=react', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `message_id=${messageId}&reaction=${type}`
    })
    .then(res => res.json())
    .then(data => updateReactionUI(btn, data))
    .catch(err => console.error("Reaction error:", err));
}


// =========================
// UPDATE UI
// =========================
function updateReactionUI(btn, data) {
    const container = btn.closest('.post-actions');
    const buttons = container.querySelectorAll('.react-btn');

    if (buttons[0]) buttons[0].innerHTML = `👍 ${data.likes}`;
    if (buttons[1]) buttons[1].innerHTML = `👎 ${data.dislikes}`;

    buttons.forEach(b => b.classList.remove('active-like', 'active-dislike'));

    if (data.myReaction === 'like') {
        buttons[0]?.classList.add('active-like');
    } else if (data.myReaction === 'dislike') {
        buttons[1]?.classList.add('active-dislike');
    }
}




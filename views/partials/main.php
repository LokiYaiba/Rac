<div id="mainView">

    <?php if ($isLoggedIn): ?>

        <button id="composeBtn">✍️ Compose Rant</button>

        <div id="composeBox" style="display:none;">
            <form action="api/save.php" method="POST">
                <input type="text" name="recipient" placeholder="To..." required>
                <input type="text" name="username" placeholder="From...">
                <textarea name="content" placeholder="Your message" required></textarea>

                <button type="submit">Send</button>
                <button type="button" id="cancelCompose">Cancel</button>
            </form>
        </div>

    <?php else: ?>

        <p>Please <a href="login.php">login</a> to post a message.</p>

    <?php endif; ?>

    <hr>

    <!-- 🔥 KEEP THESE -->
    <?php include __DIR__ . '/../messages/list.php'; ?>
    <?php include __DIR__ . '/pagination.php'; ?>

</div>
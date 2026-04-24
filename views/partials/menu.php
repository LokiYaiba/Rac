<?php if ($isLoggedIn): ?>
<div id="menuView" style="display:none;">
    <button id="backBtn">⬅ Back</button>
    <p>👤 <?= htmlspecialchars($currentUser['username']); ?></p>
    <a href="logout.php">🚪 Logout</a>
</div>
<?php endif; ?>
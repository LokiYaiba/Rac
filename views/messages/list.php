<?php foreach ($messages as $row): ?>

<?php
$recipient = htmlspecialchars($row['recipient'] ?? 'Unknown');
$username  = htmlspecialchars($row['username'] ?? 'Anonymous');
$content   = htmlspecialchars($row['content'] ?? '');
$time      = $row['created_at'] ?? '';
?>

<div class="message">

    <!-- HEADER -->
    <div class="post-header">
        <div class="to">To: <?= $recipient ?></div>
        <div class="time"><?= $time ?></div>
    </div>

    <!-- CONTENT -->
    <div class="post-content"><?= $content ?></div>

    <!-- FOOTER -->
    <div class="post-footer">
        / From: <?= $username ?>
    </div>

    <!-- ACTIONS -->
    <div class="post-actions">

        <!-- LEFT: REACTIONS -->
        <div class="left-actions">
            <?php if ($isLoggedIn): ?>
                <button class="react-btn <?= $row['myReaction']==='like' ? 'active-like' : '' ?>"
                        data-id="<?= $row['id'] ?>" data-type="like">
                    👍 <?= $row['likes'] ?>
                </button>

                <button class="react-btn <?= $row['myReaction']==='dislike' ? 'active-dislike' : '' ?>"
                        data-id="<?= $row['id'] ?>" data-type="dislike">
                    👎 <?= $row['dislikes'] ?>
                </button>
            <?php else: ?>
                <button disabled>👍 <?= $row['likes'] ?></button>
                <button disabled>👎 <?= $row['dislikes'] ?></button>
            <?php endif; ?>
        </div>

        <!-- RIGHT: COMMENT BUTTON -->
        <div class="right-actions">
            <button type="button" class="comment-toggle" data-id="<?= $row['id'] ?>">
                💬 Click to write comment (<?= count($row['comments']) ?>)
            </button>
        </div>

    </div>

    <!-- COMMENTS (HIDDEN) -->

    <div class="comment-box" id="comments-<?= $row['id'] ?>">
    <?php foreach ($row['comments'] as $c): ?>
        <div class="comment">

            <div class="comment-user" data-initial="<?= strtoupper($c['username'][0]) ?>">
                <?= htmlspecialchars($c['username']) ?>
            </div>

            <div class="comment-text">
                <?= htmlspecialchars($c['comment']) ?>
            </div>

        </div>
    <?php endforeach; ?>

        <?php if ($isLoggedIn): ?>
            <form onsubmit="return addComment(event, <?= $row['id'] ?>)">
                <input class="comment-input" type="text" name="comment" placeholder="Write a comment..." required>
            </form>
        <?php else: ?>
            <input class="comment-input" disabled placeholder="Login required">
        <?php endif; ?>

    </div>

</div>

<?php endforeach; ?>
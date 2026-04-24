<?php
require_once __DIR__ . '/../db.php';

function handleReaction($user_id, $message_id, $reaction) {
    global $conn;

    // check existing
    $stmt = $conn->prepare("SELECT reaction FROM reactions WHERE user_id = ? AND message_id = ?");
    $stmt->execute([$user_id, $message_id]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        // update
        $stmt = $conn->prepare("UPDATE reactions SET reaction = ? WHERE user_id = ? AND message_id = ?");
        $stmt->execute([$reaction, $user_id, $message_id]);
    } else {
        // insert
        $stmt = $conn->prepare("INSERT INTO reactions (user_id, message_id, reaction) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $message_id, $reaction]);
    }

    // count likes
    $stmt = $conn->prepare("SELECT COUNT(*) as c FROM reactions WHERE message_id = ? AND reaction = 'like'");
    $stmt->execute([$message_id]);
    $likes = $stmt->fetch(PDO::FETCH_ASSOC)['c'];

    // count dislikes
    $stmt = $conn->prepare("SELECT COUNT(*) as c FROM reactions WHERE message_id = ? AND reaction = 'dislike'");
    $stmt->execute([$message_id]);
    $dislikes = $stmt->fetch(PDO::FETCH_ASSOC)['c'];

    return [
        'likes' => (int)$likes,
        'dislikes' => (int)$dislikes,
        'myReaction' => $reaction
    ];
}

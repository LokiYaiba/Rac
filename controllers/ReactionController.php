<?php
require_once __DIR__ . '/../db.php';

function handleReaction($user_id, $message_id, $reaction) {
    global $conn;

    // check existing
    $stmt = $conn->prepare("SELECT reaction FROM reactions WHERE user_id=? AND message_id=?");
    $stmt->bind_param("ii", $user_id, $message_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result) {
        $stmt = $conn->prepare("UPDATE reactions SET reaction=? WHERE user_id=? AND message_id=?");
        $stmt->bind_param("sii", $reaction, $user_id, $message_id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO reactions (user_id, message_id, reaction) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $message_id, $reaction);
        $stmt->execute();
    }

    // return updated counts
    $likes = $conn->query("SELECT COUNT(*) as c FROM reactions WHERE message_id=$message_id AND reaction='like'")
                  ->fetch_assoc()['c'];

    $dislikes = $conn->query("SELECT COUNT(*) as c FROM reactions WHERE message_id=$message_id AND reaction='dislike'")
                     ->fetch_assoc()['c'];

    return [
        'likes' => $likes,
        'dislikes' => $dislikes,
        'myReaction' => $reaction
    ];
}
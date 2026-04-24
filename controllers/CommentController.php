<?php
require_once __DIR__ . '/../db.php';

function getComments($message_id) {
    global $conn;

    $stmt = $conn->prepare("SELECT username, comment FROM comments WHERE message_id = ?");
    $stmt->execute([$message_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addComment($message_id, $username, $comment) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO comments (message_id, username, comment) VALUES (?, ?, ?)");
    return $stmt->execute([$message_id, $username, $comment]);
}

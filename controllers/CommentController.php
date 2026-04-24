<?php
require_once __DIR__ . '/../db.php';

function getComments($message_id) {
    global $conn;

    $stmt = $conn->prepare("SELECT username, comment FROM comments WHERE message_id=?");
    $stmt->bind_param("i", $message_id);
    $stmt->execute();

    return $stmt->get_result();
}

function addComment($message_id, $username, $comment) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO comments (message_id, username, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $message_id, $username, $comment);
    return $stmt->execute();
}


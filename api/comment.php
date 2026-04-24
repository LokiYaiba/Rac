<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $message_id = $_POST['message_id'] ?? 0;
    $comment = $_POST['comment'] ?? '';
    $username = $_SESSION['username'] ?? 'Anonymous';

    if ($message_id && $comment) {

        $stmt = $conn->prepare("INSERT INTO comments (message_id, username, comment) VALUES (?, ?, ?)");
        $stmt->execute([$message_id, $username, $comment]);

        echo "OK"; // ✅ for AJAX
    } else {
        echo "ERROR";
    }
}
?>

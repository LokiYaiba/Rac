<?php
session_start();
require '../controllers/ReactionController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id = $_SESSION['user_id'] ?? null;
    $message_id = $_POST['message_id'] ?? 0;
    $reaction = $_POST['reaction'] ?? '';

    if (!$user_id || !$message_id) {
        exit('ERROR');
    }

    $result = handleReaction($user_id, $message_id, $reaction);

    echo json_encode($result);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id = $_SESSION['user_id'] ?? null;
    $message_id = $_POST['message_id'] ?? 0;
    $reaction = $_POST['reaction'] ?? '';

    if (!$user_id || !$message_id || !in_array($reaction, ['like', 'dislike'])) {
        exit('ERROR');
    }

    // check existing reaction
    $stmt = $conn->prepare("SELECT reaction FROM reactions WHERE user_id = ? AND message_id = ?");
    $stmt->bind_param("ii", $user_id, $message_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result) {
        // update
        $stmt = $conn->prepare("UPDATE reactions SET reaction=? WHERE user_id=? AND message_id=?");
        $stmt->bind_param("sii", $reaction, $user_id, $message_id);
        $stmt->execute();
    } else {
        // insert
        $stmt = $conn->prepare("INSERT INTO reactions (user_id, message_id, reaction) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $message_id, $reaction);
        $stmt->execute();
    }

    // return new counts
    $likes = $conn->query("SELECT COUNT(*) as c FROM reactions WHERE message_id=$message_id AND reaction='like'")
                  ->fetch_assoc()['c'];

    $dislikes = $conn->query("SELECT COUNT(*) as c FROM reactions WHERE message_id=$message_id AND reaction='dislike'")
                     ->fetch_assoc()['c'];

    echo json_encode([
        'likes' => $likes,
        'dislikes' => $dislikes,
        'myReaction' => $reaction
    ]);
}
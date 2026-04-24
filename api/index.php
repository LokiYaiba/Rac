<?php
session_start();

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch ($action) {

    case 'react':
        require '../controllers/ReactionController.php';

        $user_id = $_SESSION['user_id'] ?? null;
        $message_id = $_POST['message_id'] ?? 0;
        $reaction = $_POST['reaction'] ?? '';

        if (!$user_id || !$message_id) {
            echo json_encode(['error' => 'Invalid data']);
            exit;
        }

        $result = handleReaction($user_id, $message_id, $reaction);
        echo json_encode($result);
        break;


    case 'comment':
        require '../controllers/CommentController.php';

        $message_id = $_POST['message_id'] ?? 0;
        $comment = $_POST['comment'] ?? '';
        $username = $_SESSION['username'] ?? 'Anonymous';

        if (!$message_id || !$comment) {
            echo json_encode(['error' => 'Invalid input']);
            exit;
        }

        addComment($message_id, $username, $comment);

        echo json_encode(['status' => 'OK']);
        break;


    default:
        echo json_encode(['error' => 'Invalid action']);
}
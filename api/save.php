<?php
include '../db.php'; // go up one folder

$recipient = $_POST['recipient'];
$username = $_POST['username'] ?: "Anonymous";
$content = $_POST['content'];

$stmt = $conn->prepare("INSERT INTO messages (recipient, username, content) VALUES (?, ?, ?)");
$stmt->execute([$recipient, $username, $content]);

header("Location: ../index.php"); // go back to main page
exit();
?>

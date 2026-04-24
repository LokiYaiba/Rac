<?php
include 'db.php';

$id = $_POST['id'];

$conn->query("UPDATE messages SET likes = likes + 1 WHERE id = $id");
?>
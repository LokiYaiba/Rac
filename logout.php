<?php
session_start();

// remove all session data
$_SESSION = [];

// destroy session
session_destroy();

// prevent cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

header("Location: index.php");
exit();
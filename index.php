<?php
// ==============================
// BOOTSTRAP
// ==============================
require 'auth.php';
require 'controllers/MessageController.php';

$currentUser = user();
$isLoggedIn  = isset($_SESSION['user_id']);

// ==============================
// PAGINATION SETUP
// ==============================
$limit  = 10;
$page   = $_GET['page'] ?? 1;
$offset = ($page - 1) * $limit;

// ==============================
// DATA
// ==============================
$messages   = getMessagesWithMeta($limit, $offset, $currentUser['id'] ?? null);
$pagination = getPagination($limit);

$totalPages = $pagination['totalPages'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>RANTS AND CONFESSION</title>
</head>

<body>
<script>
const CURRENT_USER = "<?= htmlspecialchars($currentUser['username'] ?? 'You') ?>";
</script>


<div class="header">
        <h2><?php include 'views/partials/menu-button.php'; ?> RANTS AND CONFESSION</h2>
        
    </div>
<div class="container">

    <!-- HEADER -->


    <!-- MENU BUTTON -->
    

    <!-- MAIN CONTENT -->
    <?php include 'views/partials/main.php'; ?>

    <!-- MENU PANEL -->
    <?php include 'views/partials/menu.php'; ?>

</div>

<!-- JS -->
<script src="js/main.js"></script>

</body>
</html>
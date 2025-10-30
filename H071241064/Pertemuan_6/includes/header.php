<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Manajemen Proyek'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">

<?php if (isset($_SESSION['user_id'])): ?>
    <nav class="bg-black/50 text-white">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <h1 class="text-lg font-semibold flex items-center gap-2">
                Manajemen Proyek
            </h1>

            <div class="flex items-center gap-4">
                <span class="text-sm">
                    <i class="fas fa-user-circle"></i>
                    <?php echo htmlspecialchars($_SESSION['username']); ?> 
                </span>
                <a href="../auth/logout.php" 
                   class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm flex items-center gap-1">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>
<?php endif; ?>

<main class="container mx-auto px-4 py-8">

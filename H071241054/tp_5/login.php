<?php
session_start();

if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  
  <div class="bg-white shadow-lg rounded-2xl w-full max-w-sm p-8">
    <h1 class="text-2xl font-bold text-center text-gray-700 mb-6">Login</h1>

    <?php if ($error): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 text-center">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form action="proses_login.php" method="POST">
      <div class="mb-4">
        <label for="username" class="block text-gray-600 font-medium mb-2">Username</label>
        <input 
          type="text" 
          id="username" 
          name="username" 
          required
          placeholder="Masukkan username"
          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
        >
      </div>

      <div class="mb-6">
        <label for="password" class="block text-gray-600 font-medium mb-2">Password</label>
        <input 
          type="password" 
          id="password" 
          name="password" 
          required
          placeholder="Masukkan password"
          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
        >
      </div>

      <button 
        type="submit" 
        class="w-full bg-blue-500 text-white py-2 rounded-lg font-semibold hover:bg-blue-600 transition duration-300"
      >
        Login
      </button>
    </form>
  </div>

</body>
</html>
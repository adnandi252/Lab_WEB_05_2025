<?php
session_start(); 

require_once __DIR__ . '/../config/database.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Super Admin') {
    header('Location: ../auth/login.php');
    exit();
}

$page_title = 'Dashboard Super Admin';

$total_managers = 0;
$total_members = 0;
$total_projects = 0;
$total_tasks = 0;

$query_managers = "SELECT COUNT(*) as total FROM users WHERE role = 'Project Manager'";
if ($result_managers = mysqli_query($conn, $query_managers)) {
    $total_managers = mysqli_fetch_assoc($result_managers)['total'];
}

$query_members = "SELECT COUNT(*) as total FROM users WHERE role = 'Team Member'";
if ($result_members = mysqli_query($conn, $query_members)) {
    $total_members = mysqli_fetch_assoc($result_members)['total'];
}

$query_projects = "SELECT COUNT(*) as total FROM projects";
if ($result_projects = mysqli_query($conn, $query_projects)) {
    $total_projects = mysqli_fetch_assoc($result_projects)['total'];
}

$query_tasks = "SELECT COUNT(*) as total FROM tasks";
if ($result_tasks = mysqli_query($conn, $query_tasks)) {
    $total_tasks = mysqli_fetch_assoc($result_tasks)['total'];
}

include __DIR__ . '/../includes/header.php';
?>

<div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">
        <i class="fas fa-tachometer-alt"></i> Dashboard Super Admin
    </h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Project Managers</p>
                    <p class="text-3xl font-bold text-gray-800"><?= htmlspecialchars($total_managers); ?></p>
                </div>
                <div class="bg-blue-100 rounded-full p-4">
                    <i class="fas fa-user-tie text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Team Members</p>
                    <p class="text-3xl font-bold text-gray-800"><?= htmlspecialchars($total_members); ?></p>
                </div>
                <div class="bg-green-100 rounded-full p-4">
                    <i class="fas fa-users text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Proyek</p>
                    <p class="text-3xl font-bold text-gray-800"><?= htmlspecialchars($total_projects); ?></p>
                </div>
                <div class="bg-purple-100 rounded-full p-4">
                    <i class="fas fa-folder text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Tugas</p>
                    <p class="text-3xl font-bold text-gray-800"><?= htmlspecialchars($total_tasks); ?></p>
                </div>
                <div class="bg-orange-100 rounded-full p-4">
                    <i class="fas fa-tasks text-orange-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-bolt"></i> Quick Actions
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="users.php" class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg p-4 text-center transition">
                <i class="fas fa-users text-3xl mb-2"></i>
                <p class="font-semibold">Kelola User</p>
            </a>
            <a href="projects.php" class="bg-purple-500 hover:bg-purple-600 text-white rounded-lg p-4 text-center transition">
                <i class="fas fa-folder text-3xl mb-2"></i>
                <p class="font-semibold">Lihat Semua Proyek</p>
            </a>
            <a href="add_user.php" class="bg-green-500 hover:bg-green-600 text-white rounded-lg p-4 text-center transition">
                <i class="fas fa-user-plus text-3xl mb-2"></i>
                <p class="font-semibold">Tambah User Baru</p>
            </a>
        </div>
    </div>
</div>

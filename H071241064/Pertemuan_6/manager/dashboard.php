<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Project Manager') {
    header('Location: ../auth/login.php');
    exit();
}

$page_title = 'Dashboard Project Manager';
$manager_id = (int)$_SESSION['user_id'];

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM projects WHERE manager_id = ?");
$stmt->bind_param("i", $manager_id);
$stmt->execute();
$total_projects = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total 
    FROM tasks t 
    JOIN projects p ON t.project_id = p.id 
    WHERE p.manager_id = ?
");
$stmt->bind_param("i", $manager_id);
$stmt->execute();
$total_tasks = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total 
    FROM tasks t 
    JOIN projects p ON t.project_id = p.id 
    WHERE p.manager_id = ? AND t.status = 'selesai'
");
$stmt->bind_param("i", $manager_id);
$stmt->execute();
$total_completed = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

$stmt = $conn->prepare("
    SELECT COUNT(DISTINCT id) AS total 
    FROM users 
    WHERE role = 'Team Member' AND project_manager_id = ?
");
$stmt->bind_param("i", $manager_id);
$stmt->execute();
$total_members = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

include '../includes/header.php';
?>

<div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">
        Dashboard Project Manager
    </h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Proyek Saya</p>
                    <p class="text-3xl font-bold text-gray-800"><?= $total_projects; ?></p>
                </div>
                <div class="bg-blue-100 rounded-full p-4">
                    <i class="fas fa-folder text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Tugas</p>
                    <p class="text-3xl font-bold text-gray-800"><?= $total_tasks; ?></p>
                </div>
                <div class="bg-purple-100 rounded-full p-4">
                    <i class="fas fa-tasks text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Tugas Selesai</p>
                    <p class="text-3xl font-bold text-gray-800"><?= $total_completed; ?></p>
                </div>
                <div class="bg-green-100 rounded-full p-4">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Team Members</p>
                    <p class="text-3xl font-bold text-gray-800"><?= $total_members; ?></p>
                </div>
                <div class="bg-orange-100 rounded-full p-4">
                    <i class="fas fa-users text-orange-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="add_project.php" class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg p-4 text-center transition">
                <i class="fas fa-plus-circle text-3xl mb-2"></i>
                <p class="font-semibold">Buat Proyek Baru</p>
            </a>
            <a href="projects.php" class="bg-purple-500 hover:bg-purple-600 text-white rounded-lg p-4 text-center transition">
                <i class="fas fa-folder-open text-3xl mb-2"></i>
                <p class="font-semibold">Kelola Proyek</p>
            </a>
            <a href="tasks.php" class="bg-green-500 hover:bg-green-600 text-white rounded-lg p-4 text-center transition">
                <i class="fas fa-tasks text-3xl mb-2"></i>
                <p class="font-semibold">Kelola Tugas</p>
            </a>
        </div>
    </div>
</div>
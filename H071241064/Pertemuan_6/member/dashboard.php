<?php
session_start();
require_once '../config/database.php'; 

function clean_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Team Member') {
    header('Location: ../auth/login.php'); 
    exit();
}

$page_title = 'Dashboard Team Member';
$member_id = intval($_SESSION['user_id']);

function get_count($conn, $member_id, $status = null) {
    $sql = "SELECT COUNT(*) AS total FROM tasks WHERE assigned_to = $member_id";
    if ($status) $sql .= " AND status = '" . mysqli_real_escape_string($conn, $status) . "'";
    $result = mysqli_query($conn, $sql);
    return $result ? (mysqli_fetch_assoc($result)['total'] ?? 0) : 0;
}

$total_tasks = get_count($conn, $member_id);
$total_belum = get_count($conn, $member_id, 'belum');
$total_proses = get_count($conn, $member_id, 'proses');
$total_selesai = get_count($conn, $member_id, 'selesai');

$query_recent = "
    SELECT t.*, p.nama_proyek 
    FROM tasks t
    JOIN projects p ON t.project_id = p.id
    WHERE t.assigned_to = $member_id
    ORDER BY t.id DESC
    LIMIT 5
";
$recent_tasks = mysqli_query($conn, $query_recent);

include '../includes/header.php';
?>

<div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">
        <i class="fas fa-tachometer-alt"></i> Dashboard Team Member
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Tugas</p>
                    <p class="text-3xl font-bold text-gray-800"><?= $total_tasks ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-gray-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Belum Dikerjakan</p>
                    <p class="text-3xl font-bold text-gray-800"><?= $total_belum ?></p>
                </div>
                <div class="bg-gray-100 rounded-full p-4">
                    <i class="fas fa-clock text-gray-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Dalam Proses</p>
                    <p class="text-3xl font-bold text-gray-800"><?= $total_proses ?></p>
                </div>
                <div class="bg-yellow-100 rounded-full p-4">
                    <i class="fas fa-spinner text-yellow-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Selesai</p>
                    <p class="text-3xl font-bold text-gray-800"><?= $total_selesai ?></p>
                </div>
                <div class="bg-green-100 rounded-full p-4">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-bolt"></i> Quick Actions
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="tasks.php" class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg p-4 text-center transition">
                <i class="fas fa-tasks text-3xl mb-2"></i>
                <p class="font-semibold">Lihat Semua Tugas</p>
            </a>
            <a href="tasks.php?status=belum" class="bg-purple-500 hover:bg-purple-600 text-white rounded-lg p-4 text-center transition">
                <i class="fas fa-clock text-3xl mb-2"></i>
                <p class="font-semibold">Tugas Belum Dikerjakan</p>
            </a>
        </div>
    </div>
</div>

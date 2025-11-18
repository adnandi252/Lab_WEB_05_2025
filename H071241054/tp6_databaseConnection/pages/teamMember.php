<?php
require '../includes/auth.php';
require '../includes/db.php';
redirectIfNotAuthorized('Team Member');

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $task_id = $_POST['task_id'];
    $status = $_POST['status'];
    
    $sql = "UPDATE tasks SET status = ? WHERE id = ? AND assigned_to = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sii", $status, $task_id, $user_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $success = "Status tugas berhasil diperbarui!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// Ambil proyek yang ditugaskan kepada team member
$projects = array();
$sql = "SELECT DISTINCT p.* 
        FROM projects p 
        JOIN tasks t ON p.id = t.project_id 
        WHERE t.assigned_to = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($result)) {
    $projects[] = $row;
}
mysqli_stmt_close($stmt);

// Ambil data tugas
$tasks = array();
$sql = "SELECT t.*, p.nama_proyek 
        FROM tasks t 
        JOIN projects p ON t.project_id = p.id 
        WHERE t.assigned_to = ?
        ORDER BY t.status, t.id ASC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($result)) {
    $tasks[] = $row;
}
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Member Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <header class="bg-white shadow rounded-lg p-4 mb-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800">Team Member Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">Halo, <?php echo $_SESSION['username']; ?> (<?php echo $_SESSION['role']; ?>)</span>
                    <a href="../logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Logout</a>
                </div>
            </div>
        </header>

        <!-- Notifikasi -->
        <?php if (isset($success)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Daftar Proyek -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold mb-4">Proyek yang Diikuti</h2>
                <?php if (empty($projects)): ?>
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                        Anda belum ditugaskan ke proyek manapun.
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b">Nama Proyek</th>
                                    <th class="py-2 px-4 border-b">Deskripsi</th>
                                    <th class="py-2 px-4 border-b">Tanggal Mulai</th>
                                    <th class="py-2 px-4 border-b">Tanggal Selesai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($projects as $project): ?>
                                    <tr>
                                        <td class="py-2 px-4 border-b"><?php echo $project['nama_proyek']; ?></td>
                                        <td class="py-2 px-4 border-b"><?php echo $project['deskripsi']; ?></td>
                                        <td class="py-2 px-4 border-b"><?php echo $project['tanggal_mulai']; ?></td>
                                        <td class="py-2 px-4 border-b"><?php echo $project['tanggal_selesai']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Daftar Tugas -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold mb-4">Tugas Saya</h2>
                <?php if (empty($tasks)): ?>
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                        Anda belum memiliki tugas.
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b">Nama Tugas</th>
                                    <th class="py-2 px-4 border-b">Proyek</th>
                                    <th class="py-2 px-4 border-b">Deskripsi</th>
                                    <th class="py-2 px-4 border-b">Status</th>
                                    <th class="py-2 px-4 border-b">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tasks as $task): ?>
                                    <tr>
                                        <td class="py-2 px-4 border-b"><?php echo $task['nama_tugas']; ?></td>
                                        <td class="py-2 px-4 border-b"><?php echo $task['nama_proyek']; ?></td>
                                        <td class="py-2 px-4 border-b"><?php echo $task['deskripsi']; ?></td>
                                        <td class="py-2 px-4 border-b">
                                            <span class="px-2 py-1 rounded text-xs 
                                                <?php 
                                                if ($task['status'] === 'selesai') echo 'bg-green-100 text-green-800';
                                                elseif ($task['status'] === 'proses') echo 'bg-yellow-100 text-yellow-800';
                                                else echo 'bg-red-100 text-red-800';
                                                ?>
                                            ">
                                                <?php echo $task['status']; ?>
                                            </span>
                                        </td>
                                        <td class="py-2 px-4 border-b">
                                            <button onclick="openStatusModal(<?php echo $task['id']; ?>, '<?php echo $task['status']; ?>')" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-sm">Ubah Status</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal Ubah Status -->
    <div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Ubah Status Tugas</h2>
            <form method="POST" id="statusForm">
                <input type="hidden" name="task_id" id="statusTaskId">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                    <select name="status" id="statusSelect" required class="w-full px-3 py-2 border border-gray-300 rounded">
                        <option value="belum">Belum</option>
                        <option value="proses">Proses</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeStatusModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Batal</button>
                    <button type="submit" name="update_status" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openStatusModal(taskId, currentStatus) {
            document.getElementById('statusTaskId').value = taskId;
            document.getElementById('statusSelect').value = currentStatus;
            document.getElementById('statusModal').classList.remove('hidden');
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.add('hidden');
        }
    </script>
</body>
</html>
<?php
mysqli_close($conn);
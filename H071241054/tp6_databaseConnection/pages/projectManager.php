<?php
require '../includes/auth.php';
require '../includes/db.php';
redirectIfNotAuthorized('Project Manager');

$manager_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_project'])) {
        $nama_proyek = $_POST['nama_proyek'];
        $deskripsi = $_POST['deskripsi'];
        $tanggal_mulai = $_POST['tanggal_mulai'];
        $tanggal_selesai = $_POST['tanggal_selesai'];
        
        $sql = "INSERT INTO projects (nama_proyek, deskripsi, tanggal_mulai, tanggal_selesai, manager_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssi", $nama_proyek, $deskripsi, $tanggal_mulai, $tanggal_selesai, $manager_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "Proyek berhasil ditambahkan!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } 
    elseif (isset($_POST['edit_project'])) {
        $project_id = $_POST['project_id'];
        $nama_proyek = $_POST['nama_proyek'];
        $deskripsi = $_POST['deskripsi'];
        $tanggal_mulai = $_POST['tanggal_mulai'];
        $tanggal_selesai = $_POST['tanggal_selesai'];
        
        $sql = "UPDATE projects SET nama_proyek = ?, deskripsi = ?, tanggal_mulai = ?, tanggal_selesai = ? WHERE id = ? AND manager_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssii", $nama_proyek, $deskripsi, $tanggal_mulai, $tanggal_selesai, $project_id, $manager_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "Proyek berhasil diperbarui!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } 
    elseif (isset($_POST['delete_project'])) {
        $project_id = $_POST['project_id'];
        
        $delete_sql = "DELETE FROM tasks WHERE project_id = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_sql);
        mysqli_stmt_bind_param($delete_stmt, "i", $project_id);
        mysqli_stmt_execute($delete_stmt);
        mysqli_stmt_close($delete_stmt);
        
        $sql = "DELETE FROM projects WHERE id = ? AND manager_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $project_id, $manager_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "Proyek berhasil dihapus!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } 
    elseif (isset($_POST['add_task'])) {
    $nama_tugas = $_POST['nama_tugas'];
    $deskripsi = $_POST['deskripsi'];
    $project_id = $_POST['project_id'];
    $assigned_to = $_POST['assigned_to'];
    
    $sql = "INSERT INTO tasks (nama_tugas, deskripsi, project_id, assigned_to) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssii", $nama_tugas, $deskripsi, $project_id, $assigned_to);
    
    if (mysqli_stmt_execute($stmt)) {
        $success = "Tugas berhasil ditambahkan!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
    }  
    elseif (isset($_POST['edit_task'])) {
        $task_id = $_POST['task_id'];
        $nama_tugas = $_POST['nama_tugas'];
        $deskripsi = $_POST['deskripsi'];
        $assigned_to = $_POST['assigned_to'];
        
        $sql = "UPDATE tasks SET nama_tugas = ?, deskripsi = ?, assigned_to = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssii", $nama_tugas, $deskripsi, $assigned_to, $task_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "Tugas berhasil diperbarui!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } 
    elseif (isset($_POST['delete_task'])) {
        $task_id = $_POST['task_id'];
        
        $sql = "DELETE FROM tasks WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $task_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "Tugas berhasil dihapus!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}

    $projects = array();
    $sql = "SELECT * FROM projects WHERE manager_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $manager_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $projects[] = $row;
    }
    mysqli_stmt_close($stmt);

    $team_members = array();
    $sql = "SELECT * FROM users WHERE project_manager_id = ? AND (role = 'Team Member')";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $manager_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $team_members[] = $row;
    }
    mysqli_stmt_close($stmt);

$tasks = array();
if (!empty($projects)) {
    $project_ids = array();
    foreach ($projects as $project) {
        $project_ids[] = $project['id'];
    }
    
    $placeholders = str_repeat('?,', count($project_ids) - 1) . '?';
    
    $sql = "SELECT t.*, p.nama_proyek, u.username as assigned_name 
            FROM tasks t 
            JOIN projects p ON t.project_id = p.id 
            JOIN users u ON t.assigned_to = u.id 
            WHERE t.project_id IN ($placeholders)";
    
    $stmt = mysqli_prepare($conn, $sql);
    
    $types = str_repeat('i', count($project_ids));
    $params = $project_ids;
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $tasks[] = $row;
    }
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Manager Dashboard</title>
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
                <h1 class="text-2xl font-bold text-gray-800">Project Manager Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">Halo, <?php echo $_SESSION['username']; ?> (<?php echo $_SESSION['role']; ?>)</span>
                    <a href="../logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Logout</a>
                </div>
            </div>
        </header>

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
            <!-- Form Tambah Proyek -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold mb-4">Tambah Proyek Baru</h2>
                <form method="POST">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Proyek</label>
                        <input type="text" name="nama_proyek" required class="w-full px-3 py-2 border border-gray-300 rounded">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                        <textarea name="deskripsi" required class="w-full px-3 py-2 border border-gray-300 rounded"></textarea>
                    </div>
                    <div class="mb-4 grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" required class="w-full px-3 py-2 border border-gray-300 rounded">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" required class="w-full px-3 py-2 border border-gray-300 rounded">
                        </div>
                    </div>
                    <button type="submit" name="add_project" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Tambah Proyek</button>
                </form>
            </div>

            <!-- Daftar Proyek -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold mb-4">Daftar Proyek Saya</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b">Nama Proyek</th>
                                <th class="py-2 px-4 border-b">Deskripsi</th>
                                <th class="py-2 px-4 border-b">Tanggal Mulai</th>
                                <th class="py-2 px-4 border-b">Tanggal Selesai</th>
                                <th class="py-2 px-4 border-b">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($projects)): ?>
                                <tr>
                                    <td colspan="5" class="py-2 px-4 border-b text-center">Belum ada proyek</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($projects as $project): ?>
                                    <tr>
                                        <td class="py-2 px-4 border-b"><?php echo $project['nama_proyek']; ?></td>
                                        <td class="py-2 px-4 border-b"><?php echo $project['deskripsi']; ?></td>
                                        <td class="py-2 px-4 border-b"><?php echo $project['tanggal_mulai']; ?></td>
                                        <td class="py-2 px-4 border-b"><?php echo $project['tanggal_selesai']; ?></td>
                                        <td class="py-2 px-4 border-b">
                                            <button onclick="editProject(<?php echo $project['id']; ?>, '<?php echo htmlspecialchars($project['nama_proyek']); ?>', '<?php echo htmlspecialchars($project['deskripsi']); ?>', '<?php echo $project['tanggal_mulai']; ?>', '<?php echo $project['tanggal_selesai']; ?>')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-sm">Edit</button>
                                            <form method="POST" class="inline">
                                                <input type="hidden" name="project_id" value="<?php echo $project['id']; ?>">
                                                <button type="submit" name="delete_project" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm" onclick="return confirm('Yakin hapus proyek ini?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Form Tambah Tugas -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold mb-4">Tambah Tugas</h2>
                
                <!-- Info Team Members -->
                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded text-sm">
                    <strong>Info:</strong> 
                    Team Members: <?php echo count($team_members); ?> |
                    Proyek: <?php echo count($projects); ?>
                </div>
                
                <form method="POST">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Tugas</label>
                        <input type="text" name="nama_tugas" required class="w-full px-3 py-2 border border-gray-300 rounded">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                        <textarea name="deskripsi" required class="w-full px-3 py-2 border border-gray-300 rounded"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Proyek</label>
                        <select name="project_id" required class="w-full px-3 py-2 border border-gray-300 rounded">
                            <option value="">Pilih Proyek</option>
                            <?php foreach ($projects as $project): ?>
                                <option value="<?php echo $project['id']; ?>"><?php echo $project['nama_proyek']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Ditugaskan kepada</label>
                        <?php if (empty($team_members)): ?>
                            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-3 py-2 rounded mb-2">
                                Tidak ada Team Member yang terdaftar di bawah Anda. 
                                <div class="text-xs mt-1">
                                    Silakan minta Super Admin untuk menetapkan Team Member kepada Anda.
                                </div>
                            </div>
                            <select disabled class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-100">
                                <option value="">Tidak ada Team Member</option>
                            </select>
                        <?php else: ?>
                            <select name="assigned_to" required class="w-full px-3 py-2 border border-gray-300 rounded">
                                <option value="">Pilih Team Member</option>
                                <?php foreach ($team_members as $member): ?>
                                    <option value="<?php echo $member['id']; ?>">
                                        <?php echo $member['username']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>
                    <button type="submit" name="add_task" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded" <?php echo (empty($team_members) || empty($projects)) ? 'disabled' : ''; ?>>Tambah Tugas</button>
                </form>
            </div>

            <!-- Daftar Tugas -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold mb-4">Daftar Tugas</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b">Nama Tugas</th>
                                <th class="py-2 px-4 border-b">Proyek</th>
                                <th class="py-2 px-4 border-b">Ditugaskan kepada</th>
                                <th class="py-2 px-4 border-b">Status</th>
                                <th class="py-2 px-4 border-b">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($tasks)): ?>
                                <tr>
                                    <td colspan="5" class="py-2 px-4 border-b text-center">Belum ada tugas</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($tasks as $task): ?>
                                    <tr>
                                        <td class="py-2 px-4 border-b"><?php echo $task['nama_tugas']; ?></td>
                                        <td class="py-2 px-4 border-b"><?php echo $task['nama_proyek']; ?></td>
                                        <td class="py-2 px-4 border-b"><?php echo $task['assigned_name']; ?></td>
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
                                            <button onclick="editTask(<?php echo $task['id']; ?>, '<?php echo htmlspecialchars($task['nama_tugas']); ?>', '<?php echo htmlspecialchars($task['deskripsi']); ?>', <?php echo $task['assigned_to']; ?>)" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-sm">Edit</button>
                                            <form method="POST" class="inline">
                                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                                <button type="submit" name="delete_task" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm" onclick="return confirm('Yakin hapus tugas ini?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Proyek -->
    <div id="editProjectModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Edit Proyek</h2>
            <form method="POST" id="editProjectForm">
                <input type="hidden" name="project_id" id="editProjectId">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Proyek</label>
                    <input type="text" name="nama_proyek" id="editNamaProyek" required class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                    <textarea name="deskripsi" id="editDeskripsi" required class="w-full px-3 py-2 border border-gray-300 rounded"></textarea>
                </div>
                <div class="mb-4 grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="editTanggalMulai" required class="w-full px-3 py-2 border border-gray-300 rounded">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="editTanggalSelesai" required class="w-full px-3 py-2 border border-gray-300 rounded">
                    </div>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeEditProjectModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Batal</button>
                    <button type="submit" name="edit_project" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Tugas -->
    <div id="editTaskModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Edit Tugas</h2>
            <form method="POST" id="editTaskForm">
                <input type="hidden" name="task_id" id="editTaskId">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Tugas</label>
                    <input type="text" name="nama_tugas" id="editNamaTugas" required class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                    <textarea name="deskripsi" id="editTaskDeskripsi" required class="w-full px-3 py-2 border border-gray-300 rounded"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Ditugaskan kepada</label>
                    <?php if (empty($team_members)): ?>
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-3 py-2 rounded mb-2">
                            Tidak ada Team Member yang tersedia.
                        </div>
                    <?php else: ?>
                        <select name="assigned_to" id="editAssignedTo" required class="w-full px-3 py-2 border border-gray-300 rounded">
                            <option value="">Pilih Team Member</option>
                            <?php foreach ($team_members as $member): ?>
                                <option value="<?php echo $member['id']; ?>"><?php echo $member['username']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeEditTaskModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Batal</button>
                    <button type="submit" name="edit_task" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded" <?php echo empty($team_members) ? 'disabled' : ''; ?>>Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editProject(id, nama, deskripsi, mulai, selesai) {
            document.getElementById('editProjectId').value = id;
            document.getElementById('editNamaProyek').value = nama;
            document.getElementById('editDeskripsi').value = deskripsi;
            document.getElementById('editTanggalMulai').value = mulai;
            document.getElementById('editTanggalSelesai').value = selesai;
            document.getElementById('editProjectModal').classList.remove('hidden');
        }

        function closeEditProjectModal() {
            document.getElementById('editProjectModal').classList.add('hidden');
        }

        function editTask(id, nama, deskripsi, assignedTo) {
            document.getElementById('editTaskId').value = id;
            document.getElementById('editNamaTugas').value = nama;
            document.getElementById('editTaskDeskripsi').value = deskripsi;
            document.getElementById('editAssignedTo').value = assignedTo;
            document.getElementById('editTaskModal').classList.remove('hidden');
        }

        function closeEditTaskModal() {
            document.getElementById('editTaskModal').classList.add('hidden');
        }
    </script>
</body>
</html>
<?php
mysqli_close($conn);
?>
<?php
require '../includes/auth.php';
require '../includes/db.php';
redirectIfNotAuthorized('Super Admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_user'])) {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];
        $project_manager_id = NULL;

        if ($role === 'Team Member' && isset($_POST['project_manager_id']) && !empty($_POST['project_manager_id'])) {
            $project_manager_id = $_POST['project_manager_id'];
        }

        $sql = "INSERT INTO users (username, password, role, project_manager_id) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $username, $password, $role, $project_manager_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "User berhasil ditambahkan!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } 
    elseif (isset($_POST['edit_user'])) {
        $user_id = $_POST['edit_user_id'];
        $username = $_POST['edit_username'];
        $role = $_POST['edit_role'];
        $project_manager_id = NULL;

        if ($role === 'Team Member' && isset($_POST['edit_project_manager_id']) && !empty($_POST['edit_project_manager_id'])) {
            $project_manager_id = $_POST['edit_project_manager_id'];
        }

        if (!empty($_POST['edit_password'])) {
            $password = password_hash($_POST['edit_password'], PASSWORD_DEFAULT);
            $sql = "UPDATE users SET username = ?, password = ?, role = ?, project_manager_id = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssii", $username, $password, $role, $project_manager_id, $user_id);
        } else {
            $sql = "UPDATE users SET username = ?, role = ?, project_manager_id = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssii", $username, $role, $project_manager_id, $user_id);
        }
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "User berhasil diperbarui!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } 
        elseif (isset($_POST['delete_user'])) {
        $user_id = $_POST['user_id'];
        
        $check_sql = "SELECT COUNT(*) FROM users WHERE project_manager_id = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "i", $user_id);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_bind_result($check_stmt, $has_team_members);
        mysqli_stmt_fetch($check_stmt);
        mysqli_stmt_close($check_stmt);
        
        if ($has_team_members > 0) {
            $error = "Tidak dapat menghapus user karena masih menjadi Project Manager untuk Team Member lain. Silakan ubah Project Manager untuk Team Member tersebut terlebih dahulu.";
        } else {
            $check_sql = "SELECT COUNT(*) FROM projects WHERE manager_id = ?";
            $check_stmt = mysqli_prepare($conn, $check_sql);
            mysqli_stmt_bind_param($check_stmt, "i", $user_id);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_bind_result($check_stmt, $has_projects);
            mysqli_stmt_fetch($check_stmt);
            mysqli_stmt_close($check_stmt);
            
            $check_sql = "SELECT COUNT(*) FROM tasks WHERE assigned_to = ?";
            $check_stmt = mysqli_prepare($conn, $check_sql);
            mysqli_stmt_bind_param($check_stmt, "i", $user_id);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_bind_result($check_stmt, $has_tasks);
            mysqli_stmt_fetch($check_stmt);
            mysqli_stmt_close($check_stmt);
            
            if ($has_projects > 0 || $has_tasks > 0) {
                $error = "Tidak dapat menghapus user karena masih memiliki proyek atau tugas!";
            } else {
                $sql = "DELETE FROM users WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success = "User berhasil dihapus!";
                } else {
                    $error = "Gagal menghapus user!";
                }
                mysqli_stmt_close($stmt);
            }
        }
    } 
    elseif (isset($_POST['delete_project'])) {
        $project_id = $_POST['project_id'];
        
        $delete_sql = "DELETE FROM tasks WHERE project_id = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_sql);
        mysqli_stmt_bind_param($delete_stmt, "i", $project_id);
        mysqli_stmt_execute($delete_stmt);
        mysqli_stmt_close($delete_stmt);
        
        $sql = "DELETE FROM projects WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $project_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "Proyek berhasil dihapus!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } 
        elseif (isset($_POST['update_project_manager'])) {
        $team_member_id = $_POST['team_member_id'];
        $new_project_manager_id = $_POST['new_project_manager_id'];
        
        if ($new_project_manager_id === '') {
            $sql = "UPDATE users SET project_manager_id = NULL WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $team_member_id);
        } else {
            $sql = "UPDATE users SET project_manager_id = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ii", $new_project_manager_id, $team_member_id);
        }
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "Project Manager berhasil diupdate!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}

// Ambil data users
$users = array();
$sql = "SELECT u1.*, u2.username as manager_name 
        FROM users u1 
        LEFT JOIN users u2 ON u1.project_manager_id = u2.id 
        ORDER BY u1.id ASC";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
}

// Ambil data projects
$projects = array();
$sql = "SELECT p.*, u.username as manager_name
        FROM projects p
        JOIN users u ON p.manager_id = u.id 
        ORDER BY p.id ASC";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $projects[] = $row;
    }
}

// Ambil data project managers
$project_managers = array();
$sql = "SELECT * FROM users WHERE role = 'Project Manager' ORDER BY username";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $project_managers[] = $row;
    }
}

// Ambil team members 
$team_members = array();
$sql = "SELECT u1.id, u1.username, u1.role, u2.id as manager_id, u2.username as manager_name
        FROM users u1 
        LEFT JOIN users u2 ON u1.project_manager_id = u2.id 
        WHERE (u1.role = 'Team Member')
        AND u1.role != 'Super Admin'
        AND u1.role != 'Project Manager'
        ORDER BY u1.id ASC";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $team_members[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard</title>
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
                <h1 class="text-2xl font-bold text-gray-800">Super Admin Dashboard</h1>
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
            <!-- Form Tambah User -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold mb-4">Tambah User Baru</h2>
                <form method="POST" id="userForm">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                        <input type="text" name="username" required class="w-full px-3 py-2 border border-gray-300 rounded">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                        <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Role</label>
                        <select name="role" id="roleSelect" class="w-full px-3 py-2 border border-gray-300 rounded" required onchange="toggleManagerField()">
                            <option value="">Pilih Role</option>
                            <option value="Super Admin">Super Admin</option>
                            <option value="project_manager">Project Manager</option>
                            <option value="team_member">Team Member</option>
                        </select>
                    </div>
                    <div class="mb-4 hidden" id="managerField">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Project Manager</label>
                        <?php if (empty($project_managers)): ?>
                            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-3 py-2 rounded">
                                Tidak ada Project Manager. Harap buat Project Manager terlebih dahulu.
                            </div>
                        <?php else: ?>
                            <select name="project_manager_id" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                                <option value="">Pilih Project Manager</option>
                                <?php foreach ($project_managers as $pm): ?>
                                    <option value="<?php echo $pm['id']; ?>"><?php echo $pm['username']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>
                    <button type="submit" name="add_user" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded" id="submitBtn">
                        Tambah User
                    </button>
                </form>
            </div>

            <!-- Daftar User -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold mb-4">Daftar User</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b">ID</th>
                                <th class="py-2 px-4 border-b">Username</th>
                                <th class="py-2 px-4 border-b">Role</th>
                                <th class="py-2 px-4 border-b">Project Manager</th>
                                <th class="py-2 px-4 border-b">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="5" class="py-2 px-4 border-b text-center">Tidak ada data user</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td class="py-2 px-4 border-b"><?php echo $user['id']; ?></td>
                                        <td class="py-2 px-4 border-b"><?php echo $user['username']; ?></td>
                                        <td class="py-2 px-4 border-b">
                                            <span class="px-2 py-1 rounded text-xs 
                                                <?php 
                                                if ($user['role'] === 'Super Admin') echo 'bg-purple-100 text-purple-800';
                                                elseif ($user['role'] === 'project_manager') echo 'bg-blue-100 text-blue-800';
                                                else echo 'bg-green-100 text-green-800';
                                                ?>
                                            ">
                                                <?php echo $user['role']; ?>
                                            </span>
                                        </td>
                                        <td class="py-2 px-4 border-b"><?php echo $user['manager_name'] ?? '-'; ?></td>
                                        <td class="py-2 px-4 border-b">
                                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                <button onclick="openEditUserModal(
                                                    <?php echo $user['id']; ?>, 
                                                    '<?php echo htmlspecialchars($user['username']); ?>',
                                                    '<?php echo $user['role']; ?>',
                                                    '<?php echo $user['project_manager_id'] ?? ''; ?>'
                                                )" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-sm">
                                                    Edit
                                                </button>
                                                <form method="POST" class="inline" onsubmit="return confirm('Yakin hapus user <?php echo htmlspecialchars($user['username']); ?>?')">
                                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                    <button type="submit" name="delete_user" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm">Hapus</button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-gray-400 text-sm">User aktif</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow lg:col-span-2">
                <h2 class="text-xl font-bold mb-4">Kelola Project Manager Team Member</h2>
                
                
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b">Team Member</th>
                                <th class="py-2 px-4 border-b">Role</th>
                                <th class="py-2 px-4 border-b">Project Manager Saat Ini</th>
                                <th class="py-2 px-4 border-b">Project Manager Baru</th>
                                <th class="py-2 px-4 border-b">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($team_members)): ?>
                                <tr>
                                    <td colspan="5" class="py-2 px-4 border-b text-center">
                                        Tidak ada Team Member.
                                        <div class="text-xs text-gray-500 mt-1">
                                            Pastikan ada user dengan role Team Member
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($team_members as $member): ?>
                                    <tr>
                                        <td class="py-2 px-4 border-b"><?php echo $member['username']; ?></td>
                                        <td class="py-2 px-4 border-b">
                                            <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">
                                                <?php echo $member['role']; ?>
                                            </span>
                                        </td>
                                        <td class="py-2 px-4 border-b"><?php echo $member['manager_name'] ?? 'Tidak ada'; ?></td>
                                        <td class="py-2 px-4 border-b">
                                            <form method="POST" class="flex items-center space-x-2">
                                                <input type="hidden" name="team_member_id" value="<?php echo $member['id']; ?>">
                                                <select name="new_project_manager_id" class="px-3 py-2 border border-gray-300 rounded flex-1">
                                                    <option value="">Pilih Project Manager</option>
                                                    <?php foreach ($project_managers as $pm): ?>
                                                        <option value="<?php echo $pm['id']; ?>" <?php echo ($member['manager_id'] == $pm['id']) ? 'selected' : ''; ?>>
                                                            <?php echo $pm['username']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                    <option value="">Hapus Project Manager</option>
                                                </select>
                                                <button type="submit" name="update_project_manager" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm">Update</button>
                                            </form>
                                        </td>
                                        <td class="py-2 px-4 border-b">
                                            <form method="POST" class="inline" onsubmit="return confirm('Yakin hapus team member <?php echo htmlspecialchars($member['username']); ?>?')">
                                                <input type="hidden" name="user_id" value="<?php echo $member['id']; ?>">
                                                <button type="submit" name="delete_user" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Daftar Proyek -->
            <div class="bg-white p-6 rounded-lg shadow lg:col-span-2">
                <h2 class="text-xl font-bold mb-4">Daftar Semua Proyek</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b">ID</th>
                                <th class="py-2 px-4 border-b">Nama Proyek</th>
                                <th class="py-2 px-4 border-b">Deskripsi</th>
                                <th class="py-2 px-4 border-b">Tanggal Mulai</th>
                                <th class="py-2 px-4 border-b">Tanggal Selesai</th>
                                <th class="py-2 px-4 border-b">Manager</th>
                                <th class="py-2 px-4 border-b">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($projects)): ?>
                                <tr>
                                    <td colspan="7" class="py-2 px-4 border-b text-center">Tidak ada proyek</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($projects as $project): ?>
                                    <tr>
                                        <td class="py-2 px-4 border-b"><?php echo $project['id']; ?></td>
                                        <td class="py-2 px-4 border-b"><?php echo $project['nama_proyek']; ?></td>
                                        <td class="py-2 px-4 border-b"><?php echo $project['deskripsi']; ?></td>
                                        <td class="py-2 px-4 border-b"><?php echo $project['tanggal_mulai']; ?></td>
                                        <td class="py-2 px-4 border-b"><?php echo $project['tanggal_selesai']; ?></td>
                                        <td class="py-2 px-4 border-b"><?php echo $project['manager_name']; ?></td>
                                        <td class="py-2 px-4 border-b">
                                            <form method="POST" class="inline" onsubmit="return confirm('Yakin hapus proyek <?php echo htmlspecialchars($project['nama_proyek']); ?>?')">
                                                <input type="hidden" name="project_id" value="<?php echo $project['id']; ?>">
                                                <button type="submit" name="delete_project" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-sm">Hapus</button>
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

    <!-- Modal Edit User -->
    <div id="editUserModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Edit User</h2>
            <form method="POST" id="editUserForm">
                <input type="hidden" name="edit_user_id" id="editUserId">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                    <input type="text" name="edit_username" id="editUsername" required class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password (Kosongkan jika tidak ingin mengubah)</label>
                    <input type="password" name="edit_password" id="editPassword" class="w-full px-3 py-2 border border-gray-300 rounded" placeholder="Biarkan kosong untuk menjaga password saat ini">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Role</label>
                    <select name="edit_role" id="editRole" class="w-full px-3 py-2 border border-gray-300 rounded" required onchange="toggleEditManagerField()">
                        <option value="">Pilih Role</option>
                        <option value="Super Admin">Super Admin</option>
                        <option value="project_manager">Project Manager</option>
                        <option value="team_member">Team Member</option>
                    </select>
                </div>
                <div class="mb-4 hidden" id="editManagerField">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Project Manager</label>
                    <?php if (empty($project_managers)): ?>
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-3 py-2 rounded">
                            Tidak ada Project Manager. Harap buat Project Manager terlebih dahulu.
                        </div>
                    <?php else: ?>
                        <select name="edit_project_manager_id" id="editProjectManagerId" class="w-full px-3 py-2 border border-gray-300 rounded">
                            <option value="">Pilih Project Manager</option>
                            <?php foreach ($project_managers as $pm): ?>
                                <option value="<?php echo $pm['id']; ?>"><?php echo $pm['username']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeEditUserModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Batal</button>
                    <button type="submit" name="edit_user" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleManagerField() {
            const roleSelect = document.getElementById('roleSelect');
            const managerField = document.getElementById('managerField');
            const submitBtn = document.getElementById('submitBtn');
            
            if (roleSelect.value === 'team_member') {
                managerField.classList.remove('hidden');
                const hasProjectManagers = managerField.querySelector('select') !== null;
                if (!hasProjectManagers) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            } else {
                managerField.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        function toggleEditManagerField() {
            const roleSelect = document.getElementById('editRole');
            const managerField = document.getElementById('editManagerField');
            
            if (roleSelect.value === 'team_member') {
                managerField.classList.remove('hidden');
            } else {
                managerField.classList.add('hidden');
            }
        }

        document.getElementById('userForm').addEventListener('submit', function(e) {
            const role = document.getElementById('roleSelect').value;
            const managerField = document.getElementById('managerField');
            
            if (role === 'Team Member') {
                const managerSelect = managerField.querySelector('select');
                if (managerSelect && (!managerSelect.value || managerSelect.value === '')) {
                    e.preventDefault();
                    alert('Untuk Team Member, harus memilih Project Manager!');
                    managerField.classList.remove('hidden');
                }
            }
        });

        function openEditUserModal(userId, username, role, projectManagerId) {
            document.getElementById('editUserId').value = userId;
            document.getElementById('editUsername').value = username;
            document.getElementById('editRole').value = role;
            
            if (projectManagerId) {
                document.getElementById('editProjectManagerId').value = projectManagerId;
            } else {
                document.getElementById('editProjectManagerId').value = '';
            }
            
            toggleEditManagerField();
            document.getElementById('editUserModal').classList.remove('hidden');
        }

        function closeEditUserModal() {
            document.getElementById('editUserModal').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleManagerField();
        });
    </script>
</body>
</html>
<?php
mysqli_close($conn);
?>
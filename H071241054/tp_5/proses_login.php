<?php
session_start();

$users = [
    [
        'username' => 'admin',
        'password' => password_hash('admin123', PASSWORD_DEFAULT),
        'nama' => 'Admin',
        'email' => 'admin@gmail.com',
        'role' => 'admin'
    ],
    [
        'username' => 'adminkedua',
        'password' => password_hash('admin12356', PASSWORD_DEFAULT),
        'nama' => 'Mimin',
        'email' => 'adminkedua@gmail.com',
        'role' => 'admin'
    ],
    [
        'username' => 'gatriani',
        'password' => password_hash('123', PASSWORD_DEFAULT),
        'nama' => 'Marche Gatriani',
        'email' => 'gatriani@gmail.com',
        'gender' => 'Female',
        'fakultas' => 'FMIPA',
        'angkatan' => '2024',
        'role' => 'user'
    ],
    [
        'username' => 'artur',
        'password' => password_hash('asdf', PASSWORD_DEFAULT),
        'nama' => 'Artur',
        'email' => 'artur@gmail.com',
        'gender' => 'Male',
        'fakultas' => 'Ekonomi dan Bisnis',
        'angkatan' => '2021',
        'role' => 'user'
    ],
    [
        'username' => 'mey',
        'password' => password_hash('zxcvb', PASSWORD_DEFAULT),
        'nama' => 'Meyche',
        'email' => 'meyche@gmail.com',
        'gender' => 'Female',
        'fakultas' => 'Ekonomi dan Bisnis',
        'angkatan' => '2025',
        'role' => 'user'
    ]
];

// Ambil data dari form
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$foundUser = null;

// Cari user berdasarkan username
foreach ($users as $user) {
    if ($user['username'] === $username) {
        $foundUser = $user;
        break;
    }
}

if ($foundUser && password_verify($password, $foundUser['password'])) {
    $_SESSION['user'] = $foundUser;
    $_SESSION['all_users'] = $users;
    header("Location: dashboard.php");
    exit;
} else {
    $_SESSION['error'] = "Username atau password salah!";
    header("Location: login.php");
    exit;
}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10 p-5 bg-white rounded shadow-lg max-w-md">
        <h1 class="text-2xl font-bold mb-4">Tambah Data Mahasiswa</h1>
        <form action="proses_tambah.php" method="POST">
            <div class="mb-4">
                <label for="nama" class="block mb-2">Nama</label>
                <input type="text" name="nama" id="nama" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="nim" class="block mb-2">NIM</label>
                <input type="text" name="nim" id="nim" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="jurusan" class="block mb-2">Jurusan</label>
                <input type="text" name="jurusan" id="jurusan" class="w-full px-3 py-2 border rounded" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">Simpan</button>
        </form>
    </div>
</body>
</html>
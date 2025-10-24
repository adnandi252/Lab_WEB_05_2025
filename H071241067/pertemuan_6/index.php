<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto mt-10 p-5 bg-white rounded shadow-lg">
        <h1 class="text-2xl font-bold mb-4">Data Mahasiswa</h1>
        
        <a href="tambah.php" class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 mb-4 inline-block">
            + Tambah Data
        </a>

        <table class="min-w-full bg-white border">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-2 px-4 border-b">ID</th>
                    <th class="py-2 px-4 border-b">Nama</th>
                    <th class="py-2 px-4 border-b">NIM</th>
                    <th class="py-2 px-4 border-b">Jurusan</th>
                    <th class="py-2 px-4 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require 'koneksi.php';

                $sql = "SELECT * FROM mahasiswa ORDER BY id ASC";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td class='py-2 px-4 border-b text-center'>" . $row['id'] . "</td>";
                        echo "<td class='py-2 px-4 border-b'>" . $row['nama'] . "</td>";
                        echo "<td class='py-2 px-4 border-b'>" . $row['nim'] . "</td>";
                        echo "<td class='py-2 px-4 border-b'>" . $row['jurusan'] . "</td>";
                        echo "<td class='py-2 px-4 border-b text-center'>";
                        echo "<a href='edit.php?id=" . $row['id'] . "' class='bg-yellow-500 text-white font-bold py-1 px-3 rounded hover:bg-yellow-600 mr-2'>Edit</a>";
                        echo "<a href='hapus.php?id=" . $row['id'] . "' class='bg-red-500 text-white font-bold py-1 px-3 rounded hover:bg-red-600' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center py-4'>Tidak ada data.</td></tr>";
                }

                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
<?php

session_start();
include_once 'interface/header.php';

require_once __DIR__ . '/../core/v2/config.php';
require_once __DIR__ . '/../core/v2/database.php';

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrf_token = $_SESSION['csrf_token'];

// Fungsi koneksi database

// Proses CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'add' && $_POST['csrf_token'] === $csrf_token) {
        $nama_toko = $_POST['nama_toko'];
        $kota = $_POST['kota'];

        $db = db_connect();
        $stmt = $db->prepare("INSERT INTO toko (nama_toko, kota_id) VALUES (?, ?)");
        $stmt->bind_param("si", $nama_toko, $kota);
        $stmt->execute();
        $stmt->close();
        $db->close();
        echo json_encode(['success' => true]);
        exit;
    }

    if ($_POST['action'] === 'update' && $_POST['csrf_token'] === $csrf_token) {
        $id = $_POST['id'];
        $nama_toko = $_POST['nama_toko'];
        $kota = $_POST['kota'];

        $db = db_connect();
        $stmt = $db->prepare("UPDATE toko SET nama_toko = ?, kota_id = ? WHERE id = ?");
        $stmt->bind_param("sii", $nama_toko, $kota, $id);
        $stmt->execute();
        $stmt->close();
        $db->close();
        echo json_encode(['success' => true]);
        exit;
    }

    if ($_POST['action'] === 'delete' && $_POST['csrf_token'] === $csrf_token) {
        $id = $_POST['id'];

        $db = db_connect();
        $stmt = $db->prepare("DELETE FROM toko WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $db->close();
        echo json_encode(['success' => true]);
        exit;
    }
}

// Fetch data untuk ditampilkan di tabel
$db = db_connect();
$search = $_GET['search'] ?? '';
$sql = "SELECT toko.id, toko.nama_toko, kota.nama_kota FROM toko JOIN kota ON toko.kota_id = kota.id";
if (!empty($search)) {
    $sql .= " WHERE toko.nama_toko LIKE '%" . $db->real_escape_string($search) . "%'";
}
$result = $db->query($sql);
$toko = $result->fetch_all(MYSQLI_ASSOC);
$result->free();
$db->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/preline/dist/preline.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="max-w-6xl mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6"></h2>

        <!-- Notifikasi -->
        <div id="editNotification" class="hidden p-4 mb-4 text-yellow-800 bg-yellow-100 rounded-lg" role="alert">
            <span class="font-medium">Anda sedang mengedit data!</span>
        </div>

        <!-- Form Add / Edit -->
        <form id="tokoForm" class="space-y-6">
            <input type="hidden" name="id" id="id">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" name="action" id="action" value="add">

            <div>
                <label for="nama_toko" class="block text-sm font-medium text-gray-700">Nama Toko</label>
                <input type="text" id="nama_toko" name="nama_toko"
                    class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Masukkan nama toko" required>
            </div>

            <div>
                <label for="kota" class="block text-sm font-medium text-gray-700">Kota</label>
                <select id="kota" name="kota"
                    class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    required>
                    <option value="" disabled selected>Pilih Kota</option>
                    <option value="1">Solo</option>
                    <option value="2">Semarang</option>
                    <option value="3">Yogyakarta</option>
                    <option value="4">Salatiga</option>
                </select>
            </div>

            <div class="flex space-x-4">
                <button type="submit"
                    class="px-6 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                    Simpan
                </button>
                <button type="button" id="cancelEdit"
                    class="hidden px-6 py-2 text-white bg-gray-500 rounded-lg hover:bg-gray-600 focus:ring-2 focus:ring-gray-400">
                    Batal Edit
                </button>
            </div>
        </form>

        <!-- Tabel Data -->
        <div class="mt-8">
            <input type="text" id="search" placeholder="Cari Nama Toko"
                class="w-full p-3 border border-gray-300 rounded-lg shadow-sm mb-4 focus:ring-2 focus:ring-blue-500"
                autocomplete="off">
            <table class="w-full border-collapse bg-white shadow rounded-lg">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="p-3 border">Nama Toko</th>
                        <th class="p-3 border">Kota</th>
                        <th class="p-3 border">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tokoTable">
                    <?php foreach ($toko as $row): ?>
                        <tr class="hover:bg-gray-100">
                            <td class="p-3 border"><?= $row['nama_toko'] ?></td>
                            <td class="p-3 border"><?= $row['nama_kota'] ?></td>
                            <td class="p-3 border text-center flex items-center justify-center space-x-2">
                                <button class="edit p-2 bg-yellow-200 text-yellow-600 rounded-full hover:bg-yellow-300"
                                    data-id="<?= htmlspecialchars($row['id'] ?? '') ?>"
                                    data-nama="<?= htmlspecialchars($row['nama_toko'] ?? '') ?>"
                                    data-kota="<?= htmlspecialchars($row['kota_id'] ?? '') ?>" data-tooltip="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zM16.862 4.487L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </button>


                                <!-- Tombol Hapus -->
                                <button class="delete p-2 bg-red-200 text-red-600 rounded-full hover:bg-red-300"
                                    data-id="<?= $row['id'] ?>" data-tooltip="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>

    <script>
        const tokoForm = document.getElementById('tokoForm');
        const actionField = document.getElementById('action');
        const idField = document.getElementById('id');
        const namaField = document.getElementById('nama_toko');
        const kotaField = document.getElementById('kota');
        const cancelEditButton = document.getElementById('cancelEdit');
        const editNotification = document.getElementById('editNotification');

        // Submit form
        tokoForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.success) location.reload();
                });
        });

        // Edit button
        document.querySelectorAll('.edit').forEach(button => {
            button.addEventListener('click', function () {
                idField.value = this.dataset.id;
                namaField.value = this.dataset.nama;
                kotaField.value = this.dataset.kota;
                actionField.value = 'update';

                editNotification.classList.remove('hidden');
                cancelEditButton.classList.remove('hidden');
            });
        });

        // Delete button
        document.querySelectorAll('.delete').forEach(button => {
            button.addEventListener('click', function () {
                if (confirm('Yakin ingin menghapus?')) {
                    const formData = new FormData();
                    formData.append('id', this.dataset.id);
                    formData.append('csrf_token', '<?= $csrf_token ?>');
                    formData.append('action', 'delete');

                    fetch('', { method: 'POST', body: formData })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) location.reload();
                        });
                }
            });
        });

        // Search bar
        document.getElementById('search').addEventListener('input', function () {
            const query = this.value.toLowerCase();
            document.querySelectorAll('#tokoTable tr').forEach(row => {
                const namaToko = row.children[0].textContent.toLowerCase();
                row.style.display = namaToko.includes(query) ? '' : 'none';
            });
        });

        // Cancel Edit button
        cancelEditButton.addEventListener('click', function () {
            idField.value = '';
            namaField.value = '';
            kotaField.value = '';
            actionField.value = 'add';

            editNotification.classList.add('hidden');
            cancelEditButton.classList.add('hidden');
        });
    </script>
</body>

</html>
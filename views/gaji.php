<?php
require_once __DIR__ . '/../core/v2/config.php';
require_once __DIR__ . '/../core/v2/database.php';
require_once __DIR__ . '/../core/func/csrf_protection.php';


$db = db_connect();

// Fetch employees from the database
function getEmployees($db)
{
    $result = $db->query("SELECT id, nama, base_gaji FROM karyawan");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fetch salary slips by month
function getSalarySlips($db, $month)
{
    $stmt = $db->prepare("SELECT * FROM slip_gaji WHERE DATE_FORMAT(tanggal, '%Y-%m') = ?");
    $stmt->bind_param('s', $month);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Add or update a salary slip
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nama = $_POST['nama'];
    $absensi = (int) $_POST['absensi'];
    $nominal = (float) $_POST['nominal'];
    $tambahan = (float) ($_POST['tambahan'] ?? 0);
    $potongan = (float) ($_POST['potongan'] ?? 0);
    $total = ($absensi * $nominal) + $tambahan - $potongan;
    $tanggal = $_POST['tanggal'];

    if ($id) {
        // Update existing record
        $stmt = $db->prepare("UPDATE slip_gaji SET absensi = ?, tambahan = ?, potongan = ?, total = ?, tanggal = ? WHERE id = ?");
        $stmt->bind_param('dddssi', $absensi, $tambahan, $potongan, $total, $tanggal, $id);
    } else {
        // Insert new record
        $stmt = $db->prepare("INSERT INTO slip_gaji (nama, absensi, nominal, tambahan, potongan, total, tanggal) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sdddsss', $nama, $absensi, $nominal, $tambahan, $potongan, $total, $tanggal);
    }

    if ($stmt->execute()) {
        echo "<script>showNotification('Data berhasil disimpan!', 'success');</script>";
        header("Refresh: 0; url=gaji.php?month=" . date('Y-m', strtotime($tanggal)));
        exit;

    } else {
        echo "Error: " . $stmt->error;
    }
}

// Delete a salary slip
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $db->prepare("DELETE FROM slip_gaji WHERE id = ?");
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo "<script>showNotification('Data berhasil dihapus!', 'success');</script>";
        header("Refresh: 0; url=gaji.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

$month = $_GET['month'] ?? date('Y-m');
$salarySlips = getSalarySlips($db, $month);
$employees = getEmployees($db);

include_once __DIR__ . '/interface/header.php';

?>
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Slip Gaji - Bulan <?= htmlspecialchars($month) ?></h2>

    <!-- Form Filter Bulan -->
    <form method="GET" class="flex items-center gap-4 mb-6">
        <label for="month" class="text-sm font-medium">Pilih Bulan:</label>
        <input type="month" name="month" id="month" value="<?= htmlspecialchars($month) ?>"
            class="input input-bordered w-full max-w-xs border-gray-300 focus:ring focus:ring-blue-300 rounded-md">
        <button type="submit" class="btn btn-primary bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            Tampilkan
        </button>
    </form>

    <!-- Form Tambah/Edit Slip Gaji -->
    <form method="POST" class="bg-white shadow-md rounded-md p-6 mb-8">
        <input type="hidden" name="id" id="id">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="nama" class="block text-sm font-medium mb-1">Nama Karyawan:</label>
                <select name="nama" id="nama"
                    class="select select-bordered w-full border-gray-300 focus:ring focus:ring-blue-300 rounded-md">
                    <option selected>Pilih Karyawan</option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?= $employee['nama'] ?>" data-nominal="<?= $employee['base_gaji'] ?>">
                            <?= htmlspecialchars($employee['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="absensi" class="block text-sm font-medium mb-1">Absensi (Hari):</label>
                <input type="number" name="absensi" id="absensi" required
                    class="input input-bordered w-full border-gray-300 focus:ring focus:ring-blue-300 rounded-md">
            </div>

            <div>
                <label for="tambahan" class="block text-sm font-medium mb-1">Tambahan:</label>
                <input type="number" name="tambahan" id="tambahan"
                    class="input input-bordered w-full border-gray-300 focus:ring focus:ring-blue-300 rounded-md">
            </div>

            <div>
                <label for="potongan" class="block text-sm font-medium mb-1">Potongan:</label>
                <input type="number" name="potongan" id="potongan"
                    class="input input-bordered w-full border-gray-300 focus:ring focus:ring-blue-300 rounded-md">
            </div>

            <div>
                <label for="tanggal" class="block text-sm font-medium mb-1">Tanggal:</label>
                <input type="date" name="tanggal" id="tanggal" required
                    class="input input-bordered w-full border-gray-300 focus:ring focus:ring-blue-300 rounded-md">
            </div>
        </div>

        <input type="hidden" name="nominal" id="nominal">

        <button type="submit"
            class="btn btn-primary bg-green-600 text-white px-4 py-2 rounded-md mt-4 hover:bg-green-700">
            Simpan
        </button>
    </form>

    <!-- Tabel Slip Gaji -->
    <div class="overflow-x-auto">
        <table class="table-auto w-full border-collapse border border-gray-300 text-sm shadow-md">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border border-gray-300 px-4 py-2 text-left">Nama</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Absensi</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Nominal</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Tambahan</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Potongan</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Total</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Tanggal</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($salarySlips as $slip): ?>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($slip['nama']) ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($slip['absensi']) ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($slip['nominal']) ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($slip['tambahan']) ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($slip['potongan']) ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($slip['total']) ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($slip['tanggal']) ?></td>
                        <td class="border border-gray-300 px-4 py-2">
                            <a href="javascript:void(0)" class="text-blue-600 hover:text-blue-800 underline"
                                onclick="editSlip(<?= htmlspecialchars(json_encode($slip)) ?>)">Edit</a>
                            <a href="?delete=<?= $slip['id'] ?>" class="text-red-600 hover:text-red-800 underline"
                                onclick="return confirm('Yakin ingin menghapus?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div id="notification" class="hidden fixed top-5 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-md"></div>

<script>
    function showNotification(message, type = 'success') {
        const notification = document.getElementById('notification');
        notification.innerHTML = `
            <div class="alert ${type === 'success' ? 'alert-success' : 'alert-error'} shadow-lg">
                <span>${message}</span>
            </div>
        `;
        notification.classList.remove('hidden');

        setTimeout(() => {
            notification.classList.add('hidden');
        }, 3000);
    }

    document.getElementById('nama').addEventListener('change', function () {
        const nominal = this.selectedOptions[0].getAttribute('data-nominal');
        document.getElementById('nominal').value = nominal;
    });

    function editSlip(slip) {
        document.getElementById('id').value = slip.id;
        document.getElementById('nama').value = slip.nama;
        document.getElementById('absensi').value = slip.absensi;
        document.getElementById('tambahan').value = slip.tambahan;
        document.getElementById('potongan').value = slip.potongan;
        document.getElementById('tanggal').value = slip.tanggal;
        document.getElementById('nominal').value = slip.nominal;
    }
</script>


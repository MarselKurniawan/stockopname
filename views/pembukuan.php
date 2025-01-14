<?php
session_start();
require_once __DIR__ . '/../core/v2/config.php';
require_once __DIR__ . '/../core/v2/database.php';
require_once __DIR__ . '/../core/func/csrf_protection.php';

$conn = db_connect();

include_once 'interface/header.php';

// Handle AJAX Requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        $transaction_date = $_POST['transaction_date'];
        $description = $_POST['description'];
        $masuk = $_POST['masuk'] ?? 0;
        $keluar = $_POST['keluar'] ?? 0;

        $query = "INSERT INTO transactions (transaction_date, description, masuk, keluar) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssdd', $transaction_date, $description, $masuk, $keluar);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
        exit;
    }

    if ($action === 'edit') {
        $id = $_POST['id'];
        $transaction_date = $_POST['transaction_date'];
        $description = $_POST['description'];
        $masuk = $_POST['masuk'] ?? 0;
        $keluar = $_POST['keluar'] ?? 0;

        $query = "UPDATE transactions SET transaction_date = ?, description = ?, masuk = ?, keluar = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssddi', $transaction_date, $description, $masuk, $keluar, $id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
        exit;
    }

    if ($action === 'delete') {
        $id = $_POST['id'];

        $query = "DELETE FROM transactions WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
        exit;
    }
}

// Fetch transactions
$query = "SELECT * FROM transactions ORDER BY transaction_date DESC";
$result = $conn->query($query);
$transactions = [];
while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookkeeping</title>
    <link href="https://cdn.jsdelivr.net/npm/@preline/plugin/dist/preline.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body>
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Bookkeeping</h1>

        <!-- Add/Edit Transaction Form -->
        <form id="transactionForm" class="mb-6">
            <input type="hidden" id="transaction_id" name="id">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="transaction_date" class="block text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" id="transaction_date" name="transaction_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <input type="text" id="description" name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="masuk" class="block text-sm font-medium text-gray-700">Debit (Masuk)</label>
                    <input type="number" id="masuk" name="masuk" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label for="keluar" class="block text-sm font-medium text-gray-700">Kredit (Keluar)</label>
                    <input type="number" id="keluar" name="keluar" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                </div>
            </div>
            <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded shadow hover:bg-blue-600">
                Simpan Transaksi
            </button>
        </form>

        <!-- Transactions Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 uppercase">Deskripsi</th>
                        <th class="px-4 py-2 text-right text-sm font-medium text-gray-500 uppercase">Debit</th>
                        <th class="px-4 py-2 text-right text-sm font-medium text-gray-500 uppercase">Kredit</th>
                        <th class="px-4 py-2 text-right text-sm font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-4 py-2 text-right text-sm font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    $currentTotal = 0;
                    foreach ($transactions as $transaction) {
                        $currentTotal += $transaction['masuk'] - $transaction['keluar'];
                    ?>
                        <tr>
                            <td class="px-4 py-2"><?= date('j F Y', strtotime($transaction['transaction_date'])) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($transaction['description']) ?></td>
                            <td class="px-4 py-2 text-green-500 text-right"><?= number_format($transaction['masuk'], 2) ?></td>
                            <td class="px-4 py-2 text-red-500 text-right"><?= number_format($transaction['keluar'], 2) ?></td>
                            <td class="px-4 py-2 text-right">
                                <span class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded">
                                    <?= number_format($currentTotal, 2) ?>
                                </span>
                            </td>
                            <td class="px-4 py-2 text-right">
                                <button class="edit-btn text-blue-600" data-id="<?= $transaction['id'] ?>">Edit</button>
                                <button class="delete-btn text-red-600" data-id="<?= $transaction['id'] ?>">Hapus</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const form = document.getElementById('transactionForm');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(form);
            const action = formData.get('id') ? 'edit' : 'add';
            formData.append('action', action);

            try {
                const response = await axios.post('', formData);
                if (response.data.status === 'success') {
                    alert(`Transaksi berhasil ${action === 'add' ? 'ditambahkan' : 'diperbarui'}!`);
                    location.reload();
                } else {
                    alert('Gagal menyimpan transaksi.');
                }
            } catch (error) {
                console.error(error);
                alert('Terjadi kesalahan.');
            }
        });

        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                const id = button.getAttribute('data-id');
                const row = button.closest('tr');
                const cells = row.querySelectorAll('td');
                document.getElementById('transaction_id').value = id;
                document.getElementById('transaction_date').value = cells[0].innerText.trim();
                document.getElementById('description').value = cells[1].innerText.trim();
                document.getElementById('masuk').value = cells[2].innerText.replace(/,/g, '');
                document.getElementById('keluar').value = cells[3].innerText.replace(/,/g, '');
            });
        });

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', async (e) => {
                if (!confirm('Apakah Anda yakin ingin menghapus transaksi ini?')) return;

                const id = button.getAttribute('data-id');
                try {
                    const response = await axios.post('', new URLSearchParams({ action: 'delete', id }));
                    if (response.data.status === 'success') {
                        alert('Transaksi berhasil dihapus!');
                        location.reload();
                    } else {
                        alert('Gagal menghapus transaksi.');
                    }
                } catch (error) {
                    console.error(error);
                    alert('Terjadi kesalahan.');
                }
            });
        });
    </script>
</body>

</html>

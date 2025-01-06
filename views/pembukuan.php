<?php
session_start();
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
require_once __DIR__ . '/../core/v2/config.php';
require_once __DIR__ . '/../core/v2/database.php';

$mysqli = db_connect();

// Fetch all transactions
$result = $mysqli->query("SELECT * FROM transactions ORDER BY transaction_date ASC");
$transactions = [];
while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://localhost/stockopname/assets/dist/output.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <title>Pembukuan</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"] {
            width: 100%;
            border: none;
            background: transparent;
            padding: 4px;
            outline: none;
        }
    </style>
</head>

<body class="">
    <div class="max-w-7xl px-2 py-4 sm:px-6 lg:px-8 lg:py-14 mx-auto ">
        <h1 class="text-2xl font-bold mb-4">Pembukuan (Editable Tabel)</h1>

        <!-- Table of transactions -->
        <form id="transactionForm">
            <table id="transactionTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Debit (Masuk)</th>
                        <th>Credit (Keluar)</th>
                        <th>Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $currentSaldo = 0;
                    foreach ($transactions as $index => $transaction) {
                        $currentSaldo += $transaction['masuk'] - $transaction['keluar'];
                    ?>
                        <tr>
                            <td>
                                <input type="date" name="transaction_date[]" value="<?= htmlspecialchars($transaction['transaction_date']) ?>">
                            </td>
                            <td>
                                <input type="text" name="description[]" value="<?= htmlspecialchars($transaction['description']) ?>">
                            </td>
                            <td>
                                <input type="number" name="masuk[]" step="0.01" value="<?= htmlspecialchars($transaction['masuk']) ?>" class="debit">
                            </td>
                            <td>
                                <input type="number" name="keluar[]" step="0.01" value="<?= htmlspecialchars($transaction['keluar']) ?>" class="credit">
                            </td>
                            <td>
                                <span class="saldo"><?= number_format($currentSaldo, 2) ?></span>
                            </td>
                            <input type="hidden" name="id[]" value="<?= $transaction['id'] ?>">
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <button type="button" id="saveButton" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Simpan
            </button>
        </form>
    </div>

    <script>
        const table = document.getElementById('transactionTable');
        const saveButton = document.getElementById('saveButton');

        // Recalculate saldo on input change
        table.addEventListener('input', () => {
            const rows = table.querySelectorAll('tbody tr');
            let saldo = 0;

            rows.forEach(row => {
                const debit = parseFloat(row.querySelector('.debit').value) || 0;
                const credit = parseFloat(row.querySelector('.credit').value) || 0;

                saldo += debit - credit;
                row.querySelector('.saldo').textContent = saldo.toFixed(2);
            });
        });

        // Save data to the database
        saveButton.addEventListener('click', async () => {
            const formData = new FormData(document.getElementById('transactionForm'));

            try {
                const response = await axios.post('/stockopname/save_transactions.php', formData);
                alert('Data berhasil disimpan!');
                location.reload();
            } catch (error) {
                console.error(error);
                alert('Terjadi kesalahan saat menyimpan data.');
            }
        });
    </script>
</body>

</html>

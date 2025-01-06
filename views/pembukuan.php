<?php
session_start();
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
require_once __DIR__ . '/../core/v2/config.php';
require_once __DIR__ . '/../core/v2/database.php';
require_once __DIR__ . '/../core/func/csrf_protection.php';

include_once 'interface/header.php';

$mysqli = db_connect();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transaction_date = $_POST['transaction_date'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    $amount = (float) $_POST['amount'];

    // Calculate "masuk", "keluar", and update "total"
    $masuk = $type === 'in' ? $amount : 0;
    $keluar = $type === 'out' ? $amount : 0;

    // Get the last total from the database
    $last_total = 0;
    $result = $mysqli->query("SELECT total FROM transactions ORDER BY id DESC LIMIT 1");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_total = (float) $row['total'];
    }

    $new_total = $last_total + $masuk - $keluar;

    // Insert the transaction into the database
    $stmt = $mysqli->prepare("INSERT INTO transactions (transaction_date, description, masuk, keluar, total) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdd", $transaction_date, $description, $masuk, $keluar, $new_total);

    if ($stmt->execute()) {
        header("Location: /stockopname/pembukuan"); // Redirect to avoid form resubmission
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Fetch all transactions
$result = $mysqli->query("SELECT * FROM transactions ORDER BY transaction_date DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://localhost/stockopname/assets/dist/output.css">

    <!-- <meta charset="UTF-8"> -->
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <title>Pembukuan</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> -->
</head>

<body class="">
    <div class="max-w-7xl px-2 py-4 sm:px-6 lg:px-8 lg:py-14 mx-auto ">
        <h1 class="text-2xl font-bold mb-4">Pembukuan</h1>

        <!-- Form for adding transactions -->
        <form method="POST" class="mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="transaction_date" class="block font-medium text-gray-700">Date</label>
                    <input type="date" id="transaction_date" name="transaction_date" required
                        class="mt-1 p-2 border border-gray-300 rounded w-full">
                </div>
                <div>
                    <label for="description" class="block font-medium text-gray-700">Description</label>
                    <input type="text" id="description" name="description" required
                        class="mt-1 p-2 border border-gray-300 rounded w-full">
                </div>
                <div>
                    <label for="type" class="block font-medium text-gray-700">Type</label>
                    <select id="type" name="type" required class="mt-1 p-2 border border-gray-300 rounded w-full">
                        <option value="in">Masuk</option>
                        <option value="out">Keluar</option>
                    </select>
                </div>
                <div>
                    <label for="amount" class="block font-medium text-gray-700">Amount</label>
                    <input type="number" id="amount" name="amount" step="0.01" required
                        class="mt-1 p-2 border border-gray-300 rounded w-full">
                </div>
            </div>
            <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Add
                Transaction</button>
        </form>

        <!-- Table of transactions -->
        <h2 class="text-xl font-bold mb-4">Transaction History</h2>
        <table class="w-full border-collapse border border-gray-200">
            <thead>
                <tr>
                    <th class="border border-gray-200 px-4 py-2">Date</th>
                    <th class="border border-gray-200 px-4 py-2">Description</th>
                    <th class="border border-gray-200 px-4 py-2">Debit</th>
                    <th class="border border-gray-200 px-4 py-2">Kredit</th>
                    <th class="border border-gray-200 px-4 py-2">Saldo</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="border border-gray-200 px-4 py-2"><?= htmlspecialchars($row['transaction_date']) ?></td>
                        <td class="border border-gray-200 px-4 py-2"><?= htmlspecialchars($row['description']) ?></td>
                        <td class="border border-gray-200 px-4 py-2 text-green-500">
                            <?= $row['masuk'] > 0 ? number_format($row['masuk'], 2) : '-' ?>
                        </td>
                        <td class="border border-gray-200 px-4 py-2 text-red-500">
                            <?= $row['keluar'] > 0 ? number_format($row['keluar'], 2) : '-' ?>
                        </td>
                        <td class="border border-gray-200 px-4 py-2 font-bold"><?= number_format($row['total'], 2) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    
</body>

</html>
<?php
session_start(); // Ensure the session is started

header('Content-Type: application/json');
require_once '../core/func/csrf_protection.php';
require_once '../core/v2/database.php'; // Include db_connect function
require_once '../core/v2/config.php';

$conn = db_connect(); // Initialize the database connection

// Check if all required fields are present in the POST request
if (
    isset(
    $_POST['nama_produk'],
    $_POST['kemasan'],
    $_POST['ukuran_stoples'],
    $_POST['ukuran_mika'],
    $_POST['ukuran_paket'],
    $_POST['harga']
)
) {
    // Sanitize and assign the input values
    $nama_produk = htmlspecialchars($_POST['nama_produk']);
    $kemasan = htmlspecialchars($_POST['kemasan']);
    $ukuran_stoples = htmlspecialchars($_POST['ukuran_stoples']);
    $ukuran_mika = htmlspecialchars($_POST['ukuran_mika']);
    $ukuran_paket = htmlspecialchars($_POST['ukuran_paket']);
    $harga = floatval($_POST['harga']); // Ensure the price is a valid number

    try {
        // Use a prepared statement for secure database insertion
        $stmt = $conn->prepare(
            "INSERT INTO produk (nama_produk, kemasan, ukuran_stoples, ukuran_mika, ukuran_paket, harga) 
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("sssssd", $nama_produk, $kemasan, $ukuran_stoples, $ukuran_mika, $ukuran_paket, $harga);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Product added successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add product']);
        }

        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Incomplete data provided']);
}

$conn->close(); // Close the database connection
?>
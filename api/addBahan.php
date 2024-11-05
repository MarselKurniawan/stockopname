<?php
session_start(); // Start the session

header('Content-Type: application/json');
require_once '../core/func/csrf_protection.php';
require_once '../core/v2/database.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Get JSON input data
$input = json_decode(file_get_contents('php://input'), true);

// Validate CSRF token
$csrf_token = $input['csrf_token'] ?? '';
if (!verify_csrf_token($csrf_token)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid CSRF token']);
    exit;
}

// Get data from input
$supplier_id = $input['supplier_id'] ?? null;
$bahan_nama = $input['nama_bahan'] ?? null;
$harga_beli = $input['harga_beli'] ?? null;
$satuan = $input['satuan'] ?? null;
$jumlah = $input['jumlah'] ?? null;

// Validate input
if (empty($supplier_id) || empty($bahan_nama) || empty($harga_beli) || empty($satuan) || empty($jumlah)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

// Connect to the database
$conn = db_connect();
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Check if the bahan already exists based on nama_bahan
$query = "SELECT id_bahan, jumlah FROM bahan WHERE nama_bahan = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Query preparation failed: ' . $conn->error]);
    exit;
}

// Bind parameters and execute the query
$stmt->bind_param('s', $bahan_nama);
$stmt->execute();
$result = $stmt->get_result();
$existingBahan = $result->fetch_assoc();

if ($existingBahan) {
    // If it exists, update the quantity and other details
    $newJumlah = $existingBahan['jumlah'] + $jumlah;

    $updateQuery = "UPDATE bahan SET jumlah = ?, harga_beli = ?, satuan = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    
    if (!$updateStmt) {
        echo json_encode(['status' => 'error', 'message' => 'Update query preparation failed: ' . $conn->error]);
        exit;
    }

    // Bind parameters and execute the update query
    $updateStmt->bind_param('issi', $newJumlah, $harga_beli, $satuan, $existingBahan['id']);
    
    if ($updateStmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Bahan updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update bahan: ' . $updateStmt->error]);
    }

    // Close update statement
    $updateStmt->close();
} else {
    // If it does not exist, insert a new record
    $insertQuery = "INSERT INTO bahan (supplier, nama_bahan, harga_beli, satuan, jumlah) VALUES (?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertQuery);
    
    if (!$insertStmt) {
        echo json_encode(['status' => 'error', 'message' => 'Insert query preparation failed: ' . $conn->error]);
        exit;
    }

    // Bind parameters and execute the insert query
    $insertStmt->bind_param('isssi', $supplier_id, $bahan_nama, $harga_beli, $satuan, $jumlah);
    
    if ($insertStmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Bahan added successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add bahan: ' . $insertStmt->error]);
    }

    // Close insert statement
    $insertStmt->close();
}

// Close the main statement and database connection
$stmt->close();
$conn->close();
?>

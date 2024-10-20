<?php
// Menangani preflight request (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    http_response_code(200);
    exit;
}
// Error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../core/func/csrf_protection.php';
require_once '../core/v2/database.php';
require_once '../core/v2/config.php';

// Periksa CSRF token
if (!isset($_GET['csrf_token']) || !validate_csrf_token($_GET['csrf_token'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid CSRF token']);
    exit;
}

// Validasi toko_id
if (!isset($_GET['toko_id']) || !is_numeric($_GET['toko_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid toko_id']);
    exit;
}

$toko_id = (int) $_GET['toko_id'];

// Koneksi database
$conn = connect_db();
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

$stmt = $conn->prepare("SELECT stok.*, produk.nama_produk FROM stok JOIN produk ON stok.produk_id = produk.id WHERE toko_id = ?");
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Query preparation failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param('i', $toko_id);
if (!$stmt->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'Query execution failed: ' . $stmt->error]);
    exit;
}

$result = $stmt->get_result();
$stok = [];
while ($row = $result->fetch_assoc()) {
    $stok[] = $row;
}

if (!empty($stok)) {
    echo json_encode(['status' => 'success', 'data' => $stok]);
} else {
    echo json_encode(['status' => 'success', 'data' => [], 'message' => 'No stock found for this store.']);
}

$stmt->close();
$conn->close();
?>

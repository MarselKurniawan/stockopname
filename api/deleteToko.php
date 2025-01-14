<?php
// deleteToko.php
require_once __DIR__ . '/../core/v2/database.php';
require_once __DIR__ . '/../core/func/csrf_protection.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && validate_csrf_token($_GET['csrf_token'])) {
    $id = $_GET['id'];

    $query = "DELETE FROM toko WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

<?php
require_once __DIR__ . '/../core/v2/config.php';
require_once __DIR__ . '/../core/v2/database.php';

$action = $_POST['action'] ?? $_GET['action'] ?? null;

switch ($action) {
    case 'add':
        $nama_produk = $_POST['nama_produk'];
        $kemasan = $_POST['kemasan'];
        $harga = $_POST['harga'];

        $query = "INSERT INTO produk (nama_produk, kemasan, harga) VALUES ('$nama_produk', '$kemasan', '$harga')";
        if (mysqli_query($conn, $query)) {
            echo json_encode(['success' => true, 'message' => 'Produk berhasil ditambahkan!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan saat menambahkan produk.']);
        }
        break;

    case 'edit':
        $id = $_POST['id'];
        $nama_produk = $_POST['nama_produk'];
        $kemasan = $_POST['kemasan'];
        $harga = $_POST['harga'];

        $query = "UPDATE produk SET nama_produk = '$nama_produk', kemasan = '$kemasan', harga = '$harga' WHERE id = $id";
        if (mysqli_query($conn, $query)) {
            echo json_encode(['success' => true, 'message' => 'Produk berhasil diubah!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan saat mengubah produk.']);
        }
        break;

    case 'delete':
        $id = $_POST['id'];
        $query = "DELETE FROM produk WHERE id = $id";
        if (mysqli_query($conn, $query)) {
            echo json_encode(['success' => true, 'message' => 'Produk berhasil dihapus!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus produk.']);
        }
        break;

    case 'get_all_products':
        $query = "SELECT * FROM produk";
        $result = mysqli_query($conn, $query);
        if ($result) {
            $products = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $products[] = $row;
            }
            echo json_encode(['success' => true, 'products' => $products]);
        } else {
            echo json_encode(['success' => false]);
        }
        break;

    case 'get_product':
        $id = $_GET['id'];
        $query = "SELECT * FROM produk WHERE id = $id";
        $result = mysqli_query($conn, $query);
        if ($result) {
            $product = mysqli_fetch_assoc($result);
            echo json_encode(['success' => true, 'product' => $product]);
        } else {
            echo json_encode(['success' => false]);
        }
        break;
}
?>
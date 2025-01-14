<?php
session_start();
require_once __DIR__ . '/../core/v2/config.php';
require_once __DIR__ . '/../core/v2/database.php';
require_once __DIR__ . '/../core/func/csrf_protection.php';

// Handle form submission for adding or editing products
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $conn = db_connect();
  $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
  $kemasan = mysqli_real_escape_string($conn, $_POST['kemasan']);
  $ukuran = '';

  if ($kemasan === 'stoples') {
    $ukuran = mysqli_real_escape_string($conn, $_POST['ukuran_stoples']);
  } elseif ($kemasan === 'mika') {
    $ukuran = mysqli_real_escape_string($conn, $_POST['ukuran_mika']);
  } elseif ($kemasan === 'paketan') {
    $ukuran = mysqli_real_escape_string($conn, $_POST['ukuran_paket']);
  }

  $harga = mysqli_real_escape_string($conn, $_POST['harga']);
  $id = isset($_POST['id']) ? $_POST['id'] : null;

  if ($id) {
    // Update Product
    $query = "UPDATE produk SET nama_produk = ?, kemasan = ?, ukuran_stoples = ?, ukuran_mika = ?, ukuran_paket = ?, harga = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param(
      $stmt,
      'ssssssd',
      $nama_produk,
      $kemasan,
      ($kemasan === 'stoples' ? $ukuran : ""),
      ($kemasan === 'mika' ? $ukuran : ""),
      ($kemasan === 'paketan' ? $ukuran : ""),
      $harga,
      $id
    );
    mysqli_stmt_execute($stmt);
  } else {
    // Add Product
    $query = "INSERT INTO produk (nama_produk, kemasan, ukuran_stoples, ukuran_mika, ukuran_paket, harga) 
                VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param(
      $stmt,
      'sssssd',
      $nama_produk,
      $kemasan,
      ($kemasan === 'stoples' ? $ukuran : ""),
      ($kemasan === 'mika' ? $ukuran : ""),
      ($kemasan === 'paketan' ? $ukuran : ""),
      $harga
    );
    mysqli_stmt_execute($stmt);
  }

  header("Location: produk.php"); // Redirect to refresh the page after form submission
  exit;
}

// Handle delete product
if (isset($_GET['delete'])) {
  $conn = db_connect();
  $id = $_GET['delete'];
  $query = "DELETE FROM produk WHERE id = ?";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, 'd', $id);
  mysqli_stmt_execute($stmt);

  header("Location: produk.php"); // Redirect after deleting product
  exit;
}

// Fetch Products from Database
$conn = db_connect();
$query = "SELECT * FROM produk";
$products_result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produk Management</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.23/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
  <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-semibold text-gray-800">Produk Management</h1>
      <button class="bg-green-600 text-white py-2 px-4 rounded-lg" onclick="openAddProductModal()">Tambah
        Produk</button>
    </div>

    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
      <thead>
        <tr>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nama</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Kemasan</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Ukuran</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Harga</th>
          <th class="px-6 py-3 text-right text-sm font-medium text-gray-700">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($products_result)): ?>
          <tr>
            <td class="px-6 py-3"><?= htmlspecialchars($row['nama_produk']) ?></td>
            <td class="px-6 py-3"><?= htmlspecialchars($row['kemasan']) ?></td>
            <td class="px-6 py-3">
              <?= htmlspecialchars($row['kemasan'] === 'stoples' ? $row['ukuran_stoples'] :
                ($row['kemasan'] === 'mika' ? $row['ukuran_mika'] : $row['ukuran_paket'])) ?>
            </td>
            <td class="px-6 py-3"><?= number_format($row['harga'], 2) ?></td>
            <td class="px-6 py-3 text-right">
              <a href="produk.php?edit=<?= $row['id'] ?>" class="text-blue-600">Edit</a>
              <a href="produk.php?delete=<?= $row['id'] ?>" class="text-red-600">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <!-- Modal for Add/Edit Product -->
    <div id="addModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">
          <?= isset($_GET['edit']) ? 'Edit Produk' : 'Tambah Produk' ?></h2>
        <form method="POST">
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
          <input type="hidden" name="id" value="<?= isset($_GET['edit']) ? $_GET['edit'] : ''; ?>">

          <div class="mb-4">
            <label for="nama_produk" class="block text-sm font-medium text-gray-600">Nama Produk</label>
            <input type="text" id="nama_produk" name="nama_produk"
              class="block w-full mt-1 p-2 border border-gray-300 rounded-md"
              value="<?= isset($_GET['edit']) ? htmlspecialchars($row['nama_produk']) : ''; ?>" required>
          </div>

          <div class="mb-4">
            <label for="kemasan" class="block text-sm font-medium text-gray-600">Kemasan</label>
            <select id="kemasan" name="kemasan" class="block w-full mt-1 p-2 border border-gray-300 rounded-md"
              required>
              <option value="stoples" <?= isset($_GET['edit']) && $row['kemasan'] === 'stoples' ? 'selected' : ''; ?>>
                Stoples</option>
              <option value="mika" <?= isset($_GET['edit']) && $row['kemasan'] === 'mika' ? 'selected' : ''; ?>>Mika
              </option>
              <option value="paketan" <?= isset($_GET['edit']) && $row['kemasan'] === 'paketan' ? 'selected' : ''; ?>>
                Paketan</option>
            </select>
          </div>

          <div class="mb-4" id="ukuranFields">
            <!-- Dynamic size fields based on kemasan -->
            <?php if (isset($_GET['edit'])): ?>
              <!-- Add pre-filled data for edit if necessary -->
            <?php endif; ?>
          </div>

          <div class="mb-4">
            <label for="harga" class="block text-sm font-medium text-gray-600">Harga</label>
            <input type="number" id="harga" name="harga" class="block w-full mt-1 p-2 border border-gray-300 rounded-md"
              value="<?= isset($_GET['edit']) ? htmlspecialchars($row['harga']) : ''; ?>" required>
          </div>

          <div class="text-right">
            <button type="submit"
              class="bg-blue-600 text-white py-2 px-4 rounded-lg"><?= isset($_GET['edit']) ? 'Update Produk' : 'Tambah Produk' ?></button>
            <button type="button" onclick="closeAddProductModal()"
              class="bg-gray-300 text-gray-800 py-2 px-4 rounded-lg">Batal</button>
          </div>
        </form>
      </div>
    </div>

    <script>
      function openAddProductModal() {
        document.getElementById('addModal').classList.remove('hidden');
      }

      function closeAddProductModal() {
        document.getElementById('addModal').classList.add('hidden');
      }
    </script>
  </div>
</body>

</html>
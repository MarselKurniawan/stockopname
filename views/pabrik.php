<?php
require_once __DIR__ . '/../core/v2/config.php';
require_once __DIR__ . '/../core/v2/database.php';

include_once 'interface/header.php'; // Include header

$conn = db_connect(); // Koneksi ke database

// Ambil data produk untuk dropdown
$produkList = [];
try {
  $stmt = $conn->prepare("SELECT id, nama_produk, kemasan, ukuran_stoples, ukuran_mika, ukuran_paket FROM produk");
  $stmt->execute();
  $result = $stmt->get_result();

  while ($row = $result->fetch_assoc()) {
    // Tentukan kemasan yang tersedia
    $packaging = $row['ukuran_stoples'] ?: ($row['ukuran_mika'] ?: $row['ukuran_paket']);
    if ($packaging) {
      $produkList[] = [
        'id' => $row['id'],
        'display_name' => $row['nama_produk'] . ' ' . $row['kemasan'] . ' ' . $packaging,
      ];
    }
  }
} catch (Exception $e) {
  die("Error fetching products: " . $e->getMessage());
}

// Proses penyimpanan form (Add/Edit)
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $produk_id = $_POST['produk_id'];
  $hasil_roti = $_POST['hasil_roti'];
  $tanggal = $_POST['tanggal'];

  if (isset($_POST['id_pabrik'])) {
    // Edit data
    $id_pabrik = $_POST['id_pabrik'];
    try {
      $stmt = $conn->prepare("UPDATE pabrik SET produk_id = ?, hasil_roti = ?, tanggal = ? WHERE id_pabrik = ?");
      $stmt->bind_param('issi', $produk_id, $hasil_roti, $tanggal, $id_pabrik);
      $stmt->execute();
      $message = "Data berhasil diupdate!";
    } catch (Exception $e) {
      $message = "Gagal mengupdate data: " . $e->getMessage();
    }
  } else {
    // Add data
    try {
      $stmt = $conn->prepare("INSERT INTO pabrik (produk_id, hasil_roti, tanggal) VALUES (?, ?, ?)");
      $stmt->bind_param('iis', $produk_id, $hasil_roti, $tanggal);
      $stmt->execute();
      $message = "Data berhasil disimpan!";
    } catch (Exception $e) {
      $message = "Gagal menyimpan data: " . $e->getMessage();
    }
  }
}

// Proses delete data
if (isset($_GET['delete'])) {
  $id_pabrik = $_GET['delete'];
  try {
    $stmt = $conn->prepare("DELETE FROM pabrik WHERE id_pabrik = ?");
    $stmt->bind_param('i', $id_pabrik);
    $stmt->execute();
    $message = "Data berhasil dihapus!";
  } catch (Exception $e) {
    $message = "Gagal menghapus data: " . $e->getMessage();
  }
}

// Ambil data hasil produksi
$dataProduksi = [];
try {
  $stmt = $conn->prepare("
        SELECT 
            pabrik.id_pabrik, 
            produk.nama_produk, 
            produk.kemasan, 
            pabrik.hasil_roti, 
            pabrik.tanggal,
            produk.ukuran_stoples, 
            produk.ukuran_mika, 
            produk.ukuran_paket
        FROM 
            pabrik 
        JOIN 
            produk 
        ON 
            pabrik.produk_id = produk.id
    ");
  $stmt->execute();
  $result = $stmt->get_result();

  while ($row = $result->fetch_assoc()) {
    // $dataProduksi[] = $row;

    $packaging = $row['ukuran_stoples'] ?: ($row['ukuran_mika'] ?: $row['ukuran_paket']);

    if ($packaging) {
      $dataProduksi[] = [
        'id_pabrik' => $row['id_pabrik'],
        'kemasan' => $row['kemasan'],
        'hasil_roti' => $row['hasil_roti'],
        'tanggal' => $row['tanggal'],
        'display_name' => $row['nama_produk'] . ' ' . $row['kemasan'] . ' ' . $packaging,

      ];
    }
  }
} catch (Exception $e) {
  die("Error fetching production data: " . $e->getMessage());
}

// Format tanggal ke format "1 Januari 2025"
function formatTanggal($tanggal)
{
  $date = new DateTime($tanggal);
  return $date->format('j F Y');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Data Hasil Produksi</title>
  <style>
    /* Membuat tabel menjadi responsif */
    @media (max-width: 768px) {
      table {
        width: 100%;
        overflow-x: auto;
        display: block;
      }

      th,
      td {
        white-space: nowrap;
        word-wrap: break-word;
      }

      th,
      td {
        padding: 8px;
      }

      /* Membuat search input responsif */
      #searchInput {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
      }
    }
  </style>
</head>

<body class="bg-white ">
  <div class="container mx-auto p-10 max-w-8xl">
    <h2 class="text-2xl font-semibold mb-5">
      <?php if (isset($_GET['edit'])): ?>Edit Data Hasil Produksi<?php else: ?>Input Hasil Produksi<?php endif; ?>
    </h2>

    <?php if (!empty($message)): ?>
      <div class="p-4 mb-5 text-sm text-green-800 bg-green-200 rounded-lg">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <form action="" method="POST" class="space-y-6">
      <?php if (isset($_GET['edit'])): ?>
        <?php
        $id_pabrik = $_GET['edit'];
        $stmt = $conn->prepare("SELECT * FROM pabrik WHERE id_pabrik = ?");
        $stmt->bind_param('i', $id_pabrik);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        ?>
        <input type="hidden" name="id_pabrik" value="<?= $data['id_pabrik'] ?>">
        <div class="bg-yellow-50 border border-yellow-200 text-sm text-yellow-800 rounded-lg p-4" role="alert"
          tabindex="-1" aria-labelledby="hs-with-description-label">
          <div class="flex">
            <div class="shrink-0">
              <svg class="shrink-0 size-4 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                <path d="M12 9v4"></path>
                <path d="M12 17h.01"></path>
              </svg>
            </div>
            <div class="ms-4">
              <h3 id="hs-with-description-label" class="text-sm font-semibold">
                Masih edit data ini
              </h3>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <div>
        <label for="produkSelect" class="block text-sm font-medium text-gray-700">Pilih Produk</label>
        <select id="produkSelect" name="produk_id"
          class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
          required>
          <option value="" selected>Pilih Produk</option>
          <?php foreach ($produkList as $produk): ?>
            <option value="<?= $produk['id'] ?>" <?php if (isset($data) && $data['produk_id'] == $produk['id'])
                echo 'selected'; ?>>
              <?= htmlspecialchars($produk['display_name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label for="hasil_roti" class="block text-sm font-medium text-gray-700">Hasil Roti</label>
        <input type="number" oninput="validateNumberInput(event)" id="hasil_roti" name="hasil_roti"
          class="peer py-3 px-4 block w-full bg-gray-100 border-transparent rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
          value="<?= isset($data) ? htmlspecialchars($data['hasil_roti']) : '' ?>" required>
      </div>

      <div>
        <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal Produksi</label>
        <input type="date" id="tanggal" name="tanggal"
          class="peer py-3 px-4 block w-full bg-gray-100 border-transparent rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
          value="<?= isset($data) ? $data['tanggal'] : '' ?>" required>
      </div>

      <div class="flex justify-between">
        <button type="submit"
          class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">Simpan</button>
        <?php if (isset($_GET['edit'])): ?>
          <a href="/stockopname/pabrik"
            class="px-6 py-2 bg-gray-400 text-white rounded-lg shadow hover:bg-gray-500">Batal</a>
        <?php endif; ?>
      </div>
    </form>
  </div>

  <div class="container mx-auto p-10">
    <h2 class="text-2xl font-semibold mb-5">Data Hasil Produksi</h2>

    <table class="min-w-full bg-white border border-gray-300 rounded-lg table-auto">
      <!-- Input search untuk filter data -->
      <thead class="bg-gray-100">
        <tr>
          <th colspan="8" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
            <input type="text" id="searchInput" class="px-4 py-2 border border-gray-300 rounded-lg"
              placeholder="Search..." />
          </th>
        </tr>
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">No</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nama Produk</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Kemasan</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Hasil Roti</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Hasil Dos</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Satuan</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tanggal</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Aksi</th>
        </tr>
      </thead>
      <tbody id="tableBody">
        <?php if (count($dataProduksi) > 0): ?>
          <?php foreach ($dataProduksi as $index => $data): ?>
            <tr class="<?= $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' ?>" class="table-row">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= $index + 1 ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= htmlspecialchars($data['display_name']) ?>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= htmlspecialchars($data['kemasan']) ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= htmlspecialchars($data['hasil_roti']) ?>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                <?php
                $hasil_dos = floor($data['hasil_roti'] / 12); // Hasil Dos
                echo $hasil_dos;
                ?> Dos
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                <?php
                $satuan = $data['hasil_roti'] - ($hasil_dos * 12); // Satuan (sisa dari hasil roti)
                echo $satuan;
                ?>
                Pcs
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= formatTanggal($data['tanggal']) ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                <a href="?edit=<?= $data['id_pabrik'] ?>" class="text-blue-600 hover:text-blue-800">Edit</a> |
                <a href="?delete=<?= $data['id_pabrik'] ?>" class="text-red-600 hover:text-red-800"
                  onclick="return confirm('Yakin ingin menghapus data ini?')">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data hasil produksi.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

  </div>

  <script>

    // Fungsi untuk pencarian di seluruh tabel
    document.getElementById('searchInput').addEventListener('input', function () {
      const searchValue = this.value.toLowerCase();
      const rows = document.querySelectorAll('#tableBody tr');  // Mendapatkan semua baris data dalam tabel

      rows.forEach(row => {
        const cells = row.getElementsByTagName('td');
        let rowMatches = false;

        // Looping untuk memeriksa setiap cell
        for (let i = 0; i < cells.length; i++) {
          if (cells[i].textContent.toLowerCase().includes(searchValue)) {
            rowMatches = true; // Jika ada kecocokan, baris akan ditampilkan
            break;
          }
        }

        // Menampilkan atau menyembunyikan baris berdasarkan kecocokan
        if (rowMatches) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });


    // Fungsi untuk menghitung hasil dos dan satuan
    function calculateDosAndSatuan() {
      const hasilRotiInput = document.getElementById('hasil_roti');
      const hasilRoti = parseFloat(hasilRotiInput.value);

      if (isNaN(hasilRoti)) {
        return;
      }

      const hasilDos = Math.floor(hasilRoti / 12); // Hasil Dos
      const satuan = hasilRoti - (hasilDos * 12); // Satuan (sisa)

      // Menampilkan hasil dos dan satuan
      document.getElementById('hasil_dos').textContent = hasilDos;
      document.getElementById('satuan').textContent = satuan;
    }

    // Event listener untuk input hasil roti
    document.getElementById('hasil_roti').addEventListener('input', calculateDosAndSatuan);

    // Panggil fungsi perhitungan pertama kali saat halaman dimuat (untuk value yang ada saat ini)
    document.addEventListener('DOMContentLoaded', calculateDosAndSatuan);

    function validateNumberInput(event) {
      // Hanya izinkan angka dan tanda titik desimal
      const input = event.target;
      input.value = input.value.replace(/[^0-9.]/g, ''); // Hapus karakter selain angka dan titik
    }
  </script>

</body>

</html>
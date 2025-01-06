<?php
session_start();
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
require_once __DIR__ . '/../core/v2/config.php';
require_once __DIR__ . '/../core/v2/database.php';
require_once __DIR__ . '/../core/func/csrf_protection.php';


error_log('Token di sesi: ' . $_SESSION['csrf_token']);
error_log('Token yang dikirim: ' . $_SESSION['csrf_token']);


include_once 'interface/header.php';
?>

<!-- Pastikan ada tempat untuk menyimpan token CSRF -->
<div id="notification"
  class="fixed top-5 z-[100] right-5 mt-2 bg-teal-100 border border-teal-200 text-sm text-teal-800 rounded-lg p-4 hidden"
  role="alert" tabindex="-1" aria-labelledby="hs-soft-color-success-label">
  <span id="hs-soft-color-success-label" class="font-bold">Alert!</span> Anda sedang menambah stok.
</div>

<!-- Card Section -->
<div class="max-w-8xl px-2 py-4 sm:px-6 lg:px-8 lg:py-14 mx-auto " id="form-div">
  <!-- Card -->
  <div class="bg-white rounded-xl shadow p-4 sm:p-7">
    <div class="text-center mb-8">
      <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
        Input Barang
      </h2>
      <p class="text-sm text-gray-600">

      </p>
    </div>
    <form id="addStockForm" class="space-y-3">
      <input type="hidden" id="csrf_token" value="<?php echo generate_csrf_token(); ?>">

      <!-- Toko Section -->
      <div class="py-6 first:pt-0 last:pb-0 border-t first:border-transparent border-gray-200">
        <label class="inline-block text-sm font-medium">Toko</label>
        <div class="mt-2 space-y-3">
          <div class="flex flex-col sm:flex-row gap-3">
            <select id="citySelect"
              class="py-2 px-3 pe-9 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500">
              <option selected>Select City</option>
            </select>

            <select id="storeSelect"
              class="py-2 px-3 pe-9 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500">
              <option selected>Select Store</option>
            </select>

            <!-- <select id="productSelect" class="py-2 px-3 pe-9 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500">
                    <option selected>Select Produk</option>
                </select> -->

            <select id="diskonSelect"
              class="py-2 px-3 pe-9 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500">
              <option value="Harga Normal">Harga Normal</option>
              <option value="2.5%">2.5%</option>
              <option value="5%">5%</option>
              <option value="10%">10%</option>
            </select>

            <input type="date" id="tanggal"
              class="py-2 px-3 pe-11 block w-80 border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500"
              placeholder="Tanggal Masuk">

          </div>
        </div>
      </div>

      <div class="harga-after-diskon inline-flex items-end gap-3">
        <div class="grid grid-cols-1">
          <span class="harga-awal text-gray-400">

          </span>
          <span class="text-sm">

          </span>
        </div>
        <div class="persentas">
          <span
            class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-red-100 text-red-800 rounded-full dark:bg-red-500/10 dark:text-red-500">
            <svg class="shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="22 17 13.5 8.5 8.5 13.5 2 7"></polyline>
              <polyline points="16 17 22 17 22 11"></polyline>
            </svg>

          </span>
        </div>
      </div>
      <!-- SO Detail Section -->
      <!-- <div class="py-6 first:pt-0 last:pb-0 border-t first:border-transparent border-gray-200">
        <label class="inline-block text-sm font-medium">SO Detail</label>
        <div class="mt-2 space-y-3">
          <div class="flex flex-col sm:flex-row gap-3">
            <select id="productSelect"
              class="py-2 px-3 pe-9 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500">
              <option selected>Select Produk</option>
            </select>
            <input type="text" id="harga" name="Harga"
              class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500"
              placeholder="Harga">
            <input type="text" id="jumlah" name="jumlah"
              class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500"
              placeholder="Jumlah">
          </div>
          <input type="date" id="tanggal"
            class="py-2 px-3 pe-11 block w-80 border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500"
            placeholder="Tanggal Masuk">
        </div>
      </div> -->

      <!-- SO Detail Section -->
      <div class="py-6 first:pt-0 last:pb-0 border-t first:border-transparent border-gray-200">
        <label class="inline-block text-sm font-medium">SO Detail</label>
        <div id="soDetailContainer" class="mt-2 space-y-3">
          <!-- Baris Produk 1 -->
          <div class="so-detail-row flex flex-col sm:flex-row gap-3">
            <select name="product_id[]" id="productSelect"
              class="productSelect py-2 px-3 pe-9 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500">
              <option selected>Select Produk</option>
            </select>
            <input type="text" name="harga[]"
              class="hargaInput py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500"
              placeholder="Harga">
            <input type="text" name="jumlah[]"
              class="jumlahInput py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500"
              placeholder="Jumlah">
          </div>
        </div>

        <!-- Button Tambah Produk -->
        <button type="button" id="addProductRow"
          class="mt-3 py-1.5 px-3 text-sm font-medium rounded-lg bg-green-600 text-white hover:bg-green-700">
          + Tambah Produk
        </button>
      </div>

      <!-- Submit Buttons -->
      <div class="mt-5 flex justify-end gap-x-2">
        <button type="button"
          class="py-2 px-3 text-sm font-medium rounded-lg border bg-white text-gray-800 hover:bg-gray-50">Cancel</button>
        <button type="submit"
          class="py-2 px-3 text-sm font-medium rounded-lg bg-blue-600 text-white hover:bg-blue-700">Save
          changes</button>
      </div>

    </form>
    <!-- </div> -->
  </div>
  <!-- End Card -->
</div>
<!-- End Card Section -->


<!-- Table Section -->
<div class="max-w-8xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
  <!-- Card -->
  <div class="flex flex-col">
    <div class="-m-1.5 overflow-x-auto">
      <div class="p-1.5 min-w-full inline-block align-middle">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
          <!-- Header -->
          <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200">
            <div>
              <h2 class="text-xl font-semibold text-gray-800">
                Penjualan
              </h2>
              <p class="text-sm text-gray-600">
                Add Penjualan, edit and more.
              </p>
            </div>

            <div>
              <div class="inline-flex gap-x-2">
                <button id="show-form-btn"
                  class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                  href="#">
                  <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="M12 5v14" />
                  </svg>
                  Add Penjualan
                </button>
              </div>
            </div>
          </div>
          <!-- End Header -->

          <!-- Table -->
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>

                <th scope="col" class="ps-6 lg:ps-3 xl:ps-6 px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                      Kota
                    </span>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                      Nama Toko
                    </span>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                      Tanggal
                    </span>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                      Jumlah
                    </span>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                      Tagihan
                    </span>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">

                    </span>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3 text-end"></th>
              </tr>
            </thead>
            <input type="hidden" id="csrf_token" value="<?php echo generate_csrf_token(); ?>">

            <tbody class="divide-y p-6 divide-gray-200" id="stokTable">

            </tbody>
          </table>
          <!-- End Table -->
          <!-- Modal untuk detail produk -->
          <div id="detailModal" style="z-index: 90;"
            class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-lg p-6 w-2/3">
              <h3 class="text-lg font-semibold mb-4">Detail Produk</h3>
              <table class="table-auto w-full text-left border">
                <thead>
                  <tr>
                    <th class="p-4">Nama Produk</th>
                    <th class="p-4">Harga</th>
                    <th class="p-4">Jumlah</th>
                  </tr>
                </thead>
                <tbody id="detailTableBody">
                  <!-- Data detail produk akan dimasukkan di sini -->
                </tbody>
              </table>
              <div class="mt-4 text-right">
                <button id="closeModalBtn" class="px-4 py-2 bg-red-500 text-white rounded">Tutup</button>
              </div>
            </div>
          </div>
          <!-- Footer -->
          <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200">
            <div>
              <p class="text-sm text-gray-600">
                <span class="font-semibold text-gray-800">12</span> results
              </p>
            </div>

            <div>
              <div class="inline-flex gap-x-2">
                <button type="button"
                  class="py-1.5 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50">
                  <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6" />
                  </svg>
                  Prev
                </button>

                <button type="button"
                  class="py-1.5 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50">
                  Next
                  <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
          <!-- End Footer -->
        </div>
      </div>
    </div>
  </div>
  <!-- End Card -->
</div>
<!-- End Table Section -->



<script>

  document.getElementById('addProductRow').addEventListener('click', function () {
    // Dapatkan container utama SO Detail
    const container = document.getElementById('soDetailContainer');

    // Elemen HTML untuk baris baru
    const newRow = document.createElement('div');
    newRow.className = 'so-detail-row flex flex-col sm:flex-row gap-3';

    // Template untuk baris produk
    newRow.innerHTML = `
    
    <select name="product_id[]"
      class="productSelect py-2 px-3 pe-9 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500">
      <option selected>Select Produk</option>
    </select>
    <input type="text" name="harga[]"
      class="hargaInput py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500"
      placeholder="Harga">
    <input type="text" name="jumlah[]"
      class="jumlahInput py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500"
      placeholder="Jumlah">
    <button type="button" class="removeRow text-red-500 text-sm">Hapus</button>
  `;

    // Tambahkan row baru ke container
    container.appendChild(newRow);

    // Fetch dan Load Produk ke Dropdown
    const productSelect = newRow.querySelector('.productSelect'); // Cari select di baris baru
    fetch("/stockopname//api/products_select.php")
      .then(response => response.json())
      .then(data => {
        data.forEach(product => {
          let option = document.createElement("option");
          option.value = product.id;
          option.text = `${product.display_name} - Rp. ${parseInt(product.harga).toLocaleString()}`;
          option.dataset.harga = product.harga;
          productSelect.add(option);
        });
      });

    // Event listener untuk select produk
    productSelect.addEventListener('change', function () {
      const selectedOption = productSelect.options[productSelect.selectedIndex];
      const hargaInput = newRow.querySelector('.hargaInput');
      hargaInput.value = parseFloat(selectedOption.dataset.harga || 0).toLocaleString();
    });

    // Event listener untuk tombol hapus
    newRow.querySelector('.removeRow').addEventListener('click', function () {
      container.removeChild(newRow);
    });
  });

  // Load Data untuk Dropdown Pertama Saat Halaman Dimuat
  document.addEventListener("DOMContentLoaded", function () {
    const productSelect = document.querySelector('.productSelect'); // Select produk pertama

    // Fetch dan Load Produk
    fetch("/stockopname//api/products_select.php")
      .then(response => response.json())
      .then(data => {
        data.forEach(product => {
          let option = document.createElement("option");
          option.value = product.id;
          option.text = `${product.display_name} - Rp. ${parseInt(product.harga).toLocaleString()}`;
          option.dataset.harga = product.harga;
          productSelect.add(option);
        });
      });
  });




  document.addEventListener("DOMContentLoaded", function () {
    const productSelect = document.getElementById("productSelect");
    const diskonSelect = document.getElementById("diskonSelect");
    const hargaAwalDisplay = document.querySelector(".harga-awal");
    const hargaAfterDiskonDisplay = document.querySelector(".harga-after-diskon .text-sm");
    const persentaseDisplay = document.querySelector(".persentas span");

    let originalPrice = 0;


    // Load products when page loads
    fetch("/stockopname/api/products_select.php")
      .then(response => response.json())
      .then(data => {
        data.forEach(product => {
          let option = document.createElement("option");
          option.value = product.id;
          option.text = `${product.display_name} - Rp. ${parseInt(product.harga).toLocaleString()}`;
          option.dataset.harga = product.harga;
          productSelect.add(option);
        });
      });

    // Event listener for product selection
    productSelect.addEventListener("change", function () {
      const selectedOption = productSelect.options[productSelect.selectedIndex];
      originalPrice = parseFloat(selectedOption.dataset.harga || 0);

      // Display original price
      hargaAwalDisplay.textContent = `Rp. ${originalPrice.toLocaleString()}`;
      updatePriceDisplay();
    });

    // Event listener for discount selection
    diskonSelect.addEventListener("change", function () {
      updatePriceDisplay();
    });

    function updatePriceDisplay() {
      const increasePercentage = parseFloat(diskonSelect.options[diskonSelect.selectedIndex].text) || 0;

      persentaseDisplay.textContent = `${increasePercentage}%`;
      let increasedPrice = originalPrice * (1 + increasePercentage / 100);
      increasedPrice = roundToNearest500(increasedPrice);

      hargaAfterDiskonDisplay.textContent = `Rp. ${increasedPrice.toLocaleString()}`;
    }

    function roundToNearest500(price) {
      return Math.round(price / 500) * 500;
    }
  });


  document.addEventListener("DOMContentLoaded", function () {
    // Handle form submission
    document.getElementById("addStockForm").addEventListener("submit", function (e) {
      e.preventDefault();

      // Get the CSRF token and other general form data
      const csrf_token = document.getElementById("csrf_token").value;
      const cityId = document.getElementById("citySelect").value;
      const storeId = document.getElementById("storeSelect").value;
      const discount = document.getElementById("diskonSelect").value;
      const tanggal = document.getElementById("tanggal").value;

      // Prepare the array for products
      const products = [];
      const productRows = document.querySelectorAll("#soDetailContainer .so-detail-row");

      productRows.forEach(row => {
        const productId = row.querySelector(".productSelect").value;
        const harga = row.querySelector(".hargaInput").value.replace(/,/g, ""); // Remove commas for numbers
        const jumlah = row.querySelector(".jumlahInput").value;

        // Add the product data to the array
        products.push({
          product_id: productId,
          harga: harga,
          jumlah: jumlah
        });
      });

      // Prepare the final data object to send
      const formData = {
        csrf_token: csrf_token,
        city_id: cityId,
        store_id: storeId,
        discount: discount,
        tanggal: tanggal,
        products: products // Include the array of products
      };

      // Send data to the API
      fetch("/stockopname/api/addPengiriman.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(formData)
      })
        .then(response => response.json())
        .then(data => {
          if (data.status === 'success') {  // Pastikan 'success' sesuai dengan format dari API
            alert("Data saved successfully!");
            // Optionally clear the form or reload the page
          } else {
            alert("Failed to save data. " + (data.message || "Unknown error occurred."));
          }
        })
        .catch(error => {
          console.error("Error:", error);
          alert("An error occurred while saving data.");
        });
    });
  });





  document.addEventListener("DOMContentLoaded", function () {
    // Load cities when page loads
    fetch("/stockopname//api/cities_select.php")
      .then(response => response.json())
      .then(data => {
        let citySelect = document.getElementById("citySelect");
        data.forEach(city => {
          let option = document.createElement("option");
          option.value = city.id;
          option.text = city.nama_kota;
          citySelect.add(option);
        });
      });

    // Fetch stores based on selected city
    document.getElementById("citySelect").addEventListener("change", function () {
      let cityId = this.value;

      fetch(`/stockopname//api/stores_select.php?city_id=${cityId}`)
        .then(response => response.json())
        .then(data => {
          let storeSelect = document.getElementById("storeSelect");
          storeSelect.innerHTML = '<option selected>Select Store</option>';

          data.forEach(store => {
            let option = document.createElement("option");
            option.value = store.id;
            option.text = store.nama_toko;
            storeSelect.add(option);
          });
        });
    });
  });

  document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.getElementById('csrf_token').value;

    if (!csrfToken) {
      console.error('CSRF token not found');
      return;
    }

    fetch(`/stockopname/api/pengiriman.php?csrf_token=${csrfToken}`, {
      method: 'GET'
    })
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        const tableBody = document.getElementById('stokTable');
        const groupedData = groupBy(data.data, ['nama_toko', 'tanggal']); // Mengelompokkan data

        // Mengisi tabel utama
        Object.keys(groupedData).forEach(key => {
          const [nama_toko, tanggal] = key.split('|');
          const group = groupedData[key];

          // Hitung total jumlah produk
          const totalJumlah = group.reduce((sum, item) => sum + item.jumlah, 0);

          // Ambil tanggal tagihan dari produk pertama dalam grup
          const tglTagihan = group[0].tgl_tagihan;

          // Format tanggal
          const formattedTanggal = formatTanggal(tanggal);
          const formattedTglTagihan = formatTanggal(tglTagihan);

          // Tentukan warna badge berdasarkan tanggal tagihan
          const badgeClass = getBadgeClass(tglTagihan);

          // Membuat baris tabel
          let row = `
                <tr>
                    <td class="p-6 text-sm">${group[0].nama_kota}</td>
                    <td class="p-6 text-sm">${nama_toko}</td>
                    <td class="p-6 text-sm">${formattedTanggal}</td>
                    <td class="p-6 text-sm">${totalJumlah}</td>
                    <td class="p-6 text-sm">
                        <span class="${badgeClass}">${formattedTglTagihan}</span>
                    </td>
                    <td class="p-6 text-sm">
                        <button 
                            class="detail-btn px-4 py-2 bg-blue-500 text-white rounded" 
                            data-detail='${JSON.stringify(group)}'>
                            Detail
                        </button>
                    </td>
                </tr>`;
          tableBody.insertAdjacentHTML('beforeend', row);
        });

        // Event listener untuk tombol detail
        document.querySelectorAll('.detail-btn').forEach(button => {
          button.addEventListener('click', function () {
            const detailData = JSON.parse(this.getAttribute('data-detail'));
            openDetailModal(detailData);
          });
        });
      })
      .catch(error => {
        console.error('Error fetching stock data:', error);
      });

    // Fungsi untuk membuka modal detail
    function openDetailModal(detailData) {
      const detailTableBody = document.getElementById('detailTableBody');
      detailTableBody.innerHTML = ''; // Kosongkan isi sebelumnya

      detailData.forEach(item => {
        let hargaFormatted = new Intl.NumberFormat('id-ID', {
          style: 'currency',
          currency: 'IDR'
        }).format(item.harga);

        let row = `
            <tr>
                <td class="p-4">${item.nama_produk}</td>
                <td class="p-4">${hargaFormatted}</td>
                <td class="p-4">${item.jumlah}</td>
            </tr>`;
        detailTableBody.insertAdjacentHTML('beforeend', row);
      });

      document.getElementById('detailModal').classList.remove('hidden');
    }

    // Event listener untuk tombol tutup modal
    document.getElementById('closeModalBtn').addEventListener('click', function () {
      document.getElementById('detailModal').classList.add('hidden');
    });

    // Fungsi untuk mengelompokkan data berdasarkan properti tertentu
    function groupBy(array, keys) {
      return array.reduce((result, currentValue) => {
        const groupKey = keys.map(key => currentValue[key]).join('|');
        if (!result[groupKey]) {
          result[groupKey] = [];
        }
        result[groupKey].push(currentValue);
        return result;
      }, {});
    }

    function formatTanggal(dateStr) {
      if (!dateStr) return 'Tanggal tidak tersedia'; // Jika tanggal kosong
      const date = new Date(dateStr);
      if (isNaN(date)) return 'Tanggal tidak valid'; // Jika tanggal tidak valid
      const options = { day: 'numeric', month: 'long', year: 'numeric' };
      return new Intl.DateTimeFormat('id-ID', options).format(date);
    }


    // Fungsi untuk menentukan warna badge
    function getBadgeClass(tglTagihan) {
      const today = new Date();
      const tagihanDate = new Date(tglTagihan);
      const diffInDays = Math.ceil((tagihanDate - today) / (1000 * 60 * 60 * 24));

      if (diffInDays <= 3) {
        return 'bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full';
      } else if (diffInDays <= 7) {
        return 'bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full';
      } else   {
        return 'bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full';
      } 
    }
  });


</script>
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
            <select id="produkSelect"
              class="py-2 px-3 pe-9 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500">
              <option selected>Select Produk</option>
            </select>

            <select id="bakerSelect"
              class="py-2 px-3 pe-9 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500">
              <option selected>Select Pembuat</option>
            </select>
          </div>
        </div>
      </div>

      <!-- SO Detail Section -->
      <div class="py-6 first:pt-0 last:pb-0 border-t first:border-transparent border-gray-200">
        <label class="inline-block text-sm font-medium">SO Detail</label>
        <div class="mt-2 space-y-3">
          <div class="flex flex-col sm:flex-row gap-3">
            <input type="text" id="jumlah" name="jumlah"
              class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500"
              placeholder="Jumlah">
            <input type="date" id="tanggal"
              class="py-2 px-3 pe-11 block w-80 border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500"
              placeholder="Tanggal Masuk">
          </div>

          <div class="flex flex-col sm:flex-row gap-3">
            <input type="text" id="Jumlah Adonan" name="Jumlah Adonan"
              class="py-2 px-3 pe-11 block w-lg border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500"
              placeholder="Jumlah">
            <p class="text-gray-700 font-bold">Adonan</p>
          </div>
        </div>
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

    <!-- </d -->
  </div>
  <!-- End Card -->
</div>
<!-- End Card Section -->
<!-- Card Section -->
<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-2 mx-auto">
  <!-- Grid -->
  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">

    <!-- Card -->
    <div class="flex flex-col space-x-4 w-full bg-white border shadow-sm rounded-xl" style="width: 60vh !important;">
      <div class="p-4 md:p-5">
        <div class="flex justify-between">
          <div class="flex items-center gap-x-2">
            <p class="text-xs uppercase tracking-wide text-gray-500">
              roti yang dihasilkan
            </p>
          </div>
          <div class="">
            <div class="m-1 hs-dropdown [--trigger:hover] relative inline-flex">
              <button id="hs-dropdown-hover-event" type="button"
                class="hs-dropdown-toggle py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none"
                aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                Select Date
                <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                  height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="m6 9 6 6 6-6" />
                </svg>
              </button>
              <div
                class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg mt-2 after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full before:h-4 before:absolute before:-top-4 before:start-0 before:w-full"
                role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-hover-event">
                <div class="p-1 space-y-0.5">
                  <!-- Date options will be dynamically populated here -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="mt-1 flex items-center gap-x-2">
      <h3 class="text-xl sm:text-2xl font-medium text-gray-800" id="tableBody">
        23
      </h3>
    </div>
  </div>
</div>
</div>
<!-- End Grid -->
</div>
<!-- End Card Section -->


<!-- Table Section -->
<div class="max-w-[85rem] py-4 sm:px-6 lg:px-8 lg:py-4 mx-auto">
  <!-- Card -->
  <div class="flex flex-col">
    <div class="-m-1.5 overflow-x-auto">
      <div class="p-1.5 min-w-full inline-block align-middle">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
          <!-- Header -->
          <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200">
            <div>
              <h2 class="text-xl font-semibold text-gray-800">
                Pabrik
              </h2>
              <p class="text-sm text-gray-600">
                Add, edit and more.
              </p>
            </div>

            <div>
              <div class="inline-flex gap-x-2">
                <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                  href="#">
                  <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="M12 5v14" />
                  </svg>
                  Add Data
                </a>
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
                      Produk
                    </span>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                      Toko
                    </span>
                  </div>
                </th>
                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                      Kemasan
                    </span>
                  </div>
                </th>
                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                      Ukuran
                    </span>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                      Produk Laku
                    </span>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                      Nominal Laku
                    </span>
                  </div>
                </th>

                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                      Tanggal Keluar
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

  document.addEventListener("DOMContentLoaded", function () {
    // Load products when the page loads
    loadProducts();

    // Event listener for when the product is changed
    document.getElementById("produkSelect").addEventListener("change", function () {
      console.log("Selected product ID:", this.value);
    });
  });

  function loadProducts() {
    const productSelect = document.getElementById("produkSelect");

    // Clear existing options to avoid duplicates
    productSelect.innerHTML = '<option selected>Select Produk</option>';

    fetch("/stockopname/api/products_select.php")
      .then(response => {
        if (!response.ok) {
          throw new Error(`Network response was not ok: ${response.statusText}`);
        }
        return response.json();
      })
      .then(data => {
        console.log("Fetched data:", data); // Check the fetched data

        if (!Array.isArray(data)) {
          console.error("Expected an array but received:", data);
          return;
        }

        data.forEach(product => {
          let option = document.createElement("option");
          option.value = product.id;
          option.text = product.display_name;
          productSelect.add(option);
        });
      })
      .catch(error => {
        console.error("Error fetching products:", error);
      });
  }



  document.addEventListener("DOMContentLoaded", function () {
    fetch("/stockopname/api/selectDates.php")
      .then(response => response.json())
      .then(data => {
        const dropdownMenu = document.querySelector(".hs-dropdown-menu .p-1");

        // Populate dropdown with dates
        data.forEach(dateObj => {
          let dateLink = document.createElement("a");
          dateLink.className = "flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100";
          dateLink.href = "#";

          // Parse the date and format as "9 November 2024"
          const parsedDate = new Date(dateObj.tanggal);
          const options = { day: 'numeric', month: 'long', year: 'numeric' };

          // Check if the date is valid
          dateLink.textContent = !isNaN(parsedDate) ? parsedDate.toLocaleDateString('id-ID', options) : "Invalid Date";

          dropdownMenu.appendChild(dateLink);
        });
      })
      .catch(error => console.error("Error fetching dates:", error));
  });


  document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.getElementById('csrf_token').value;

    if (!csrfToken) {
      console.error('CSRF token not found');
      return;
    }

    fetch(`/stockopname/api/get_pabrik.php?csrf_token=${csrfToken}`, {
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
        data.data.forEach(item => {
          // Memformat harga menjadi ribuan
          let hargaKeluarFormatted = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
          }).format(item.nominal);

          // Memformat tanggal ke format tanggal bulan tahun
          let tanggalKeluarFormatted = new Date(item.tanggal).toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
          });

          // Membuat baris tabel dengan data yang diformat
          let row = `
                <tr>
                    <td class="p-6 text-sm">${item.nama_produk}</td>
                    <td class="p-6 text-sm">${item.nama_toko}</td>
                    <td class="p-6 text-sm">${item.kemasan}</td>
                    <td class="p-6 text-sm">${item.ukuran_stoples}</td>
                    <td class="p-6 text-sm">${item.hasil_roti}</td>
                    <td class="p-6 text-sm">${hargaKeluarFormatted}</td>
                    <td class="p-6 text-sm">${tanggalKeluarFormatted}</td>
                </tr>`;
          tableBody.insertAdjacentHTML('beforeend', row); // Menambahkan baris baru ke tabel
        });
      })
      .catch(error => {
        console.error('Error fetching stock data:', error);
      });
  });
</script>
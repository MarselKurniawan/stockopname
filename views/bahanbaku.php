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
<div class="max-w-[85rem] px-4 py-4 sm:px-6 lg:px-8 lg:py-2 mx-auto">
  <!-- Grid -->
  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
    <!-- Card -->
    <div class="flex flex-col bg-white border shadow-sm rounded-xl">
      <div class="p-4 md:p-5">
        <div class="flex items-center gap-x-2">
          <p class="text-xs uppercase font-semibold tracking-wide text-gray-500">
            Roti yang dihasilkan / <span style="font-size: 10px !important;">hari</span>
          </p>
          <div class="hs-tooltip">
            <div class="hs-tooltip-toggle">
              <svg class="shrink-0 size-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                <path d="M12 17h.01" />
              </svg>
              <span
                class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-xs font-medium text-white rounded shadow-sm"
                role="tooltip">
                The number of daily users
              </span>
            </div>
          </div>


        </div>

        <div class="mt-1 flex items-center gap-x-2">

          <h3 class="text-xl sm:text-2xl font-medium text-gray-800">
            540
          </h3>
          <span class="flex items-center gap-x-1 text-green-600">
            <svg class="inline-block size-4 self-center" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
              viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
              stroke-linejoin="round">
              <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
              <polyline points="16 7 22 7 22 13" />
            </svg>
            <span class="inline-block text-sm">
              1.7%
            </span>
          </span>
        </div>
      </div>
    </div>
    <!-- End Card -->
  </div>
  <!-- End Grid -->
</div>
<!-- End Card Section -->


<!-- Card Section -->
<div class="max-w-8xl px-2 py-4 sm:px-6 lg:px-8 lg:py-14 mx-auto " id="form-div">
  <!-- Card -->
  <div class="bg-white rounded-xl shadow p-4 sm:p-7">
    <div class="text-center mb-8">
      <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
        Input Barang
      </h2>
    </div>
    <form id="addBahanForm" class="space-y-3">
      <input type="hidden" id="csrf_token" value="<?php echo generate_csrf_token(); ?>">
      <div class="py-4 first:pt-0 last:pb-0">
        <label class="inline-block text-sm font-medium">Source</label>
        <div class="mt-2 space-y-3">
          <div class="flex flex-col sm:flex-row gap-3">
            <select id="supplierSelect"
              class="py-2 px-3 pe-9 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500">
              <option selected>Select Supplier</option>
            </select>
          </div>
        </div>
      </div>

      <!-- SO Detail Section -->
      <div class="py-6 first:pt-0 last:pb-0 border-t first:border-transparent border-gray-200">
        <label class="inline-block text-sm font-medium">Detail</label>
        <div class="mt-2 space-y-3">
          <div class="flex flex-col sm:flex-row gap-3">
            <div class="">
              <select id="bahanSelect"
                class="hidden py-2 px-3 pe-9 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500">
                <option selected>Select Bahan</option>
              </select>
              <input type="text" id="namaBahanInput" name="Nama Bahan"
                class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500"
                placeholder="Nama Bahan">
              <span class="text-sm" id="stok-add-element">Tambah Jumlah Stok Produk?</span>
            </div>
            <div class="">
              <select id="satuanSelect"
                class="py-2 px-3 pe-9 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500">
                <option selected>Select Satuan</option>
                <option value="kg">Kg</option>
                <option value="butir">Butir</option>
                <option value="liter">Liter</option>
                <option value="pieces">Pieces</option>
              </select>
              <input type="text" id="" name="" class="hidden" placeholder="">
              <span class="text-sm" id="stok-add-element"></span>
            </div>
          </div>
          <div class="flex flex-col sm:flex-row gap-3">
            <input type="text" id="jumlah" name="jumlah"
              class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500"
              placeholder="Jumlah">
            <input type="number" inputmode="numeric" id="hargaBeli" name="hargaBeli"
              class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500"
              placeholder="Harga Beli">
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
    <!-- </div> -->
  </div>
  <!-- End Card -->
</div>

<!-- Table Section -->
<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
  <!-- Card -->
  <div class="flex flex-col">
    <div class="-m-1.5 overflow-x-auto">
      <div class="p-1.5 min-w-full inline-block align-middle">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
          <!-- Header -->
          <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200">
            <div>
              <h2 class="text-xl font-semibold text-gray-800">
                Bahan Baku
              </h2>
              <p class="text-sm text-gray-600">
                Add Bahan Baku, edit and more.
              </p>
            </div>

            <div>
              <div class="inline-flex gap-x-2">
                <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50"
                  href="#">
                  View all
                </a>
                <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                  href="#">
                  <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="M12 5v14" />
                  </svg>
                  Add Bahan Baku
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
                      Nama Bahan
                    </span>
                  </div>
                </th>

                  <!-- <th scope="col" class="px-6 py-3 text-start">
                    <div class="flex items-center gap-x-2">
                      <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                        Jumlah
                      </span>
                    </div>
                  </th>

                  <th scope="col" class="px-6 py-3 text-start">
                    <div class="flex items-center gap-x-2">
                      <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                        Stok Masuk
                      </span>
                    </div>
                  </th>

                  <th scope="col" class="px-6 py-3 text-start">
                    <div class="flex items-center gap-x-2">
                      <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                        Stok Awal
                      </span>
                    </div>
                  </th>

                  <th scope="col" class="px-6 py-3 text-start">
                    <div class="flex items-center gap-x-2">
                      <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                        Stok Akhir
                      </span>
                    </div>
                  </th> -->

                <th scope="col" class="px-6 py-3 text-start">
                  <div class="flex items-center gap-x-2">
                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                      Harga Beli
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
  document.getElementById('addBahanForm').addEventListener('submit', function (event) {
    event.preventDefault();

    const supplierId = document.getElementById('supplierSelect').value;
    const bahanId = document.getElementById('bahanSelect').value;
    const namaBahanInput = document.getElementById('namaBahanInput').value;
    const satuan = document.getElementById('satuanSelect').value;
    const jumlah = document.getElementById('jumlah').value;

    // Check if a bahan is selected or if a new bahan name is entered
    const bahanName = (bahanId && bahanId !== "Select Bahan") ? bahanId : namaBahanInput;

    // Construct data object for submission
    const data = {
      csrf_token: document.getElementById('csrf_token').value,
      supplier_id: supplierId,
      nama_bahan: bahanName,
      satuan: satuan,
      jumlah: jumlah
    };

    // Check if the bahan is being added or updated
    fetch('/stockopname/api/addBahan.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    })
      .then(response => response.json())
      .then(result => {
        if (result.status === 'success') {
          alert('Bahan added/updated successfully');
        } else {
          alert(result.message);
        }
      })
      .catch(error => console.error('Error:', error));
  });


  // Function to check and set the satuan when selecting bahan
  document.getElementById('bahanSelect').addEventListener('change', function () {
    const selectedBahanId = this.value;

    // Fetch the satuan for the selected bahan (you would need to implement this API)
    fetch(`/stockopname/api/getBahanSatuan.php?id=${selectedBahanId}`)
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          document.getElementById('satuanSelect').value = data.satuan;
        }
      })
      .catch(error => console.error('Error fetching satuan:', error));
  });



  const stokAddElement = document.getElementById("stok-add-element");
  const bahanSelect = document.getElementById("bahanSelect");
  const namaBahan = document.getElementById("namaBahanInput");

  // Event listener untuk klik pada stok-add-element
  stokAddElement.addEventListener("click", function () {
    // Toggle visibility
    if (bahanSelect.classList.contains("hidden")) {
      // Jika select tersembunyi, tampilkan select dan sembunyikan input text
      bahanSelect.classList.remove("hidden");
      namaBahan.classList.add("hidden");
      // Ubah teks span
      stokAddElement.textContent = "Masukkan Bahan Baru";
    } else {
      // Jika select terlihat, sembunyikan select dan tampilkan input text
      bahanSelect.classList.add("hidden");
      namaBahan.classList.remove("hidden");
      // Ubah teks span kembali
      stokAddElement.textContent = "Tambah Jumlah Stok Produk?";
    }
  });
  document.addEventListener("DOMContentLoaded", function () {
    // Load products when page loads
    fetch("/stockopname/api/supplier_select.php")
      .then(response => response.json())
      .then(data => {
        let supplierSelect = document.getElementById("supplierSelect");
        data.forEach(product => {
          let option = document.createElement("option");
          option.value = product.id;
          option.text = product.nama;
          supplierSelect.add(option);
        });
      });
  });

  document.addEventListener("DOMContentLoaded", function () {
    // Load products when page loads
    fetch("/stockopname/api/selectBahan.php")
      .then(response => response.json())
      .then(data => {
        let bahanSelect = document.getElementById("bahanSelect");
        data.forEach(product => {
          let option = document.createElement("option");
          option.value = product.id_bahan;
          option.text = product.nama_bahan;
          bahanSelect.add(option);
        });
      });
  });
  document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.getElementById('csrf_token').value;

    if (!csrfToken) {
      console.error('CSRF token not found');
      return;
    }

    fetch(`/stockopname/api/get_bahan.php?csrf_token=${csrfToken}`, {
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
          let hargaBeliFormatted = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
          }).format(item.harga_beli);
          let hargaJualFormatted = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
          }).format(item.harga_jual);

          // Memformat tanggal ke format tanggal bulan tahun
          let tanggalMasukFormatted = new Date(item.tanggal_masuk).toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
          });
          let tanggalKadaluarsaFormatted = new Date(item.tanggal_kadaluarsa).toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
          });

          // Membuat baris tabel dengan data yang diformat
          let row = `
                <tr>
                    <td class="p-6 text-sm">${item.nama_bahan}</td>
                    <td class="p-6 text-sm">${hargaBeliFormatted}</td>
                </tr>`;
          tableBody.insertAdjacentHTML('beforeend', row); // Menambahkan baris baru ke tabel
        });
      })
      .catch(error => {
        console.error('Error fetching stock data:', error);
      });
  });
</script>
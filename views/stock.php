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
<div id="notification" class="fixed top-5 z-[100] right-5 mt-2 bg-teal-100 border border-teal-200 text-sm text-teal-800 rounded-lg p-4 hidden" role="alert" tabindex="-1" aria-labelledby="hs-soft-color-success-label">
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
                        <select id="pengirimanSelect" class="py-2 px-3 pe-9 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500">
                            <option selected>Select Pengiriman</option>
                        </select>
                    </div>
                   <div class="grid md:grid-cols-2 gap-3 p-2" id="detailPengiriman">
    <div>
        <div class="grid space-y-3">
            <dl class="flex flex-col sm:flex-row gap-x-3 text-sm">
                <dt class="min-w-36 max-w-[200px] text-gray-500 dark:text-neutral-500">Nama Toko:</dt>
                <dd class="font-medium text-gray-500">
                    <span class="block font-semibold" id="namaToko">-</span>
                </dd>
            </dl>
            <dl class="flex flex-col sm:flex-row gap-x-3 text-sm">
                <dt class="min-w-36 max-w-[200px] text-gray-500 dark:text-neutral-500">Produk:</dt>
                <dd class="font-medium text-gray-500">
                    <span class="block font-semibold" id="namaProduk">-</span>
                </dd>
            </dl>
        </div>
    </div>

    <div>
        <div class="grid space-y-3">
            <dl class="flex flex-col sm:flex-row gap-x-3 text-sm">
                <dt class="min-w-36 max-w-[200px] text-gray-500 dark:text-neutral-500">Jumlah Stok:</dt>
                <dd class="font-medium text-gray-500">
                    <span id="jumlahStok">-</span>
                </dd>
            </dl>
            <dl class="flex flex-col sm:flex-row gap-x-3 text-sm">
                <dt class="min-w-36 max-w-[200px] text-gray-500 dark:text-neutral-500">Harga:</dt>
                <dd class="font-medium text-gray-500">
                    <span id="harga">-</span>
                </dd>
            </dl>
            <dl class="flex flex-col sm:flex-row gap-x-3 text-sm">
                <dt class="min-w-36 max-w-[200px] text-gray-500 dark:text-neutral-500">Tanggal:</dt>
                <dd class="font-medium text-gray-500">
                    <span id="tanggal">-</span>
                </dd>
            </dl>
        </div>
    </div>
</div>


                </div>
            </div>
            <!-- SO Detail Section -->
            <div class="py-6 first:pt-0 last:pb-0 border-t first:border-transparent border-gray-200">
                <label class="inline-block text-sm font-medium">SO Detail</label>
                <div class="mt-2 space-y-3">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input type="text" id="laku" name="Laku" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500" placeholder="Laku">
                        <span id="lakuError" class="text-red-500 text-sm hidden">Jumlah 'Laku' tidak boleh lebih dari jumlah stok!</span>
                        <input type="date" id="tanggal" class="py-2 px-3 pe-11 block w-80 border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500" placeholder="Tanggal Masuk">
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-5 flex justify-end gap-x-2">
                <button type="button" class="py-2 px-3 text-sm font-medium rounded-lg border bg-white text-gray-800 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="py-2 px-3 text-sm font-medium rounded-lg bg-blue-600 text-white hover:bg-blue-700">Save changes</button>
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
                                Stok
                            </h2>
                            <p class="text-sm text-gray-600">
                                Add stock, edit and more.
                            </p>
                        </div>

                        <div>
                            <div class="inline-flex gap-x-2">
                                <button id="show-form-btn" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" href="#">
                                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14" />
                                        <path d="M12 5v14" />
                                    </svg>
                                    Add Stock
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
                                            Nama Produk
                                        </span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                                            Harga
                                        </span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                                            Stok
                                        </span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                                            Laku
                                        </span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                                            Laku Nominal
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
                                <button type="button" class="py-1.5 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50">
                                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m15 18-6-6 6-6" />
                                    </svg>
                                    Prev
                                </button>

                                <button type="button" class="py-1.5 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50">
                                    Next
                                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
    document.addEventListener("DOMContentLoaded", function() {
        // Handle form submission
        document.getElementById("addStockForm").addEventListener("submit", function(e) {
            e.preventDefault();

            // Get the form data
            const csrf_token = document.getElementById("csrf_token").value;
            const cityId = document.getElementById("pengirimanSelect").value;
            const storeId = document.getElementById("storeSelect").value;
            const productId = document.getElementById("productSelect").value;
            const harga = document.getElementById("harga").value;
            const tanggal = document.getElementById("tanggal").value;
            const jumlah = document.getElementById("jumlah").value;

            // Prepare data to send
            const formData = {
                csrf_token: csrf_token,
                city_id: cityId,
                store_id: storeId,
                product_id: productId,
                harga: harga,
                tanggal: tanggal,
                jumlah: jumlah
            };

            // Send data to addStock.php
            fetch("/stockopname/api/addPengiriman.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') { // Pastikan 'success' sesuai dengan format dari API
                        alert("Data saved successfully!");
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
    document.addEventListener("DOMContentLoaded", function() {
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
        productSelect.addEventListener("change", function() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            originalPrice = parseFloat(selectedOption.dataset.harga || 0);

            // Display original price
            hargaAwalDisplay.textContent = `Rp. ${originalPrice.toLocaleString()}`;
            updatePriceDisplay();
        });

        // Event listener for discount selection
        diskonSelect.addEventListener("change", function() {
            updatePriceDisplay();
        });

        function updatePriceDisplay() {
            const increasePercentage = parseFloat(diskonSelect.options[diskonSelect.selectedIndex].text) || 0;

            // Display the selected percentage
            persentaseDisplay.textContent = `${increasePercentage}%`;

            // Calculate increased price with the selected percentage
            let increasedPrice = originalPrice * (1 + increasePercentage / 100);

            // Round to nearest 500
            increasedPrice = roundToNearest500(increasedPrice);

            // Display the increased price
            hargaAfterDiskonDisplay.textContent = `Rp. ${increasedPrice.toLocaleString()}`;
        }

        // Function to round to the nearest 500
        function roundToNearest500(price) {
            return Math.round(price / 500) * 500;
        }
    });


document.addEventListener("DOMContentLoaded", function() {
    let jumlahStok = 0; // To store the stock amount

    // Fetch pengiriman data and populate the select dropdown
    fetch("/stockopname/api/selectPengiriman.php")
        .then(response => response.json())
        .then(data => {
            let pengirimanSelect = document.getElementById("pengirimanSelect");
            data.forEach(item => {
                let option = document.createElement("option");
                option.value = item.id;
                option.text = item.nama_toko;
                pengirimanSelect.add(option);
            });

            pengirimanSelect.addEventListener("change", function() {
                const selectedId = pengirimanSelect.value;
                const selectedData = data.find(item => item.id == selectedId);
                if (selectedData) {
                    jumlahStok = selectedData.jumlah; // Update the jumlahStok variable

                    document.getElementById("namaToko").textContent = selectedData.nama_toko;
                    document.getElementById("namaProduk").textContent = selectedData.nama_produk;
                    document.getElementById("jumlahStok").textContent = jumlahStok;

                    const hargaFormatted = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(selectedData.harga);
                    document.getElementById("harga").textContent = hargaFormatted;

                    const date = new Date(selectedData.tanggal);
                    const tanggalFormatted = date.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric'
                    });
                    document.getElementById("tanggal").textContent = tanggalFormatted;
                }
            });
        });

    // Check if the "Laku" input exceeds the "jumlahStok"
    const lakuInput = document.getElementById("laku");
    const lakuError = document.getElementById("lakuError");

    lakuInput.addEventListener("input", function() {
        if (parseInt(lakuInput.value) > jumlahStok) {
            // Show the error message and add red border
            lakuError.classList.remove("hidden");
            lakuInput.classList.add("border-red-500");
        } else {
            // Hide the error message and remove red border if within limit
            lakuError.classList.add("hidden");
            lakuInput.classList.remove("border-red-500");
        }
    });
});





    document.getElementById("show-form-btn").addEventListener("click", function() {
        // Tampilkan form dengan animasi naik
        const formDiv = document.getElementById("form-div");
        formDiv.classList.remove("hidden", "opacity-0", "-translate-y-5");
        formDiv.classList.add("opacity-100", "translate-y-0");

        // Tampilkan notifikasi di pojok kanan atas
        const notification = document.getElementById("notification");
        notification.classList.remove("hidden");

        // Sembunyikan notifikasi setelah beberapa detik
        setTimeout(function() {
            notification.classList.add("hidden");
        }, 3000); // Notifikasi akan hilang setelah 3 detik
    });

    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.getElementById('csrf_token').value;

        if (!csrfToken) {
            console.error('CSRF token not found');
            return;
        }

        fetch(`/stockopname/api/pesngiriman.php?csrf_token=${csrfToken}`, {
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
                    let hargaFormatted = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(item.harga);

                    // Memformat tanggal ke format tanggal bulan tahun
                    let tanggalFormatted = new Date(item.tanggal).toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });


                    // Membuat baris tabel dengan data yang diformat
                    let row = `
                <tr>
                    <td class="p-6 text-sm">${item.nama_kota}</td>
                    <td class="p-6 text-sm">${item.nama_toko}</td>
                    <td class="p-6 text-sm">${item.nama_produk}</td>
                    <td class="p-6 text-sm">${hargaFormatted}</td>
                    <td class="p-6 text-sm">${item.jumlah}</td>
                    <td class="p-6 text-sm">${tanggalFormatted}</td>

                </tr>`;
                    tableBody.insertAdjacentHTML('beforeend', row); // Menambahkan baris baru ke tabel
                });
            })
            .catch(error => {
                console.error('Error fetching stock data:', error);
            });
    });
</script>
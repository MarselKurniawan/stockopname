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


<!-- Full-Screen Modal -->
<div id="fullScreenModal"
    class="hidden fixed inset-0 top-0 z-[80] flex justify-center items-center overflow-x-hidden overflow-y-auto">
    <div class="w-full h-full bg-white top-0 flex flex-col">
        <!-- Modal Header -->
        <div class="flex justify-between items-center py-3 px-4 border-b">
            <h3 class="font-bold text-gray-800 uppercase text-2xl">
                Form Retur
            </h3>
            <button id="closeModalBtn"
                class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none">
                <span class="sr-only">Close</span>
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </button>
        </div>
        <!-- Modal Content -->
        <div class="p-4 overflow-y-auto">
            <!-- SO Detail Section -->
            <div class="py-6 first:pt-0 last:pb-0 border-t first:border-transparent border-gray-200">
                <label class="inline-block text-sm font-medium">SO Detail</label>
                <div id="soDetailContainer" class="mt-2 space-y-3">
                    <!-- Baris Produk 1 -->
                    <div id="soDetailContainer" class="mt-2 space-y-3">
                        <!-- Baris Produk 1 -->
                    </div>

                </div>

                <!-- Button Tambah Produk -->
                <button type="button" id="addProductRow"
                    class="mt-3 py-1.5 px-3 text-sm font-medium rounded-lg bg-green-600 text-white hover:bg-green-700">
                    + Tambah Produk
                </button>
            </div>

        </div>
        <!-- Modal Footer -->
        <div class="flex justify-end items-center gap-x-2 py-3 px-4 mt-auto border-t ">
            <button id="closeModalFooterBtn"
                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none ">
                Close
            </button>
            <button type="button"
                class="save-changes py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                Save changes
            </button>
        </div>
    </div>
</div>
<!-- Pastikan ada tempat untuk menyimpan token CSRF -->
<div id="notification"
    class="fixed top-5 z-[100] right-5 mt-2 bg-teal-100 border border-teal-200 text-sm text-teal-800 rounded-lg p-4 hidden"
    role="alert" tabindex="-1" aria-labelledby="hs-soft-color-success-label">
    <span id="hs-soft-color-success-label" class="font-bold">Alert!</span> Anda sedang menambah stok.
</div>




<div class="max-w-8xl">

    <div class="max-w-8xl hidden px-4 sm:px-6 lg:px-8 mx-auto my-4 sm:my-10" id="contentToPrint">
        <div class="sm:w-11/12 max-w-8xl lg:w-3/4 mx-auto">
            <!-- Card -->
            <div class="flex flex-col p-4 sm:p-10 bg-white max-w-8xl rounded-xl">
                <div class="py-12 flex justify-end gap-x-3" id="buttonTriggered">
                    <button id="openModalBtn"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50">
                        <svg class="size-4 text-gray-800" width="24" height="24" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 12h14m-7 7V5" />
                        </svg>
                        Input Retur
                    </button>
                    <button id="selesaiBtn"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-green-200 bg-white text-green-800 shadow-sm hover:bg-green-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-green-50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        Selesai
                    </button>
                    <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                        href="javascript:void(0);" onclick="printElement('contentToPrint', ['buttonTriggered'])">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 6 2 18 2 18 9" />
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                            <rect width="12" height="8" x="6" y="14" />
                        </svg>
                        Print
                    </a>
                </div>
                <!-- Grid -->
                <div class="flex justify-between">
                    <div>
                        <h1 class="mt-2 text-2xl md:text-3xl font-semibold text-green-900">RISNA</h1>
                        <span class="text-gray-500 text-sm">Bakkery & Cookies.</span>
                        <address class="not-italic text-sm text-gray-800">
                            DEP. KES. RI. P-IRT NO. 2053374020970-28<br>
                            TELP (024) 8442782-8445719 SEMARANG<br>
                            FAX. (024) 8442782<br>
                        </address>
                    </div>
                    <!-- Col -->

                    <div class="text-end">
                        <h2 class="text-2xl md:text-3xl font-semibold text-gray-800">Invoice #</h2>
                        <span class="mt-1 block text-gray-500 modal-title">3682303</span>
                    </div>
                    <!-- Col -->
                </div>
                <!-- End Grid -->

                <!-- Grid -->
                <div class="mt-8 grid sm:grid-cols-2 gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Bill to:</h3>
                        <h3 class="text-lg font-semibold text-gray-800 modal-toko"></h3>
                    </div>
                    <!-- Col -->

                    <div class="sm:text-end space-y-2">
                        <!-- Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-1 gap-3 sm:gap-2">
                            <dl class="grid sm:grid-cols-5 gap-x-3">
                                <dt class="col-span-3 font-semibold text-gray-800">Invoice date:</dt>
                                <dd class="col-span-2 text-gray-500 modal-tanggal"></dd>
                            </dl>
                        </div>
                        <!-- End Grid -->
                    </div>
                    <!-- Col -->
                </div>
                <!-- End Grid -->

                <!-- Table -->
                <div class="mt-6">
                    <div class="border border-gray-200 p-4 rounded-lg space-y-4">
                        <div class="hidden sm:grid sm:grid-cols-5">
                            <div class="sm:col-span-2 text-xs font-medium text-gray-500 uppercase">Nama Barang</div>
                            <div class="text-start text-xs font-medium text-gray-500 uppercase">Qty</div>
                            <div class="text-start text-xs font-medium text-gray-500 uppercase">Harga</div>
                            <div class="text-end text-xs font-medium text-gray-500 uppercase">Jumlah</div>
                        </div>

                        <div class="hidden sm:block border-b border-gray-200"></div>

                        <div class="modal-content">

                        </div>
                    </div>
                </div>
                <!-- End Table -->

                <!-- Flex -->
                <div class="mt-8 flex sm:justify-end">
                    <div class="w-full max-w-2xl sm:text-end space-y-2">
                        <!-- Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-1 gap-3 sm:gap-2 modal-total">

                        </div>
                        <!-- End Grid -->
                    </div>
                </div>
                <!-- End Flex -->

                <div class="flex justify-between py-8">
                    <div class="">
                        <h2>Tanda Terima</h2>
                    </div>
                    <div class="">
                        <h2>Hormat Kami</h2>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
<!-- End Invoice -->

<!-- Card Section -->
<div class="max-w-8xl px-2 py-4 sm:px-6 lg:px-8 mx-auto" id="form-div">
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
                        <select id="pengirimanSelect"
                            class="py-2 px-3 pe-9 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500">
                            <option selected>Select Pengiriman</option>
                        </select>
                    </div>

                    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
                        <!-- Grid -->
                        <div class="grid sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-6"
                            id="detailPengiriman">

                        </div>
                        <!-- End Grid -->
                    </div>
                </div>
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
                    <div
                        class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200">
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
                                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                                            Tanggal Pengiriman
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                                            Jumlah Retur
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                                            Tagihan Retur
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                                            Status Pengiriman
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-end">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                                            Aksi
                                        </span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <input type="hidden" id="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        <tbody class="divide-y p-6 divide-gray-200" id="pembayaranTable">
                            <!-- Data akan diisi secara dinamis oleh JavaScript -->
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
                                <button id="closeModalBtn"
                                    class="px-4 py-2 bg-red-500 text-white rounded">Tutup</button>
                            </div>
                        </div>
                    </div>
                    <!-- Footer -->
                    <div
                        class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200">
                        <div>
                            <p class="text-sm text-gray-600">
                                <span class="font-semibold text-gray-800">12</span> results
                            </p>
                        </div>

                        <div>
                            <div class="inline-flex gap-x-2">
                                <button type="button"
                                    class="py-1.5 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50">
                                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m15 18-6-6 6-6" />
                                    </svg>
                                    Prev
                                </button>

                                <button type="button"
                                    class="py-1.5 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50">
                                    Next
                                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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

<!-- Modal Produk -->
<div id="modalProduk" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6">
        <h2 class="text-lg font-bold mb-4">Detail Produk</h2>
        <div id="modalProdukContent"></div>
        <button onclick="document.getElementById('modalProduk').classList.add('hidden');"
            class="mt-4 bg-red-500 text-white px-4 py-2 rounded">Tutup</button>
    </div>
</div>

<!-- Modal Retur -->
<div id="modalRetur" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6">
        <h2 class="text-lg font-bold mb-4">Detail Retur</h2>
        <div id="modalReturContent"></div>
        <button onclick="document.getElementById('modalRetur').classList.add('hidden');"
            class="mt-4 bg-red-500 text-white px-4 py-2 rounded">Tutup</button>
    </div>
</div>




<script>
    function printElement(elementId, excludedElementIds = []) {
        const element = document.getElementById(elementId); // Ambil elemen berdasarkan ID
        const printWindow = window.open('', '', 'width=800,height=600'); // Buat jendela cetak baru

        let excludeCSS = excludedElementIds.map(id => `#${id} { display: none !important; }`).join(" ");

        // Mendapatkan tanggal saat ini dalam forma t  D D -MM-YYYY
        const today = new Date();
        const day = String(today.getDate()).padStart(2, '0'); // Format dua digit
        const month = String(today.getMonth() + 1).padStart(2, '0'); // Format dua digit
        const year = today.getFullYear();
        const formattedDate = `${day}-${month}-${year}`;

        // Tu lis struktur HTML lengkap termasuk link ke CDN Tailwind
        printWindow.document.write(`
        <html>
        <head>
            <title>INV_${formattedDate}</title>
            <!-- Tambahkan CDN Tailwind -->
            <link rel="stylesheet" href="https://localhost/stockopname/assets/css/font.css">
            <link rel="stylesheet" href="/stockopname/assets/dist/output.css">
            <link rel="stylesheet" href="https://localhost/stockopname/assets/css/element.css">
            <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
            <style>
                ${excludeCSS} /* Tambahkan gaya untuk elemen yang dikecualikan */
            </style>
        </head>
        <body class="bg-white text-black">
            ${element.innerHTML} <!-- Salin konten elemen -->
        </body>
        </html>
    `);

        printWindow.document.close(); // Tutup dokumen cetak
        printWindow.focus(); // Fokus ke jendela cetak

        // Jika menggunakan "Save as PDF", browser akan menggunakan judul halaman sebagai nama file.
        printWindow.print(); // Cetak
        printWindow.close(); // Tutup jendela cetak
    }




    function showNotification(message) {
        const notification = document.getElementById("notification");
        const notificationLabel = document.getElementById("hs-soft-color-success-label");

        notificationLabel.textContent = "Sukses!";
        notification.innerHTML += ` ${message}`; // Tambahkan pesan
        notification.classList.remove("hidden"); // Tampilkan notifikasi
        // Sembunyikan notifikasi setelah 3 detik dan refresh halaman
        setTimeout(() => {
            notification.classList.add("hidden");
            location.reload(); // Refresh halaman
        }, 3000);
    }

    document.addEventListener("DOMContentLoaded", function () {
        const pengirimanSelect = document.getElementById("pengirimanSelect");
        const detailPengirimanDiv = document.getElementById("detailPengiriman");

        let pengirimanData = [];
        let groupedData = {};

        fetch("/stockopname/api/selectPengiriman.php")
            .then(response => response.json())
            .then(data => {
                console.log("Fetched data:", data);

                if (Array.isArray(data) && data.length > 0) {
                    pengirimanData = data;

                    // Tambahkan log untuk data yang digroup
                    groupedData = pengirimanData.reduce((acc, item) => {
                        if (!acc[item.nama_toko]) {
                            acc[item.nama_toko] = [];
                        }
                        acc[item.nama_toko].push(item);
                        return acc;
                    }, {});

                    console.log("Grouped data:", groupedData);

                    Object.keys(groupedData).forEach(toko => {
                        let option = document.createElement("option");
                        option.value = toko;
                        option.text = toko;
                        pengirimanSelect.add(option);
                    });

                    pengirimanSelect.addEventListener("change", function () {
                        const selectedToko = pengirimanSelect.value;
                        console.log("Selected toko:", selectedToko);
                        const tokoData = groupedData[selectedToko];
                        if (tokoData) {
                            displayData(tokoData);
                        }
                    });
                } else {
                    console.error("Invalid data format received from API.");
                }
            })
            .catch(error => console.error("Error fetching pengiriman data:", error));


        function displayData(tokoData) {
            // Group data by date
            const groupedByDate = tokoData.reduce((acc, item) => {
                const dateKey = item.tanggal; // Use raw date as key
                if (!acc[dateKey]) {
                    acc[dateKey] = [];
                }
                acc[dateKey].push(item);
                return acc;
            }, {});

            // Clear previous content
            detailPengirimanDiv.innerHTML = "";

            // Display grouped data
            Object.keys(groupedByDate).forEach(date => {
                const dateData = groupedByDate[date];
                const formattedDate = new Date(date).toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric',
                });

                const detailCard = document.createElement("div");
                detailCard.innerHTML = `
            <a class="group flex flex-col bg-white border shadow-sm rounded-xl hover:shadow-md focus:outline-none focus:shadow-md transition cursor-pointer"
                data-date="${date}">
                <div class="p-4 md:p-5">
                    <div class="flex justify-between items-center gap-x-3">
                        <div class="grow">
                            <h3 class="group-hover:text-blue-600 font-semibold text-gray-800">
                                ${formattedDate}
                            </h3>
                            <p class="text-sm text-gray-500">
                                ${dateData.length} Produk
                            </p>
                        </div>
                        <div>
                            <svg class="shrink-0 size-5 text-gray-800"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9 18 6-6-6-6" />
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
        `;


                detailCard.querySelector("a").addEventListener("click", () => {
                    const idPengiriman = dateData.length > 0 ? dateData[0].id_pengiriman : null;

                    if (idPengiriman) {
                        localStorage.removeItem('id_pengiriman');  // Pastikan ID lama dihapus
                        localStorage.setItem('id_pengiriman', idPengiriman);


                        openModal(dateData, formattedDate);

                    } else {
                        console.log("ID Pengiriman tidak ditemukan dalam data");
                    }
                });


                // Append the detailCard to the container
                detailPengirimanDiv.appendChild(detailCard);
            });
        }

        function openModal(data, formattedDate) {
            const modal = document.getElementById("contentToPrint");
            if (!modal) {
                console.error("Modal element not found!");
                return;
            }

            const modalTitle = modal.querySelector(".modal-title");
            const modalContent = modal.querySelector(".modal-content");
            const modalTotal = modal.querySelector(".modal-total");
            const modalToko = modal.querySelector(".modal-toko");
            const modalTanggal = modal.querySelector(".modal-tanggal");

            modalTitle.innerHTML = `Detail Produk - ${formattedDate}`;
            modalContent.innerHTML = "";
            modalTotal.innerHTML = "";
            modalToko.innerHTML = "";
            modalTanggal.innerHTML = "";

            const idPengiriman = localStorage.getItem('id_pengiriman'); // ID pengiriman dari localStorage

            fetch(`/stockopname/api/getRetur.php?id_pengiriman=${idPengiriman}`)
                .then(response => response.json())
                .then(returData => {
                    if (returData.status === 'error') {
                        console.error('Failed to fetch retur data:', returData.message);
                        return;
                    }

                    const dataRetur = returData.data;

                    let totalKeseluruhan = data.reduce((sum, item) => sum + (item.harga * item.jumlah), 0);

                    let totalReturNominal = dataRetur.reduce((sum, item) => {
                        let returNominal = parseFloat(item.total_retur_nominal) || 0;
                        return sum + returNominal;
                    }, 0);

                    let totalReturNominalFormatted = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(totalReturNominal);

                    let totalKeseluruhanAfterRetur = totalKeseluruhan - totalReturNominal;

                    let totalKeseluruhanFormatted = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(totalKeseluruhanAfterRetur);

                    let format = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(totalKeseluruhan);



                    // Tambahkan setiap item ke modal content untuk produk biasa
                    data.forEach(item => {
                        // Format harga dan total sebagai IDR
                        let hargaFormatted = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        }).format(item.harga);

                        let totalFormatted = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        }).format(item.harga * item.jumlah);

                        if (data.length > 0) {
                            modalToko.innerHTML = data[0].nama_toko; // Hanya tampilkan nama toko yang pertama
                        }
                        // Fungsi untuk mendapatkan tanggal sekarang dalam format 'DD MMMM YYYY'
                        function getFormattedDate() {
                            const today = new Date();

                            // Array bulan dalam bahasa Indonesia
                            const bulan = [
                                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                            ];

                            const day = today.getDate(); // Mendapatkan tanggal
                            const month = bulan[today.getMonth()]; // Mendapatkan nama bulan
                            const year = today.getFullYear(); // Mendapatkan tahun

                            // Formatkan tanggal menjadi 'DD MMMM YYYY'
                            return `${day} ${month} ${year}`;
                        }

                        // Mendapatkan tanggal sekarang dalam format yang diinginkan
                        const tanggalsekarang = getFormattedDate();

                        // Menampilkan tanggal ke dalam elemen modal
                        modalTanggal.innerHTML = tanggalsekarang;

                        modalContent.innerHTML += `
                                            <div class="grid grid-cols-3 sm:grid-cols-5 gap-2 py-2">
                        <div class="col-span-full sm:col-span-2">
                                                    <h5 class="sm:hidden text-xs font-medium text-gray-500 uppercase">Nama Barang</h5>
                                                    <p class="font-medium text-sm text-gray-800 uppercase">${item.nama_produk}</p>
                        </div>
                        <div>
                            <h5 class="sm:hidden text-xs font-medium text-gray-500 uppercase">Qty</h5>
                            <p class="text-gray-800 text-sm ">${item.jumlah}</p>
                        </div>
                        <div>
                            <h5 class="sm:hidden text-xs font-medium text-gray-500 uppercase">Harga</h5>
                            <p class="text-gray-800 text-sm">${hargaFormatted}</p>
                        </div>
                        <div>
                            <h5 class="sm:hidden text-xs font-medium text-gray-500 uppercase">Total</h5>
                            <p class="sm:text-end text-gray-800 text-sm">${totalFormatted}</p>
                        </div>
                    </div>
                    <div class="border-b border-gray-200"></div>
                `;
                    });


                    // Tambahkan data retur
                    dataRetur.forEach(item => {
                        // Format harga dan total sebagai IDR
                        let hargaFormatted = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        }).format(item.total_retur_nominal);

                        let totalFormatted = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        }).format(item.total_retur_nominal);

                        let totalReturNominalFormatted = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        }).format(totalReturNominal);

                        // Format total keseluruhan setelah retur ke IDR
                        let totalKeseluruhanFormatted = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        }).format(totalKeseluruhanAfterRetur);


                        // Format total keseluruhan setelah retur ke IDR
                        let format = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        }).format(totalKeseluruhan);


                        modalContent.innerHTML += `
                    <div class="grid grid-cols-3 sm:grid-cols-5 gap-2 py-2">
                        <div class="col-span-full sm:col-span-2">
                            <h5 class="sm:hidden text-xs font-medium text-red-500 uppercase">Nama Barang</h5>
                            <p class="font-medium text-sm text-red-800 uppercase">${item.nama_produk}</p>
                        </div>
                        <div>
                            <h5 class="sm:hidden text-xs font-medium text-red-500 uppercase">Qty</h5>
                            <p class="text-red-800 text-sm ">-${item.jumlah_retur}</p>
                        </div>
                        <div>
                            <h5 class="sm:hidden text-xs font-medium text-red-500 uppercase">Harga</h5>
                            <p class="text-red-800 text-sm">-</p>
                        </div>
                        <div>
                            <h5 class="sm:hidden text-xs font-medium text-red-500 uppercase">Total</h5>
                            <p class="sm:text-end text-red-800 text-sm">-${totalFormatted}</p>
                        </div>
                    </div>
                    <div class="border-b border-gray-200"></div>
                `;
                    });

                    // Tambahkan total keseluruhan ke modal total
                    modalTotal.innerHTML = `
                <dl class="grid sm:grid-cols-5 gap-x-3">
                    <dt class="col-span-3 font-medim text-sm text-gray-600">Total:</dt>
                    <dd class="col-span-2 text-red-500">
                        <div>
                            <span class="text-gray-600 text-sm">
                                ${format}
                            </span>
                        </div>
                    </dd>
                </dl>
                <dl class="grid sm:grid-cols-5 gap-x-3">
                    <dt class="col-span-3 font-medium text-gray-600 text-sm">Total Retur Nominal:</dt>
                    <dd class="col-span-2 text-red-500">
                        <div>
                            <span class="text-sm text-red-800">
                                -${totalReturNominalFormatted}
                            </span>
                        </div>
                    </dd>
                </dl>
                <dl class="grid sm:grid-cols-5 gap-x-3">
                    <dt class="col-span-3 font-semibold text-gray-800">Total Keseluruhan:</dt>
                    <dd class="col-span-2 text-red-500">
                        <div>
                            <span class="py-1 px-2 inline-flex items-center gap-x-1 text-xl font-medium bg-green-100 text-green-800 rounded-full  ">
                                ${totalKeseluruhanFormatted}
                            </span>
                        </div>
                    </dd>
                </dl>
            `;

                    // Tampilkan modal
                    modal.classList.remove("hidden");
                })
                .catch(error => {
                    console.error('Error fetching retur data:', error);
                });
        }




        // Close modal
        function closeModal() {
            const modal = document.getElementById("hs-ai-offcanvas");
            modal.classList.add("hidden");
        }

        // Attach event listeners to close buttons
        document.getElementById("closeModalButton").addEventListener("click", closeModal);
        document.getElementById("closeFooterButton").addEventListener("click", closeModal);

    });

    document.getElementById("selesaiBtn").addEventListener("click", () => {
        const idPengiriman = localStorage.getItem('id_pengiriman'); // Ambil ID pengiriman dari localStorage

        if (!idPengiriman) {
            alert("ID pengiriman tidak ditemukan!");
            return;
        }

        // Kirim data ke server
        fetch('/stockopname/api/updateStatus.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id_pengiriman: idPengiriman,
                status: 'done',
            }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Status berhasil diperbarui menjadi done!');
                    // Lakukan sesuatu, misalnya menutup modal
                    closeModal();
                } else {
                    console.error('Gagal memperbarui status:', data.message);
                    alert('Terjadi kesalahan saat memperbarui status.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal mengirim data ke server.');
            });
    });




    document.getElementById("show-form-btn").addEventListener("click", function () {
        const formDiv = document.getElementById("form-div");
        formDiv.classList.remove("hidden", "opacity-0", "-translate-y-5");
        formDiv.classList.add("opacity-100", "translate-y-0");

        const notification = document.getElementById("notification");
        notification.classList.remove("hidden");

        setTimeout(function () {
            notification.classList.add("hidden");
        }, 3000);
    });

    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.getElementById('csrf_token').value;

        if (!csrfToken) {
            console.error('CSRF token not found');
            return;
        }

        fetch(`/stockopname/api/getPembayaran.php?csrf_token=${csrfToken}`, {
            method: 'GET'
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const tableBody = document.getElementById('pembayaranTable');
                data.data.forEach(item => {
                    // Format tanggal
                    let tanggalFormatted = new Date(item.tanggal_pengiriman).toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });

                    // Format nama produk sebagai list
                    let produkList = item.produk.map(prod => `<li>${prod}</li>`).join('');

                    // Format data retur sebagai list
                    let returList = item.retur.length > 0 ? item.retur.map(retur => `
                    <li>
                        Jumlah: ${retur.jumlah_retur}, 
                        Nominal: ${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(retur.total_retur_nominal)}, 
                        Tanggal: ${new Date(retur.tanggal_retur).toLocaleDateString('id-ID')}
                    </li>
                `).join('') : '-';

                    // Membuat baris tabel
                    let row = `
                <tr>
                    <td class="p-6 text-sm">${item.nama_kota}</td>
                    <td class="p-6 text-sm">${item.nama_toko}</td>
                    <td class="p-6 text-sm">${tanggalFormatted}</td>
                    <td class="p-6 text-sm">${item.produk.length}</td>
                    <td class="p-6 text-sm">${item.retur.length}</td>
                    <td class="p-6 text-sm">${item.status_pengiriman || '-'}</td>
                    <td class="p-6 text-end">
                        <button class="btn-detail-produk bg-blue-500 text-white rounded px-3 py-1" data-produk="${encodeURIComponent(produkList)}">
                            Lihat Produk
                        </button>
                        <button class="btn-detail-retur bg-red-500 text-white rounded px-3 py-1" data-retur="${encodeURIComponent(returList)}">
                            Lihat Retur
                        </button>
                    </td>
                </tr>`;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });

                // Event listener untuk tombol detail produk
                document.querySelectorAll('.btn-detail-produk').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const produk = decodeURIComponent(this.getAttribute('data-produk'));
                        document.getElementById('modalProdukContent').innerHTML = `<ul>${produk}</ul>`;
                        document.getElementById('modalProduk').classList.remove('hidden');
                    });
                });

                // Event listener untuk tombol detail retur
                document.querySelectorAll('.btn-detail-retur').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const retur = decodeURIComponent(this.getAttribute('data-retur'));
                        document.getElementById('modalReturContent').innerHTML = `<ul>${retur}</ul>`;
                        document.getElementById('modalRetur').classList.remove('hidden');
                    });
                });
            })
            .catch(error => {
                console.error('Error fetching stock data:', error);
            });
    });




    document.addEventListener('DOMContentLoaded', function () {
        // Ambil tombol yang membuka modal
        const openModalBtn = document.querySelector('[data-hs-overlay="#hs-full-screen-modal"]');
        // Ambil tombol yang menutup modal
        const closeModalBtn = document.querySelector('[data-hs-overlay="#hs-full-screen-modal"]');
        // Ambil modal itu sendiri
        const modal = document.querySelector('#hs-full-screen-modal');

        // Jika tombol untuk membuka modal ada, tambahkan event listener
        if (openModalBtn) {
            openModalBtn.addEventListener('click', function () {
                modal.classList.remove('hidden'); // Menghilangkan class 'hidden' untuk menunjukkan modal
                modal.classList.add('opacity-100', 'pointer-events-auto'); // Menambahkan animasi dan pointer events
            });
        }

        // Jika tombol untuk menutup modal ada, tambahkan event listener
        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', function () {
                modal.classList.add('hidden'); // Menyembunyikan modal dengan menambah class 'hidden'
                modal.classList.remove('opacity-100', 'pointer-events-auto'); // Menghilangkan animasi dan pointer events
            });
        }
    });
</script>
<script>

    // Array untuk menyimpan ID produk yang sudah dipilih
    let selectedProducts = [];

    document.getElementById('addProductRow').addEventListener('click', function () {
        const container = document.getElementById('soDetailContainer');
        const newRow = document.createElement('div');
        newRow.className = 'so-detail-row flex flex-col sm:flex-row gap-3';

        newRow.innerHTML = `
        <select name="product_id[]"
            class="productSelect py-2 px-3 pe-9 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500">
            <option selected>Select Produk</option>
        </select>
        <input type="text" name="jumlah[]"
            class="jumlahInput py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500"
            placeholder="Jumlah">
        <button type="button" class="removeRow text-red-500 text-sm">Hapus</button>
    `;

        container.appendChild(newRow);

        const idPengiriman = localStorage.getItem('id_pengiriman');
        if (idPengiriman) {
            const productSelect = newRow.querySelector('.productSelect');
            fetch(`/stockopname/api/productSelect_byIdPengiriman.php?id_pengiriman=${idPengiriman}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(product => {
                        if (!selectedProducts.includes(product.id)) {
                            let option = document.createElement("option");
                            option.value = product.id;
                            option.text = `${product.display_name} - Rp. ${parseInt(product.harga).toLocaleString()}`;
                            option.dataset.harga = product.harga;
                            option.dataset.toko_id = product.toko_id;
                            option.dataset.produk_id = product.produk_id;
                            productSelect.add(option);
                        }
                    });
                })
                .catch(error => {
                    console.error('Error fetching products:', error);
                });
        }

        // Event listener for product select change
        const productSelect = newRow.querySelector('.productSelect');
        productSelect.addEventListener('change', function () {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const hargaInput = newRow.querySelector('.hargaInput');
            hargaInput.value = parseFloat(selectedOption.dataset.harga || 0).toLocaleString();

            const selectedProductId = selectedOption.value;
            if (selectedProductId && !selectedProducts.includes(selectedProductId)) {
                selectedProducts.push(selectedProductId);

                // Disable selected products in all selects
                updateProductOptions();
            }
        });

        // Event listener for remove row button
        newRow.querySelector('.removeRow').addEventListener('click', function () {
            const removedProductId = newRow.querySelector('.productSelect').value;
            selectedProducts = selectedProducts.filter(id => id !== removedProductId);

            // Update all product select options after removing a row
            updateProductOptions();

            // Remove the row
            container.removeChild(newRow);
        });
    });

    // Update product select options in all rows based on selectedProducts
    function updateProductOptions() {
        const allProductSelects = document.querySelectorAll('.productSelect');
        allProductSelects.forEach(select => {
            const options = select.querySelectorAll('option');
            options.forEach(option => {
                if (selectedProducts.includes(option.value)) {
                    option.disabled = true; // Disable selected product
                } else {
                    option.disabled = false; // Enable unselected product
                }
            });
        });
    }

    // Handle saving the form data
    document.querySelector('.save-changes').addEventListener('click', function () {
        const formData = [];
        const rows = document.querySelectorAll('.so-detail-row');
        rows.forEach(row => {
            const productSelect = row.querySelector('.productSelect');
            const jumlahInput = row.querySelector('.jumlahInput');
            const productId = productSelect.value;
            const quantity = jumlahInput.value;
            const price = productSelect.options[productSelect.selectedIndex].dataset.harga;
            const produkId = productSelect.options[productSelect.selectedIndex].dataset.produk_id;
            const tokoId = productSelect.options[productSelect.selectedIndex].dataset.toko_id;
            const totalNominal = quantity * price;

            if (productId && quantity) {
                formData.push({
                    id_pengiriman: localStorage.getItem('id_pengiriman'),
                    produk_id: produkId,
                    jumlah: quantity,
                    harga: price,
                    toko_id: tokoId,
                    total_retur_nominal: totalNominal
                });
            }
        });


        // Send data to the API
        fetch('/stockopname/api/addRetur.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        })
            .then(response => response.json())
            .then(data => {
                console.log('Data saved successfully:', data);
                // Handle success (e.g., show a success message or redirect)
            })
            .catch(error => {
                console.error('Error saving data:', error);
                // Handle error (e.g., show an error message)
            });
    });




    // Modal controls
    const openModalBtn = document.getElementById("openModalBtn");
    const closeModalBtn = document.getElementById("closeModalBtn");
    const closeModalFooterBtn = document.getElementById("closeModalFooterBtn");
    const fullScreenModal = document.getElementById("fullScreenModal");

    // Open Modal Function
    const openModal = () => {
        fullScreenModal.classList.remove("hidden");
    };

    // Close Modal Function
    const closeModal = () => {
        window.location.reload();
    };


    // Event Listeners
    openModalBtn.addEventListener("click", openModal);
    closeModalBtn.addEventListener("click", closeModal);
    closeModalFooterBtn.addEventListener("click", closeModal);

</script>
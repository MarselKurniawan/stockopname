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
<div class="max-w-8xl px-2 py-4 sm:px-6 lg:px-8 lg:py-14 mx-auto" id="form-div">
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

                    <div class="filters">
                        <select id="filterBulan" class="py-2 px-3 border-gray-200 shadow-sm rounded-lg">
                            <option value="">Pilih Bulan</option>
                            <option value="1">Januari</option>
                            <option value="2">Februari</option>
                            <option value="3">Maret</option>
                            <option value="4">April</option>
                            <option value="5">Mei</option>
                            <option value="6">Juni</option>
                            <option value="7">Juli</option>
                            <option value="8">Agustus</option>
                            <option value="9">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>

                        <input type="number" id="filterHari" class="py-2 px-3 border-gray-200 shadow-sm rounded-lg"
                            placeholder="Hari" min="1" max="31">

                        <input type="number" id="filterTahun" class="py-2 px-3 border-gray-200 shadow-sm rounded-lg"
                            placeholder="Tahun" min="1900" max="2100">
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

            <!-- Submit Buttons -->
            <div class="mt-5 flex justify-end gap-x-2">
                <button type="button"
                    class="py-2 px-3 text-sm font-medium rounded-lg border bg-white text-gray-800 hover:bg-gray-50">Cancel</button>
                <button type="" id="submitButton"
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
                    <div
                        class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200">
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
                                <button id="show-form-btn"
                                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                                    href="#">
                                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                                            Sisa
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


<div id="hs-ai-offcanvas"
    class="hs-overlay hs-overlay-open:translate-x-0 fixed top-0 end-0 transition-all duration-300 transform h-full max-w-md w-full z-[80] bg-white border-e"
    role="dialog" tabindex="-1" aria-labelledby="hs-ai-offcanvas-label">
    <div
        class="relative overflow-hidden min-h-32 text-center bg-[url('https://preline.co/assets/svg/examples/abstract-bg-1.svg')] bg-no-repeat bg-center">
        <!-- Close Button -->
        <div class="absolute top-2 end-2">
            <button type="button" id="closeModalButton"
                class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none"
                aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>
        <!-- End Close Button -->

        <!-- SVG Background Element -->
        <figure class="absolute inset-x-0 bottom-0 -mb-px">
            <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 1920 100.1">
                <path fill="currentColor" class="fill-white" d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z"></path>
            </svg>
        </figure>
        <!-- End SVG Background Element -->
    </div>

    <div class="relative z-10 -mt-12">
        <!-- Icon -->
        <span
            class="mx-auto flex justify-center items-center size-[62px] rounded-full border border-gray-200 bg-white text-gray-700 shadow-sm">
            <svg class="size-6" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                viewBox="0 0 16 16">
                <path
                    d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27zm.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0l-.509-.51z" />
                <path
                    d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z" />
            </svg>
        </span>
        <!-- End Icon -->
    </div>

    <!-- Body -->
    <div class="p-4 sm:p-7 overflow-y-auto">
        <div class="text-center">
            <h3 id="hs-ai-offcanvas-label" class="text-lg font-semibold text-gray-800">
                Invoice
            </h3>
            <p class="modal-title text-sm text-gray-500">

            </p>
        </div>

        <!-- Grid -->
        <div class="mt-5 sm:mt-10 grid grid-cols-2 sm:grid-cols-3 gap-5 ">
            <div>
                <span class="block text-xs uppercase text-gray-500">Amount paid:</span>
                <span class="block text-sm font-medium text-gray-800">$316.8</span>
            </div>
            <!-- End Col -->
            <!-- Modal Content -->
            <div class="p-4 modal-content">
                <!-- Konten produk akan ditambahkan di sini -->
            </div>
            <div>
                <span class="block text-xs uppercase text-gray-500">Date paid:</span>
                <span class="block text-sm font-medium text-gray-800">April 22, 2020</span>
            </div>
            <!-- End Col -->
        </div>
        <!-- End Grid -->

        <div class="mt-5 sm:mt-10">
            <h4 class="text-xs font-semibold uppercase text-gray-800">Summary</h4>

            <ul class="mt-3 flex flex-col">
                <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
                    <div class="flex items-center justify-between w-full">
                        <span>Payment to Front</span>
                        <span>$264.00</span>
                    </div>
                </li>
                <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
                    <div class="flex items-center justify-between w-full">
                        <span>Tax fee</span>
                        <span>$52.8</span>
                    </div>
                </li>
                <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-semibold bg-gray-50 border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
                    <div class="flex items-center justify-between w-full">
                        <span>Amount paid</span>
                        <span>$316.8</span>
                    </div>
                </li>
            </ul>
            <ul class="mt-3 flex flex-col">
                <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
                    <div class="flex items-center justify-between w-full">
                        <span>Payment to Front</span>
                        <span>$264.00</span>
                    </div>
                </li>
                <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
                    <div class="flex items-center justify-between w-full">
                        <span>Tax fee</span>
                        <span>$52.8</span>
                    </div>
                </li>
                <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-semibold bg-gray-50 border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
                    <div class="flex items-center justify-between w-full">
                        <span>Amount paid</span>
                        <span>$316.8</span>
                    </div>
                </li>
            </ul>
            <ul class="mt-3 flex flex-col">
                <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
                    <div class="flex items-center justify-between w-full">
                        <span>Payment to Front</span>
                        <span>$264.00</span>
                    </div>
                </li>
                <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
                    <div class="flex items-center justify-between w-full">
                        <span>Tax fee</span>
                        <span>$52.8</span>
                    </div>
                </li>
                <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-semibold bg-gray-50 border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
                    <div class="flex items-center justify-between w-full">
                        <span>Amount paid</span>
                        <span>$316.8</span>
                    </div>
                </li>
            </ul>
            <ul class="mt-3 flex flex-col">
                <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
                    <div class="flex items-center justify-between w-full">
                        <span>Payment to Front</span>
                        <span>$264.00</span>
                    </div>
                </li>
                <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
                    <div class="flex items-center justify-between w-full">
                        <span>Tax fee</span>
                        <span>$52.8</span>
                    </div>
                </li>
                <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-semibold bg-gray-50 border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
                    <div class="flex items-center justify-between w-full">
                        <span>Amount paid</span>
                        <span>$316.8</span>
                    </div>
                </li>
            </ul>
            <ul class="mt-3 flex flex-col">
                <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
                    <div class="flex items-center justify-between w-full">
                        <span>Payment to Front</span>
                        <span>$264.00</span>
                    </div>
                </li>
                <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
                    <div class="flex items-center justify-between w-full">
                        <span>Tax fee</span>
                        <span>$52.8</span>
                    </div>
                </li>
                <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-semibold bg-gray-50 border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
                    <div class="flex items-center justify-between w-full">
                        <span>Amount paid</span>
                        <span>$316.8</span>
                    </div>
                </li>
            </ul>
            <ul class="mt-3 flex flex-col">
                <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
                    <div class="flex items-center justify-between w-full">
                        <span>Payment to Front</span>
                        <span>$264.00</span>
                    </div>
                </li>
                <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
                    <div class="flex items-center justify-between w-full">
                        <span>Tax fee</span>
                        <span>$52.8</span>
                    </div>
                </li>
                <li
                    class="inline-flex items-center gap-x-2 py-3 px-4 text-sm font-semibold bg-gray-50 border text-gray-800 -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg">
                    <div class="flex items-center justify-between w-full">
                        <span>Amount paid</span>
                        <span>$316.8</span>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Button -->
        <div class="mt-5 flex justify-end gap-x-2">
            <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50"
                href="#">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                    <polyline points="7 10 12 15 17 10" />
                    <line x1="12" x2="12" y1="15" y2="3" />
                </svg>
                Invoice PDF
            </a>
            <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                href="#">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9" />
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                    <rect width="12" height="8" x="6" y="14" />
                </svg>
                Print
            </a>
        </div>
        <!-- End Buttons -->

        <div class="mt-5 sm:mt-10">
            <p class="text-sm text-gray-500">If you have any questions, please contact us at <a
                    class="inline-flex items-center gap-x-1.5 text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium"
                    href="#">example@site.com</a> or call at <a
                    class="inline-flex items-center gap-x-1.5 text-blue-600 decoration-2 hover:underline focus:outline-none focus:underline font-medium"
                    href="tel:+1898345492">+1 898-34-5492</a></p>
        </div>

        <div class="" id="closeFooterButton">Tutup</div>
    </div>
    <!-- End Body -->
</div>





<script>

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
        const filterBulan = document.getElementById("filterBulan");
        const filterTahun = document.getElementById("filterTahun");
        const filterHari = document.getElementById("filterHari");
        const submitButton = document.getElementById("submitButton");

        let pengirimanData = [];
        let groupedData = {};

        // Fetch data from API
        fetch("/stockopname/api/selectPengiriman.php")
            .then(response => response.json())
            .then(data => {
                console.log("Fetched data:", data);

                if (Array.isArray(data) && data.length > 0) {
                    pengirimanData = data;

                    // Group data by toko
                    groupedData = pengirimanData.reduce((acc, item) => {
                        if (!acc[item.nama_toko]) {
                            acc[item.nama_toko] = [];
                        }
                        acc[item.nama_toko].push(item);
                        return acc;
                    }, {});

                    // Populate dropdown with toko options
                    let defaultOption = document.createElement("option");
                    defaultOption.text = "Select Toko";
                    defaultOption.disabled = true;
                    defaultOption.selected = true;
                    pengirimanSelect.add(defaultOption);

                    Object.keys(groupedData).forEach(toko => {
                        let option = document.createElement("option");
                        option.value = toko;
                        option.text = toko;
                        pengirimanSelect.add(option);
                    });

                    // Listen for change on the toko select
                    pengirimanSelect.addEventListener("change", function () {
                        const selectedToko = pengirimanSelect.value;
                        const tokoData = groupedData[selectedToko];
                        if (tokoData) {
                            filterAndDisplayData(tokoData);
                        }
                    });

                    // Listen for changes on filter inputs
                    filterBulan.addEventListener("change", function () {
                        const selectedToko = pengirimanSelect.value;
                        const tokoData = groupedData[selectedToko];
                        if (tokoData) {
                            filterAndDisplayData(tokoData);
                        }
                    });

                    filterTahun.addEventListener("input", function () {
                        const selectedToko = pengirimanSelect.value;
                        const tokoData = groupedData[selectedToko];
                        if (tokoData) {
                            filterAndDisplayData(tokoData);
                        }
                    });

                    filterHari.addEventListener("input", function () {
                        const selectedToko = pengirimanSelect.value;
                        const tokoData = groupedData[selectedToko];
                        if (tokoData) {
                            filterAndDisplayData(tokoData);
                        }
                    });
                } else {
                    console.error("Invalid data format received from API.");
                }
            })
            .catch(error => console.error("Error fetching pengiriman data:", error));

        function filterAndDisplayData(tokoData) {
            const bulanValue = filterBulan.value;
            const tahunValue = filterTahun.value;
            const hariValue = filterHari.value;

            // Group filtered data by date
            const filteredData = tokoData.filter(item => {
                const itemDate = new Date(item.tanggal);
                const itemMonth = itemDate.getMonth() + 1;
                const itemYear = itemDate.getFullYear();
                const itemDay = itemDate.getDate();

                let matchBulan = true;
                let matchTahun = true;
                let matchHari = true;

                if (bulanValue) {
                    matchBulan = itemMonth === parseInt(bulanValue);
                }
                if (tahunValue) {
                    matchTahun = itemYear === parseInt(tahunValue);
                }
                if (hariValue) {
                    matchHari = itemDay === parseInt(hariValue);
                }

                return matchBulan && matchTahun && matchHari;
            });

            const groupedByDate = filteredData.reduce((acc, item) => {
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
                    month: '2-digit',
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

                // Attach click event to open modal
                detailCard.querySelector("a").addEventListener("click", () => {
                    openModal(dateData, formattedDate);
                });

                detailPengirimanDiv.appendChild(detailCard);
            });
        }

        function openModal(data, formattedDate) {
           

            const modal = document.getElementById("hs-ai-offcanvas");
            if (!modal) {
                console.error("Modal element not found!");
                return;
            }

            const modalTitle = modal.querySelector(".modal-title");
            const modalContent = modal.querySelector(".modal-content");

            modalTitle.innerHTML = `Detail Produk - ${formattedDate}`;
            modalContent.innerHTML = "";

            data.forEach(item => {
                modalContent.innerHTML += `
            <div class="flex justify-between items-center p-4 border-b">
                <span>${item.nama_produk}</span>
                <span>${item.jumlah}</span>
            </div>
        `;
            });

            modal.classList.remove("hidden");

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

        fetch(`/stockopname//api/stock.php?csrf_token=${csrfToken}`, {
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

                    let lakuFormatted = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(item.laku_nominal);

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
                    <td class="p-6 text-sm">${item.stok}</td>
                    <td class="p-6 text-sm">${item.laku}</td>
                    <td class="p-6 text-sm">${item.sisa}</td>
                    <td class="p-6 text-sm">${lakuFormatted}</td>
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
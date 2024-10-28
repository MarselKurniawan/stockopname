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
          <button id="hs-dropdown-hover-event" type="button" class="hs-dropdown-toggle py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
            Actions
            <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
          </button>

          <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg mt-2 after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full before:h-4 before:absolute before:-top-4 before:start-0 before:w-full" role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-hover-event">
            <div class="p-1 space-y-0.5">
              <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100" href="#">
                Newsletter
              </a>
              <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100" href="#">
                Purchases
              </a>
              <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100" href="#">
                Downloads
              </a>
              <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100" href="#">
                Team Account
              </a>
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



<button type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-scroll-inside-body-modal" data-hs-overlay="#hs-scroll-inside-body-modal">
  Scroll inside body
</button>

<div id="hs-scroll-inside-body-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="hs-scroll-inside-body-modal-label">
  <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 h-[calc(100%-3.5rem)] sm:mx-auto">
    <div class="max-h-full overflow-hidden flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
      <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
        <h3 id="hs-scroll-inside-body-modal-label" class="font-bold text-gray-800 dark:text-white">
          Modal title
        </h3>
        <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-400 dark:focus:bg-neutral-600" aria-label="Close" data-hs-overlay="#hs-scroll-inside-body-modal">
          <span class="sr-only">Close</span>
          <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18"></path>
            <path d="m6 6 12 12"></path>
          </svg>
        </button>
      </div>
      <div class="p-4 overflow-y-auto">
        <div class="space-y-4">
          <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Be bold</h3>
            <p class="mt-1 text-gray-800 dark:text-neutral-400">
              Motivate teams to do their best work. Offer best practices to get users going in the right direction. Be bold and offer just enough help to get the work started, and then get out of the way. Give accurate information so users can make educated decisions. Know your user's struggles and desired outcomes and give just enough information to let them get where they need to go.
            </p>
          </div>

          <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Be optimistic</h3>
            <p class="mt-1 text-gray-800 dark:text-neutral-400">
              Focusing on the details gives people confidence in our products. Weave a consistent story across our fabric and be diligent about vocabulary across all messaging by being brand conscious across products to create a seamless flow across all the things. Let people know that they can jump in and start working expecting to find a dependable experience across all the things. Keep teams in the loop about what is happening by informing them of relevant features, products and opportunities for success. Be on the journey with them and highlight the key points that will help them the most - right now. Be in the moment by focusing attention on the important bits first.
            </p>
          </div>

          <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Be practical, with a wink</h3>
            <p class="mt-1 text-gray-800 dark:text-neutral-400">
              Keep our own story short and give teams just enough to get moving. Get to the point and be direct. Be concise - we tell the story of how we can help, but we do it directly and with purpose. Be on the lookout for opportunities and be quick to offer a helping hand. At the same time realize that nobody likes a nosy neighbor. Give the user just enough to know that something awesome is around the corner and then get out of the way. Write clear, accurate, and concise text that makes interfaces more usable and consistent - and builds trust. We strive to write text that is understandable by anyone, anywhere, regardless of their culture or language so that everyone feels they are part of the team.
            </p>
          </div>
        </div>
      </div>
      <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
        <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" data-hs-overlay="#hs-scroll-inside-body-modal">
          Close
        </button>
        <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
          Save changes
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Table Section -->
<div class="max-w-[85rem] px py-4 sm:px-6 lg:px-8 lg:py-4 mx-auto">
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
                <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" href="#">
                  <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
  document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.getElementById('csrf_token').value;

    if (!csrfToken) {
      console.error('CSRF token not found');
      return;
    }

    fetch(`https://localhost/stockopname/api/get_pabrik.php?csrf_token=${csrfToken}`, {
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
          let tanggalKeluarFormatted = new Date(item.tanggal_keluar).toLocaleDateString('id-ID', {
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
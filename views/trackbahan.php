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
<div class="max-w-sm">
  <!-- SearchBox -->
  <div class="relative" data-hs-combo-box='{
    "groupingType": "default",
    "preventSelection": true,
    "isOpenOnFocus": true,
    "groupingTitleTemplate": "<div class=\"block text-xs text-gray-500 m-3 mb-1"></div>"
  }'>
    <div class="relative">
      <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-3.5">
        <svg class="shrink-0 size-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"></circle>
          <path d="m21 21-4.3-4.3"></path>
        </svg>
      </div>
      <input class="py-3 ps-10 pe-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" type="text" role="combobox" aria-expanded="false" placeholder="Search or type a command" value="" data-hs-combo-box-input="">
    </div>

    <!-- SearchBox Dropdown -->
    <div class="absolute z-50 w-full bg-white rounded-xl shadow-[0_10px_40px_10px_rgba(0,0,0,0.08)]" style="display: none;" data-hs-combo-box-output="">
      <div class="max-h-[500px] p-2 overflow-y-auto overflow-hidden [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300" data-hs-combo-box-output-items-wrapper="">
        <div data-hs-combo-box-output-item='{"group": {"name": "recent", "title": "Recent"}}' tabindex="0">
          <a class="py-2 px-3 flex items-center gap-x-3 hover:bg-gray-100 rounded-lg" href="/">
            <svg class="shrink-0 size-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect width="20" height="16" x="2" y="4" rx="2"></rect>
              <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
            </svg>
            <span class="text-sm text-gray-800" data-hs-combo-box-search-text="Compose an email" data-hs-combo-box-value="">Compose an email</span>
            <span class="ms-auto text-xs text-gray-400" data-hs-combo-box-search-text="Gmail" data-hs-combo-box-value="">Gmail</span>
          </a>
        </div>
        <div data-hs-combo-box-output-item='{"group": {"name": "recent", "title": "Recent"}}' tabindex="1">
          <a class="py-2 px-3 flex items-center gap-x-3 hover:bg-gray-100 rounded-lg" href="/">
            <svg class="shrink-0 size-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M14 9a2 2 0 0 1-2 2H6l-4 4V4c0-1.1.9-2 2-2h8a2 2 0 0 1 2 2v5Z"></path>
              <path d="M18 9h2a2 2 0 0 1 2 2v11l-4-4h-6a2 2 0 0 1-2-2v-1"></path>
            </svg>
            <span class="text-sm text-gray-800" data-hs-combo-box-search-text="Start a conversation" data-hs-combo-box-value="">Start a conversation</span>
            <span class="ms-auto text-xs text-gray-400" data-hs-combo-box-search-text="Slack" data-hs-combo-box-value="">Slack</span>
          </a>
        </div>
        <div data-hs-combo-box-output-item='{"group": {"name": "recent", "title": "Recent"}}' tabindex="2">
          <a class="py-2 px-3 flex items-center gap-x-3 hover:bg-gray-100 rounded-lg" href="/">
            <svg class="shrink-0 size-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M5 12h14"></path>
              <path d="M12 5v14"></path>
            </svg>
            <span class="text-sm text-gray-800" data-hs-combo-box-search-text="Create a project" data-hs-combo-box-value="">Create a project</span>
            <span class="ms-auto text-xs text-gray-400" data-hs-combo-box-search-text="Notion" data-hs-combo-box-value="">Notion</span>
          </a>
        </div>
        <div data-hs-combo-box-output-item='{"group": {"name": "people", "title": "People"}}' tabindex="5">
          <a class="py-2 px-2.5 flex items-center gap-x-3 hover:bg-gray-100 rounded-lg focus:outline-none focus:bg-gray-100" href="/">
            <img class="shrink-0 size-5 rounded-full" src="https://images.unsplash.com/photo-1548142813-c348350df52b?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=3&w=320&h=320&q=80" alt="Avatar">
            <span class="text-sm text-gray-800" data-hs-combo-box-search-text="Kim Ya Sung" data-hs-combo-box-value="">Kim Ya Sung</span>
            <span class="ms-auto text-xs text-teal-600" data-hs-combo-box-search-text="Online" data-hs-combo-box-value="">Online</span>
          </a>
        </div>
        <div data-hs-combo-box-output-item='{"group": {"name": "people", "title": "People"}}' tabindex="6">
          <a class="py-2 px-2.5 flex items-center gap-x-3 hover:bg-gray-100 rounded-lg focus:outline-none focus:bg-gray-100" href="/">
            <img class="shrink-0 size-5 rounded-full" src="https://images.unsplash.com/photo-1610186593977-82a3e3696e7f?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=3&w=320&h=320&q=80" alt="Avatar">
            <span class="text-sm text-gray-800" data-hs-combo-box-search-text="Chris Peti" data-hs-combo-box-value="">Chris Peti</span>
            <span class="ms-auto text-xs text-gray-400" data-hs-combo-box-search-text="Offline" data-hs-combo-box-value="">Offline</span>
          </a>
        </div>
        <div data-hs-combo-box-output-item='{"group": {"name": "people", "title": "People"}}' tabindex="7">
          <a class="py-2 px-2.5 flex items-center gap-x-3 hover:bg-gray-100 rounded-lg focus:outline-none focus:bg-gray-100" href="/">
            <img class="shrink-0 size-5 rounded-full" src="https://images.unsplash.com/photo-1568048689711-5e0325cea8c0?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=3&w=320&h=320&q=80" alt="Avatar">
            <span class="text-sm text-gray-800" data-hs-combo-box-search-text="Martin Azara" data-hs-combo-box-value="">Martin Azara</span>
            <span class="ms-auto text-xs text-gray-400" data-hs-combo-box-search-text="Offline" data-hs-combo-box-value="">Offline</span>
          </a>
        </div>
      </div>
    </div>
    <!-- End SearchBox Dropdown -->
  </div>
  <!-- End SearchBox -->
</div>

<!-- Timeline -->
<div class="p-8">
  <!-- Heading -->
  <div class="ps-2 my-2 first:mt-0">
    <h3 class="text-xs font-medium uppercase text-gray-500">
      1 Aug, 2023
    </h3>
  </div>
  <!-- End Heading -->

  <!-- Item -->
  <div class="flex gap-x-3">
    <!-- Icon -->
    <div class="relative last:after:hidden after:absolute after:top-7 after:bottom-0 after:start-3.5 after:w-px after:-translate-x-[0.5px] after:bg-gray-200">
      <div class="relative z-10 size-7 flex justify-center items-center">
        <div class="size-2 rounded-full bg-gray-400"></div>
      </div>
    </div>
    <!-- End Icon -->

    <!-- Right Content -->
    <div class="grow pt-0.5 pb-8">
      <h3 class="flex gap-x-1.5 font-semibold text-gray-800">
        <svg class="shrink-0 size-4 mt-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
          <polyline points="14 2 14 8 20 8"></polyline>
          <line x1="16" x2="8" y1="13" y2="13"></line>
          <line x1="16" x2="8" y1="17" y2="17"></line>
          <line x1="10" x2="8" y1="9" y2="9"></line>
        </svg>
        Menggunakan "Terigu 1kg" Roti 12 
      </h3>
      <button type="button" class="mt-1 -ms-1 p-1 inline-flex items-center gap-x-2 text-xs rounded-lg border border-transparent text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none">
        James Collins
      </button>
    </div>
    <!-- End Right Content -->
  </div>
  <!-- End Item -->
  <!-- Item -->
  <div class="flex gap-x-3">
    <!-- Icon -->
    <div class="relative last:after:hidden after:absolute after:top-7 after:bottom-0 after:start-3.5 after:w-px after:-translate-x-[0.5px] after:bg-gray-200">
      <div class="relative z-10 size-7 flex justify-center items-center">
        <div class="size-2 rounded-full bg-gray-400"></div>
      </div>
    </div>
    <!-- End Icon -->

    <!-- Right Content -->
    <div class="grow pt-0.5 pb-8">
      <h3 class="flex gap-x-1.5 font-semibold text-gray-800">
        <svg class="shrink-0 size-4 mt-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
          <polyline points="14 2 14 8 20 8"></polyline>
          <line x1="16" x2="8" y1="13" y2="13"></line>
          <line x1="16" x2="8" y1="17" y2="17"></line>
          <line x1="10" x2="8" y1="9" y2="9"></line>
        </svg>
        Menggunakan "Terigu 1kg" Roti 12 
      </h3>
      <button type="button" class="mt-1 -ms-1 p-1 inline-flex items-center gap-x-2 text-xs rounded-lg border border-transparent text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none">
        James Collins
      </button>
    </div>
    <!-- End Right Content -->
  </div>
  <!-- End Item -->
  <!-- Item -->
  <div class="flex gap-x-3">
    <!-- Icon -->
    <div class="relative last:after:hidden after:absolute after:top-7 after:bottom-0 after:start-3.5 after:w-px after:-translate-x-[0.5px] after:bg-gray-200">
      <div class="relative z-10 size-7 flex justify-center items-center">
        <div class="size-2 rounded-full bg-gray-400"></div>
      </div>
    </div>
    <!-- End Icon -->

    <!-- Right Content -->
    <div class="grow pt-0.5 pb-8">
      <h3 class="flex gap-x-1.5 font-semibold text-gray-800">
        <svg class="shrink-0 size-4 mt-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
          <polyline points="14 2 14 8 20 8"></polyline>
          <line x1="16" x2="8" y1="13" y2="13"></line>
          <line x1="16" x2="8" y1="17" y2="17"></line>
          <line x1="10" x2="8" y1="9" y2="9"></line>
        </svg>
        Menggunakan "Terigu 1kg" Roti 12 
      </h3>
      <button type="button" class="mt-1 -ms-1 p-1 inline-flex items-center gap-x-2 text-xs rounded-lg border border-transparent text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none">
        James Collins
      </button>
    </div>
    <!-- End Right Content -->
  </div>
  <!-- End Item -->
  <!-- Item -->
  <div class="flex gap-x-3">
    <!-- Icon -->
    <div class="relative last:after:hidden after:absolute after:top-7 after:bottom-0 after:start-3.5 after:w-px after:-translate-x-[0.5px] after:bg-gray-200">
      <div class="relative z-10 size-7 flex justify-center items-center">
        <div class="size-2 rounded-full bg-gray-400"></div>
      </div>
    </div>
    <!-- End Icon -->

    <!-- Right Content -->
    <div class="grow pt-0.5 pb-8">
      <h3 class="flex gap-x-1.5 font-semibold text-gray-800">
        <svg class="shrink-0 size-4 mt-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
          <polyline points="14 2 14 8 20 8"></polyline>
          <line x1="16" x2="8" y1="13" y2="13"></line>
          <line x1="16" x2="8" y1="17" y2="17"></line>
          <line x1="10" x2="8" y1="9" y2="9"></line>
        </svg>
        Menggunakan "Terigu 1kg" Roti 12 
      </h3>
      <button type="button" class="mt-1 -ms-1 p-1 inline-flex items-center gap-x-2 text-xs rounded-lg border border-transparent text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none">
        James Collins
      </button>
    </div>
    <!-- End Right Content -->
  </div>
  <!-- End Item -->
  <!-- Item -->
  <div class="flex gap-x-3">
    <!-- Icon -->
    <div class="relative last:after:hidden after:absolute after:top-7 after:bottom-0 after:start-3.5 after:w-px after:-translate-x-[0.5px] after:bg-gray-200">
      <div class="relative z-10 size-7 flex justify-center items-center">
        <div class="size-2 rounded-full bg-gray-400"></div>
      </div>
    </div>
    <!-- End Icon -->

    <!-- Right Content -->
    <div class="grow pt-0.5 pb-8">
      <h3 class="flex gap-x-1.5 font-semibold text-gray-800">
        <svg class="shrink-0 size-4 mt-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
          <polyline points="14 2 14 8 20 8"></polyline>
          <line x1="16" x2="8" y1="13" y2="13"></line>
          <line x1="16" x2="8" y1="17" y2="17"></line>
          <line x1="10" x2="8" y1="9" y2="9"></line>
        </svg>
        Menggunakan "Terigu 1kg" Roti 12 
      </h3>
      <button type="button" class="mt-1 -ms-1 p-1 inline-flex items-center gap-x-2 text-xs rounded-lg border border-transparent text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none">
        James Collins
      </button>
    </div>
    <!-- End Right Content -->
  </div>
  <!-- End Item -->
  <!-- Item -->
  <div class="flex gap-x-3">
    <!-- Icon -->
    <div class="relative last:after:hidden after:absolute after:top-7 after:bottom-0 after:start-3.5 after:w-px after:-translate-x-[0.5px] after:bg-gray-200">
      <div class="relative z-10 size-7 flex justify-center items-center">
        <div class="size-2 rounded-full bg-gray-400"></div>
      </div>
    </div>
    <!-- End Icon -->

    <!-- Right Content -->
    <div class="grow pt-0.5 pb-8">
      <h3 class="flex gap-x-1.5 font-semibold text-gray-800">
        <svg class="shrink-0 size-4 mt-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
          <polyline points="14 2 14 8 20 8"></polyline>
          <line x1="16" x2="8" y1="13" y2="13"></line>
          <line x1="16" x2="8" y1="17" y2="17"></line>
          <line x1="10" x2="8" y1="9" y2="9"></line>
        </svg>
        Membuat Roti 120
      </h3>
      <p class="mt-1 text-sm text-gray-600">
        Bahan : 12kg Tepung, 12kg Gandum, 9kg Nanas, 9kg Nanas, 9kg Nanas, 9kg Nanas, 
      </p>
      <button type="button" class="mt-1 -ms-1 p-1 inline-flex items-center gap-x-2 text-xs rounded-lg border border-transparent text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none">
        James Collins
      </button>
    </div>
    <!-- End Right Content -->
  </div>
  <!-- End Item -->

  <!-- Heading -->
  <div class="ps-2 my-2 first:mt-0">
    <h3 class="text-xs font-medium uppercase text-gray-500">
      31 Jul, 2023
    </h3>
  </div>
  <!-- End Heading -->

  <!-- Item -->
  <div class="flex gap-x-3">
    <!-- Icon -->
    <div class="relative last:after:hidden after:absolute after:top-7 after:bottom-0 after:start-3.5 after:w-px after:-translate-x-[0.5px] after:bg-gray-200">
      <div class="relative z-10 size-7 flex justify-center items-center">
        <div class="size-2 rounded-full bg-gray-400"></div>
      </div>
    </div>
    <!-- End Icon -->

    <!-- Right Content -->
    <div class="grow pt-0.5 pb-8">
      <h3 class="flex gap-x-1.5 font-semibold text-gray-800">
        Take a break ⛳️
      </h3>
      <p class="mt-1 text-sm text-gray-600">
        Just chill for now... 😉
      </p>
    </div>
    <!-- End Right Content -->
  </div>
  <!-- End Item -->
</div>
<!-- End Timeline -->
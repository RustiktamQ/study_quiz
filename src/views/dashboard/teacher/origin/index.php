<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../auth/');
    exit;
}

$user = $_SESSION['user'];

require __DIR__ . "\..\partials\header.php";
?>

<script>
    window.addEventListener("load", () => {
        const preloader = document.getElementById("preloader");
        const loadingMessage = document.getElementById("loading-message");
        clearTimeout(showMessageTimeout);
        preloader.style.transition = "opacity 0.5s ease";
        preloader.style.opacity = "0";
        setTimeout(() => {
            preloader.style.display = "none";
        }, 500);
    });
    const showMessageTimeout = setTimeout(() => {
        const loadingMessage = document.getElementById("loading-message");
        loadingMessage.style.opacity = "1";
    }, 10000);
</script>

<div id="preloader" class="preloader fixed inset-0 z-50 bg-white flex flex-col items-center justify-center">
    <div class="animate-spin inline-block w-8 h-8 border-[3px] border-current border-t-transparent text-green-600 rounded-full " role="status" aria-label="loading">
        <span class="sr-only">Loading...</span>
    </div>
    <p id="loading-message" class="mt-4 text-gray-500 opacity-0 transition-opacity duration-500">Still loading...</p>
</div>

<?php include '../partials/sidebar.php'; ?>

<div class="fixed inset-0 flex flex-col items-center justify-center">
    <h1 class="quest text-5xl mb-4">Let's finish your profile.</h1>
    <div class="p-4 bg-white rounded-lg shadow-md">
  <!-- Stepper -->
  <div data-hs-stepper="">
    <!-- Stepper Nav -->
    <ul class="relative flex flex-row gap-x-2">
      <li class="flex items-center gap-x-2 shrink basis-0 flex-1 group" data-hs-stepper-nav-item='{
        "index": 1
      }'>
        <span class="min-w-7 min-h-7 group inline-flex items-center text-xs align-middle">
          <span class="size-7 flex justify-center items-center shrink-0 bg-gray-100 font-medium text-gray-800 rounded-full group-focus:bg-gray-200 hs-stepper-active:bg-green-500 hs-stepper-active:text-white hs-stepper-success:bg-green-500 hs-stepper-success:text-white hs-stepper-completed:bg-teal-500 hs-stepper-completed:group-focus:bg-teal-600">
            <span class="hs-stepper-success:hidden hs-stepper-completed:hidden">1</span>
            <svg class="hidden shrink-0 size-3 hs-stepper-success:block" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
          </span>
          <span class="ms-2 text-sm font-medium text-gray-800">
            Step
          </span>
        </span>
        <div class="w-full h-px flex-1 bg-gray-200 group-last:hidden hs-stepper-success:bg-green-500 hs-stepper-completed:bg-teal-600"></div>
      </li>

      <li class="flex items-center gap-x-2 shrink basis-0 flex-1 group" data-hs-stepper-nav-item='{
        "index": 2
      }'>
        <span class="min-w-7 min-h-7 group inline-flex items-center text-xs align-middle">
          <span class="size-7 flex justify-center items-center shrink-0 bg-gray-100 font-medium text-gray-800 rounded-full group-focus:bg-gray-200 hs-stepper-active:bg-green-500 hs-stepper-active:text-white hs-stepper-success:bg-green-500 hs-stepper-success:text-white hs-stepper-completed:bg-teal-500 hs-stepper-completed:group-focus:bg-teal-600">
            <span class="hs-stepper-success:hidden hs-stepper-completed:hidden">2</span>
            <svg class="hidden shrink-0 size-3 hs-stepper-success:block" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
          </span>
          <span class="ms-2 text-sm font-medium text-gray-800">
            Step
          </span>
        </span>
        <div class="w-full h-px flex-1 bg-gray-200 group-last:hidden hs-stepper-success:bg-green-500 hs-stepper-completed:bg-teal-600"></div>
      </li>

      <li class="flex items-center gap-x-2 shrink basis-0 flex-1 group" data-hs-stepper-nav-item='{
          "index": 3
        }'>
        <span class="min-w-7 min-h-7 group inline-flex items-center text-xs align-middle">
          <span class="size-7 flex justify-center items-center shrink-0 bg-gray-100 font-medium text-gray-800 rounded-full group-focus:bg-gray-200 hs-stepper-active:bg-green-500 hs-stepper-active:text-white hs-stepper-success:bg-green-500 hs-stepper-success:text-white hs-stepper-completed:bg-teal-500 hs-stepper-completed:group-focus:bg-teal-600">
            <span class="hs-stepper-success:hidden hs-stepper-completed:hidden">3</span>
            <svg class="hidden shrink-0 size-3 hs-stepper-success:block" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
          </span>
          <span class="ms-2 text-sm font-medium text-gray-800">
            Step
          </span>
        </span>
        <div class="w-full h-px flex-1 bg-gray-200 group-last:hidden hs-stepper-success:bg-green-500 hs-stepper-completed:bg-teal-600"></div>
      </li>
      <!-- End Item -->
    </ul>
    <!-- End Stepper Nav -->

    <!-- Stepper Content -->
    <div class="mt-5 sm:mt-8">
      <!-- First Content -->
      <div data-hs-stepper-content-item='{
        "index": 1
      }'>
        <div class="p-4 h-48 bg-gray-50 flex justify-center items-center border border-dashed border-gray-200 rounded-xl">
          <h3 class="text-gray-500">
            First content
          </h3>
        </div>
      </div>
      <!-- End First Content -->

      <!-- First Content -->
      <div data-hs-stepper-content-item='{
        "index": 2
      }' style="display: none;">
        <div class="p-4 h-48 bg-gray-50 flex justify-center items-center border border-dashed border-gray-200 rounded-xl">
          <h3 class="text-gray-500">
            Second content
          </h3>
        </div>
      </div>
      <!-- End First Content -->

      <!-- First Content -->
      <div data-hs-stepper-content-item='{
        "index": 3
      }' style="display: none;">
        <div class="p-4 h-48 bg-gray-50 flex justify-center items-center border border-dashed border-gray-200 rounded-xl">
          <h3 class="text-gray-500">
            Third content
          </h3>
        </div>
      </div>
      <!-- End First Content -->

      <!-- Final Content -->
      <div data-hs-stepper-content-item='{
        "isFinal": true
      }' style="display: none;">
        <div class="p-4 h-48 bg-gray-50 flex justify-center items-center border border-dashed border-gray-200 rounded-xl">
          <h3 class="text-gray-500">
            Final content
          </h3>
        </div>
      </div>
      <!-- End Final Content -->

      <!-- Button Group -->
      <div class="mt-5 flex justify-between items-center gap-x-2">
        <button type="button" class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none" data-hs-stepper-back-btn="">
          <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m15 18-6-6 6-6"></path>
          </svg>
          Back
        </button>
        <button type="button" class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-medium rounded-lg border border-transparent bg-green-500 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" data-hs-stepper-next-btn="">
          Next
          <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m9 18 6-6-6-6"></path>
          </svg>
        </button>
        <button type="button" class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-medium rounded-lg border border-transparent bg-green-500 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" data-hs-stepper-finish-btn="" style="display: none;">
          Finish
        </button>
        <button type="reset" class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-medium rounded-lg border border-transparent bg-green-500 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" data-hs-stepper-reset-btn="" style="display: none;">
          Reset
        </button>
      </div>
      <!-- End Button Group -->
    </div>
    <!-- End Stepper Content -->
  </div>
  <!-- End Stepper -->
</div>
</div>
<script src="https://cdn.tailwindcss.com/"></script>
<script src="https://cdn.jsdelivr.net/npm/preline@2.6.0/dist/preline.min.js"></script>
<?php include '/../partials/footer.php'; ?>
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
  window.addEventListener("load", () => { const preloader = document.getElementById("preloader"); const loadingMessage = document.getElementById("loading-message"); clearTimeout(showMessageTimeout); preloader.style.transition = "opacity 0.5s ease"; preloader.style.opacity = "0"; setTimeout(() => { preloader.style.display = "none"; }, 500); }); const showMessageTimeout = setTimeout(() => { const loadingMessage = document.getElementById("loading-message"); loadingMessage.style.opacity = "1"; }, 10000);
</script>
<div id="preloader" class="preloader fixed inset-0 z-50 bg-white flex flex-col items-center justify-center">
   <div class="animate-spin inline-block w-8 h-8 border-[3px] border-current border-t-transparent text-green-600 rounded-full " role="status" aria-label="loading">
      <span class="sr-only">Loading...</span>
   </div>
   <p id="loading-message" class="mt-4 text-gray-500 opacity-0 transition-opacity duration-500">Still loading...</p>
</div>

<?php include '../partials/sidebar.php'; ?>

<div class="w-full lg:ps-64">
   <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
      <ol class="flex items-center whitespace-nowrap">
         <li class="inline-flex items-center">
            <a class="flex items-center text-sm text-gray-500 hover:text-green-600 focus:outline-none focus:text-green-600" href="<?= ROOT_URL ?>dashboard/">
            Dashboard
            </a>
            <svg class="shrink-0 mx-2 size-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
               <path d="m9 18 6-6-6-6"></path>
            </svg>
         </li>
         <li class="inline-flex items-center">
            <a class="flex items-center text-sm text-gray-500 hover:text-green-600 focus:outline-none focus:text-green-600" href="#">
               Profile
               <svg class="shrink-0 mx-2 size-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="m9 18 6-6-6-6"></path>
               </svg>
            </a>
         </li>
         <li class="inline-flex items-center text-sm font-semibold text-gray-800 truncate" aria-current="page">
            My profile
         </li>
      </ol>
      <!-- Grid -->
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
         <!-- Card -->
         <div class="flex flex-col bg-white border shadow-sm rounded-xl  ">
            <div class="p-4 md:p-5">
               <div class="flex items-center gap-x-2">
                  <p class="text-xs uppercase tracking-wide text-gray-500 ">
                     QUESTIONS THAT WE'VE ANSWERED THIS YEAR
                  </p>
                  <div class="hs-tooltip">
                     <div class="hs-tooltip-toggle">
                        <svg class="shrink-0 size-4 text-gray-500 " xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                           <circle cx="12" cy="12" r="10" />
                           <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                           <path d="M12 17h.01" />
                        </svg>
                        <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-xs font-medium text-white rounded shadow-sm " role="tooltip">
                        The number of daily users
                        </span>
                     </div>
                  </div>
               </div>
               <div class="mt-1 flex items-center gap-x-2">
                  <h3 class="text-xl sm:text-2xl font-medium text-gray-800 ">
                     72,540
                  </h3>
                  <span class="flex items-center gap-x-1 text-green-600">
                     <svg class="inline-block size-4 self-center" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
   </div>
</div>
</div>
<?php include '/../partials/footer.php'; ?>
 <header class="admin-header">
     <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
         <div class="container-fluid">
             <!-- Logo/Brand - Now first on the left -->
             <a class="navbar-brand d-flex align-items-center" href="./index.html">
                 <img src="data:image/svg+xml,%3csvg%20width='32'%20height='32'%20viewBox='0%200%2032%2032'%20fill='none'%20xmlns='http://www.w3.org/2000/svg'%3e%3c!--%20Background%20circle%20for%20the%20M%20--%3e%3ccircle%20cx='16'%20cy='16'%20r='16'%20fill='url(%23logoGradient)'/%3e%3c!--%20Centered%20Letter%20M%20--%3e%3cpath%20d='M10%2024V8h2.5l2.5%206.5L17.5%208H20v16h-2V12.5L16.5%2020h-1L14%2012.5V24H10z'%20fill='white'%20font-weight='700'/%3e%3c!--%20Gradient%20definition%20--%3e%3cdefs%3e%3clinearGradient%20id='logoGradient'%20x1='0%25'%20y1='0%25'%20x2='100%25'%20y2='100%25'%3e%3cstop%20offset='0%25'%20style='stop-color:%236366f1;stop-opacity:1'%20/%3e%3cstop%20offset='100%25'%20style='stop-color:%238b5cf6;stop-opacity:1'%20/%3e%3c/linearGradient%3e%3c/defs%3e%3c/svg%3e"
                     alt="Logo" height="32" class="d-inline-block align-text-top me-2">
                 <h1 class="h4 mb-0 fw-bold text-primary">PMS-</h1>
             </a>

             <!-- Search Bar with Alpine.js -->
             <div class="search-container flex-grow-1 mx-4" x-data="searchComponent">
                 <div class="position-relative">
                     <input type="search" class="form-control" placeholder="Search... (Ctrl+K)" x-model="query"
                         @input="search()" data-search-input aria-label="Search">
                     <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3"></i>

                     <!-- Search Results Dropdown -->
                     <div x-show="results.length > 0" x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                         class="position-absolute top-100 start-0 w-100 bg-white border rounded-2 shadow-lg mt-1 z-3">
                         <template x-for="result in results" :key="result.title">
                             <a :href="result.url"
                                 class="d-block px-3 py-2 text-decoration-none text-dark border-bottom">
                                 <div class="d-flex align-items-center">
                                     <i class="bi bi-file-text me-2 text-muted"></i>
                                     <span x-text="result.title"></span>
                                     <small class="ms-auto text-muted" x-text="result.type"></small>
                                 </div>
                             </a>
                         </template>
                     </div>
                 </div>
             </div>

             <!-- Right Side Icons -->
             <div class="navbar-nav flex-row">
              

                 <!-- Fullscreen Toggle -->
                 <button class="btn btn-outline-secondary me-2" type="button" data-fullscreen-toggle
                     data-bs-toggle="tooltip" data-bs-placement="bottom" title="Toggle fullscreen">
                     <i class="bi bi-arrows-fullscreen icon-hover"></i>
                 </button>

            

                 <!-- User Menu -->
                 <div class="dropdown">
                     <button class="btn btn-outline-secondary d-flex align-items-center" type="button"
                         data-bs-toggle="dropdown" aria-expanded="false">
                         <img src="data:image/svg+xml,%3csvg%20width='32'%20height='32'%20viewBox='0%200%2032%2032'%20fill='none'%20xmlns='http://www.w3.org/2000/svg'%3e%3c!--%20Background%20circle%20--%3e%3ccircle%20cx='16'%20cy='16'%20r='16'%20fill='url(%23avatarGradient)'/%3e%3c!--%20Person%20silhouette%20--%3e%3cg%20fill='white'%20opacity='0.9'%3e%3c!--%20Head%20--%3e%3ccircle%20cx='16'%20cy='12'%20r='5'/%3e%3c!--%20Body%20--%3e%3cpath%20d='M16%2018c-5.5%200-10%202.5-10%207v1h20v-1c0-4.5-4.5-7-10-7z'/%3e%3c/g%3e%3c!--%20Subtle%20border%20--%3e%3ccircle%20cx='16'%20cy='16'%20r='15.5'%20fill='none'%20stroke='rgba(255,255,255,0.2)'%20stroke-width='1'/%3e%3c!--%20Gradient%20definition%20--%3e%3cdefs%3e%3clinearGradient%20id='avatarGradient'%20x1='0%25'%20y1='0%25'%20x2='100%25'%20y2='100%25'%3e%3cstop%20offset='0%25'%20style='stop-color:%236b7280;stop-opacity:1'%20/%3e%3cstop%20offset='100%25'%20style='stop-color:%234b5563;stop-opacity:1'%20/%3e%3c/linearGradient%3e%3c/defs%3e%3c/svg%3e"
                             alt="User Avatar" width="24" height="24" class="rounded-circle me-2">
                         <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                         <i class="bi bi-chevron-down ms-1"></i>
                     </button>
                     <ul class="dropdown-menu dropdown-menu-end">
                         <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                         <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                         <li>
                             <hr class="dropdown-divider">
                         </li>
                         <li><a class="dropdown-item" href="{{ route('user.logout') }}"><i
                                     class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                     </ul>
                 </div>
             </div>
         </div>
     </nav>
 </header>

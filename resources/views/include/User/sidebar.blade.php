<!-- Sidebar toggle button -->
<button id="sidebarToggle" class="btn btn-primary"
    style="
        position: fixed; 
        top: 15px; 
        left: 240px; 
        z-index: 1100; 
        border-radius: 4px; 
        padding: 16px 20px; 
        font-size: 18px;
        width: 40px; 
        height: 40px;
        display: flex; 
        align-items: center; 
        justify-content: center;
    ">
    <i class="bi bi-list" style="font-size: 20px"></i>
</button>


        <aside class="admin-sidebar" id="admin-sidebar">
            <div class="sidebar-content">
                <nav class="sidebar-nav">
                    <ul class="nav flex-column">

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}"
                                href="{{ route('user.dashboard') }}">
                                <i class="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li
                            class="nav-item dropdown w-100 
    {{ request()->is('user/reports*') ||
    request()->routeIs('user.vehicle.report') ||
    request()->routeIs('user.user.report') ||
    request()->routeIs('user.pass.report')
        ? 'active show'
        : '' }}">

                            <a class="nav-link dropdown-toggle d-flex align-items-center justify-content-between px-3 py-2 
                            {{ request()->is('user/reports*') ||
                            request()->routeIs('user.vehicle.report') ||
                            request()->routeIs('user.user.report') ||
                            request()->routeIs('user.pass.report')
                                ? 'active'
                                : '' }}"
                                href="#" id="reportsDropdown" role="button" data-bs-toggle="dropdown"
                                aria-expanded="{{ request()->is('user/reports*') ||
                                request()->routeIs('user.vehicle.report') ||
                                request()->routeIs('user.user.report') ||
                                request()->routeIs('user.pass.report')
                                    ? 'true'
                                    : 'false' }}"
                                style="border-radius: 8px; font-weight: 600;">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-bag-check me-2 fs-5"></i>
                                    <span>Reports</span>
                                </div>
                            </a>

                            <ul class="dropdown-menu w-100 shadow border-0 mt-2 {{ request()->is('user/reports*') ||
                            request()->routeIs('user.vehicle.report') ||
                            request()->routeIs('user.user.report') ||
                            request()->routeIs('user.pass.report')
                                ? 'show'
                                : '' }}"
                                aria-labelledby="reportsDropdown" style="border-radius: 10px; overflow: hidden;">

                                <li>
                                    <a class="dropdown-item d-flex align-items-center py-2 
                {{ request()->routeIs('user.reports') || request()->is('user/reports*') ? 'active' : '' }}"
                                        href="{{ route('user.reports') }}">
                                        <i class="bi bi-calendar-day text-primary me-2 fs-5"></i>
                                        <span>Daily Report</span>
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item d-flex align-items-center py-2 
                {{ request()->routeIs('user.vehicle.report') ? 'active' : '' }}"
                                        href="{{ route('user.vehicle.report') }}">
                                        <i class="bi bi-calendar-week text-success me-2 fs-5"></i>
                                        <span>Vehicle Wise Report</span>
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item d-flex align-items-center py-2 
                {{ request()->routeIs('user.user.report') ? 'active' : '' }}"
                                        href="{{ route('user.user.report') }}">
                                        <i class="bi bi-calendar3 text-warning me-2 fs-5"></i>
                                        <span>User Wise Report</span>
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item d-flex align-items-center py-2 
                {{ request()->routeIs('user.pass.report') ? 'active' : '' }}"
                                        href="{{ route('user.pass.report') }}">
                                        <i class="bi bi-funnel text-danger me-2 fs-5"></i>
                                        <span>Pass Report</span>
                                    </a>
                                </li>
                            </ul>
                        </li>





                    </ul>

                </nav>
            </div>
        </aside>
        <style>
            .dropdown-menu .dropdown-item {
                transition: all 0.2s ease-in-out;
                border-radius: 6px;
            }

            .dropdown-menu .dropdown-item:hover {
                background-color: #c4c5f1;
                color: #3e40b4 !important;
            }

            .dropdown-menu .dropdown-item:hover i {
                color: #fff !important;
            }

            .dropdown-menu {
                min-width: 250px;
            }
            /* Sidebar base */


/* Sidebar hidden */
.admin-sidebar.closed {
    transform: translateX(-100%);
}

/* Main content area */


/* When sidebar closed, main content full width */
.admin-main.full-width {
    margin-left: 0 !important;
}

        </style>

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
                        <li class="nav-item dropdown w-100">
                            <a class="nav-link dropdown-toggle d-flex align-items-center justify-content-between px-3 py-2 
        {{ request()->is('user/reports*') ? 'active' : '' }}"
                                href="" id="reportsDropdown" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false" style="border-radius: 8px; font-weight: 600;">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-bag-check me-2 fs-5"></i>
                                    <span>Reports</span>
                                </div>
                            </a>

                            <ul class="dropdown-menu w-100 shadow border-0 mt-2" aria-labelledby="reportsDropdown"
                                style="border-radius: 10px; overflow: hidden;">
                                <li>
                                    <a class="dropdown-item report-link d-flex align-items-center py-2"
                                        data-type="daily" href="{{ route('user.reports') }}">
                                        <i class="bi bi-calendar-day text-primary me-2 fs-5"></i>
                                        <span>Daily Report</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item report-link d-flex align-items-center py-2"
                                        data-type="weekly" href="{{ route('user.vehicle.report') }}">
                                        <i class="bi bi-calendar-week text-success me-2 fs-5"></i>
                                        <span>Vehicle Wise Report</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item report-link d-flex align-items-center py-2"
                                        data-type="monthly" href="{{ route('user.user.report') }}">
                                        <i class="bi bi-calendar3 text-warning me-2 fs-5"></i>
                                        <span>User Wise Report</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item report-link d-flex align-items-center py-2"
                                        data-type="custom" href="{{ route('user.pass.report') }}">
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
                background-color: #6366f1;
                color: #fff !important;
            }

            .dropdown-menu .dropdown-item:hover i {
                color: #fff !important;
            }

            .dropdown-menu {
                min-width: 250px;
            }
        </style>

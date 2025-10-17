@extends('layouts.user')
@section('content')
    <main class="admin-main">
        <div class="container-fluid p-4 p-lg-5">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Dashboard</h1>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-2"></i>
                        New Item
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Refresh data">
                        <i class="bi bi-arrow-clockwise icon-hover"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Export data">
                        <i class="bi bi-download icon-hover"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Settings">
                        <i class="bi bi-gear icon-hover"></i>
                    </button>
                </div>
            </div>

            <!-- Stats Cards with Alpine.js -->
            <div class="row g-4 mb-4">
                <div class="col-xl-3 col-lg-6">
                    <div class="card stats-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-bicycle"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 text-muted">Cycle</h6>
                                <h3 class="mb-0">₹{{ number_format($totals['Cycle']) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card stats-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="stats-icon bg-success bg-opacity-10 text-success">
                                    <i class="bi bi-bicycle"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 text-muted">Vehicle</h6>
                                <h3 class="mb-0">₹{{ number_format($totals['BikeAndScooter']) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card stats-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                                    <i class="bi bi-car-front"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 text-muted">Four Wheelers</h6>
                                <h3 class="mb-0">₹{{ number_format($totals['FourWheeler']) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card stats-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="stats-icon bg-info bg-opacity-10 text-info">
                                    <i class="bi bi-truck"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 text-muted">C-Vehicle</h6>
                                <h3 class="mb-0">₹{{ number_format($totals['CVehicle']) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Revenue Overview</h5>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary active"
                                    data-chart-period="7d">7D</button>
                                <button type="button" class="btn btn-outline-primary" data-chart-period="30d">30D</button>
                                <button type="button" class="btn btn-outline-primary" data-chart-period="90d">90D</button>
                                <button type="button" class="btn btn-outline-primary" data-chart-period="1y">1Y</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="revenueChart" height="250"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                     <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Total Revenue</h5>
                        </div>
                        <div class="card-body">
                            <div id="storageStatusChart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">User Growth (Last 7 Days)</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="userGrowthChart" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Order Status Distribution</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="orderStatusChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Widgets Row -->
            <div class="row g-4 mb-4">
                <!-- Recent Orders -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Recent Orders</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody id="recent-orders-table">
                                        <!-- Orders will be injected here by dashboard.js -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Storage Status -->
                <div class="col-lg-4">
               
                </div>
            </div>

            <!-- Sales by Location -->
            <div class="row g-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Sales by Location</h5>
                        </div>
                        <div class="card-body">
                            <div id="salesByLocationChart" style="min-height: 400px; width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection

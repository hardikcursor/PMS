@extends('layouts.user')
@section('content')
<main class="admin-main">
    <div class="container-fluid p-4 p-lg-5" style="overflow-x: hidden; overflow-y: auto; min-height: 100vh;">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">Dashboard</h1>
            </div>
            <div class="d-flex gap-2 flex-wrap">
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

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            @foreach(['Cycle', 'BikeAndScooter', 'FourWheeler', 'CVehicle'] as $type)
            <div class="col-xl-3 col-lg-6">
                <div class="card stats-card">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-bar-chart"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0 text-muted">{{ $type }}</h6>
                            <h3 class="mb-0">₹{{ number_format($totals[$type]) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Chart Section -->
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Revenue Overview</h5>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary active" data-chart-period="7d">7D</button>
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

            <!-- Total Income Circle -->
            <div class="col-lg-4">
                <div class="card bg-dark text-white">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Total Income</h5>
                    </div>
                    <div class="card-body text-center">
                        <div id="incomeChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- JS for Chart -->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            fetch('{{ route("total.revenue") }}')
                .then(res => res.json())
                .then(data => {
                    const total = data.total_revenue || 0;

                    var options = {
                        chart: {
                            height: 300,
                            type: 'radialBar',
                        },
                        series: [100],
                        colors: ['#00E396'],
                        plotOptions: {
                            radialBar: {
                                hollow: {
                                    size: '70%'
                                },
                                dataLabels: {
                                    name: {
                                        show: true,
                                        color: '#fff',
                                        offsetY: -10,
                                        text: 'Total Income'
                                    },
                                    value: {
                                        show: true,
                                        color: '#fff',
                                        fontSize: '22px',
                                        formatter: () => '₹' + total.toLocaleString()
                                    }
                                }
                            }
                        },
                        labels: ['Total Income'],
                    };

                    var chart = new ApexCharts(document.querySelector("#incomeChart"), options);
                    chart.render();
                });
        </script>

        <!-- More Widgets -->
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Total Revenue (Last 7 Days)</h5>
                    </div>
                    <div class="card-body text-center">
                        <canvas id="userGrowthChart" height="200"></canvas>


                    </div>

                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Order Status Distribution</h5>
                        <h6 class="text-center text-danger mt-3">Total no of vehicles in last 7 days</h6>
                    </div>
                    <div class="card-body d-flex justify-content-center">
                        <div style="max-width:180px; max-height:180px;">
                            <canvas id="vehicle7DaysChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                const ctx = document.getElementById('vehicle7DaysChart').getContext('2d');
                const chartData = @json($last7DaysCollections);

                const labels = Object.keys(chartData);
                const data = Object.values(chartData);

                new Chart(ctx, {
                    type: 'doughnut', // circular chart
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Vehicles (Last 7 Days)',
                            data: data,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.7)',
                                'rgba(54, 162, 235, 0.7)',
                                'rgba(255, 206, 86, 0.7)',
                                'rgba(75, 192, 192, 0.7)',
                                'rgba(153, 102, 255, 0.7)',
                                'rgba(255, 159, 64, 0.7)',
                                'rgba(201, 203, 207, 0.7)'
                            ],
                            borderColor: '#fff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        cutout: '70%', // thickness of the doughnut ring
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': ' + context.raw + ' vehicles';
                                    }
                                }
                            }
                        }
                    }
                });
            </script>

        </div>

        <!-- Recent Orders -->
        <div class="row g-4 mb-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recently User's Wise Collection (Today)</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Device ID</th>
                                        <th>User Name</th>
                                        <th>Total Collection</th>
                                        <th>Total Vehicles</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($todayCollections as $key => $row)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $row->name }}</td>
                                        <td>₹{{ number_format($row->total_collection, 2) }}</td>
                                        <td>{{ $row->total_vehicle }}</td>
                                        <td>{{ \Carbon\Carbon::parse($row->date)->format('d/m/Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-danger">No collections found for today</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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
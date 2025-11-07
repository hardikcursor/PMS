@extends('layouts.user')

@section('content')
    <main class="admin-main">
        <div class="container-fluid p-4 p-lg-5" style="overflow-x: hidden; overflow-y: auto; min-height: 100vh;">
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

            <div class="row g-4 mb-4">
                @foreach (['Cycle', 'Bike', 'Four Wheeler', 'Comm Vehicle'] as $type)
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

            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Total Revenue (Last 7 Days)</h5>
                        </div>
                        <div class="card-body text-center">
                            <canvas id="revenue7DaysChart" height="140"></canvas>
                        </div>
                    </div>

                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script>
                        const ctx = document.getElementById('revenue7DaysChart').getContext('2d');
                        const labels = @json($labels);
                        const dataAmounts = @json($amounts);
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Total Collection (₹)',
                                    data: dataAmounts,
                                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1,
                                    borderRadius: 5
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: true
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: (context) => `₹ ${context.parsed.y}`
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'Date (Last 7 Days)'
                                        },
                                        ticks: {
                                            font: {
                                                size: 12
                                            },
                                            maxRotation: 0,
                                            autoSkip: false
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Amount (₹)'
                                        }
                                    }
                                }
                            }
                        });
                    </script>
                </div>

                <div class="col-lg-4">
                    <div class="card bg-dark text-white">
                        <div class="card-header">
                            <h5 class="card-title mb-0 text-center">Total Collections</h5>
                        </div>
                        <div class="card-body text-center">
                            <div id="incomeChart"></div>
                        </div>
                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
                <script>
                    fetch('{{ route('total.revenue') }}')
                        .then(res => res.json())
                        .then(data => {
                            const total = data.total_revenue || 0;
                            var options = {
                                chart: {
                                    height: 300,
                                    type: 'radialBar'
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
                                labels: ['Total Revenue'],
                            };
                            var chart = new ApexCharts(document.querySelector("#incomeChart"), options);
                            chart.render();
                        });
                </script>


            </div>

            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Revenue Overview (Month-wise)</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="revenueChart2" height="150"></canvas>
                        </div>
                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const ctx = document.getElementById('revenueChart2').getContext('2d');
                        const allMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                        const rawMonthLabels = @json($monthLabels);
                        const monthAmounts = @json($monthAmounts);
                        const monthVehicles = @json($monthVehicles);
                        const monthMap = {
                            '01': 'Jan',
                            '1': 'Jan',
                            'January': 'Jan',
                            '02': 'Feb',
                            '2': 'Feb',
                            'February': 'Feb',
                            '03': 'Mar',
                            '3': 'Mar',
                            'March': 'Mar',
                            '04': 'Apr',
                            '4': 'Apr',
                            'April': 'Apr',
                            '05': 'May',
                            '5': 'May',
                            '06': 'Jun',
                            '6': 'Jun',
                            'June': 'Jun',
                            '07': 'Jul',
                            '7': 'Jul',
                            'July': 'Jul',
                            '08': 'Aug',
                            '8': 'Aug',
                            'August': 'Aug',
                            '09': 'Sep',
                            '9': 'Sep',
                            'September': 'Sep',
                            '10': 'Oct',
                            'October': 'Oct',
                            '11': 'Nov',
                            'November': 'Nov',
                            '12': 'Dec',
                            'December': 'Dec'
                        };
                        const monthLabels = rawMonthLabels.map(m => {
                            const month = m.split(" ")[0];
                            return monthMap[month] || month;
                        });
                        const revenueData = allMonths.map(m => {
                            const index = monthLabels.indexOf(m);
                            return index !== -1 ? monthAmounts[index] : 0;
                        });
                        const vehicleData = allMonths.map(m => {
                            const index = monthLabels.indexOf(m);
                            return index !== -1 ? monthVehicles[index] : 0;
                        });
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: allMonths,
                                datasets: [{
                                        label: 'Amount (₹)',
                                        data: revenueData,
                                        borderColor: '#4B77BE',
                                        backgroundColor: 'rgba(75, 119, 190, 0.2)',
                                        pointBackgroundColor: '#4B77BE',
                                        tension: 0.4,
                                        borderWidth: 3,
                                        fill: true
                                    },
                                    {
                                        label: 'Vehicles',
                                        data: vehicleData,
                                        borderColor: '#2ECC71',
                                        backgroundColor: 'rgba(46, 204, 113, 0.2)',
                                        pointBackgroundColor: '#2ECC71',
                                        tension: 0.4,
                                        borderWidth: 3,
                                        fill: false
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top'
                                    },
                                    tooltip: {
                                        callbacks: {
                                            title: items => ' ' + items[0].label,
                                            label: context => {
                                                const dataset = context.dataset.label;
                                                const val = context.parsed.y;
                                                if (dataset.includes('Amount')) return ' Amount: ₹' + val
                                                    .toLocaleString();
                                                if (dataset.includes('Vehicles')) return ' Vehicles: ' + val;
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Value'
                                        }
                                    },
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'Months (Jan-Dec)'
                                        }
                                    }
                                }
                            }
                        });
                    });
                </script>

                <div class="col-lg-4">
                    <div class="card" style="height: 370px">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Total No of vehicles in last 7 days</h5>
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center">
                            <div style="max-width:300px; max-height:300px;">
                                <canvas id="vehicle7DaysChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const rawData = @json($last7DaysCollections);
                        const canvas = document.getElementById('vehicle7DaysChart');
                        const container = canvas.parentElement;
                        if (!rawData || Object.keys(rawData).length === 0) {
                            container.innerHTML =
                                '<p class="text-muted text-center mt-3">No data available for the last 7 days.</p>';
                            return;
                        }
                        const labels = Object.keys(rawData);
                        const data = Object.values(rawData);
                        const formattedLabels = labels.map(date => {
                            const d = new Date(date);
                            return d.toLocaleDateString('en-GB', {
                                day: '2-digit',
                                month: 'short'
                            });
                        });
                        const ctx = canvas.getContext('2d');
                        new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: formattedLabels,
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
                                cutout: '70%',
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
                    });
                </script>
            </div>


            <div class="row g-4 mb-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Recently User's Wise Collection</h5>
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
                                                <td colspan="5" class="text-center text-danger">No collections found for
                                                    today</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Location Wise Collection</h5>
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

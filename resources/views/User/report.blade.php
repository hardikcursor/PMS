@extends('layouts.user')
@section('content')
    <main class="admin-main">
        <div class="container-fluid p-4 p-lg-5" style="overflow-x: hidden; overflow-y: auto; min-height: 100vh;">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Reports Collection</h1>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="row g-4 mb-4">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Daily Reports Collection</h5>
                            <small class="text-light">Filter & Download Reports</small>
                        </div>

                        <div class="card-body">
                            <form method="GET" action="{{ route('user.reports') }}" class="row g-3 mb-3">
                                <div class="col-md-3">
                                    <label for="from_date" class="form-label">From Date</label>
                                    <input type="date" name="from_date" id="from_date" class="form-control"
                                        value="{{ request('from_date') }}">
                                </div>

                                <div class="col-md-3">
                                    <label for="to_date" class="form-label">To Date</label>
                                    <input type="date" name="to_date" id="to_date" class="form-control"
                                        value="{{ request('to_date') }}">
                                </div>

                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-funnel"></i> Show
                                    </button>
                                </div>

                                <div class="col-md-2 d-flex align-items-end">
                                    <a href="{{ route('user.reports', [
                                        'download' => 'pdf',
                                        'from_date' => request('from_date'),
                                        'to_date' => request('to_date'),
                                    ]) }}"
                                        class="btn btn-danger w-100" style="height: 48px; line-height: 26px;">
                                        <i class="bi bi-download"></i> PDF
                                    </a>
                                </div>
                            </form>

                            <hr>

                            <div class="table-responsive">
                                @php
                                    $grandQty = $reports->sum('qty');
                                    $grandAmount = $reports->sum('total_amount');
                                @endphp

                                <table class="table table-bordered table-striped text-center">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>SR NO</th>
                                            <th>VEHICLE TYPE</th>
                                            <th>QTY</th>
                                            <th>AMOUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reports as $key => $report)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $report['vehicle_type'] }}</td>
                                                <td>{{ $report['qty'] }}</td>
                                                <td>{{ number_format($report['total_amount'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot style="background:#e8f5e9; font-weight:bold;">
                                        <tr>
                                            <td colspan="2">Grand Total</td>
                                            <td>{{ $grandQty }}</td>
                                            <td>{{ number_format($grandAmount, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>



                    </div>
                </div>
            </div>

        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // If page is loaded by refresh (not through form submit)
            if (performance.navigation.type === performance.navigation.TYPE_RELOAD) {
                document.querySelectorAll('input[type="date"]').forEach(el => el.value = '');
            }
        });
    </script>
@endsection

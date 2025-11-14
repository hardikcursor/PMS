@extends('layouts.user')
<style>
    .btn-small {
        min-width: 80px;
        padding: 0.25rem 0.5rem;
        /* height અને width control */
        font-size: 0.875rem;
        /* thoda chhota font */
    }
</style>
@section('content')
    <main class="admin-main">
        <div class="container-fluid p-4 p-lg-5" style="overflow-x: hidden; overflow-y: auto; min-height: 100vh;">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Reports Collection</h1>
                </div>
            </div>
            <div class="row g-4 mb-4">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">User Reports Collection</h5>
                            <small class="text-light">Filter & Download Reports</small>
                        </div>

                        <div class="card-body">
                            <form method="GET" action="{{ route('user.user.report') }}" id="filterForm"
                                class="row g-3 mb-3 align-items-end">

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

                                <div class="col-md-3">
                                    <label for="pos_user_id" class="form-label">User Name</label>
                                    <select name="pos_user_id" id="pos_user_id" class="form-select">
                                        <option value="">-- Select User --</option>
                                        @foreach ($posUsers as $user)
                                            <option value="{{ $user->id }}"
                                                {{ request('pos_user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3 d-flex align-items-end gap-2">
                                    <button type="submit" class="btn btn-primary px-4 py-3">
                                        <i class="bi bi-funnel"></i> Show
                                    </button>

                                    <button type="button" id="downloadPdf" class="btn btn-danger px-4 py-3">
                                        <i class="bi bi-download"></i> PDF
                                    </button>
                                </div>
                            </form>

                            <!-- Show session message -->
                            @if (session('msg'))
                                <div class="alert alert-warning text-center fw-bold mt-3">
                                    {{ session('msg') }}
                                </div>
                            @endif

                            <hr>

                            <div class="table-responsive">
                                @if ($report->isEmpty())
                                    @if (request()->hasAny(['from_date', 'to_date', 'pos_user_id']))
                                        <div class="alert alert-warning text-center m-3 fw-bold">
                                            <i class="bi bi-exclamation-triangle"></i> No records found for the selected
                                            date range.
                                        </div>
                                    @else
                                        <div class="text-center text-muted m-3">
                                            Please select filter criteria and click <strong>Show</strong> to display data.
                                        </div>
                                    @endif
                                @else
                                    @php
                                        $grandQty = $report->sum('vehicle_count');
                                        $grandAmount = $report->sum('total_amount');
                                    @endphp

                                    <table class="table table-bordered table-striped text-center align-middle">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>SR NO</th>
                                                <th>VEHICLE TYPE</th>
                                                <th>QTY</th>
                                                <th>AMOUNT (₹)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($report as $key => $item)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                     <td>{{ $item->vehicle->vehicle_type ?? 'N/A' }}</td>
                                                    <td>{{ $item->vehicle_count }}</td>
                                                    <td>{{ number_format($item->total_amount, 2) }}</td>
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
                                @endif
                            </div>
                        </div>

                        <script>
                            document.getElementById('downloadPdf').addEventListener('click', function(e) {
                                e.preventDefault();
                                const fromDate = document.getElementById('from_date').value;
                                const toDate = document.getElementById('to_date').value;
                                const posUserId = document.getElementById('pos_user_id').value;

                                let url = "{{ route('user.user.report') }}?download=pdf";

                                if (fromDate) url += `&from_date=${fromDate}`;
                                if (toDate) url += `&to_date=${toDate}`;
                                if (posUserId) url += `&pos_user_id=${posUserId}`;

                                window.location.href = url;
                            });
                        </script>


                        <!-- 🔹 Auto Clear Dates on Page Refresh -->
                        <script>
                            if (performance.navigation.type === performance.navigation.TYPE_RELOAD) {
                                document.getElementById('from_date').value = '';
                                document.getElementById('to_date').value = '';
                            }
                        </script>


                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection

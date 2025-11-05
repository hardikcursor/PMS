@extends('layouts.user')
@section('content')
    <main class="admin-main">
        <div class="container-fluid p-4 p-lg-5" style="overflow-x: hidden; overflow-y: auto; min-height: 100vh;">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">User Reports</h1>
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
                            {{-- ✅ Filter Form --}}


                            <hr>

                            {{-- ✅ Table --}}
                            <div class="table-responsive">
                                @php
                                    $grandQty = 0;
                                    $grandAmount = 0;
                                    $currentType = null;
                                    $typeQty = 0;
                                    $typeAmount = 0;
                                @endphp

                                @php
                                    $grandQty = $reports->sum('qty');
                                    $grandAmount = $reports->sum('total_amount');
                                @endphp

                                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th class="text-center">SR NO</th>
                                            <th class="text-center">VEHICLE TYPE</th>
                                            <th class="text-center">QTY</th>
                                            <th class="text-center">AMOUNT</th>
                                            <th class="text-center">DATE</th>
                                            <th class="text-center">PDF</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $grandTotal = 0; @endphp
                                        @foreach ($reports as $key => $report)
                                            <tr>
                                                <td class="text-center">{{ ++$key }}</td>
                                                <td class="text-center">{{ $report->vehicle_type }}</td>
                                                <td class="text-center">{{ $report->qty }}</td>
                                                <td class="text-center">{{ number_format($report->total_amount, 2) }}</td>
                                                <td class="text-center">{{ \Carbon\Carbon::parse($report->date)->format('d M Y') }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('user.reports', ['download' => 'pdf', 'vehicle_type' => $report->vehicle_type]) }}"
                                                        class="btn btn-sm btn-outline-primary" target="_blank">
                                                        <i class="bi bi-download"></i> PDF
                                                    </a>
                                                </td>
                                            </tr>
                                            @php $grandTotal += $report->total_amount; @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr style="background-color:#e8f5e9; font-weight:bold;">
                                            <td colspan="3" class="text-center">Grand Total:</td>
                                            <td class="text-center">{{ number_format($grandTotal, 2) }}</td>
                                            <td colspan="1"></td>
                                            <td ></td>
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
@endsection

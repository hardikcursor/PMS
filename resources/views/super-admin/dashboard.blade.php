@extends('layouts.admin')

@section('content')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Dashboard</h4>



                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-xl-4">
                        <div class="card overflow-hidden">
                            <div class="bg-primary bg-soft">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="text-primary p-3">
                                            <h5 class="text-primary">Welcome Back !</h5>
                                            <p> Super Admin Dashboard</p>

                                        </div>
                                    </div>
                                    <div class="col-5 align-self-end">
                                        <img src="{{ asset('admin_assets/images/profile-img.png') }}" alt=""
                                            class="img-fluid">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="avatar-md profile-user-wid mb-4">
                                            <img src="{{ asset('admin_assets/images/users/avatar-1.jpg') }}" alt=""
                                                class="img-thumbnail rounded-circle">
                                        </div>

                                    </div>

                                    <div class="col-sm-8">
                                        <div class="pt-4">



                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Monthly Earning</h4>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="text-muted">This month</p>
                                        <h3>
                                            ₹{{ number_format(
                                                $company->sum(function ($user) {
                                                    return $user->setsubscriptionprice->price ?? 0;
                                                }),
                                                2,
                                            ) }}
                                        </h3>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mt-4 mt-sm-0">
                                            <div id="radialBar-chart" data-colors='["--bs-primary"]' class="apex-charts">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card mini-stats-wid">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-muted fw-medium">Total Companies</p>
                                                <h4 class="mb-0">{{ $company->count() }}</h4>
                                            </div>
                                            <div class="flex-shrink-0 align-self-center">
                                                <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                                    <span class="avatar-title">
                                                        <i class="bx bx-copy-alt font-size-24"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card mini-stats-wid">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-muted fw-medium">Enabled Companies</p>
                                                <h4 class="mb-0">{{ $company->where('status', 1)->count() }}</h4>
                                            </div>

                                            <div class="flex-shrink-0 align-self-center ">
                                                <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                    <span class="avatar-title rounded-circle bg-primary">
                                                        <i class="bx bx-archive-in font-size-24"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card mini-stats-wid">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-muted fw-medium">Disabled Companies</p>
                                                <h4 class="mb-0">{{ $company->where('status', 0)->count() }}</h4>
                                            </div>

                                            <div class="flex-shrink-0 align-self-center">
                                                <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                    <span class="avatar-title rounded-circle bg-primary">
                                                        <i class="bx bx-purchase-tag-alt font-size-24"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->

                        <div class="card">
                            <div class="card-body">
                                <div class="d-sm-flex flex-wrap">
                                    <h4 class="card-title mb-4">Company Statistics</h4>
                                    <div class="ms-auto"></div>
                                </div>

                                <div id="stacked-column-chart" class="apex-charts" dir="ltr"></div>
                            </div>
                        </div>

                        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                var options = {
                                    chart: {
                                        type: 'bar',
                                        height: 350,
                                        stacked: true,
                                        toolbar: {
                                            show: false
                                        }
                                    },
                                    series: [{
                                            name: "Enabled Companies",
                                            data: @json($enabledCounts)
                                        },
                                        {
                                            name: "Disabled Companies",
                                            data: @json($disabledCounts)
                                        },
                                        {
                                            name: "POS Machines",
                                            data: @json($posMachineCounts) // new series
                                        }
                                    ],
                                    xaxis: {
                                        categories: @json($months)
                                    },
                                    colors: ["#34c38f", "#f46a6a", "#556ee6"], // POS Machines color: blue
                                    dataLabels: {
                                        enabled: true
                                    },
                                    legend: {
                                        position: 'top'
                                    }
                                };

                                var chart = new ApexCharts(document.querySelector("#stacked-column-chart"), options);
                                chart.render();
                            });
                        </script>




                    </div>
                </div>
                <!-- end row -->


                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Latest Transaction</h4>
                                <div class="table-responsive">
                                    <table class="table align-middle table-nowrap mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="align-middle">Billing Name</th>
                                                <th class="align-middle">Date</th>
                                                <th class="align-middle">Total</th>
                                                <th class="align-middle">Payment Status</th>
                                                <th class="align-middle">Payment Method</th>
                                                <th class="align-middle">View Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($license as $item)
                                                <tr>
                                                    <td>{{ $item->company->name ?? 'N/A' }}</td>
                                                    <td>{{ $item->created_at }}</td>
                                                    <td>{{ $item->price }}</td>
                                                    <td><span
                                                            class="badge badge-pill badge-soft-success font-size-11">Paid</span>
                                                    </td>
                                                    <td><i class="fab fa-cc-mastercard me-1"></i> Cash</td>
                                                    <td>
                                                        <button type="button"
                                                            class="btn btn-primary btn-sm btn-rounded waves-effect waves-light"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#transaction-detailModal-{{ $item->id }}">
                                                            View Details
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>


                                    </table>
                                </div>
                                <!-- end table-responsive -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <!-- Transaction Modal -->
        @foreach ($license as $item)
            <div class="modal fade" id="transaction-detailModal-{{ $item->id }}" tabindex="-1"
                aria-labelledby="transactionDetailLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="transactionDetailLabel{{ $item->id }}">
                                Device Details
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                style="filter: invert(1);"></button>
                        </div>

                        <!-- Modal Body -->
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Pos Serial No</th>
                                            <th>Android ID</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($item->company->devices as $device)
                                            <tr>
                                                <td>{{ $device->created_at->format('d M, Y') }}</td>
                                                <td>{{ $device->serial_number ?? 'N/A' }}</td>
                                                <td>{{ $device->android_id ?? 'N/A' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">No devices found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach


        <!-- end modal -->


        <!-- end modal -->
        @include('include.admin.footer')
    </div>
@endsection

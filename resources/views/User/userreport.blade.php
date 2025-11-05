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
            <div class="row g-4 mb-4">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">User Reports Collection</h5>
                            <small class="text-light">Filter & Download Reports</small>
                        </div>

                        <div class="card-body">
                            <hr>
                            <div class="table-responsive">
                                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th class="text-center">SR NO</th>
                                            <th class="text-center">VEHICLE NO</th>
                                            <th class="text-center">VEHICLE TYPE</th>
                                            <th class="text-center">Entry Time</th>
                                            <th class="text-center">Exit Time</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">PDF</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <tr>
                                                <td class="text-center">1</td>
                                                <td class="text-center">2</td>
                                                <td class="text-center">3</td>
                                                <td class="text-center">4</td>
                                                <td class="text-center">5</td>
                                                <td class="text-center">6</td>
                                                <td class="text-center">
                                                    <a href="" class="btn btn-sm btn-outline-primary"
                                                        target="_blank">
                                                        <i class="bi bi-download"></i> PDF
                                                    </a>
                                                </td>
                                            </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr style="background-color:#e8f5e9; font-weight:bold;">
                                            <td colspan="3" class="text-center">Grand Total:</td>
                                            <td class="text-center"></td>
                                            <td></td>
                                            <td class="text-center">null</td>
                                            <td></td>
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

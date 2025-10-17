@extends('layouts.admin')
@section('content')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Daily Collection Report</h4>



                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">


                                @php
                                    $grandQty = 0;
                                    $grandAmount = 0;
                                    $currentType = null;
                                    $typeQty = 0;
                                    $typeAmount = 0;
                                @endphp

                                @php
                                    $grandQty = $report->sum('qty');
                                    $grandAmount = $report->sum('total_amount');
                                @endphp

                                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th class="text-center">SR NO</th>
                                            <th class="text-center">VEHICLE TYPE</th>
                                            <th class="text-center">QTY</th>
                                            <th class="text-center">AMOUNT</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php
                                            $grandTotal = 0;
                                        @endphp

                                        @foreach ($report as $key => $reports)
                                            <tr>
                                                <td class="text-center">{{ ++$key }}</td>
                                                <td class="text-center">{{ $reports->vehicle_type }}</td>
                                                <td class="text-center">{{ $reports->qty }}</td>
                                                <td class="text-center">{{ number_format($reports->total_amount, 2) }}</td>
                                            </tr>
                                            @php
                                                $grandTotal += $reports->total_amount;
                                            @endphp
                                        @endforeach
                                    </tbody>

                                 
                                    <tfoot>
                                        <tr style="background-color:#e8f5e9; font-weight:bold;">
                                            <td class="text-center">Grand Total:</td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-center">{{ number_format($grandTotal, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>






                            </div>
                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row -->



            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
    </div>
@endsection

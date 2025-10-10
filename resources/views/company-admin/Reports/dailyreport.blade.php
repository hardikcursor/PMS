@extends('layouts.admin')
@section('content')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Daily  Collection Report</h4>

                          

                        </div>
                    </div>
                </div>
          
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                {{-- <h4 class="card-title mb-4">Daily Vehiclewise Collection Report</h4> --}}

                                <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center" >SR NO</th>
                                            <th scope="col" class="text-center">VEHICLE TYPE</th>
                                            <th scope="col" class="text-center"> QTY</th>
                                            <th scope="col" class="text-center">AMOUNT</th>
                                            <th scope="col" class="text-center">ACTION</th>
                                        </tr>
                                    </thead>


                                    <tbody id="subscriptionsBody">
                                        @foreach ($report as $reports)
                                               <tr>
                                                <td class="text-center">{{ $reports->serial_number}}</td>
                                                <td class="text-center">{{ $reports->vehicle_type }}</td>
                                                <td class="text-center">{{ $reports->pos_user_count }}</td>
                                                <td class="text-center">{{ $reports->amount }}</td>
                                                <td class="text-center">
                                                  <a href=""
                                                        class="edit-row"><i class="fas fa-edit text-warning"></i></a>
                                                    <form
                                                        action=""
                                                        method="POST" style="display:inline;"
                                                        onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-link p-0 m-0 delete-row text-danger ms-2"
                                                            style="border: none; background: none;">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                         
                                         
                                    </tbody>
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

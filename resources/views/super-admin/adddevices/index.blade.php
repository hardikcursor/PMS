@extends('layouts.admin')
@section('content')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">POS Machine Devices Tables</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <a href="{{ route('superadmin.adddevices.create') }}" class="btn btn-primary">Add
                                        Device</a>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">



                                <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center" style="width: 5%;">#</th>
                                            <th scope="col" style="width: 15%;">Company Name</th>
                                            <th scope="col" style="width: 20%;">Serial Number</th>
                                            <th scope="col" style="width: 15%;">Android ID</th>
                                            <th scope="col" class="text-center" style="width: 15%;">Actions</th>
                                        </tr>
                                    </thead>


                                    <tbody id="machineUsersBody">
                                        @forelse ($postmachine as $index => $machine)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $machine->company->name }}</td>
                                                <td>{{ $machine->serial_number }}</td>
                                                <td>{{ $machine->android_id ? $machine->android_id : 'N/A' }}</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-sm btn-primary">View</a>
                                                    <a href="#" class="btn btn-sm btn-danger">Delete</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">No record found</td>
                                            </tr>
                                        @endforelse
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

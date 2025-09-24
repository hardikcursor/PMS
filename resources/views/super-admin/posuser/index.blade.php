@extends('layouts.admin')
@section('content')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Data Tables</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <a href="{{ route('superadmin.posuser.addnewposuser') }}" class="btn btn-primary">Add
                                        Pos User</a>
                                </ol>
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
                                            <th scope="col" style="width: 20%;"> Company Name</th>
                                            <th scope="col" class="text-end">User Name</th>
                                            <th scope="col" class="text-end">User Rights</th>
                                            <th scope="col" class="text-end">Login ID</th>
                                            <th scope="col" class="text-center">Actions</th>
                                        </tr>
                                    </thead>


                                    <tbody id="subscriptionsBody">
                                        @forelse ($posUsers as $key => $posUser)
                                            <tr>
                                                <td class="text-center">{{ ++$key }}</td>
                                                <td>{{ $posUser->company->name }}</td>
                                                <td class="text-end">{{ $posUser->UserName }}</td>
                                                <td class="text-end">{{ $posUser->user_rights }}</td>
                                                <td class="text-end">{{ $posUser->login_id }}</td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#viewMachineUserModal">View</button>
                                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#deleteConfirmModal">Delete</button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">No record found</td>
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

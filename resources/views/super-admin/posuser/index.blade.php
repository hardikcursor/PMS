@extends('layouts.super-admin')
@section('content')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">POS Users Table</h4>

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
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    
                @elseif (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                    
                @endif
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
                                                <td class="text-end">{{ $posUser->name }}</td>
                                                <td class="text-end">{{ $posUser->position }}</td>
                                                <td class="text-end">{{ $posUser->login_id }}</td>
                                                <td class="text-center">
                                                  <a href="{{ route('superadmin.posuser.editposuser', $posUser->id) }}"
                                                        class="edit-row"><i class="fas fa-edit text-warning"></i></a>
                                                    <form
                                                        action="{{ route('superadmin.posuser.deleteposuser', $posUser->id) }}"
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

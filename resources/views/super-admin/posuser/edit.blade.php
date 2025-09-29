@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Add POS User</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10">

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Update POS User</h4>

                            <form method="POST" action="{{ route('superadmin.posuser.updateposuser', $posuser->id) }}">
                                @csrf
                                @method('PUT')

                                <!-- Company -->
                                <div class="mb-3 row">
                                    <label class="col-sm-3 col-form-label">Company</label>
                                    <div class="col-sm-9">
                                        <select class="form-select @error('company_id') is-invalid @enderror" name="company_id">
                                            <option value="" disabled selected>Select Company</option>
                                            @foreach ($category as $comp)
                                                <option value="{{ $comp->id }}" {{ $comp->id == $posuser->company_id ? 'selected' : '' }}>
                                                    {{ $comp->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('company_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- User Rights -->
                                <div class="mb-3 row">
                                    <label class="col-sm-3 col-form-label">User Rights</label>
                                    <div class="col-sm-9">
                                        <select class="form-select @error('user_rights') is-invalid @enderror" name="user_rights">
                                            <option value="" disabled selected>Select User Rights</option>
                                            <option value="operator" {{ $posuser->user_rights == 'operator' ? 'selected' : '' }}>Operator</option>
                                            <option value="admin" {{ $posuser->user_rights == 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                        @error('user_rights')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- User Name -->
                                <div class="mb-3 row">
                                    <label class="col-sm-3 col-form-label">User Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="UserName" class="form-control @error('UserName') is-invalid @enderror"
                                               value="{{ old('UserName', $posuser->UserName) }}" placeholder="Enter user name">
                                        @error('UserName')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Login ID -->
                                <div class="mb-3 row">
                                    <label class="col-sm-3 col-form-label">Login ID</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="login_id" class="form-control @error('login_id') is-invalid @enderror"
                                               value="{{ old('login_id', $posuser->login_id) }}" placeholder="Enter login ID">
                                        @error('login_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="mb-3 row">
                                    <label class="col-sm-3 col-form-label">Password</label>
                                    <div class="col-sm-9">
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                               placeholder="Enter password" value="{{ old('password', $posuser->password) }}">
                                        @error('password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="row justify-content-end">
                                    <div class="col-sm-9 offset-sm-3">
                                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->

                </div>
            </div>
            <!-- end row -->

        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
</div>
@endsection

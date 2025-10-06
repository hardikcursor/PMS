@extends('layouts.super-admin')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Add POS Device</h4>
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
                                <h4 class="card-title mb-4">Update POS Device</h4>

                                <form method="POST" action="{{ route('superadmin.adddevices.update', $postmachine->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="row mb-4">
                                        <label for="horizontal-firstname-input"
                                            class="col-sm-3 col-form-label">Company</label>
                                        <div class="col-sm-9">
                                            <select class="form-select @error('Cname') is-invalid @enderror" id="Cname"
                                                name="Cname">
                                                <option value="" disabled selected>Select Company</option>
                                                @foreach ($company as $comp)
                                                    <option value="{{ $comp->id }}" {{ $comp->id == $postmachine->company_id ? 'selected' : '' }}>{{ $comp->name }}</option>
                                                @endforeach
                                            </select>

                                            @error('Cname')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="horizontal-email-input" class="col-sm-3 col-form-label">Serial
                                            Number</label>
                                        <div class="col-sm-9">
                                            <input type="number"
                                                class="form-control @error('Srnumber') is-invalid @enderror" id="Srnumber"
                                                name="Srnumber" value="{{ $postmachine->serial_number }}" placeholder="Enter device serial number">
                                            <span class="text-danger">
                                                @error('Srnumber')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="horizontal-password-input" class="col-sm-3 col-form-label">Android
                                            ID</label>
                                        <div class="col-sm-9">
                                            <input type="text"
                                                class="form-control @error('AndroidId') is-invalid @enderror" id="AndroidId"
                                                name="AndroidId" value="{{ $postmachine->android_id }}" placeholder="Enter Android ID (optional)">
                                            <span class="text-danger">
                                                @error('AndroidId')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </span>
                                        </div>
                                    </div>

                                    <div class="row justify-content-end">
                                        <div class="col-sm-9">
                                            <div>
                                                <button type="submit" class="btn btn-primary w-md">Submit</button>
                                            </div>
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

@extends('layouts.admin')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Vehicle Form & List</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <!-- Success / Error Messages -->
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <!-- Vehicle Form -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Add Vehicle Category</h4>

                        <form action="{{ route('superadmin.faremetrix.addvehicle') }}" method="POST">
                            @csrf
                            <div class="row mb-4">
                                <label for="vehicle_category" class="col-sm-3 col-form-label">Vehicle Category</label>
                                <div class="col-sm-9">
                                    <input type="text" id="vehicle_category" name="vehicle_category"
                                        class="form-control @error('vehicle_category') is-invalid @enderror"
                                        placeholder="e.g. Two Wheeler, Four Wheeler"
                                        value="{{ old('vehicle_category') }}">
                                    @error('vehicle_category')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row justify-content-end">
                                <div class="col-sm-9">
                                    <button type="submit" class="btn btn-primary w-md">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Vehicle Table (show below form) -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Vehicle Category List</h4>

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Vehicle Category</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vehicles as $index => $vehicle)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $vehicle->vehicle_type }}</td>
                                        <td>{{ $vehicle->created_at->format('d-m-Y') }}</td>
                                        <td>
                                            <a href="{{ route('superadmin.faremetrix.edit', $vehicle->id) }}" class="text-warning me-2">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="#" 
                                                  method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0"
                                                    onclick="return confirm('Are you sure you want to delete this vehicle?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No vehicles found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

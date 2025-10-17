@extends('layouts.super-admin')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <!-- Success / Error Messages -->
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <!-- Add Vehicle Form -->
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
                                        placeholder="e.g. Two Wheeler, Four Wheeler" value="{{ old('vehicle_category') }}">
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

                <!-- Vehicle List Table -->
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
                                            <!-- Edit button triggers modal -->
                                            <button type="button" class="btn btn-link text-warning p-0 me-2"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editVehicleModal{{ $vehicle->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <!-- Delete -->
                                            <form action="{{ route('superadmin.faremetrix.deletevehicle', $vehicle->id) }}"
                                                method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0"
                                                    onclick="return confirm('Are you sure you want to delete this vehicle?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>

                                            <!-- Modal per vehicle -->
                                            <div class="modal fade" id="editVehicleModal{{ $vehicle->id }}" tabindex="-1"
                                                aria-labelledby="editVehicleModalLabel{{ $vehicle->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form
                                                            action="{{ route('superadmin.faremetrix.updatevehicle', $vehicle->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="editVehicleModalLabel{{ $vehicle->id }}">Edit
                                                                    Vehicle</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="vehicle_category{{ $vehicle->id }}"
                                                                        class="form-label">Vehicle Category</label>
                                                                    <input type="text" class="form-control"
                                                                        id="vehicle_category{{ $vehicle->id }}"
                                                                        name="vehicle_category"
                                                                        value="{{ $vehicle->vehicle_type }}">
                                                                    <span class="text-danger">
                                                                        @error('vehicle_category')
                                                                            <div class="text-danger">{{ $message }}</div>
                                                                        @enderror
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Update
                                                                    Vehicle</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Modal -->

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

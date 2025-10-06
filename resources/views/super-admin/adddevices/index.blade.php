@extends('layouts.super-admin')
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
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <h4 class="card-title mb-4">POS Devices List</h4>

                                <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th >#</th>
                                            <th>Company Name</th>
                                            <th>Serial Number</th>
                                            <th>Android ID</th>
                                            <th>Status</th>
                                            <th >Actions</th>
                                        </tr>
                                    </thead>


                                    <tbody >
                                        @forelse ($postmachine as $index => $machine)
                                            <tr>
                                                <td >{{ $index + 1 }}</td>
                                                <td>{{ $machine->company->name }}</td>
                                                <td>{{ $machine->serial_number }}</td>
                                                <td>{{ $machine->android_id ? $machine->android_id : 'N/A' }}</td>
                                                <td>
                                                    @if ($machine->status)
                                                        <button class="btn btn-sm btn-success changestatus"
                                                            data-id="{{ $machine->id }}" data-val="0">Enable</button>
                                                    @else
                                                        <button class="btn btn-sm btn-danger changestatus"
                                                            data-id="{{ $machine->id }}" data-val="1">Disable</button>
                                                    @endif
                                                </td>
                                                <td >
                                                    <a href="{{ route('superadmin.adddevices.edit', $machine->id) }}"
                                                        class="edit-row"><i class="fas fa-edit text-warning"></i></a>
                                                    <form
                                                        action="{{ route('superadmin.adddevices.destroy', $machine->id) }}"
                                                        method="POST" style="display:inline;"
                                                        onsubmit="return confirm('Are you sure you want to delete this device?');">
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        $('.changestatus').on('click', function(event) {
            event.preventDefault();
            var val = $(this).data('val');
            var id = $(this).data('id');
            var url = "{{ route('superadmin.adddevices.changeStatus') }}";

            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    id: id,
                    val: val,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    location.reload();
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });
    </script>
@endsection

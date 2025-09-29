@extends('layouts.admin')
@section('content')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Company Tables</h4>



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
                                            <th class="sortable" data-field="companyName">COMPANY NAME </th>
                                            <th class="sortable" data-field="companyLogo">LOGO </th>
                                            <th class="sortable" data-field="cEmail">EMAIL </th>
                                            <th class="sortable" data-field="subscriptionName">SUBSCRIPTION </th>
                                            <th class="sortable" data-field="licenseKey">LICENSE VALIDITY </th>
                                            <th class="sortable" data-field="status">STATUS </th>

                                            <th>ACTIONS</th>
                                        </tr>
                                    </thead>


                                    <tbody id="companiesTableBody">
                                        @forelse($company as $company)
                                            <tr>
                                                <td>{{ $company->name }}</td>
                                                <td>
                                                    @if ($company->image)
                                                        <img src="{{ asset('admin/uploads/company/' . $company->image) }}"
                                                            alt="Company Logo" class="img-fluid"
                                                            style="max-width: 100px; height: 50px;">
                                                    @endif
                                                </td>
                                                <td>{{ $company->email }}</td>
                                                <td>
                                                    @if ($company->subscriptionprice->isNotEmpty())
                                                        {{ $company->subscriptionprice->pluck('price')->implode(', ') }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>

                                                <td>{{ $company->license->license_validity ?? 'N/A' }}</td>

                                                <td>
                                                    @if ($company->status)
                                                        <button class="btn btn-sm btn-success changestatus"
                                                            data-id="{{ $company->id }}" data-val="0">Enable</button>
                                                    @else
                                                        <button class="btn btn-sm btn-danger changestatus"
                                                            data-id="{{ $company->id }}" data-val="1">Disable</button>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('superadmin.company.edit', $company->id) }}"
                                                        class="edit-row"><i class="fas fa-edit text-warning"></i></a>
                                                    <form
                                                        action="{{ route('superadmin.company.delete', $company->id) }}"
                                                        method="POST" style="display:inline;"
                                                        onsubmit="return confirm('Are you sure you want to delete this company?');">
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
                                                <td colspan="8" class="text-center">No companies found.</td>
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
            var url = "{{ route('superadmin.company.changeStatus') }}";

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

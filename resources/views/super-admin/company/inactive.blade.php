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
                                            <th class="sortable" data-field="companyName">COMPANY NAME <i
                                                    class="bi bi-arrow-down-up"></i></th>
                                            <th class="sortable" data-field="companyLogo">LOGO <i
                                                    class="bi bi-arrow-down-up"></i></th>
                                            <th class="sortable" data-field="cEmail">EMAIL <i
                                                    class="bi bi-arrow-down-up"></i>
                                            </th>
                                            <th class="sortable" data-field="subscriptionName">SUBSCRIPTION <i
                                                    class="bi bi-arrow-down-up"></i></th>
                                            <th>ACTIONS</th>
                                        </tr>
                                    </thead>


                                    <tbody id="companiesTableBody">
                                        @forelse ($inactive as $company)
                                            <tr>
                                                <td>{{ $company->name }}</td>
                                                <td>
                                                    @if ($company->image)
                                                        <img src="{{ asset('admin/uploads/company/' . $company->image) }}"
                                                            alt="Logo" class="img-fluid" style="max-width: 100px;">
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
                                                <td>
                                                    <button class="btn btn-danger btn-sm" data-id="{{ $company->id }}"
                                                        id="btn-delete">Delete</button>
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

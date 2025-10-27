@extends('layouts.super-admin')
@section('content')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Company Lincense Tables</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <a href="{{ route('superadmin.subscription.create') }}" class="btn btn-primary">Add
                                        Licence</a>
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

                                <h4 class="card-title mb-4">Lincense List</h4>

                                <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center" style="width: 5%;">#</th>
                                            <th scope="col" style="width: 20%;">Company</th>
                                            <th scope="col" style="width: 20%;">Licence Key</th>
                                            <th scope="col" class="text-end">Price (₹)</th>
                                            <th scope="col" class="text-end">Licence Validity</th>
                                            <th scope="col" class="text-center">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody id="subscriptionsBody">
                                        @forelse ($subscriptions as $key => $subscription)
                                            <tr>
                                                <td class="text-center">{{ ++$key }}</td>
                                                <td>{{ $subscription->company->name }}</td>
                                                <td>{{ $subscription->name ?? 'N/A' }}</td>
                                                <td class="text-end">₹{{ number_format($subscription->price, 2) }}</td>
                                                <td class="text-end">{{ $subscription->duration }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('superadmin.subscription.edit', $subscription->id) }}"
                                                        class="edit-row"><i class="fas fa-edit text-warning"></i></a>
                                                    <form
                                                        action="{{ route('superadmin.subscription.destroy', $subscription->id) }}"
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewBtns = document.querySelectorAll('.viewSubscriptionBtn');

            viewBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // attributes read karo
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const price = this.getAttribute('data-price');
                    const duration = this.getAttribute('data-duration');
                    const company = this.getAttribute('data-company');

                    // modal fields ma set karo
                    document.getElementById('modalCompanyName').innerText = company;
                    document.getElementById('modalLicenceName').innerText = name;
                    document.getElementById('modalLicencePrice').innerText = "₹ " + price;
                    document.getElementById('modalLicenceDuration').innerText = duration;

                    // Edit Button link set karvo hoy to
                    document.getElementById('modalEditBtn').setAttribute('href',
                        '/subscriptions/edit/' + id);
                });
            });
        });
    </script>
@endsection

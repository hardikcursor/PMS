@extends('layouts.user')
@section('content')
    <main class="admin-main">
        <div class="container-fluid p-4 p-lg-5" style="overflow-x: hidden; overflow-y: auto; min-height: 100vh;">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">User Reports</h1>
                </div>
            </div>
            <div class="row g-4 mb-4">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Vehicle Reports Collection</h5>
                            <small class="text-light">Filter & Download Reports</small>
                        </div>

                        <div class="card-body">
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Total Entries</th>
                                            <th class="text-center">Total Amount</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 1; @endphp
                                        @foreach ($reports as $date => $entries)
                                            @php
                                                $total = $entries->sum('amount');
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $i++ }}</td>
                                                <td class="text-center">{{ $date }}</td>
                                                <td class="text-center">{{ $entries->count() }}</td>
                                                <td class="text-center">₹{{ number_format($total, 2) }}</td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-outline-primary toggle-btn btn-effect-waves"
                                                        data-date="{{ Str::slug($date) }}">
                                                        <i class="bi bi-plus-circle"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                      
                                            <tr class="detail-row d-none" id="details-{{ Str::slug($date) }}">
                                                <td colspan="5">
                                                    <table class="table table-sm table-bordered mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th class="text-center">Vehicle No</th>
                                                                <th class="text-center">Vehicle Type</th>
                                                                <th class="text-center">Entry Time</th>
                                                                <th class="text-center">Exit Time</th>
                                                                <th class="text-center">Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($entries as $entry)
                                                                <tr>
                                                                    <td class="text-center">{{ $entry->vehicle_no ?? '-' }}
                                                                    </td>
                                                                    <td class="text-center">{{ $entry->vehicle_type }}</td>
                                                                    <td class="text-center">{{ $entry->in_time }}</td>
                                                                    <td class="text-center">{{ $entry->out_time }}</td>
                                                                    <td class="text-center">₹{{ $entry->amount }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>



                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script>
        document.querySelectorAll('.toggle-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const dateSlug = this.dataset.date;
                const detailsRow = document.getElementById(`details-${dateSlug}`);
                const icon = this.querySelector('i');
                detailsRow.classList.toggle('d-none');
                if (detailsRow.classList.contains('d-none')) {
                    icon.classList.remove('bi-dash-circle');
                    icon.classList.add('bi-plus-circle');
                } else {
                    icon.classList.remove('bi-plus-circle');
                    icon.classList.add('bi-dash-circle');
                }
            });
        });
    </script>
@endsection

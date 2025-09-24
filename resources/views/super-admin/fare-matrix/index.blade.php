@extends('layouts.admin')
<style>
    .rate-input {
        min-width: 40px;
    }

    .table-scroll {
        max-height: 300px;
        overflow-y: auto;
        overflow-x: auto;
    }

    .vehicle-select {
        min-width: 180px;
    }
</style>
@section('content')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Fare Matrix Tables</h4>

                            <div class="page-title-right">
                                <a href="{{ route('superadmin.faremetrix.vehicleadd') }}" class="btn btn-primary">Add
                                    Vehicle</a>
                                <a href="{{ route('superadmin.faremetrix.addslot') }}" class="btn btn-secondary">Add
                                    Slot</a>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="GET" id="companyFilterForm" class="mb-3">
                                    <select name="user_id" id="user_id" class="form-select vehicle-select"
                                        onchange="document.getElementById('companyFilterForm').submit()">
                                        <option value="">-- Select Company --</option>
                                        @foreach ($companyCategories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $selectedCompany == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>


                                <div class="table-responsive">
                                    <table class="table table-bordered text-center align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Vehicle</th>
                                                @if ($selectedCompany)
                                                    @foreach ($slots as $slot)
                                                        <th>{{ $slot->slot_hours }}</th>
                                                    @endforeach
                                                    <th>Actions</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($selectedCompany)
                                                @if ($faremetrix->isNotEmpty())
                                                    @foreach ($faremetrix->groupBy('vehicleCategory.id') as $vehicleRates)
                                                        @php
                                                            $vehicle = $vehicleRates->first()->vehicleCategory;
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $vehicle->vehicle_type }}</td>
                                                            @foreach ($slots as $slot)
                                                                @php
                                                                    $record = $vehicleRates
                                                                        ->where('slot_id', $slot->id)
                                                                        ->first();
                                                                    $rate = $record->rate ?? '';
                                                                    // Remove ".00" if not needed
                                                                    if (is_numeric($rate)) {
                                                                        $rate = rtrim(
                                                                            rtrim(
                                                                                number_format($rate, 2, '.', ''),
                                                                                '0',
                                                                            ),
                                                                            '.',
                                                                        );
                                                                    }
                                                                @endphp
                                                                <td>
                                                                    <input type="text" value="{{ $rate }}"
                                                                        class="form-control form-control-sm text-center rate-input"
                                                                        disabled />
                                                                </td>
                                                            @endforeach
                                                            <td>
                                                                <a href=""><i class="fas fa-edit text-warning"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#editModal"></i></a>
                                                                <i class="fas fa-trash-alt text-danger"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#deleteModal"></i>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="{{ $slots->count() + 2 }}"
                                                            class="text-center text-muted">
                                                            No records found
                                                        </td>
                                                    </tr>
                                                @endif
                                            @else
                                                <tr>
                                                    <td colspan="{{ $slots->count() + 2 }}" class="text-center text-muted">
                                                        Please select a company
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>



                                    </table>
                                </div>

                            </div>
                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row -->



            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

    </div>


    <script>
        document.getElementById('page-size-select').addEventListener('change', function() {
            var selectedCompany = this.value;
            var rows = document.querySelectorAll("#subscriptionsBody tr[data-company]");
            var visibleCount = 0;

            rows.forEach(function(row) {
                row.style.display = "none";
            });

            var noRecordRow = document.getElementById("no-record-row");
            if (noRecordRow) {
                noRecordRow.remove();
            }

            if (selectedCompany === "") {
                var tbody = document.getElementById("subscriptionsBody");
                var colCount = document.querySelectorAll("#subscriptionsTable thead th").length;

                var tr = document.createElement("tr");
                tr.id = "no-record-row";
                var td = document.createElement("td");
                td.colSpan = colCount;
                td.className = "text-center text-muted";
                td.innerText = "Please select a company";
                tr.appendChild(td);
                tbody.appendChild(tr);
                return;
            }

            rows.forEach(function(row) {
                var companyId = row.getAttribute("data-company");
                if (companyId === selectedCompany) {
                    row.style.display = "";
                    visibleCount++;
                }
            });

            if (visibleCount === 0) {
                var tbody = document.getElementById("subscriptionsBody");
                var colCount = document.querySelectorAll("#subscriptionsTable thead th").length;

                var tr = document.createElement("tr");
                tr.id = "no-record-row";
                var td = document.createElement("td");
                td.colSpan = colCount;
                td.className = "text-center text-muted";
                td.innerText = "No record found";
                tr.appendChild(td);
                tbody.appendChild(tr);
            }
        });
    </script>
@endsection

@extends('layouts.super-admin')

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

                <div class="row">
                    <div class="col-lg-12">

                        <div class="card shadow-sm border-0">


                            <div id="alertContainer" class="p-3">
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif
                            </div>

                            <div class="card-body">
                                <form action="{{ route('superadmin.faremetrix.ratecreate') }}" method="POST">
                                    @csrf

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label for="company_category" class="form-label fw-semibold">Company</label>
                                            <select name="company_category" id="company_category"
                                                class="form-select @error('company_category') is-invalid @enderror">
                                                <option value="">Select Company</option>
                                                @foreach ($companyCategories as $company)
                                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('company_category')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="table-scroll">
                                        <table class="table table-bordered table-sm align-middle text-center mb-0"
                                            id="slotTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="min-width:200px">Vehicle</th>
                                                    @foreach ($slots as $slot)
                                                        <th>{{ $slot->slot_hours }}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody id="slotTableBody">
                                                <tr class="slot-row">
                                                    <td>
                                                        <select name="vehicle_category[]"
                                                            class="form-select vehicle-select vehicle-select-dynamic">
                                                            <option value="" selected disabled>Select Vehicle</option>
                                                        </select>
                                                    </td>
                                                    @foreach ($slots as $slot)
                                                        <td>
                                                            <input type="text" name="rate[{{ $slot->id }}][]"
                                                                class="form-control form-control-sm text-center rate-input">
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="text-end mt-3">
                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-save"></i> Save
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div> <!-- end card -->

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slotTableBody = document.getElementById('slotTableBody');
            const originalRow = slotTableBody.querySelector('.slot-row');

            $('#company_category').on('change', function() {
                const companyId = $(this).val();
                if (!companyId) return;

                $.ajax({
                    url: "{{ route('superadmin.getCompanyVehicles', ':id') }}".replace(':id',
                        companyId),
                    method: 'GET',
                    success: function(data) {
                        const vehicleSelects = document.querySelectorAll(
                            '.vehicle-select-dynamic');
                        vehicleSelects.forEach(select => {
                            select.innerHTML =
                                '<option value="" selected disabled>Select Vehicle</option>';
                            data.forEach(vehicle => {
                                select.innerHTML +=
                                    `<option value="${vehicle.id}">${vehicle.vehicle_type}</option>`;
                            });
                        });
                    },
                    error: function() {
                        alert('Failed to load vehicles. Please try again.');
                    }
                });
            });

            function updateDropdownOptions() {
                const allSelects = slotTableBody.querySelectorAll('.vehicle-select-dynamic');
                const selectedValues = Array.from(allSelects)
                    .map(s => s.value)
                    .filter(v => v !== '');
                allSelects.forEach(sel => {
                    const currentValue = sel.value;
                    sel.querySelectorAll('option').forEach(opt => {
                        if (opt.value === "") {
                            opt.disabled = true;
                        } else {
                            if (selectedValues.includes(opt.value) && opt.value !== currentValue) {
                                opt.disabled = true;
                            } else {
                                opt.disabled = false;
                            }
                        }
                    });
                });
            }

            function addNewRowIfNeeded(select) {
                const rows = slotTableBody.querySelectorAll('.slot-row');
                const lastRow = rows[rows.length - 1];
                const selectedOption = select.options[select.selectedIndex];
                const isLastOption = select.selectedIndex === select.options.length - 1; 

             
                if (select.closest('tr') === lastRow && select.value !== '' && !isLastOption) {
                    const newRow = originalRow.cloneNode(true);
                    newRow.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
                    newRow.querySelectorAll('input[type=text]').forEach(i => i.value = '');
                    slotTableBody.appendChild(newRow);
                }

                updateDropdownOptions();
            }

            slotTableBody.addEventListener('change', function(e) {
                if (e.target && e.target.classList.contains('vehicle-select-dynamic')) {
                    addNewRowIfNeeded(e.target);
                }
            });

            $('form').on('submit', function() {
                $('.slot-row').each(function() {
                    const vehicle = $(this).find('.vehicle-select-dynamic').val();
                    const allInputsEmpty = $(this).find('.rate-input').filter(function() {
                        return $(this).val().trim() !== '';
                    }).length === 0;

                    if (!vehicle || allInputsEmpty) {
                        $(this).remove();
                    }
                });
            });
        });
    </script>
@endsection

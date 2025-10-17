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
                                <a href="{{ route('admin.faremetrix.vehicleadd') }}" class="btn btn-primary">Add
                                    Vehicle</a>
                                <a href="{{ route('admin.faremetrix.addslot') }}" class="btn btn-secondary">Add
                                    Slot</a>
                            </div>

                        </div>
                    </div>
                </div>
                <div id="alert-placeholder"></div> <!-- Alert messages AJAX के लिए -->

                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Hours</th>
                                @foreach ($slots as $slot)
                                    <th>{{ $slot->slot_hours }}</th>
                                @endforeach
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($faremetrix->isNotEmpty())
                                @foreach ($faremetrix->groupBy('vehicleCategory.id') as $vehicleRates)
                                    @php
                                        $vehicle = $vehicleRates->first()->vehicleCategory;
                                    @endphp
                                    <tr class="rate-row" data-vehicle-id="{{ $vehicle->id }}">
                                        <td class="text-start">{{ $vehicle->vehicle_type }}</td>

                                        @foreach ($slots as $slot)
                                            @php
                                                $record = $vehicleRates->where('slot_id', $slot->id)->first();
                                                $rate = $record->rate ?? '';
                                                if (is_numeric($rate)) {
                                                    $rate = rtrim(rtrim(number_format($rate, 2, '.', ''), '0'), '.');
                                                }
                                            @endphp
                                            <td>
                                                <input type="text" value="{{ $rate }}"
                                                    class="form-control form-control-sm text-center rate-input"
                                                    data-slot-id="{{ $slot->id }}" disabled />
                                            </td>
                                        @endforeach

                                        <td>
                                            <a href="javascript:void(0)" class="edit-row"><i
                                                    class="fas fa-edit text-warning"></i></a>
                                            <a href="javascript:void(0)" class="save-row d-none"><i
                                                    class="fas fa-check text-success"></i></a>
                                            <a href="javascript:void(0)" class="cancel-row d-none"><i
                                                    class="fas fa-times text-danger"></i></a>
                                            <a href="javascript:void(0)" class="delete-row text-danger ms-2"><i
                                                    class="fas fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="{{ $slots->count() + 2 }}" class="text-center text-muted">
                                        No records found
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>




            </div>
        </div>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            function showAlert(message, type = 'success') {
                const alertPlaceholder = document.getElementById('alert-placeholder');
                const wrapper = document.createElement('div');
                wrapper.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
                alertPlaceholder.append(wrapper);
                setTimeout(() => {
                    wrapper.remove();
                }, 3000);
            }

            // Edit row
            document.querySelectorAll('.edit-row').forEach(btn => {
                btn.addEventListener('click', function() {
                    const row = this.closest('tr');
                    row.querySelectorAll('.rate-input').forEach(input => input.disabled = false);

                    row.querySelector('.edit-row').classList.add('d-none');
                    row.querySelector('.save-row').classList.remove('d-none');
                    row.querySelector('.cancel-row').classList.remove('d-none');
                    row.querySelector('.delete-row').classList.add('d-none');
                });
            });

            // Cancel row
            document.querySelectorAll('.cancel-row').forEach(btn => {
                btn.addEventListener('click', function() {
                    const row = this.closest('tr');
                    row.querySelectorAll('.rate-input').forEach(input => {
                        input.value = input.defaultValue;
                        input.disabled = true;
                        input.classList.remove('is-invalid');
                    });

                    row.querySelector('.edit-row').classList.remove('d-none');
                    row.querySelector('.save-row').classList.add('d-none');
                    row.querySelector('.cancel-row').classList.add('d-none');
                    row.querySelector('.delete-row').classList.remove('d-none');
                });
            });

            // Save row
            document.querySelectorAll('.save-row').forEach(btn => {
                btn.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const vehicleId = row.dataset.vehicleId;
                    const rates = [];
                    let hasError = false;

                    row.querySelectorAll('.rate-input').forEach(input => {
                        const value = input.value.trim();
                        if (isNaN(value) || Number(value) < 0) {
                            input.classList.add('is-invalid');
                            hasError = true;
                        } else {
                            input.classList.remove('is-invalid');
                            rates.push({
                                slot_id: input.dataset.slotId,
                                rate: value
                            });
                        }
                    });

                    if (hasError) {
                        showAlert('Please enter valid numeric values', 'danger');
                        return;
                    }

                    row.querySelectorAll('.rate-input').forEach(input => input.disabled = true);
                    row.querySelector('.edit-row').classList.remove('d-none');
                    row.querySelector('.save-row').classList.add('d-none');
                    row.querySelector('.cancel-row').classList.add('d-none');
                    row.querySelector('.delete-row').classList.remove(
                        'd-none');

                    // AJAX save
                    fetch("{{ route('admin.update.vehicle.rate') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                vehicle_id: vehicleId,
                                rates: rates
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                showAlert('Rates updated successfully');
                                row.querySelectorAll('.rate-input').forEach(input => input
                                    .defaultValue = input.value);
                            } else {
                                showAlert('Error updating rates', 'danger');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            showAlert('AJAX request failed', 'danger');
                        });
                });
            });

            // Delete row
            document.querySelectorAll('.delete-row').forEach(btn => {
                btn.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const vehicleId = row.dataset.vehicleId;

                    if (!confirm('Are you sure you want to delete this row?')) return;

                    fetch("{{ route('admin.delete.vehicle.rate') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                vehicle_id: vehicleId
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                row.remove();
                                showAlert('Row deleted successfully');
                            } else {
                                showAlert('Delete failed', 'danger');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            showAlert('AJAX delete failed', 'danger');
                        });
                });
            });

        });
    </script>
@endsection

@extends('layouts.super-admin')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Add Subscription</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10">

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Update Subscription</h4>

                            <form method="POST" action="{{ route('superadmin.subscription.update', $subscription->id) }}">
                                @csrf
                                @method('PUT')

                                <!-- Company -->
                                <div class="mb-3 row">
                                    <label class="col-sm-3 col-form-label">Company <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <select name="Company" id="companySelect"
                                            class="form-select @error('Company') is-invalid @enderror">
                                            <option value="">-- Select Company --</option>
                                            @foreach ($companies as $company)
                                                <option value="{{ $company->id }} "
                                                    {{ $company->id == $subscription->company_id ? 'selected' : '' }}
                                                    data-license-key="{{ $company->license->license_key ?? '' }}"
                                                    data-validity="{{ $company->license->license_validity ?? '' }}">
                                                    {{ $company->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('Company')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Licence Name -->
                                <div class="mb-3 row">
                                    <label class="col-sm-3 col-form-label">Licence Key <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="SubscriptionName" id="SubscriptionName" value="{{ $subscription->name }}"
                                            class="form-control @error('SubscriptionName') is-invalid @enderror"
                                            placeholder="Enter licence name" value="{{ old('SubscriptionName') }}" readonly>
                                        @error('SubscriptionName')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Licence Price -->
                                <div class="mb-3 row">
                                    <label class="col-sm-3 col-form-label">Licence Price (₹) <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="number" name="Price" id="Price" min="1" step="0.01" value="{{ $subscription->price }}"
                                            class="form-control @error('Price') is-invalid @enderror"
                                            placeholder="Enter price" value="{{ old('Price') }}">
                                        @error('Price')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- License Validity -->
                                <div class="mb-3 row">
                                    <label class="col-sm-3 col-form-label">License Validity <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="datetime-local" name="license_validity" id="licenseValidity"
                                            class="form-control @error('license_validity') is-invalid @enderror"
                                            value="{{ old('license_validity', $subscription->duration) }}"
                                            min="{{ \Carbon\Carbon::today()->toDateString() }}">
                                        @error('license_validity')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="row justify-content-end">
                                    <div class="col-sm-9 offset-sm-3">
                                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->

                </div>
            </div>
            <!-- end row -->

        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const companySelect = document.getElementById('companySelect');
    const subscriptionNameField = document.getElementById('SubscriptionName');
    const licenseValidityField = document.getElementById('licenseValidity');

    companySelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const licenseKey = selectedOption.getAttribute('data-license-key');
        let validity = selectedOption.getAttribute('data-validity');

        // Set licence name
        subscriptionNameField.value = licenseKey || '';

    
        if (validity) {
          
            if (validity.includes('/')) {
                const parts = validity.split('/');
                validity = `${parts[2]}-${parts[1].padStart(2,'0')}-${parts[0].padStart(2,'0')}`;
            } else if (validity.includes(' ')) {
                validity = validity.split(' ')[0];
            }
            licenseValidityField.value = validity;
        } else {
            licenseValidityField.value = '';
        }
    });
});
</script>
@endsection

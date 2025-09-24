@extends('layouts.admin')
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                   
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Company Details</h4>

                                <form id="companyForm" method="POST" action="{{ route('superadmin.company.store') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div id="basic-example">
                                        <!-- Company Details -->
                                        <h3>Company Details</h3>
                                        <section>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>Agency Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="agency_name"
                                                            placeholder="Enter Agency Name">
                                                        @error('agency_name')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>Phone No <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="phone_no"
                                                            placeholder="Enter Your Phone No.">
                                                        @error('phone_no')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>Email <span class="text-danger">*</span></label>
                                                        <input type="email" class="form-control" name="email"
                                                            placeholder="Enter Your Email ID">
                                                        @error('email')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>Password <span class="text-danger">*</span></label>
                                                        <input type="password" class="form-control" name="password"
                                                            placeholder="Enter Your Password">
                                                        @error('password')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>Fax No</label>
                                                        <input type="text" class="form-control" name="fax"
                                                            placeholder="Enter Your Fax No">
                                                        @error('fax')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>Company Logo</label>
                                                        <input type="file" class="form-control" name="logo">
                                                        @error('logo')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label>Address <span class="text-danger">*</span></label>
                                                        <textarea class="form-control" name="address" rows="2" placeholder="Enter Your Address">{{ old('address') }}</textarea>
                                                        @error('address')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </section>

                                        <!-- License Information -->
                                        <h3>License Information</h3>
                                        <section>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>License Key</label>
                                                        <input type="text" class="form-control" name="license_key"
                                                            id="licenseKeyInput" placeholder="Click License Key" readonly
                                                            onclick="generateLicense()" />
                                                        @error('license_key')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>License Validity</label>
                                                        <input type="date" class="form-control" name="license_validity"
                                                            min="{{ \Carbon\Carbon::today()->toDateString() }}"
                                                            value="{{ old('license_validity') }}">
                                                        @error('license_validity')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>Android App Ver</label>
                                                        <input type="text" class="form-control" name="android_version"
                                                            value="{{ old('android_version') }}">
                                                        @error('android_version')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>GST No.</label>
                                                        <input type="text" class="form-control" name="gst_no"
                                                            value="{{ old('gst_no') }}">
                                                        @error('gst_no')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="basicpill-companyuin-input">C GST (%)</label>
                                                        <input type="number" step="0.01"
                                                            class="form-control @error('c_gst') is-invalid @enderror"
                                                            name="c_gst" placeholder="C_GST (%)"
                                                            value="{{ old('c_gst') }}">
                                                        <span class="text-danger">
                                                            @error('c_gst')
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="basicpill-declaration-input">S GST (%)</label>
                                                        <input type="number" step="0.01"
                                                            class="form-control @error('s_gst') is-invalid @enderror"
                                                            name="s_gst" placeholder="S_GST (%)"
                                                            value="{{ old('s_gst') }}">
                                                        <span class="text-danger">
                                                            @error('s_gst')
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="basicpill-companyuin-input">CIN NO</label>
                                                        <input type="text"
                                                            class="form-control @error('cin_no') is-invalid @enderror"
                                                            name="cin_no" placeholder="Enter CIN No"
                                                            value="{{ old('cin_no') }}">
                                                        <span class="text-danger">
                                                            @error('cin_no')
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="basicpill-declaration-input">PAN NO</label>
                                                        <input type="text"
                                                            class="form-control @error('pan_no') is-invalid @enderror"
                                                            name="pan_no" placeholder="Enter PAN No"
                                                            value="{{ old('pan_no') }}">
                                                        <span class="text-danger">
                                                            @error('pan_no')
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </span>
                                                    </div>
                                                </div>
                                        </section>

                                        <!-- Header & Footer -->
                                        <h3>Header & Footer Details</h3>
                                        <section>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>Header 1</label>
                                                        <input type="text" class="form-control" name="header1"
                                                            value="{{ old('header1') }}" maxlength="15">
                                                        @error('header1')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>Header 2</label>
                                                        <input type="text" class="form-control" name="header2"
                                                            value="{{ old('header2') }}" maxlength="15">
                                                        @error('header2')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="basicpill-cardno-input">Header 3</label>
                                                        <input type="text"
                                                            class="form-control @error('header3') is-invalid @enderror"
                                                            name="header3" placeholder="Header 3"
                                                            value="{{ old('header3') }}" maxlength="15">
                                                        <span class="text-danger">
                                                            @error('header3')
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="basicpill-card-verification-input">Header 4</label>
                                                        <input type="text"
                                                            class="form-control @error('header4') is-invalid @enderror"
                                                            name="header4" placeholder="Header 4"
                                                            value="{{ old('header4') }}" maxlength="15">
                                                        <span class="text-danger">
                                                            @error('header4')
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>Footer 1</label>
                                                        <input type="text" class="form-control" name="footer1"
                                                            value="{{ old('footer1') }}" maxlength="15">
                                                        @error('footer1')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>Footer 2</label>
                                                        <input type="text" class="form-control" name="footer2"
                                                            value="{{ old('footer2') }}" maxlength="15">
                                                        @error('footer2')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="basicpill-cardno-input">Footer 3</label>
                                                        <input type="text"
                                                            class="form-control @error('footer3') is-invalid @enderror"
                                                            name="footer3" placeholder="Footer 3"
                                                            value="{{ old('footer3') }}" maxlength="15">
                                                        <span class="text-danger">
                                                            @error('footer3')
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="basicpill-card-verification-input">Footer 4</label>
                                                        <input type="text"
                                                            class="form-control @error('footer4') is-invalid @enderror"
                                                            name="footer4" placeholder="Footer 4"
                                                            value="{{ old('footer4') }}" maxlength="15">
                                                        <span class="text-danger">
                                                            @error('footer4')
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @section('scripts')
        <script src="{{ asset('admin_assets/libs/jquery-steps/build/jquery.steps.min.js') }}"></script>
        <script>
            // Initialize jQuery Steps
            $("#basic-example").steps({
                headerTag: "h3",
                bodyTag: "section",
                transitionEffect: "slideLeft",
                autoFocus: true,
                onStepChanging: function(event, currentIndex, newIndex) {
                    // Always allow going backward
                    if (currentIndex > newIndex) {
                        return true;
                    }
                    // Validate current step fields
                    let form = $("#companyForm");
                    if (currentIndex === 0) { // Company Details validation
                        let valid = true;
                        form.find(
                            'input[name="agency_name"], input[name="phone_no"], input[name="email"], input[name="password"], textarea[name="address"]'
                        ).each(function() {
                            if (!$(this).val()) {
                                valid = false;
                                $(this).addClass('is-invalid');
                            } else {
                                $(this).removeClass('is-invalid');
                            }
                        });
                        return valid;
                    }
                    if (currentIndex === 1) { // License Details validation
                        let valid = true;
                        form.find('input[name="license_key"], input[name="license_validity"]').each(function() {
                            if (!$(this).val()) {
                                valid = false;
                                $(this).addClass('is-invalid');
                            } else {
                                $(this).removeClass('is-invalid');
                            }
                        });
                        return valid;
                    }
                    if (currentIndex === 2) { // Header Details validation
                        let valid = true;
                        form.find(
                            'input[name="header1"], input[name="header2"], input[name="header3"], input[name="footer1"], input[name="footer2"], input[name="footer3"],input[name="footer4"]'
                        ).each(function() {
                            if (!$(this).val()) {
                                valid = false;
                                $(this).addClass('is-invalid');
                            } else {
                                $(this).removeClass('is-invalid');
                            }
                        });
                        return valid;
                    }
                    return true;
                },
                onFinishing: function(event, currentIndex) {
                    // Final validation if needed
                    return true;
                },
                onFinished: function(event, currentIndex) {
                    $("#companyForm").submit();
                }
            });

            // License key generator
            function generateLicense() {
                const input = document.getElementById("licenseKeyInput");
                if (!input.value) {
                    let chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                    let randomKey = Array(16).fill(0).map(() =>
                        chars.charAt(Math.floor(Math.random() * chars.length))
                    ).join("");
                    input.value = randomKey;
                }
            }
        </script>
    @endsection
@endsection

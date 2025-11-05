@extends('layouts.super-admin')
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Company Details</h4>

                                <form id="companyForm" method="POST" action="{{ route('superadmin.company.store') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div id="basic-example">
                                        <h3>Company Details</h3>
                                        <section>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>Agency Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="agency_name"
                                                            placeholder="Enter Agency Name"
                                                            value="{{ old('agency_name') }}">
                                                        @error('agency_name')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>Phone No <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="phone_no"
                                                            placeholder="Enter Your Phone No."
                                                            value="{{ old('phone_no') }}">
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
                                                            placeholder="Enter Your Email ID" value="{{ old('email') }}">
                                                        @error('email')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>Phone Number 2 <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="phone"
                                                            placeholder="Enter Your Phone Number"
                                                            value="{{ old('phone') }}">
                                                        @error('phone')
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

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>Fax No</label>
                                                        <input type="text" class="form-control" name="fax"
                                                            placeholder="Enter Your Fax No" value="{{ old('fax') }}">
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
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>User ID <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="username"
                                                            placeholder="Enter Your User ID" value="{{ old('username') }}">
                                                        @error('username')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>Password<span class="text-danger"> *</span></label>
                                                        <input type="password" class="form-control" name="password"
                                                            placeholder="Enter Your Password">
                                                        @error('password')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label>Select Role <span class="text-danger">*</span></label>
                                                        <select class="form-control" name="role">
                                                            <option value="">Select Role</option>
                                                            <option value="admin"
                                                                {{ old('role') == 'admin' ? 'selected' : '' }}>Admin
                                                            </option>
                                                            <option value="user"
                                                                {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                                        </select>
                                                        @error('role')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                        <h3>License Information</h3>
                                        <section>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>License Key</label>
                                                        <input type="text" class="form-control" name="license_key"
                                                            id="licenseKeyInput" placeholder="Click License Key" readonly
                                                            onclick="generateLicense()" value="{{ old('license_key') }}">
                                                        @error('license_key')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>License Validity</label>
                                                        <input type="date" class="form-control"
                                                            name="license_validity"
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
                                                        <label>C GST (%)</label>
                                                        <input type="number" step="0.01" class="form-control"
                                                            name="c_gst" placeholder="C_GST (%)"
                                                            value="{{ old('c_gst') }}">
                                                        @error('c_gst')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>S GST (%)</label>
                                                        <input type="number" step="0.01" class="form-control"
                                                            name="s_gst" placeholder="S_GST (%)"
                                                            value="{{ old('s_gst') }}">
                                                        @error('s_gst')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>CIN NO</label>
                                                        <input type="text" class="form-control" name="cin_no"
                                                            placeholder="Enter CIN No" value="{{ old('cin_no') }}">
                                                        @error('cin_no')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label>PAN NO</label>
                                                        <input type="text" class="form-control" name="pan_no"
                                                            placeholder="Enter PAN No" value="{{ old('pan_no') }}">
                                                        @error('pan_no')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                        <h3>Header & Footer Details</h3>
                                        <section>
                                            @for ($i = 1; $i <= 4; $i++)
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label>Header {{ $i }}</label>
                                                            <input type="text" class="form-control"
                                                                name="header{{ $i }}"
                                                                placeholder="Enter Your Header"
                                                                value="{{ old('header' . $i) }}" maxlength="24">

                                                            <span class="text-muted">Maximum Length is 24 character</span>
                                                            @error('header' . $i)
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror

                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label>Footer {{ $i }}</label>
                                                            <input type="text" class="form-control"
                                                                name="footer{{ $i }}"
                                                                value="{{ old('footer' . $i) }}" maxlength="24"
                                                                placeholder="Enter Your Footer">
                                                            <span class="text-muted">Maximum Length is 24 character</span>
                                                            @error('footer' . $i)
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor
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
            $("#basic-example").steps({
                headerTag: "h3",
                bodyTag: "section",
                transitionEffect: "slideLeft",
                autoFocus: true,
                enableAllSteps: true,
                enablePagination: true,
                titleTemplate: '<span class="number">#index#.</span> #title#',
                onStepChanging: function(event, currentIndex, newIndex) {

                    let form = $("#companyForm");


                    if (currentIndex === 0) {
                        form.find(
                            'input[name="agency_name"], input[name="phone_no"], input[name="email"], input[name="password"], textarea[name="address"]'
                        ).each(function() {
                            if (!$(this).val()) {
                                $(this).addClass('is-invalid');
                            } else {
                                $(this).removeClass('is-invalid');
                            }
                        });
                    }

                    if (currentIndex === 1) {
                        form.find('input[name="license_key"], input[name="license_validity"]').each(function() {
                            if (!$(this).val()) {
                                $(this).addClass('is-invalid');
                            } else {
                                $(this).removeClass('is-invalid');
                            }
                        });
                    }


                    return true;
                },
                onFinished: function(event, currentIndex) {
                    $("#companyForm").submit();
                }
            });


            function generateLicense() {
                const input = document.getElementById("licenseKeyInput");
                if (!input.value) {
                    let chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                    let randomKey = Array(16)
                        .fill(0)
                        .map(() => chars.charAt(Math.floor(Math.random() * chars.length)))
                        .join("");
                    input.value = randomKey;
                }
            }
        </script>
    @endsection
@endsection

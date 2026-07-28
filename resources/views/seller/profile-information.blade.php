@extends('seller.layouts.app')
@section('title', 'Edit Profile')

@section('content')
    <div class="container-fluid px-0">
        <h4 class="fw-bold mb-3 text-dark">Edit Profile</h4>

        <div class="row g-4 align-items-stretch">
            <div class="col-md-6 d-flex">
                <form id="personalForm" class="flex-fill d-flex flex-column">
                    @csrf
                    <input type="hidden" name="section" value="personal">

                    <div class="card shadow-sm border-0 flex-fill d-flex flex-column" style="border-radius: 12px;">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="fw-semibold text-dark mb-0">
                                Personal Information
                            </h5>
                        </div>
                        <div class="card-body flex-grow-1">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" value="{{ auth('seller')->user()->name }}"
                                        class="form-control" required>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" value="{{ auth('seller')->user()->email }}"
                                        class="form-control" required>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" value="{{ auth('seller')->user()->phone }}"
                                        class="form-control" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Profile Picture</label>
                                    <x-image-input name="image" :image="auth('seller')->user()->image
                                        ? storage_url(auth('seller')->user()->image)
                                        : asset('assets/frontend/images/default.png')" />
                                </div>
                            </div>
                        </div>
                        <div class="text-end p-3 border-top bg-white">
                            <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">Update Personal Info</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-6 d-flex">
                <form id="passwordForm" class="flex-fill d-flex flex-column">
                    @csrf
                    <input type="hidden" name="section" value="password">

                    <div class="card shadow-sm border-0 flex-fill d-flex flex-column" style="border-radius: 12px;">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="fw-semibold text-dark mb-0">Update Password</h5>
                        </div>

                        <div class="card-body flex-grow-1">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" name="current_password" class="form-control">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="password" class="form-control">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="text-end p-3 border-top bg-white">
                            <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                                Update Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(function() {
                $('form').on('submit', function(e) {
                    e.preventDefault();

                    let form = $(this);
                    let formData = new FormData(this);
                    let btn = form.find('button[type=submit]');

                    $.ajax({
                        url: "{{ route('seller.profile') }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            btn.prop('disabled', true).html(
                                '<i class="fa fa-spinner fa-spin"></i> Saving...');
                        },
                        success: function(res) {
                            btn.prop('disabled', false).html('Saved');
                            showSuccessToast(res.message);

                            if (form.find('input[name="section"]').val() === 'password') {
                                form[0].reset();
                            }
                        },
                        error: function(xhr) {
                            btn.prop('disabled', false).html('Save');

                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                let message = Object.values(errors)
                                    .map(err => err[0])
                                    .join('<br>');
                                showErrorToast(message);
                            } else {
                                showErrorToast('Something went wrong!');
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection

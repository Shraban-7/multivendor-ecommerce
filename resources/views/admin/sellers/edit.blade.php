@extends('admin.layouts.app')
@section('title', 'Edit Seller')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Edit Seller</h4>
            <a href="{{ route('admin.sellers.index') }}" class="btn btn-outline-secondary btn-sm">
                <i data-feather="arrow-left" class="icon-xs me-1"></i> Back to Sellers
            </a>
        </div>

        <form id="sellerEditForm" method="POST" enctype="multipart/form-data" class="needs-validation">
            @csrf

            <div class="row">
                <!-- Personal Info -->
                <div class="col-md-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i data-feather="user" class="me-2"></i> Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" 
                                    value="{{ old('name', $seller->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $seller->email) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control"
                                    value="{{ old('phone', $seller->phone) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">NID Number</label>
                                <input type="text" name="nid_no" class="form-control"
                                    value="{{ old('nid_no', $seller->nid_no) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Profile Image</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                @if ($seller->image)
                                    <img src="{{ asset($seller->image) }}" alt="Profile" class="img-thumbnail mt-2"
                                        width="80">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Business Info -->
                <div class="col-md-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i data-feather="briefcase" class="me-2"></i> Business Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Business Name <span class="text-danger">*</span></label>
                                <input type="text" name="business_name" class="form-control"
                                    value="{{ old('business_name', $seller->business_name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Business Email</label>
                                <input type="email" name="business_email" class="form-control"
                                    value="{{ old('business_email', $seller->business_email) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Business Address <span class="text-danger">*</span></label>
                                <textarea name="business_address" class="form-control" rows="2" required>{{ old('business_address', $seller->business_address) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Division <span class="text-danger">*</span></label>
                                <select name="division_id" class="form-select" required>
                                    <option value="">Select Division</option>
                                    @foreach ($divisions as $division)
                                        <option value="{{ $division->id }}"
                                            {{ $division->id == old('division_id', $seller->division_id) ? 'selected' : '' }}>
                                            {{ $division->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">District <span class="text-danger">*</span></label>
                                <select name="district_id" class="form-select" required>
                                    <option value="">Select District</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->id }}"
                                            {{ $district->id == old('district_id', $seller->district_id) ? 'selected' : '' }}>
                                            {{ $district->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Business Logo</label>
                                <input type="file" name="business_logo" class="form-control" accept="image/*">
                                @if ($seller->business_logo)
                                    <img src="{{ asset($seller->business_logo) }}" alt="Logo"
                                        class="img-thumbnail mt-2" width="80">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documents -->
                <div class="col-md-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i data-feather="file-text" class="me-2"></i> Documents Upload</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Trade License Number</label>
                                <input type="text" name="trade_license_no" class="form-control"
                                    value="{{ old('trade_license_no', $seller->trade_license_no) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Trade License Image</label>
                                <input type="file" name="trade_license_image" class="form-control" accept="image/*">
                                @if ($seller->trade_license_image)
                                    <img src="{{ asset($seller->trade_license_image) }}" alt="Trade License"
                                        class="img-thumbnail mt-2" width="80">
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Shop Image</label>
                                <input type="file" name="shop_image" class="form-control" accept="image/*">
                                @if ($seller->shop_image)
                                    <img src="{{ asset($seller->shop_image) }}" alt="Shop" class="img-thumbnail mt-2"
                                        width="80">
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label">NID Front Image</label>
                                <input type="file" name="nid_front_image" class="form-control" accept="image/*">
                                @if ($seller->nid_front_image)
                                    <img src="{{ asset($seller->nid_front_image) }}" alt="NID Front"
                                        class="img-thumbnail mt-2" width="80">
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label">NID Back Image</label>
                                <input type="file" name="nid_back_image" class="form-control" accept="image/*">
                                @if ($seller->nid_back_image)
                                    <img src="{{ asset($seller->nid_back_image) }}" alt="NID Back"
                                        class="img-thumbnail mt-2" width="80">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="text-end mt-3">
                <button type="submit" id="updateButton" class="btn btn-primary">
                    <i data-feather="save" class="icon-xs me-1"></i> Update Seller
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            feather.replace();

            $(document).ready(function() {
                $('select[name="division_id"]').on('change', function() {
                    let divisionId = $(this).val();
                    let districtSelect = $('select[name="district_id"]');
                    districtSelect.html('<option value="">Loading...</option>');

                    if (divisionId) {
                        $.get('{{ url('/get-districts') }}/' + divisionId, function(data) {
                            districtSelect.html('<option value="">Select District</option>');
                            $.each(data, function(key, district) {
                                districtSelect.append('<option value="' + key + '">' + district +
                                    '</option>');
                            });
                        });
                    } else {
                        districtSelect.html('<option value="">Select District</option>');
                    }
                });

                $('#updateButton').click(function(e) {
                    e.preventDefault();

                    let form = $('#sellerEditForm')[0];
                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('admin.sellers.update', $seller->id) }}",
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            $('#updateButton').attr('disabled', true).text('Updating...');
                        },
                        success: function(response) {
                            if (response.status) {
                                toastr.success('Seller updated successfully!');
                                setTimeout(() => {
                                    window.location.href = "{{ route('admin.sellers.index') }}";
                                }, 1500);
                            } else {
                                toastr.error(response.messages);
                            }
                        },
                        error: function(xhr) {
                            $('#updateButton').attr('disabled', false).text('Update Seller');
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                let messages = Object.values(errors).map(item => item[0]).join('<br>');
                                toastr.error(messages);
                            } else {
                                toastr.error('Something went wrong. Please try again.');
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection

@extends('seller.layouts.app')
@section('title', 'Add Attributes')
@section('content')

<div class="mb-4 d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Add Attributes</h4>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="rounded-lg shadow-sm card">
            <div class="card-body">
                <form id="form">
                    @CSRF
                    <div class="row">
                        <div class="mb-3 col-12">
                            <label class="form-label fw-bold">Select Existing Attribute (Optional)</label>
                            <select name="attribute_name" id="attributeSelect" class="form-control">
                                <option value="">-- Select Attribute --</option>
                                @foreach ($productAttributes as $attribute)
                                    <option value="{{ $attribute->name }}">{{ $attribute->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-12">
                            <label class="form-label fw-bold">Or Create New Attribute</label>
                            <input name="name" type="text" class="form-control" placeholder="Enter Attribute Name">
                        </div>
                    </div>
                    <div id="optionsContainer">
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Add Options</h5>
                            <button type="button" id="addOption" class="btn btn-primary btn-sm"><i data-feather="plus"></i> Add Option</button>
                        </div>
                        <div class="mb-3 optionRow">
                            <input name="options[0][value]" type="text" placeholder="Option Value" class="mb-2 form-control" required>
                            <input name="options[0][additional_price]" type="number" step="0.01" placeholder="Additional Price" class="form-control" required>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" id="submitBtn" class="btn btn-success">Save Attribute</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let optionIndex = 1;

    document.getElementById("addOption").addEventListener("click", function() {
        const optionsContainer = document.getElementById("optionsContainer");
        const optionRow = document.createElement("div");
        optionRow.classList.add("optionRow", "mb-3");
        optionRow.innerHTML = `
            <input name="options[${optionIndex}][value]" type="text" placeholder="Option Value" class="mb-2 form-control" required>
            <input name="options[${optionIndex}][additional_price]" type="number" step="0.01" placeholder="Additional Price" class="form-control" required>
            <button type="button" class="mt-2 btn btn-danger btn-sm removeOption">Remove</button>
        `;
        optionsContainer.appendChild(optionRow);

        optionRow.querySelector(".removeOption").addEventListener("click", function() {
            optionsContainer.removeChild(optionRow);
        });

        optionIndex++;
    });

    $("#form").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: "{{ route('seller.products.addAttributes', $product->id) }}",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                location.reload();
            }
        });
    });
</script>
@endpush

@endsection

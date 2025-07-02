<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $product->name }} - Product Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-4">
        <h1 class="mb-3">{{ $product->name }}</h1>
        <p>{{ $product->description }}</p>

        <form id="variantForm">
            <div class="row">
                @foreach ($options as $optionName => $values)
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ $optionName }}</label>
                        <div class="variant-option" data-option-name="{{ $optionName }}">
                            @foreach ($values as $value)
                                @php
                                    $inputId = strtolower($optionName) . '-' . $value->id;
                                @endphp
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="{{ $optionName }}"
                                        id="{{ $inputId }}" value="{{ $value->id }}">
                                    <label class="form-check-label" for="{{ $inputId }}">
                                        {{ $value->value }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

            </div>

            <div class="alert alert-info d-none" id="variantInfo">
                <p><strong>SKU:</strong> <span id="sku"></span></p>
                <p><strong>Price:</strong> $<span id="price"></span></p>
                <p><strong>Stock:</strong> <span id="stock"></span></p>
            </div>

            <div class="alert alert-danger d-none" id="variantNotFound">
                Variant not found for selected options.
            </div>
        </form>
    </div>

    <script>
        const variants = @json($variants);

        document.querySelectorAll('.variant-option').forEach(optionDiv => {
            // For each option group, listen to change events on all radio inputs inside it
            optionDiv.querySelectorAll('input[type=radio]').forEach(radio => {
                radio.addEventListener('change', handleVariantChange);
            });
        });

        function handleVariantChange() {
            let selected = [];

            // For each option group, find the selected radio value (if any)
            document.querySelectorAll('.variant-option').forEach(optionDiv => {
                const checked = optionDiv.querySelector('input[type=radio]:checked');
                if (checked) {
                    selected.push(parseInt(checked.value));
                }
            });

            selected.sort();

            // Find matching variant whose options exactly match selected option IDs
            let match = variants.find(v => JSON.stringify(v.options.sort()) === JSON.stringify(selected));

            if (match) {
                document.getElementById('variantInfo').classList.remove('d-none');
                document.getElementById('variantNotFound').classList.add('d-none');
                document.getElementById('sku').textContent = match.sku;
                document.getElementById('price').textContent = match.price;
                document.getElementById('stock').textContent = match.stock_qty;
            } else {
                document.getElementById('variantInfo').classList.add('d-none');
                if (selected.length === document.querySelectorAll('.variant-option').length) {
                    document.getElementById('variantNotFound').classList.remove('d-none');
                } else {
                    document.getElementById('variantNotFound').classList.add('d-none');
                }
            }
        }
    </script>
</body>

</html>

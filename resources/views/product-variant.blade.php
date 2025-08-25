<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product Variants Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

    <style>
        .option-value-btn {
            margin-right: 8px;
            margin-bottom: 8px;
            padding: 6px 12px;
            border: 1px solid #ccc;
            background-color: white;
            cursor: pointer;
        }

        .option-value-btn.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
    </style>
</head>

<?php
$product = [
    'name' => 'Smartphone XYZ',
    'options' => [
        [
            'id' => 1,
            'name' => 'Color',
            'values' => [
                ['id' => 1, 'value' => 'Black'],
                ['id' => 2, 'value' => 'White'],
            ]
        ],
        [
            'id' => 2,
            'name' => 'Storage',
            'values' => [
                ['id' => 3, 'value' => '64GB'],
                ['id' => 4, 'value' => '128GB'],
            ]
        ]
    ],
    'variants' => [
        [
            'id' => 101,
            'sku' => 'XYZ-BLK-64',
            'price' => 699.00,
            'stock' => 10,
            'value_ids' => [1, 3], // Black + 64GB
        ],
        // [
        //     'id' => 102,
        //     'sku' => 'XYZ-BLK-128',
        //     'price' => 749.00,
        //     'stock' => 0,
        //     'value_ids' => [1, 4], // Black + 128GB
        // ],
        // [
        //     'id' => 103,
        //     'sku' => 'XYZ-WHT-64',
        //     'price' => 699.00,
        //     'stock' => 5,
        //     'value_ids' => [2, 3], // White + 64GB
        // ],
        [
            'id' => 104,
            'sku' => 'XYZ-WHT-128',
            'price' => 749.00,
            'stock' => 2,
            'value_ids' => [2, 4], // White + 128GB
        ],
    ]
];
?>

<body>
    <div class="container">
        <h1>{{ $product['name'] }}</h1>

        @php
        $defaultVariant = $product['default_variant'];
        $defaultValueIds = $defaultVariant['value_ids'] ?? [];
        @endphp


        @foreach ($product['options'] as $option)
        <div class="option-group" data-option-id="{{ $option['id'] }}">
            <strong>{{ $option['name'] }}:</strong><br>
            @foreach ($option['values'] as $value)
            @php
            $isActive = in_array($value['id'], $defaultValueIds);
            @endphp
            <button
                type="button"
                class="option-value-btn {{ $isActive ? 'active' : '' }}"
                data-option-id="{{ $option['id'] }}"
                data-value-id="{{ $value['id'] }}">
                {{ $value['value'] }}
            </button>
            @endforeach
        </div>
        @endforeach

        {{-- 3. Variant Info Section (Price, Stock, SKU) --}}
        @php
        $defaultVariant = $product['variants'];
        @endphp

        <div id="variant-info" style="margin-top: 20px;">
            <p><strong>SKU:</strong> <span id="sku">{{ $defaultVariant['sku'] ?? 'N/A' }}</span></p>
            <p><strong>Price:</strong> $<span id="price">{{ number_format($defaultVariant['price'] ?? 0, 2) }}</span></p>
            <p><strong>Availability:</strong> <span id="availability">{{ ($defaultVariant && $defaultVariant['stock'] > 0) ? 'In Stock' : 'Out of Stock' }}</span></p>
        </div>

    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        const product = @json($product);
        const variants = product.variants;
        const defaultVariant = @json($defaultVariant);

        const selectedOptions = {};

        // === 1. Build value_id => option_id map dynamically ===
        const valueToOptionMap = {};
        product.options.forEach(option => {
            option.values.forEach(value => {
                valueToOptionMap[value.id] = option.id;
            });
        });

        // === 2. Initialize selectedOptions from default variant ===
        if (defaultVariant && defaultVariant.value_ids) {
            defaultVariant.value_ids.forEach(valueId => {
                const optionId = valueToOptionMap[valueId];
                if (optionId) {
                    selectedOptions[optionId] = valueId;

                    // Also mark the corresponding button as active
                    $(`.option-value-btn[data-option-id="${optionId}"][data-value-id="${valueId}"]`).addClass('active');
                }
            });
        }

        // === 3. Display default variant info ===
        if (defaultVariant) {
            $('#sku').text(defaultVariant.sku);
            $('#price').text(defaultVariant.price.toFixed(2));
            $('#availability').text(defaultVariant.stock > 0 ? 'In Stock' : 'Out of Stock');
        }

        // === 4. Handle user selection ===
        $(document).ready(function() {
            $('.option-value-btn').on('click', function() {
                const optionId = $(this).data('option-id');
                const valueId = $(this).data('value-id');

                // Save selection
                selectedOptions[optionId] = valueId;

                // Highlight button
                $(this).siblings().removeClass('active');
                $(this).addClass('active');

                // When all options are selected, find matching variant
                const totalOptions = product.options.length;
                if (Object.keys(selectedOptions).length === totalOptions) {
                    const selectedIds = Object.values(selectedOptions).map(Number).sort();

                    const matchingVariant = variants.find(variant => {
                        const sorted = variant.value_ids.slice().sort();
                        return JSON.stringify(sorted) === JSON.stringify(selectedIds);
                    });

                    if (matchingVariant) {
                        $('#sku').text(matchingVariant.sku);
                        $('#price').text(matchingVariant.price.toFixed(2));
                        $('#availability').text(matchingVariant.stock > 0 ? 'In Stock' : 'Out of Stock');
                    } else {
                        $('#sku').text('N/A');
                        $('#price').text('0.00');
                        $('#availability').text('Not Available');
                    }
                }
            });
        });
    </script>

</body>

</html>

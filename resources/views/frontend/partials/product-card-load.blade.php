@foreach ($products as $product)
    @include('frontend.partials.product-card', ['product' => $product])
    <script type="application/json" data-quickview>
        {
            "id": {{ $product['id'] }},
            "product": @json($product),
            "defaultVariant": @json($product['default_variant'])
        }
    </script>
@endforeach

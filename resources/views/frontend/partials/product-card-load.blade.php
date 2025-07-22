@foreach ($products as $product)
    @include('frontend.partials.product-card', ['product' => $product])
    @include('frontend.partials.quick-view-modal', ['product' => $product])
    <script type="application/json" data-quickview>
        {
            "id": {{ $product['id'] }},
            "product": @json($product),
            "defaultVariant": @json(collect($product['variants'])->firstWhere('is_default', 1))
        }
    </script>
@endforeach

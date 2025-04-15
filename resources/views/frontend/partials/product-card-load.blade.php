@foreach ($products as $product)
    @include('frontend.partials.product-card', ['product' => $product])
    @include('frontend.partials.quick-view-modal', ['product' => $product])
@endforeach

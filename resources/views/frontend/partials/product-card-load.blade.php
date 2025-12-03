@foreach ($products as $product)
    <x-frontend.product-card :product="$product"/>
@endforeach

@forelse ($cartItems as $item)
    @php
        $product = $item->variant->product ?? $item->product;
        $name = $item->variant->label ?? $product->name;
        $sellingPrice = $item->variant->price ?? $product->price;
        $discountedPrice = $item->variant->compare_price ?? $product->compare_price ?? $sellingPrice;
    @endphp

    <tr class="cart-item" id="cart-item-{{ $item->id }}" data-id="{{ $item->id }}" data-variant-id="{{ $item->product_variant_id }}" data-product-id="{{ $item->product_id }}">
        <td class="small align-middle">
            <p class="fw-bold mb-0">{{ $product->name }}</p>
            @if ($item->variant)
                <small class="text-muted">{{ $item->variant->label }}</small>
            @endif
        </td>
        <td class="text-center align-middle">
            <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-secondary update-qty-btn" data-id="{{ $item->id }}" data-action="decrease">−</button>
                <button class="btn btn-outline-secondary disabled quantity">{{ $item->quantity }}</button>
                <button class="btn btn-outline-secondary update-qty-btn" data-id="{{ $item->id }}" data-action="increase">+</button>
            </div>
        </td>
        <td class="text-end align-middle">
            <div class="input-group input-group-sm justify-content-end" style="max-width: 130px; margin-left: auto;">
                <span class="input-group-text"><small class="text-muted">{{ removeZeroFromDecimal($sellingPrice) }}</small></span>
                <input type="number" class="form-control text-end price-input" data-price="{{ removeZeroFromDecimal($sellingPrice) }}" value="{{ removeZeroFromDecimal($discountedPrice) }}" data-id="{{ $item->id }}" min="0" />
            </div>
        </td>
        <td class="text-end align-middle">
            <button class="btn btn-sm btn-link text-danger p-0 delete-cart-item-btn" data-id="{{ $item->id }}" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                <i data-feather="trash-2"></i>
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center text-muted py-3">No items added</td>
    </tr>
@endforelse

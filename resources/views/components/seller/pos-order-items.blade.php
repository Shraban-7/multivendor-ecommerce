@forelse ($orderItems as $item)
@php
    $variant = $item->variant ?? null;
    $product = $variant->product ?? $item->product ?? null;

    $productName = $product->name;

    $variantName = $variant->fullName ?? 'No Variant';

    $sellingPrice = $item->selling_price
        ?? $variant->selling_price
        ?? $product->selling_price;

    $unitPrice = $item->unit_price
        ?? ($variant->discounted_price ?? null)
        ?? ($item->price ?? null)
        ?? $sellingPrice;
@endphp

<tr class="order-item" id="order-item-{{ $item->id }}" data-id="{{ $item->id }}" data-variant-id="{{ $item->product_variant_id }}" data-product-id="{{ $item->product_id }}">
    <td class="small align-middle">
        <p class="fw-bold mb-0">{{ $productName }}</p>
        <small class="text-muted">{{ $variantName }}</small>
    </td>

    <td class="text-center align-middle">
        <div class="btn-group btn-group-sm">
            <button class="btn btn-outline-secondary update-order-qty-btn"
                    data-id="{{ $item->id }}" data-action="decrease">−</button>

            <button class="btn btn-outline-secondary disabled quantity">
                {{ $item->quantity }}
            </button>

            <button class="btn btn-outline-secondary update-order-qty-btn"
                    data-id="{{ $item->id }}" data-action="increase">+</button>
        </div>
    </td>

    <td class="text-end align-middle">
        <div class="input-group input-group-sm justify-content-end" style="max-width: 130px; margin-left: auto;">
            <span class="input-group-text">
                <small class="text-muted">
                    {{ removeZeroFromDecimal($sellingPrice) }}
                </small>
            </span>

            <input type="number"
                   class="form-control text-end price-input"
                   data-price="{{ removeZeroFromDecimal($sellingPrice) }}"
                   value="{{ removeZeroFromDecimal($unitPrice) }}"
                   data-id="{{ $item->id }}"
                   min="0" />
        </div>
    </td>

    <td class="text-end align-middle">
        <button class="btn btn-sm btn-link text-danger p-0 delete-order-item-btn"
                data-id="{{ $item->id }}"
                data-bs-toggle="modal"
                data-bs-target="#deleteOrderConfirmModal">
            <i data-feather="trash-2"></i>
        </button>
    </td>
</tr>
@empty
<tr>
    <td colspan="4" class="text-center text-muted">No items in order</td>
</tr>
@endforelse

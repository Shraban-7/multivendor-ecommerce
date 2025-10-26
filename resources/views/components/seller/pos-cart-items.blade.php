@forelse ($cartItems as $item)
<tr class="cart-item" id="cart-item-{{ $item->id }}" data-id="{{ $item->id }}">
    <td class="small align-middle">
        <p class="fw-bold mb-0">{{ $item->variant->product->name }}</p>
        <small class="text-muted">{{ $item->variant->fullName }}</small>
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
            <span class="input-group-text">
                <small class="text-muted">{{ removeZeroFromDecimal($item->variant->selling_price) }}</small>
            </span>
            <input type="number" class="form-control text-end price-input"
                data-price="{{ removeZeroFromDecimal($item->variant->selling_price) }}"
                value="{{ removeZeroFromDecimal($item->variant->discounted_price ?? $item->variant->selling_price) }}"
                data-id="{{ $item->id }}" min="0" />
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
    <td colspan="4" class="text-center text-muted">No items added</td>
</tr>
@endforelse
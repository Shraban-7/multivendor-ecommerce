@forelse ($cartItems as $item)
    <tr class="cart-item-{{ $item->id }}">
        <td class="small">
            <p class="fw-bold mb-0">{{ $item->variant->product->name }}</p>
            <small class="text-muted">{{ $item->variant->fullName }}</small>
        </td>
        <td class="text-center">
            <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-secondary update-qty-btn" data-id="{{ $item->id }}"
                    data-action="decrease">-</button>
                <button class="btn btn-outline-secondary disabled">{{ $item->quantity }}</button>
                <button class="btn btn-outline-secondary update-qty-btn" data-id="{{ $item->id }}"
                    data-action="increase">+</button>
            </div>
        </td>
        <td class="text-end">
            @if ($item->variant->discounted_price)
                <span class="text-muted text-decoration-line-through me-1 small">
                    {{ money($item->variant->selling_price * $item->quantity) }}
                </span>
                <span class="small">
                    {{ money($item->variant->discounted_price * $item->quantity) }}
                </span>
            @else
            <span class="small">
                {{ money($item->variant->selling_price * $item->quantity) }}
            </span>
            @endif
        </td>
        <td class="text-end">
            <button class="btn btn-sm btn-link text-danger p-0 delete-cart-item-btn" data-id="{{ $item->id }}"
                data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                <i data-feather="trash-2"></i>
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center text-muted">No items added</td>
    </tr>
@endforelse

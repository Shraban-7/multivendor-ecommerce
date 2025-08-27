@forelse ($cartItems as $item)
    <tr class="cart-item-{{ $item->id }}">
        <td class="small">
            <p><strong>{{ $item->variant->product->name }}</strong></p>
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

        </td>
        <td class="text-end">{{ money($item->price * $item->quantity) }}</td>
        <td class="text-end">
            <button class="btn btn-sm btn-link text-danger p-0 delete-cart-item-btn" data-id="{{ $item->id }}"
                data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center text-muted">No items in cart</td>
    </tr>
@endforelse

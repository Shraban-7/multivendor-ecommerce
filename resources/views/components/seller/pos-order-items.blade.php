@forelse ($orderItems as $item)
    <tr class="order-item-{{ $item->id }}">
        <td class="small">
            <p class="fw-bold mb-0">{{ $item->variant->product->name }}</p>
            <small class="text-muted">{{ $item->variant->fullName }}</small>
        </td>
        <td class="text-center">
            <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-secondary update-order-qty-btn" data-id="{{ $item->id }}"
                    data-action="decrease">-</button>
                <button class="btn btn-outline-secondary disabled">{{ $item->quantity }}</button>
                <button class="btn btn-outline-secondary update-order-qty-btn" data-id="{{ $item->id }}"
                    data-action="increase">+</button>
            </div>
        </td>
        <td class="text-end">
            {{ money($item->unit_price * $item->quantity) }}
        </td>
        <td class="text-end">
            <button class="btn btn-sm btn-link text-danger p-0 delete-order-item-btn" data-id="{{ $item->id }}"
                data-bs-toggle="modal" data-bs-target="#deleteOrderConfirmModal">
                <i data-feather="trash-2"></i>
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center text-muted">No items in order</td>
    </tr>
@endforelse

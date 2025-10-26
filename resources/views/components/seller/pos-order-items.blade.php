{{-- @forelse ($orderItems as $item)
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
            @php
                $sellingPrice = $item->selling_price ?? $item->variant->selling_price;
                $unitPrice = $item->unit_price ?? ($item->variant->discounted_price ?? ($item->price ?? $sellingPrice));
                $quantity = $item->quantity ?? 1;
            @endphp

            @if (!empty($item->variant->discounted_price))
                <span class="text-muted text-decoration-line-through me-1 small">
                    {{ money($sellingPrice * $quantity) }}
                </span>
            @endif

            <span class="small">
                {{ money($unitPrice * $quantity) }}
            </span>
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
@endforelse --}}


@forelse ($orderItems as $item)
<tr class="order-item" id="order-item-{{ $item->id }}" data-id="{{ $item->id }}">
    <td class="small align-middle">
        <p class="fw-bold mb-0">{{ $item->variant->product->name }}</p>
        <small class="text-muted">{{ $item->variant->fullName }}</small>
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
        @php
            $sellingPrice = $item->selling_price ?? $item->variant->selling_price;
            $unitPrice = $item->unit_price 
                ?? ($item->variant->discounted_price ?? ($item->price ?? $sellingPrice));
        @endphp

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


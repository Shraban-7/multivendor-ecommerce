<div class="mb-4 shadow-sm card">
    <div class="bg-white card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 card-title">Inventory Status</h5>
        <div>
            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                data-bs-target="#stockUpdateModal">
                <i class="fas fa-plus-circle me-1"></i> Update Stock
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <!-- Header -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3">
                    <h6 class="text-muted fw-bold small mb-2 mb-md-0">
                        <i class="fas fa-boxes-stacked me-1 text-primary"></i> Stock History
                    </h6>

                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-bordered align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Date</th>
                                <th>Variant</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($product->stock_history as $history)
                            <tr>
                                <td class="text-nowrap small">
                                    {{ $history->created_at?->format('d/m/y h:i A') ?? '-' }}
                                </td>
                                <td class="text-nowrap small">
                                    {{ $history->variant?->fullName === null ? 'Default' : $history->variant->fullName }}
                                </td>
                                <td class="text-center small">
                                    {{ abs($history->quantity ?? 0) }}
                                </td>
                                <td class="text-center small">
                                    @switch($history->type)
                                    @case(\App\Enums\StockType::ADD_STOCK)
                                    <span class="badge bg-success">Added</span>
                                    @break

                                    @case(\App\Enums\StockType::REMOVE_STOCK)
                                    <span class="badge bg-danger">Removed</span>
                                    @break

                                    @case(\App\Enums\StockType::SET_EXACT_STOCK)
                                    <span class="badge bg-warning text-dark">Set Exact</span>
                                    @break
                                    @endswitch
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No stock history
                                    available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
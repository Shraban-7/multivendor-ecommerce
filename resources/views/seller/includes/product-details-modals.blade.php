<!-- product delete modal -->
<div class="modal fade" id="deleteModal-{{ $product->id }}" tabindex="-1"
    aria-labelledby="deleteModalLabel-{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel-{{ $product->id }}">Confirm
                    Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="text-center modal-body">
                <div class="alert alert-warning d-flex" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2 text-danger" style="font-size: 1.5rem;"></i>
                    <p class="mt-1 text-secondary">
                        Are you sure you want to delete this Product?
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('seller.products.delete', $product->id) }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Stock Update Modal -->
<div class="modal fade" id="stockUpdateModal2" tabindex="-1" aria-hidden="true" data-id="{{ $product->id }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('seller.products.stockUpdate', $product->id) }}" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Update Inventory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Variants</label>
                        <select class="form-select" id="variant" name="product_variant_id">
                            <option value="">--Select Variant--</option>
                            @foreach ($product->variants as $variant)
                            <option value="{{ $variant->id }}">
                                {{ $variant?->fullName == null ? 'Default' : $variant->fullName }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Action</label>
                        <select class="form-select" id="stockAction" name="stock_action">
                            <option value="{{ \App\Enums\StockType::ADD_STOCK->value }}">
                                {{ \App\Enums\StockType::ADD_STOCK->label() }}
                            </option>
                            <option value="{{ \App\Enums\StockType::REMOVE_STOCK->value }}">
                                {{ \App\Enums\StockType::REMOVE_STOCK->label() }}
                            </option>
                            <option value="{{ \App\Enums\StockType::SET_EXACT_STOCK->value }}">
                                {{ \App\Enums\StockType::SET_EXACT_STOCK->label() }}
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="stockQuantity" name="stock_quantity"
                            min="1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Note (Optional)</label>
                        <textarea class="form-control" id="stockNote" name="stock_note" rows="2"
                            placeholder="Reason for this inventory change"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save
                        Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="stockUpdateModal" tabindex="-1" aria-hidden="true" data-id="{{ $product->id }}">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('seller.products.stockUpdate', $product->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Update Inventory</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <button class="btn btn-outline-info btn-sm" type="button" data-bs-toggle="collapse"
                            data-bs-target="#stockInstruction" aria-expanded="false"
                            aria-controls="stockInstruction">
                            ℹ️ স্টক আপডেট নির্দেশনা দেখুন
                        </button>
                        <div class="collapse mt-2" id="stockInstruction">
                            <div class="alert alert-info mb-0" role="alert">
                                <h5 class="alert-heading">📦 স্টক আপডেট করার নিয়ম</h5>
                                <ul class="mb-0">
                                    <li><strong>স্টক অ্যাকশন</strong> অপশন থেকে নির্বাচন করুন:</li>
                                    <ul>
                                        <li><strong>স্টক যুক্ত করুন (Add Stock)</strong> – নতুন পণ্য যোগ করতে।</li>
                                        <li><strong>স্টক বাদ দিন (Remove Stock)</strong> – স্টক কমাতে।</li>
                                        <li><strong>স্টক নির্ধারণ করুন (Set Exact Stock)</strong> – স্টক ঠিক করে
                                            দিতে।</li>
                                    </ul>
                                    <li><strong>Qty</strong> ঘরে সংখ্যাটি দিন (যেমন: 5, 10)।</li>
                                    <li><strong>Note</strong> ঘরে চাইলে মন্তব্য দিন (ঐচ্ছিক)।</li>
                                    <li>বর্তমান স্টক পরিমাণ ব্র্যাকেটের ভিতরে দেখানো হয়েছে।</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    @foreach ($product->variants as $variant)
                    <h5>{{ $variant->fullName == null ? 'Default' : $variant->fullName }}</h5>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <select class="form-select form-select-sm" name="stock_action[{{ $variant->id }}]">
                                <option value="{{ \App\Enums\StockType::ADD_STOCK->value }}">
                                    {{ \App\Enums\StockType::ADD_STOCK->label() }}
                                </option>
                                <option value="{{ \App\Enums\StockType::REMOVE_STOCK->value }}">
                                    {{ \App\Enums\StockType::REMOVE_STOCK->label() }}
                                </option>
                                <option value="{{ \App\Enums\StockType::SET_EXACT_STOCK->value }}">
                                    {{ \App\Enums\StockType::SET_EXACT_STOCK->label() }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Qty
                                    ({{ $variant->stock_in - $variant->stock_out }})
                                </span>
                                <input type="number" class="form-control"
                                    name="stock_quantity[{{ $variant->id }}]" min="1">
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Note</span>
                                <input type="text" class="form-control"
                                    name="stock_note[{{ $variant->id }}]">
                            </div>
                        </div>
                    </div>
                    <hr>
                    @endforeach
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Stocks</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Variant Add Modal -->
<div class="modal fade" id="addVariantModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Variant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="variantAlert"></div>
                <form id="variantForm" action="{{ route('seller.productVariants.store', $product->id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-6">
                            <label class="form-label">Buying Price</label>
                            <div class="input-group">
                                <span class="input-group-text">{{ currency() }}</span>
                                <input type="number" class="form-control" name="buying_price" required>
                            </div>
                        </div>
                        <div class="mb-3 col-6">
                            <label class="form-label">Selling Price</label>
                            <div class="input-group">
                                <span class="input-group-text">{{ currency() }}</span>
                                <input type="number" class="form-control" name="selling_price" required>
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Discount Type</label>
                            <select name="discount_type" class="form-select w-100" id="" required>
                                <option value="" selected>--Choose--</option>
                                <option value="{{ \App\Enums\DiscountType::FLAT->value }}"
                                    {{ $product->discount_type == \App\Enums\DiscountType::FLAT->value ? 'selected' : '' }}>
                                    {{ ucfirst(\App\Enums\DiscountType::FLAT->label()) }}
                                </option>
                                <option value="{{ \App\Enums\DiscountType::PERCENTAGE->value }}"
                                    {{ $product->discount_type == \App\Enums\DiscountType::PERCENTAGE->value ? 'selected' : '' }}>
                                    {{ ucfirst(\App\Enums\DiscountType::PERCENTAGE->label()) }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Discount Value</label>
                            <input name="discount_value" type="number" class="form-control" required>
                        </div>
                        <div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">Select Options</label>
                                    <select id="mainOptionSelect" class="form-select" multiple>
                                        @foreach ($product_options as $option)
                                        <option value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                @foreach ($product_options as $option)
                                <div class="col-md-12 mb-3 option-values" id="option-{{ $option->id }}"
                                    style="display:none;">
                                    <label class="form-label fw-bold">{{ $option->name }}</label>
                                    <select name="option_values[{{ $option->id }}][]" class="form-select"
                                        multiple>
                                        @foreach ($option->options as $item)
                                        <option value="{{ $item->id }}">{{ $item->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveVariant">Save Variant</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addOptionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('seller.options.store', $product->id) }}">
                @csrf
                <div class="modal-header bg-white text-dark">
                    <h5 class="modal-title" id="addOptionModalLabel">Add Product Option</h5>
                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-4">
                        <label for="attribute_name" class="form-label fw-bold">Select Existing Option</label>
                        <select class="form-select" id="attribute_name" name="option_id">
                            <option value="" disabled selected>Select an option</option>
                            @foreach ($product_options as $option)
                            <option value="{{ $option->id }}">{{ $option->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="text-center mb-3 fw-semibold text-muted">— or create new —</div>
                    <div class="mb-3">
                        <label for="new_attribute_name" class="form-label fw-bold">New Option Name</label>
                        <input type="text" class="form-control" id="new_attribute_name" name="name"
                            placeholder="Enter new attribute name">
                    </div>
                    <div class="mb-3">
                        <label for="attribute_value" class="form-label fw-bold">Value <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="attribute_value" name="value"
                            placeholder="e.g., Red, XL" required>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
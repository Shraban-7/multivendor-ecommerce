@extends('seller.layouts.app')
@section('title', 'New Ticket')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Open a new support ticket</h4>
        <a href="{{ route('seller.support.index') }}" class="btn btn-sm btn-light border">← Back</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('seller.support.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Subject <span class="text-danger">*</span></label>
                        <input type="text" name="subject" class="form-control form-control-sm" maxlength="200" value="{{ old('subject') }}" required>
                        @error('subject') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Priority <span class="text-danger">*</span></label>
                        <select name="priority" class="form-select form-select-sm" required>
                            @foreach (\App\Domain\Support\Enums\TicketPriority::cases() as $p)
                                <option value="{{ $p->value }}" @selected(old('priority', 'normal') === $p->value)>{{ $p->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select form-select-sm" required>
                            @foreach ($categories as $value => $label)
                                <option value="{{ $value }}" @selected(old('category', 'other') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Related order (optional)</label>
                        <input type="number" name="order_id" class="form-control form-control-sm" value="{{ old('order_id') }}" placeholder="e.g. 12345">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Describe the issue <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="8" required maxlength="10000" placeholder="Provide as much detail as possible — order numbers, screenshots of error, etc.">{{ old('description') }}</textarea>
                        @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary">Submit Ticket</button>
                        <a href="{{ route('seller.support.index') }}" class="btn btn-light border">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

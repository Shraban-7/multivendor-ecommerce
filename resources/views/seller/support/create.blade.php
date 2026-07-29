@extends('seller.layouts.app')
@section('title', 'New Ticket')
@section('content')

    <div class="flex justify-between items-center mb-3">
        <h4 class="font-bold mb-0">Open a new support ticket</h4>
        <a href="{{ route('seller.support.index') }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-surface-muted text-ink text-sm font-medium border border-border rounded-xs hover:bg-border/30 focus:outline-none transition-colors gap-1">← Back</a>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0">
        <div class="p-5">
            <form method="POST" action="{{ route('seller.support.store') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                    <div class="md:col-span-8">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Subject <span class="text-feedback-danger">*</span></label>
                        <input type="text" name="subject" class="w-full px-2.5 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" maxlength="200" value="{{ old('subject') }}" required>
                        @error('subject') <div class="text-feedback-danger text-sm">{{ $message }}</div> @enderror
                    </div>
                    <div class="md:col-span-4">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Priority <span class="text-feedback-danger">*</span></label>
                        <select name="priority" class="w-full px-2.5 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                            @foreach (\App\Domain\Support\Enums\TicketPriority::cases() as $p)
                                <option value="{{ $p->value }}" @selected(old('priority', 'normal') === $p->value)>{{ $p->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-6">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Category <span class="text-feedback-danger">*</span></label>
                        <select name="category" class="w-full px-2.5 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                            @foreach ($categories as $value => $label)
                                <option value="{{ $value }}" @selected(old('category', 'other') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-6">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Related order (optional)</label>
                        <input type="number" name="order_id" class="w-full px-2.5 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('order_id') }}" placeholder="e.g. 12345">
                    </div>

                    <div class="col-span-full">
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Describe the issue <span class="text-feedback-danger">*</span></label>
                        <textarea name="description" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="8" required maxlength="10000" placeholder="Provide as much detail as possible — order numbers, screenshots of error, etc.">{{ old('description') }}</textarea>
                        @error('description') <div class="text-feedback-danger text-sm">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-span-full">
                        <button class="inline-flex items-center justify-center px-4 py-2 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors gap-1">Submit Ticket</button>
                        <a href="{{ route('seller.support.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-surface-muted text-ink text-sm font-medium border border-border rounded-xs hover:bg-border/30 focus:outline-none transition-colors gap-1">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

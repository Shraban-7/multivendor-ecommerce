@extends('seller.layouts.app')
@section('title', 'Create Coupon')

@section('content')
    <div class="flex items-center gap-2 mb-4">
        <a href="{{ route('seller.coupons.index') }}" class="btn btn-light btn-sm">
            <i data-lucide="arrow-left" style="width:16px;height:16px;"></i>
        </a>
        <div>
            <h1 class="text-xl font-semibold text-ink">Create Coupon</h1>
            <p class="text-sm text-ink-secondary mt-0.5">Add a discount code to boost sales</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-3 rounded-sm bg-red-50 border border-red-200 text-feedback-danger text-sm mb-4">
            <strong class="font-semibold">Please fix the following:</strong>
            <ul class="list-disc list-inside mt-1 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                    <h6 class="text-xs font-semibold text-ink uppercase tracking-wider">Coupon Details</h6>
                </div>
                <form method="POST" action="{{ route('seller.coupons.store') }}">
                    @csrf
                    <div class="p-5">
                        @include('seller.coupons._form')
                    </div>
                    <div class="flex justify-end px-4 py-3 border-t border-border bg-surface-muted gap-2">
                        <a href="{{ route('seller.coupons.index') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i data-lucide="save" class="icon-xs"></i> Create Coupon</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                    <h6 class="text-xs font-semibold text-ink uppercase tracking-wider"><i data-lucide="lightbulb" class="icon-xs me-1"></i> Tips</h6>
                </div>
                <div class="p-5 text-sm space-y-3">
                    <div class="flex gap-3">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-tint text-brand shrink-0">
                            <i data-lucide="key-round" style="width:14px;height:14px;"></i>
                        </span>
                        <div>
                            <div class="font-semibold text-ink">Memorable codes</div>
                            <p class="text-ink-tertiary text-xs mb-0">Use a memorable, unique code — e.g. <code class="font-mono">SUMMER20</code>.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-tint text-brand shrink-0">
                            <i data-lucide="repeat" style="width:14px;height:14px;"></i>
                        </span>
                        <div>
                            <div class="font-semibold text-ink">Usage limit</div>
                            <p class="text-ink-tertiary text-xs mb-0">Cap redemptions to create urgency and prevent abuse.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-tint text-brand shrink-0">
                            <i data-lucide="package" style="width:14px;height:14px;"></i>
                        </span>
                        <div>
                            <div class="font-semibold text-ink">Targeted products</div>
                            <p class="text-ink-tertiary text-xs mb-0">Restrict to specific products for sharper promotions.</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-brand-tint text-brand shrink-0">
                            <i data-lucide="shopping-bag" style="width:14px;height:14px;"></i>
                        </span>
                        <div>
                            <div class="font-semibold text-ink">Min purchase</div>
                            <p class="text-ink-tertiary text-xs mb-0">Set a minimum cart value to lift average order size.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
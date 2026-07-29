@extends('admin.layouts.app')
@section('title', 'Shipping Carriers')

@section('content')
<div class="flex flex-wrap justify-between items-center mb-3">
    <h3 class="font-bold mb-0">Shipping Carriers</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCarrierModal">
        <i data-lucide="plus" style="width: 16px; height: 16px;"></i> Add Carrier
    </button>
</div>

<div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm" style="border-radius: 12px;">
    <div class="p-5 p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-ink border-collapse">
                <thead class="bg-surface-muted">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3">Name</th>
                        <th class="py-3">Slug</th>
                        <th class="py-3">API Endpoint</th>
                        <th class="py-3 text-center">Active</th>
                        <th class="py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($carriers as $carrier)
                        <tr>
                            <td class="px-4">{{ $carrier->id }}</td>
                            <td class="font-semibold">{{ $carrier->name }}</td>
                            <td><code>{{ $carrier->slug }}</code></td>
                            <td class="text-sm text-ink-tertiary">{{ Str::limit($carrier->api_endpoint, 40) }}</td>
                            <td class="text-center">
                                <span class="badge {{ $carrier->is_active ? 'badge-soft-success' : 'badge-soft-secondary' }}">
                                    {{ $carrier->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-right">
                                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editCarrierModal-{{ $carrier->id }}">
                                    <i data-lucide="edit" class="icon-xs"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.shipping.carriers.destroy', $carrier) }}" class="inline"
                                      onsubmit="return confirm('Delete this carrier?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i data-lucide="trash-2" class="icon-xs"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- Edit Modal --}}
                        <div class="modal fade" id="editCarrierModal-{{ $carrier->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('admin.shipping.carriers.update', $carrier) }}">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Carrier</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            @include('admin.shipping._carrier_form', ['carrier' => $carrier])
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-ink-tertiary">
                                <i data-lucide="truck" style="width: 48px; height: 48px;" class="mb-3"></i>
                                <p class="mb-0">No carriers configured.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($carriers->hasPages())
        <div class="px-5 py-3 border-t border-border bg-surface-muted bg-white border-t flex justify-end">
            {{ $carriers->links() }}
        </div>
    @endif
</div>

{{-- Add Carrier Modal --}}
<div class="modal fade" id="addCarrierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.shipping.carriers.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Shipping Carrier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('admin.shipping._carrier_form', ['carrier' => null])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

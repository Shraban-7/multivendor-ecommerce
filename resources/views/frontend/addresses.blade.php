@extends('frontend.layouts.app')
@section('title', 'My Addresses')

@section('dashboard')
    <div class="space-y-6">

        {{-- Addresses --}}
        <div class="bg-white rounded-sm border border-[#E5E5E5]">
            <div class="px-5 py-3.5 border-b border-[#E5E5E5] flex items-center justify-between">
                <h2 class="text-base font-semibold text-[#191919]">My Addresses</h2>
                <button type="button" onclick="toggleModal('addBillingAddressModal')"
                    class="inline-flex items-center gap-1.5 text-sm font-medium text-[#F85606] hover:text-[#E04D05] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add New
                </button>
            </div>

            <div class="p-5">
                @if ($billingAddresses->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($billingAddresses as $address)
                            <div class="relative border border-[#E5E5E5] rounded-sm p-4 {{ $address->is_default ? 'border-[#F85606] bg-[#FFF8F5]' : '' }}">
                                @if ($address->is_default)
                                    <span class="absolute top-2.5 right-2.5 text-[10px] font-semibold text-[#F85606] bg-[#F85606]/10 px-2 py-0.5 rounded-sm">Default</span>
                                @endif
                                <div class="flex items-start gap-2 mb-2">
                                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#767676] bg-[#F5F5F5] px-2 py-0.5 rounded-sm">
                                        {{ $address->type == \App\Enums\AddressType::HOME->value ? 'Home' : 'Office' }}
                                    </span>
                                </div>
                                <p class="font-medium text-sm text-[#191919]">{{ $address->customer_name }}</p>
                                <p class="text-sm text-[#767676]">{{ $address->customer_phone }}</p>
                                <p class="text-sm text-[#767676] mt-1 leading-relaxed">
                                    {{ $address->address }},
                                    {{ $address->district->name }},
                                    {{ $address->division->name }}
                                </p>
                                <div class="flex items-center gap-2 mt-3 pt-3 border-t border-[#E5E5E5]">
                                    <button type="button" onclick="toggleModal('edit-address-modal-{{ $address->id }}')"
                                        class="text-xs font-medium text-[#F85606] hover:text-[#E04D05] transition-colors">
                                        Edit
                                    </button>
                                    <button type="button" onclick="deleteAddress({{ $address->id }})"
                                        class="text-xs font-medium text-red-500 hover:text-red-600 transition-colors">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10">
                        <div class="w-12 h-12 mx-auto mb-3 bg-[#F5F5F5] rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#A0A0A0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-[#767676]">No addresses yet.</p>
                        <button type="button" onclick="toggleModal('addBillingAddressModal')"
                            class="mt-3 text-sm font-medium text-[#F85606] hover:text-[#E04D05] transition-colors">
                            Add an address
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Add Billing Address Modal --}}
    <div id="addBillingAddressModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="bg-white rounded-sm w-full max-w-md mx-4">
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-[#E5E5E5]">
                <h3 class="text-base font-semibold text-[#191919]">Add Address</h3>
                <button type="button" onclick="toggleModal('addBillingAddressModal')" class="text-[#A0A0A0] hover:text-[#767676] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('billing_addresses.store') }}" class="p-5 space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-[#191919]">Full Name</label>
                    <input type="text" name="customer_name" required placeholder="John Doe"
                        class="w-full px-3.5 py-2.5 border border-[#E5E5E5] rounded-sm text-sm focus:outline-none focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606]/20 transition-colors">
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-[#191919]">Phone Number</label>
                    <input type="tel" name="customer_phone" required placeholder="01XXXXXXXXX"
                        class="w-full px-3.5 py-2.5 border border-[#E5E5E5] rounded-sm text-sm focus:outline-none focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606]/20 transition-colors">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-[#191919]">Division</label>
                        <select name="division_id" id="add-division"
                            class="w-full px-3.5 py-2.5 border border-[#E5E5E5] rounded-sm text-sm focus:outline-none focus:border-[#F85606] bg-white">
                            <option value="">Select</option>
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}">{{ $division->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-[#191919]">District</label>
                        <select name="district_id" id="add-district"
                            class="w-full px-3.5 py-2.5 border border-[#E5E5E5] rounded-sm text-sm focus:outline-none focus:border-[#F85606] bg-white">
                            <option value="">Select</option>
                        </select>
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-[#191919]">Address Type</label>
                    <select name="type"
                        class="w-full px-3.5 py-2.5 border border-[#E5E5E5] rounded-sm text-sm focus:outline-none focus:border-[#F85606] bg-white">
                        <option value="{{ \App\Enums\AddressType::HOME->value }}">Home</option>
                        <option value="{{ \App\Enums\AddressType::OFFICE->value }}">Office</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-[#191919]">Address</label>
                    <textarea name="address" required rows="2" placeholder="Street, area, landmark"
                        class="w-full px-3.5 py-2.5 border border-[#E5E5E5] rounded-sm text-sm focus:outline-none focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606]/20 transition-colors resize-none"></textarea>
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_default" value="1" class="accent-[#F85606]">
                    <span class="text-sm text-[#191919]">Set as default address</span>
                </label>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="toggleModal('addBillingAddressModal')"
                        class="px-4 py-2 text-sm font-medium text-[#767676] hover:text-[#191919] transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-5 py-2 bg-[#F85606] text-white text-sm font-semibold rounded-sm hover:bg-[#E04D05] transition-colors">
                        Save Address
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Address Modals --}}
    @foreach ($billingAddresses as $address)
    <div id="edit-address-modal-{{ $address->id }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="bg-white rounded-sm w-full max-w-md mx-4">
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-[#E5E5E5]">
                <h3 class="text-base font-semibold text-[#191919]">Edit Address</h3>
                <button type="button" onclick="toggleModal('edit-address-modal-{{ $address->id }}')" class="text-[#A0A0A0] hover:text-[#767676] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('billing_addresses.update', $address->id) }}" class="p-5 space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-[#191919]">Full Name</label>
                    <input type="text" name="customer_name" required value="{{ $address->customer_name }}"
                        class="w-full px-3.5 py-2.5 border border-[#E5E5E5] rounded-sm text-sm focus:outline-none focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606]/20 transition-colors">
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-[#191919]">Phone Number</label>
                    <input type="tel" name="customer_phone" required value="{{ $address->customer_phone }}"
                        class="w-full px-3.5 py-2.5 border border-[#E5E5E5] rounded-sm text-sm focus:outline-none focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606]/20 transition-colors">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-[#191919]">Division</label>
                        <select name="division_id" id="edit-division-{{ $address->id }}"
                            class="w-full px-3.5 py-2.5 border border-[#E5E5E5] rounded-sm text-sm focus:outline-none focus:border-[#F85606] bg-white division-select">
                            <option value="">Select</option>
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}" {{ $address->division_id == $division->id ? 'selected' : '' }}>{{ $division->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-[#191919]">District</label>
                        <select name="district_id" id="edit-district-{{ $address->id }}"
                            class="w-full px-3.5 py-2.5 border border-[#E5E5E5] rounded-sm text-sm focus:outline-none focus:border-[#F85606] bg-white district-select"
                            data-selected="{{ $address->district_id }}">
                            <option value="">Select</option>
                        </select>
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-[#191919]">Address Type</label>
                    <select name="type"
                        class="w-full px-3.5 py-2.5 border border-[#E5E5E5] rounded-sm text-sm focus:outline-none focus:border-[#F85606] bg-white">
                        <option value="{{ \App\Enums\AddressType::HOME->value }}" {{ $address->type == \App\Enums\AddressType::HOME->value ? 'selected' : '' }}>Home</option>
                        <option value="{{ \App\Enums\AddressType::OFFICE->value }}" {{ $address->type == \App\Enums\AddressType::OFFICE->value ? 'selected' : '' }}>Office</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-[#191919]">Address</label>
                    <textarea name="address" required rows="2"
                        class="w-full px-3.5 py-2.5 border border-[#E5E5E5] rounded-sm text-sm focus:outline-none focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606]/20 transition-colors resize-none">{{ $address->address }}</textarea>
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_default" value="1" {{ $address->is_default ? 'checked' : '' }} class="accent-[#F85606]">
                    <span class="text-sm text-[#191919]">Set as default address</span>
                </label>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="toggleModal('edit-address-modal-{{ $address->id }}')"
                        class="px-4 py-2 text-sm font-medium text-[#767676] hover:text-[#191919] transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-5 py-2 bg-[#F85606] text-white text-sm font-semibold rounded-sm hover:bg-[#E04D05] transition-colors">
                        Update Address
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    @push('scripts')
    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;
            const isHidden = modal.classList.contains('hidden');
            if (isHidden) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function deleteAddress(id) {
            if (!confirm('Are you sure you want to delete this address?')) return;
            $.ajax({
                url: '{{ route("billing_addresses.delete", "__id__") }}'.replace('__id__', id),
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function() { location.reload(); }
            });
        }

        $(document).ready(function () {
            function loadDistricts(divisionId, districtSelect, selectedId) {
                if (!divisionId) {
                    districtSelect.html('<option value="">Select</option>');
                    return;
                }
                districtSelect.html('<option value="">Loading...</option>');
                $.get('/get-districts/' + divisionId, function (data) {
                    let options = '<option value="">Select</option>';
                    $.each(data, function (id, name) {
                        options += '<option value="' + id + '">' + name + '</option>';
                    });
                    districtSelect.html(options);
                    if (selectedId) districtSelect.val(selectedId);
                });
            }

            $('#add-division').on('change', function () {
                loadDistricts($(this).val(), $('#add-district'));
            });

            $('.division-select').each(function () {
                const $div = $(this);
                const id = $div.attr('id').split('-').pop();
                const $dist = $('#edit-district-' + id);
                const selected = $dist.data('selected');
                const val = $div.val();
                if (val) loadDistricts(val, $dist, selected);
            });

            $('.division-select').on('change', function () {
                const id = $(this).attr('id').split('-').pop();
                loadDistricts($(this).val(), $('#edit-district-' + id));
            });
        });
    </script>
    @endpush
@endsection

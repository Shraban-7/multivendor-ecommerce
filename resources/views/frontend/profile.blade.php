@extends('frontend.layouts.app')
@section('title', 'My Profile')

@section('dashboard')
    <div class="space-y-6">

        {{-- Profile Header --}}
        <div class="bg-white rounded-sm border border-[#E5E5E5] p-6 flex items-center gap-5">
            <div class="relative group shrink-0">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full overflow-hidden border-2 border-[#F85606]/20">
                    <img id="preview-image" src="{{ $user->avatar }}" alt="{{ $user->name }}"
                        class="w-full h-full object-cover">
                </div>
            </div>
            <div class="min-w-0">
                <h1 class="text-lg sm:text-xl font-bold text-[#191919] truncate">{{ $user->name }}</h1>
                <p class="text-sm text-[#767676] truncate">{{ $user->email }}</p>
                <p class="text-xs text-[#A0A0A0] mt-0.5">Member since {{ $user->created_at->format('M Y') }}</p>
            </div>
        </div>

        {{-- Account Settings --}}
        <div class="bg-white rounded-sm border border-[#E5E5E5]">
            <div class="px-5 py-3.5 border-b border-[#E5E5E5]">
                <h2 class="text-base font-semibold text-[#191919]">Account Settings</h2>
            </div>
            <form action="{{ route('accountUpdate') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-5">
                @csrf

                <div class="flex items-center gap-5">
                    <div class="relative group shrink-0">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full overflow-hidden border-2 border-[#E5E5E5]">
                            <img id="avatar-preview" src="{{ $user->avatar }}" alt=""
                                class="w-full h-full object-cover">
                        </div>
                        <label for="avatar-input"
                            class="absolute inset-0 flex items-center justify-center bg-black/40 rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </label>
                        <input id="avatar-input" type="file" name="image" class="hidden" accept="image/*">
                    </div>
                    <p class="text-sm text-[#767676]">Upload a new avatar. JPG, PNG or SVG. Max 2MB.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="full-name" class="text-sm font-medium text-[#191919]">Full Name</label>
                        <input required type="text" id="full-name" name="name"
                            value="{{ old('name', $user->name) }}"
                            class="w-full px-3.5 py-2.5 border border-[#E5E5E5] rounded-sm text-sm focus:outline-none focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606]/20 transition-colors">
                    </div>
                    <div class="space-y-1.5">
                        <label for="email" class="text-sm font-medium text-[#191919]">Email</label>
                        <input required type="email" id="email" name="email"
                            value="{{ old('email', $user->email) }}"
                            class="w-full px-3.5 py-2.5 border border-[#E5E5E5] rounded-sm text-sm focus:outline-none focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606]/20 transition-colors">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="phone-number" class="text-sm font-medium text-[#191919]">Phone Number</label>
                        <input type="tel" id="phone-number" name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            class="w-full px-3.5 py-2.5 border border-[#E5E5E5] rounded-sm text-sm focus:outline-none focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606]/20 transition-colors">
                    </div>
                </div>

                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#F85606] text-white text-sm font-semibold rounded-sm hover:bg-[#E04D05] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Changes
                </button>
            </form>
        </div>

        {{-- Change Password --}}
        <div class="bg-white rounded-sm border border-[#E5E5E5]">
            <div class="px-5 py-3.5 border-b border-[#E5E5E5]">
                <h2 class="text-base font-semibold text-[#191919]">Change Password</h2>
            </div>
            <form action="{{ route('updatePassword') }}" method="POST" class="p-5 space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label for="current-password" class="text-sm font-medium text-[#191919]">Current Password</label>
                        <div class="relative">
                            <input type="password" id="current-password" name="current_password" required
                                class="w-full px-3.5 py-2.5 pr-10 border border-[#E5E5E5] rounded-sm text-sm focus:outline-none focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606]/20 transition-colors">
                            <button type="button" onclick="togglePassword('current-password', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-[#A0A0A0] hover:text-[#767676]">
                                <i class="fa-regular fa-eye text-sm"></i>
                                <i class="fa-regular fa-eye-slash text-sm hidden"></i>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label for="new-password" class="text-sm font-medium text-[#191919]">New Password</label>
                        <div class="relative">
                            <input type="password" id="new-password" name="password" required
                                class="w-full px-3.5 py-2.5 pr-10 border border-[#E5E5E5] rounded-sm text-sm focus:outline-none focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606]/20 transition-colors">
                            <button type="button" onclick="togglePassword('new-password', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-[#A0A0A0] hover:text-[#767676]">
                                <i class="fa-regular fa-eye text-sm"></i>
                                <i class="fa-regular fa-eye-slash text-sm hidden"></i>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label for="confirm-password" class="text-sm font-medium text-[#191919]">Confirm Password</label>
                        <div class="relative">
                            <input type="password" id="confirm-password" name="password_confirmation" required
                                class="w-full px-3.5 py-2.5 pr-10 border border-[#E5E5E5] rounded-sm text-sm focus:outline-none focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606]/20 transition-colors">
                            <button type="button" onclick="togglePassword('confirm-password', this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-[#A0A0A0] hover:text-[#767676]">
                                <i class="fa-regular fa-eye text-sm"></i>
                                <i class="fa-regular fa-eye-slash text-sm hidden"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#F85606] text-white text-sm font-semibold rounded-sm hover:bg-[#E04D05] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Update Password
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('avatar-input')?.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (ev) {
                document.getElementById('avatar-preview').src = ev.target.result;
            };
            reader.readAsDataURL(file);
        });
    </script>
    @endpush
@endsection

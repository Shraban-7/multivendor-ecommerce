@extends('frontend.layouts.app')
@section('title', 'Verify Account | ' . $settings->app_name)
@section('content')
    <main class="min-h-full bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            <!-- Card Container -->
            <div class="bg-white shadow-xl rounded-2xl border border-gray-200 p-8 sm:p-10">
                <!-- Header -->
                <div class="text-center space-y-3 mb-3">
                    <a href="{{ route('home') }}">
                        <img src="{{ storage_url($settings->logo_white) }}" alt="Logo"
                            class="mx-auto h-12 sm:h-16 object-contain" />
                    </a>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                        Verify Your Account
                    </h1>
                    <div class="flex items-center flex-col p-4 mb-4 text-yellow-800 rounded-lg bg-yellow-50 space-y-2">
                        <div class="ms-3 text-sm font-medium">
                            Enter the verification code sent to
                        </div>
                        <div class="font-bold text-lg">{{ $email ?? old('email') }}</div>
                    </div>

                </div>

                <!-- Verification Form -->
                <form id="verifyForm" action="{{ route('verify') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

                    <div>
                        <input type="text" name="code" id="code" maxlength="6" required
                            class="mt-2 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 text-center uppercase tracking-widest text-lg font-semibold text-gray-800 placeholder-gray-400 transition duration-200" />
                        @error('code')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Resend Code -->
                    <div class="text-center text-sm mt-4 flex items-center justify-center space-x-2">
                        <span class="text-muted">Didn't receive the code?</span>
                        <button id="resendBtn" class="text-yellow-600 font-medium hover:underline">
                            Resend Code
                        </button>
                        <span id="timer" class="text-gray-500 text-xs">
                            <span id="seconds"></span>
                        </span>
                    </div>


                    <button type="button" onclick="submitVerification()"
                        class="w-full py-3 bg-yellow-500 text-white font-semibold rounded-lg hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">
                        Verify Account
                    </button>
                </form>

                <!-- Footer -->
                <div class="text-center text-sm text-gray-500 mt-6">
                    <a href="{{ route('home') }}" class="text-yellow-600 font-medium hover:underline">
                        Back to Login
                    </a>
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <script>
            function submitVerification() {
                var $form = $('#verifyForm');
                var formData = $form.serialize();

                $.ajax({
                    url: $form.attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        toastr.success(response.message || 'Account verified successfully!');
                        window.location.href = "{{ route('home') }}";
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            for (let key in xhr.responseJSON.errors) {
                                toastr.error(xhr.responseJSON.errors[key][0]);
                            }
                        } else {
                            toastr.error(xhr.responseJSON?.message || 'Verification failed.');
                        }
                    }
                });
            }

            $(document).ready(function() {
                var $resendBtn = $('#resendBtn');
                var $secondsEl = $('#seconds');
                var interval;

                function startTimer(seconds) {
                    var remaining = seconds;
                    $resendBtn.prop('disabled', true);
                    $secondsEl.text('(' + remaining + ')');

                    interval = setInterval(function() {
                        remaining--;
                        $secondsEl.text('(' + remaining + ')');

                        if (remaining <= 0) {
                            clearInterval(interval);
                            $resendBtn.prop('disabled', false);
                            $secondsEl.text(''); // Clear timer
                        }
                    }, 1000);
                }

                // Initial countdown (if last resend is stored in session)
                var initialSeconds =
                    {{ session('last_resend_time') ? max(0, 120 - now()->diffInSeconds(session('last_resend_time'))) : 0 }};
                if (initialSeconds > 0) startTimer(initialSeconds);

                // Resend code click
                $resendBtn.click(function(e) {
                    e.preventDefault();

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('verify.resend') }}",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            toastr.success(response.message || 'Verification code resent!');
                            startTimer(response.resend_seconds || 120);
                        },
                        error: function(xhr) {
                            if (xhr.status === 429 && xhr.responseJSON?.resend_seconds) {
                                startTimer(xhr.responseJSON.resend_seconds);
                                toastr.error(xhr.responseJSON.message ||
                                    'Please wait before requesting a new code.');
                            } else {
                                toastr.error(xhr.responseJSON?.message || 'Something went wrong.');
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection

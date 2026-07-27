<div id="authModal"
    class="hidden fixed inset-0 z-[70] flex items-center justify-center transition-all duration-300 opacity-0">
    <div id="authOverlay" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300"></div>

    <div class="relative bg-white w-[95%] max-w-[420px] rounded-sm shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 translate-y-4"
        id="authBox">

        <div class="absolute top-3 right-3 z-20">
            <button id="closeAuth"
                class="w-7 h-7 flex items-center justify-center rounded-sm text-gray-400 hover:bg-[#F5F5F5] hover:text-[#191919] transition-colors duration-100">
                <i class="fa-solid fa-times text-sm"></i>
            </button>
        </div>

        <div class="text-center pt-6 pb-4 px-6 border-b border-[#E5E5E5]">
            @php $appName = app_name(); $settings = settings(); @endphp
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 mb-1">
                @if (!empty($settings?->logo_white))
                    <img src="{{ storage_url($settings->logo_white) }}" alt="{{ $appName }}" class="h-7 w-auto">
                @else
                    <span class="text-lg font-bold text-[#F85606]">{{ $appName }}</span>
                @endif
            </a>
            <p class="text-[11px] text-[#767676]">Sign in to your account</p>
        </div>

        <div class="px-6 py-5">

            <div id="step-phone" class="step-content transition-opacity duration-300">
                <h3 class="text-sm font-semibold text-[#191919] mb-1">Welcome!</h3>
                <p class="text-xs text-[#767676] mb-4">Enter your mobile number to continue.</p>

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-[#595959] mb-1">Mobile Number</label>
                        <div class="flex items-center border border-[#E5E5E5] rounded-sm overflow-hidden focus-within:border-[#F85606] focus-within:ring-1 focus-within:ring-[#F85606] transition-colors duration-100 h-10">
                            <div class="bg-[#F5F5F5] h-full flex items-center px-2.5 border-r border-[#E5E5E5] text-[#595959] text-xs font-medium select-none shrink-0">
                                +88
                            </div>
                            <input type="tel" id="inputPhone" name="phone" autocomplete="off" maxlength="11"
                                placeholder="017XXXXXXXX"
                                class="flex-1 h-full border-none focus:ring-0 text-sm text-[#191919] font-medium tracking-wide placeholder-[#C7C7C7] outline-none px-3 bg-transparent">
                            <div id="phoneCheck" class="pr-3 text-green-500 opacity-0 transition-opacity shrink-0">
                                <i class="fas fa-check-circle text-sm"></i>
                            </div>
                        </div>
                        <p id="phoneError" class="text-red-500 text-[11px] mt-1 hidden">
                            <i class="fas fa-exclamation-circle mr-1"></i> Please enter a valid 11-digit BD number
                        </p>
                    </div>

                    <button id="btnSendOtp" disabled
                        class="w-full bg-[#E5E5E5] text-[#C7C7C7] text-xs font-semibold py-2.5 rounded-sm transition-colors duration-100 cursor-not-allowed flex justify-center items-center gap-2">
                        <span>Continue</span>
                        <i class="fas fa-arrow-right text-[11px]"></i>
                    </button>

                    <p class="text-center text-xs text-[#767676] pt-2">
                        <a href="javascript:void(0)" class="text-[#F85606] font-medium hover:underline">Login with email</a>
                    </p>
                </div>
            </div>

            <div id="step-otp" class="step-content hidden transition-opacity duration-300">
                <div class="flex items-center gap-1.5 mb-3 cursor-pointer text-[#767676] hover:text-[#191919] transition-colors duration-100 w-fit"
                    onclick="auth.goToStep('phone')">
                    <i class="fas fa-arrow-left text-[10px]"></i>
                    <span class="text-[11px] font-medium">Change Number</span>
                </div>
                <h3 class="text-sm font-semibold text-[#191919] mb-1">Verify Phone</h3>
                <p class="text-xs text-[#767676] mb-4">Code sent to <span id="displayPhone" class="font-semibold text-[#191919]">+8801XXXXXXX</span></p>

                <div class="space-y-4">
                    <div class="flex justify-between gap-1.5" id="otpContainer">
                        <input type="text" maxlength="1"
                            class="otp-input w-10 h-11 border border-[#E5E5E5] rounded-sm text-center text-base font-bold text-[#191919] focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606] outline-none transition-colors duration-100">
                        <input type="text" maxlength="1"
                            class="otp-input w-10 h-11 border border-[#E5E5E5] rounded-sm text-center text-base font-bold text-[#191919] focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606] outline-none transition-colors duration-100">
                        <input type="text" maxlength="1"
                            class="otp-input w-10 h-11 border border-[#E5E5E5] rounded-sm text-center text-base font-bold text-[#191919] focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606] outline-none transition-colors duration-100">
                        <input type="text" maxlength="1"
                            class="otp-input w-10 h-11 border border-[#E5E5E5] rounded-sm text-center text-base font-bold text-[#191919] focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606] outline-none transition-colors duration-100">
                        <input type="text" maxlength="1"
                            class="otp-input w-10 h-11 border border-[#E5E5E5] rounded-sm text-center text-base font-bold text-[#191919] focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606] outline-none transition-colors duration-100">
                        <input type="text" maxlength="1"
                            class="otp-input w-10 h-11 border border-[#E5E5E5] rounded-sm text-center text-base font-bold text-[#191919] focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606] outline-none transition-colors duration-100">
                    </div>

                    <button id="btnVerifyOtp" disabled
                        class="w-full bg-[#E5E5E5] text-[#C7C7C7] text-xs font-semibold py-2.5 rounded-sm transition-colors duration-100 cursor-not-allowed">
                        Verify Code
                    </button>

                    <div class="text-center">
                        <p class="text-[11px] text-[#767676]">Didn't receive code?</p>
                        <button id="resendOtpBtn"
                            class="text-[11px] font-semibold text-[#F85606] hover:text-[#C43D00] mt-0.5 transition-colors duration-100 disabled:text-[#C7C7C7] disabled:cursor-not-allowed"
                            disabled>
                            Resend OTP (<span id="otpTimer">30</span>s)
                        </button>
                    </div>
                </div>
            </div>

            <div id="step-password" class="step-content hidden transition-opacity duration-300">
                <div class="flex items-center gap-2.5 mb-4">
                    <div class="w-10 h-10 rounded-sm bg-[#FFF1EA] text-[#F85606] flex items-center justify-center">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-[#191919]">Welcome Back!</h3>
                        <p class="text-[11px] text-[#767676]" id="welcomeUserPhone">+8801700000000</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-[#595959] mb-1">Enter Password</label>
                        <div class="relative">
                            <input type="password" id="loginPassword" name="password" autocomplete="current-password"
                                class="w-full border border-[#E5E5E5] rounded-sm px-3 py-2.5 text-sm focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606] transition-colors duration-100 outline-none text-[#191919]"
                                placeholder="••••••••">
                            <button class="absolute right-3 top-1/2 -translate-y-1/2 text-[#C7C7C7] hover:text-[#595959] transition-colors duration-100"
                                onclick="auth.togglePassword('loginPassword')">
                                <i class="far fa-eye text-sm"></i>
                            </button>
                        </div>
                        <div class="flex justify-end mt-1.5">
                            <button class="text-[11px] text-[#767676] hover:text-[#F85606] transition-colors duration-100">Forgot Password?</button>
                        </div>
                    </div>

                    <button id="btnLogin"
                        class="w-full bg-[#F85606] hover:bg-[#C43D00] text-white text-xs font-semibold py-2.5 rounded-sm transition-colors duration-100">
                        Login
                    </button>
                </div>
            </div>

            <div id="step-name" class="step-content hidden transition-opacity duration-300">
                <h3 class="text-sm font-semibold text-[#191919] mb-1">Create Account</h3>
                <p class="text-xs text-[#767676] mb-4">Looks like you're new here! Let's get you set up.</p>

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-[#595959] mb-1">Full Name</label>
                        <input type="text" id="newName" name="name" autocomplete="name"
                            class="w-full border border-[#E5E5E5] rounded-sm px-3 py-2.5 text-sm focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606] transition-colors duration-100 outline-none text-[#191919]">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-[#595959] mb-1">Set Password</label>
                        <div class="relative">
                            <input type="password" id="newPassword" name="password" autocomplete="new-password"
                                class="w-full border border-[#E5E5E5] rounded-sm px-3 py-2.5 text-sm focus:border-[#F85606] focus:ring-1 focus:ring-[#F85606] transition-colors duration-100 outline-none text-[#191919]"
                                placeholder="Min. 6 characters">
                            <button class="absolute right-3 top-1/2 -translate-y-1/2 text-[#C7C7C7] hover:text-[#595959] transition-colors duration-100"
                                onclick="auth.togglePassword('newPassword')">
                                <i class="far fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <button id="btnRegister"
                        class="w-full bg-[#F85606] hover:bg-[#C43D00] text-white text-xs font-semibold py-2.5 rounded-sm transition-colors duration-100">
                        Finish Registration
                    </button>
                </div>
            </div>

        </div>

        <div class="bg-[#FAFAFA] px-6 py-3 text-center border-t border-[#E5E5E5]">
            <p class="text-[10px] text-[#767676] leading-tight">
                By continuing, you agree to {{ $appName }}'s <a href="#" class="text-[#595959] hover:underline">Terms of Service</a> & <a href="#" class="text-[#595959] hover:underline">Privacy Policy</a>.
            </p>
        </div>
    </div>
</div>

<script>
    const API_ENDPOINTS = {
        checkPhone: "{{ route('auth.checkPhone') }}",
        verifyOtp: "{{ route('auth.verifyOtp') }}",
        login: "{{ route('auth.login') }}",
        register: "{{ route('auth.register') }}",
    };

    const PHONE_CODE = '+88';

    const auth = {
        modalId: 'authModal',
        contentId: 'authBox',
        steps: ['step-phone', 'step-otp', 'step-password', 'step-name'],
        currentPhone: '',
        otpTimerInterval: null,

        init() {
            this.cacheDOM();
            this.bindEvents();
        },

        cacheDOM() {
            this.modal = document.getElementById(this.modalId);
            this.content = document.getElementById(this.contentId);
            this.inputPhone = document.getElementById('inputPhone');
            this.btnSendOtp = document.getElementById('btnSendOtp');
            this.phoneError = document.getElementById('phoneError');
            this.phoneCheck = document.getElementById('phoneCheck');
            this.otpInputs = document.querySelectorAll('.otp-input');
            this.btnVerifyOtp = document.getElementById('btnVerifyOtp');
            this.btnLogin = document.getElementById('btnLogin');
            this.btnRegister = document.getElementById('btnRegister');
        },

        bindEvents() {
            document.getElementById('closeAuth').addEventListener('click', () => this.toggleModal(false));
            document.getElementById('authOverlay').addEventListener('click', () => this.toggleModal(false));
            this.inputPhone.addEventListener('input', (e) => this.validatePhone(e.target.value));
            this.btnSendOtp.addEventListener('click', () => {
                if (!this.btnSendOtp.disabled) this.sendOtp();
            });
            this.otpInputs.forEach((input, index) => {
                input.addEventListener('keydown', (e) => this.handleOtpKeydown(e, index));
                input.addEventListener('input', (e) => this.handleOtpInput(e, index));
            });
            this.btnVerifyOtp.addEventListener('click', () => this.verifyOtp());
            this.btnLogin.addEventListener('click', () => this.finalizeAuth(this.btnLogin, 'Logging in...',
                'Login Successful!'));
            this.btnRegister.addEventListener('click', () => this.finalizeAuth(this.btnRegister,
                'Creating Account...', 'Registration Complete!'));
        },

        goToStep(stepId) {
            this.steps.forEach(id => {
                document.getElementById(id).classList.add('hidden', 'opacity-0');
            });
            const target = document.getElementById(stepId === 'phone' ? 'step-phone' : stepId);
            target.classList.remove('hidden');
            setTimeout(() => {
                target.classList.remove('opacity-0');
            }, 50);
        },

        toggleModal(show) {
            if (show) {
                this.goToStep('step-phone');
                this.inputPhone.value = '';
                this.validatePhone('');
                this.modal.classList.remove('hidden');
                setTimeout(() => {
                    this.modal.classList.remove('opacity-0');
                    this.content.classList.remove('scale-95', 'translate-y-4');
                    this.content.classList.add('scale-100', 'translate-y-0');
                }, 10);
            } else {
                this.modal.classList.add('opacity-0');
                this.content.classList.add('scale-95', 'translate-y-4');
                this.content.classList.remove('scale-100', 'translate-y-0');
                setTimeout(() => {
                    this.modal.classList.add('hidden');
                }, 300);
            }
        },

        validatePhone(value) {
            const cleanVal = value.replace(/\D/g, '');
            this.inputPhone.value = cleanVal;
            const isValid = /^01[3-9]\d{8}$/.test(cleanVal);
            if (isValid) {
                this.btnSendOtp.disabled = false;
                this.btnSendOtp.classList.remove('bg-[#E5E5E5]', 'text-[#C7C7C7]', 'cursor-not-allowed');
                this.btnSendOtp.classList.add('bg-[#F85606]', 'text-white', 'hover:bg-[#C43D00]');
                this.phoneCheck.classList.remove('opacity-0');
                this.phoneError.classList.add('hidden');
                this.currentPhone = cleanVal;
            } else {
                this.btnSendOtp.disabled = true;
                this.btnSendOtp.classList.add('bg-[#E5E5E5]', 'text-[#C7C7C7]', 'cursor-not-allowed');
                this.btnSendOtp.classList.remove('bg-[#F85606]', 'text-white', 'hover:bg-[#C43D00]');
                this.phoneCheck.classList.add('opacity-0');
                if ((cleanVal.length > 2 && !cleanVal.startsWith('01')) || (cleanVal.length === 11 && !isValid)) {
                    this.phoneError.classList.remove('hidden');
                } else {
                    this.phoneError.classList.add('hidden');
                }
            }
        },

        async sendOtp() {
            this.btnSendOtp.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            try {
                const response = await fetch(API_ENDPOINTS.checkPhone, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        phone: this.currentPhone
                    })
                });
                const data = await response.json();
                if (response.ok) {
                    if (data.data.user_exists == true) {
                        document.getElementById('welcomeUserPhone').innerText = PHONE_CODE + this.currentPhone;
                        this.goToStep('step-password');
                    } else {
                        document.getElementById('displayPhone').innerText = PHONE_CODE + this.currentPhone;
                        this.goToStep('step-otp');
                        setTimeout(() => {
                            this.otpInputs[0].focus();
                        }, 100);
                        if (data.data.remaining_otp_time > 0) this.startOtpTimer(data.data.remaining_otp_time);
                    }
                } else {
                    this.phoneError.innerText = data.data.message || 'Error sending OTP. Please try again.';
                    this.phoneError.classList.remove('hidden');
                }
            } catch (error) {
                this.phoneError.innerText = error;
                this.phoneError.classList.remove('hidden');
            } finally {
                this.btnSendOtp.innerHTML = 'Continue <i class="fas fa-arrow-right text-[11px]"></i>';
            }
        },

        handleOtpInput(e, index) {
            const input = e.target;
            input.value = input.value.replace(/[^0-9]/g, '');
            if (input.value && index < this.otpInputs.length - 1) this.otpInputs[index + 1].focus();
            this.checkOtpComplete();
        },

        handleOtpKeydown(e, index) {
            if (e.key === 'Backspace' && !e.target.value && index > 0) this.otpInputs[index - 1].focus();
        },

        async verifyOtp() {
            let otpCode = '';
            this.otpInputs.forEach(input => otpCode += input.value);
            this.btnVerifyOtp.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';
            try {
                const response = await fetch(API_ENDPOINTS.verifyOtp, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        phone: this.currentPhone,
                        otp: otpCode
                    })
                });
                const data = await response.json();
                if (response.ok) {
                    if (data.data.is_existing_user) {
                        document.getElementById('welcomeUserPhone').innerText = PHONE_CODE + this.currentPhone;
                        this.goToStep('step-password');
                    } else {
                        this.goToStep('step-name');
                    }
                } else {
                    alert(data.data.message || 'Invalid OTP. Please try again.');
                    this.otpInputs.forEach(input => input.value = '');
                    this.otpInputs[0].focus();
                }
            } catch (error) {
                alert('Verification failed due to a network error.');
            } finally {
                this.btnVerifyOtp.innerHTML = 'Verify Code';
            }
        },

        checkOtpComplete() {
            let code = '';
            this.otpInputs.forEach(input => code += input.value);
            if (code.length === 6) {
                this.btnVerifyOtp.disabled = false;
                this.btnVerifyOtp.classList.remove('bg-[#E5E5E5]', 'text-[#C7C7C7]', 'cursor-not-allowed');
                this.btnVerifyOtp.classList.add('bg-[#F85606]', 'text-white', 'hover:bg-[#C43D00]');
            } else {
                this.btnVerifyOtp.disabled = true;
                this.btnVerifyOtp.classList.add('bg-[#E5E5E5]', 'text-[#C7C7C7]', 'cursor-not-allowed');
                this.btnVerifyOtp.classList.remove('bg-[#F85606]', 'text-white', 'hover:bg-[#C43D00]');
            }
        },

        togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling.querySelector('i');
            if (field.type === "password") {
                field.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        },

        async finalizeAuth(btnElement, loadingText, successText) {
            btnElement.disabled = true;
            const originalText = btnElement.innerText;
            btnElement.innerHTML = `<i class="fas fa-circle-notch fa-spin mr-2"></i> ${loadingText}`;
            let url = btnElement.id === 'btnLogin' ? API_ENDPOINTS.login : API_ENDPOINTS.register;
            let payload = {
                phone: this.currentPhone
            };
            if (btnElement.id === 'btnLogin') payload.password = document.getElementById('loginPassword').value;
            else {
                payload.name = document.getElementById('newName').value;
                payload.password = document.getElementById('newPassword').value;
                payload.password_confirmation = document.getElementById('newPassword').value;
            }
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify(payload)
                });
                const data = await response.json();
                if (response.ok) {
                    btnElement.classList.remove('bg-[#F85606]', 'hover:bg-[#C43D00]');
                    btnElement.classList.add('bg-green-600', 'cursor-default');
                    btnElement.innerHTML = `<i class="fas fa-check-circle mr-2"></i> ${successText}`;
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert(data.data.message || 'Authentication failed. Please check your credentials.');
                    btnElement.disabled = false;
                    btnElement.innerHTML = originalText;
                    btnElement.classList.add('bg-[#F85606]', 'hover:bg-[#C43D00]');
                    btnElement.classList.remove('bg-green-600', 'cursor-default');
                }
            } catch (error) {
                alert('A critical network error occurred.');
                btnElement.disabled = false;
                btnElement.innerHTML = originalText;
            }
        },

        startOtpTimer(seconds) {
            const btn = document.getElementById('resendOtpBtn');
            if (!btn) return;

            if (this.otpTimerInterval) clearInterval(this.otpTimerInterval);

            let remaining = parseInt(seconds, 10);
            btn.disabled = true;
            btn.textContent = `Resend OTP (${remaining}s)`;

            this.otpTimerInterval = setInterval(() => {
                remaining--;
                if (remaining <= 0) {
                    clearInterval(this.otpTimerInterval);
                    this.otpTimerInterval = null;
                    btn.disabled = false;
                    btn.textContent = 'Resend OTP';
                } else {
                    btn.textContent = `Resend OTP (${remaining}s)`;
                }
            }, 1000);
        },

        resendOtp(phone) {
            fetch(API_ENDPOINTS.checkPhone, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ phone })
            }).then(res => res.json()).then(data => {
                if (data.data.remaining_otp_time > 0) this.startOtpTimer(data.data.remaining_otp_time);
            }).catch(err => console.error('Resend OTP error:', err));
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        auth.init();
        document.querySelectorAll('.auth-btn').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                auth.toggleModal(true);
            });
        });

        const resendBtn = document.getElementById('resendOtpBtn');
        if (resendBtn) {
            resendBtn.addEventListener('click', () => {
                if (!resendBtn.disabled) auth.resendOtp(auth.currentPhone);
            });
        }
    });
</script>

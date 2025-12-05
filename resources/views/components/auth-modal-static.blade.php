<div id="authModal" class="hidden-custom fixed inset-0 z-[70] flex items-center justify-center transition-all duration-300 opacity-0">
    <div id="authOverlay" class="absolute inset-0 bg-gray-900/60 backdrop-blur-md transition-opacity duration-300"></div>

    <!-- 2. Modal Container -->
    <div class="relative bg-white w-[95%] max-w-[480px] rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 translate-y-4" id="authBox">

        <!-- Header / Close -->
        <div class="absolute top-4 right-4 z-20">
            <button id="closeAuth" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-500 transition duration-200">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <!-- Brand Header -->
        <div class="text-center pt-8 pb-4 bg-gradient-to-b from-primary-50 to-white">
            <div class="inline-flex items-center gap-2 mb-2">
                <div class="bg-primary-500 text-white p-1.5 rounded-lg shadow-lg shadow-primary-500/30">
                    <i class="fas fa-shopping-bag text-lg"></i>
                </div>
                <span class="text-xl font-extrabold tracking-tight text-gray-800">Slash<span class="text-primary-600">Mart</span></span>
            </div>
            <p class="text-xs font-medium text-gray-500 uppercase tracking-widest">Secure Login</p>
        </div>

        <!-- ==================== DYNAMIC CONTENT AREA ==================== -->
        <div class="p-6 md:p-8 pt-2">

            <!-- STEP 1: PHONE NUMBER INPUT -->
            <div id="step-phone" class="step-content transition-opacity duration-300">
                <h3 class="text-xl font-bold text-gray-900 mb-2">Welcome! 👋</h3>
                <p class="text-sm text-gray-500 mb-6">Enter your mobile number to continue.</p>

                <div class="space-y-4">
                    <div class="relative group">
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 ml-1">Mobile Number</label>
                        <div class="flex items-center border border-gray-300 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-primary-500/50 focus-within:border-primary-500 transition-all duration-200 h-12 bg-white">
                            <div class="bg-gray-50 h-full flex items-center px-3 border-r border-gray-200 text-gray-500 font-medium select-none">
                                <img src="https://flagcdn.com/w20/bd.png" class="w-5 mr-2 shadow-sm rounded-sm" alt="BD Flag">
                                +88
                            </div>
                            <input type="tel" id="inputPhone" maxlength="11" placeholder="017XXXXXXXX" class="flex-1 h-full border-none focus:ring-0 text-gray-800 font-semibold tracking-wide placeholder-gray-300 outline-none px-3 bg-transparent">
                            <div id="phoneCheck" class="pr-3 text-green-500 opacity-0 transition-opacity">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <p id="phoneError" class="text-red-500 text-xs mt-1 ml-1 hidden"><i class="fas fa-exclamation-circle mr-1"></i> Please enter a valid 11-digit BD number</p>
                    </div>

                    <button id="btnSendOtp" disabled class="w-full bg-gray-200 text-gray-400 font-bold py-3.5 rounded-xl transition-all duration-300 cursor-not-allowed flex justify-center items-center gap-2 shadow-sm">
                        <span>Continue</span>
                        <i class="fas fa-arrow-right text-sm"></i>
                    </button>

                    <div class="relative flex py-2 items-center">
                        <div class="flex-grow border-t border-gray-100"></div>
                        <span class="flex-shrink-0 mx-4 text-gray-300 text-xs">OR</span>
                        <div class="flex-grow border-t border-gray-100"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <button class="flex items-center justify-center gap-2 py-2.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-sm font-medium text-gray-600">
                            <i class="fab fa-google text-red-500"></i> Google
                        </button>
                        <button class="flex items-center justify-center gap-2 py-2.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-sm font-medium text-gray-600">
                            <i class="fab fa-facebook text-blue-600"></i> Facebook
                        </button>
                    </div>
                </div>
            </div>

            <!-- STEP 2: OTP VERIFICATION -->
            <div id="step-otp" class="step-content hidden-custom transition-opacity duration-300">
                <div class="flex items-center gap-2 mb-2 cursor-pointer text-gray-400 hover:text-gray-800 transition w-fit" onclick="auth.goToStep('phone')">
                    <i class="fas fa-arrow-left text-xs"></i> <span class="text-xs font-medium">Change Number</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Verify Phone</h3>
                <p class="text-sm text-gray-500 mb-6">Code sent to <span id="displayPhone" class="font-bold text-gray-800">+8801XXXXXXX</span></p>

                <div class="space-y-6">
                    <div class="flex justify-between gap-2" id="otpContainer">
                        <!-- 6 Inputs generated explicitly for styling -->
                        <input type="text" maxlength="1" class="otp-input w-10 md:w-12 h-12 md:h-14 border border-gray-300 rounded-lg text-center text-xl font-bold text-primary-600 focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 outline-none transition-all shadow-sm bg-gray-50 focus:bg-white">
                        <input type="text" maxlength="1" class="otp-input w-10 md:w-12 h-12 md:h-14 border border-gray-300 rounded-lg text-center text-xl font-bold text-primary-600 focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 outline-none transition-all shadow-sm bg-gray-50 focus:bg-white">
                        <input type="text" maxlength="1" class="otp-input w-10 md:w-12 h-12 md:h-14 border border-gray-300 rounded-lg text-center text-xl font-bold text-primary-600 focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 outline-none transition-all shadow-sm bg-gray-50 focus:bg-white">
                        <input type="text" maxlength="1" class="otp-input w-10 md:w-12 h-12 md:h-14 border border-gray-300 rounded-lg text-center text-xl font-bold text-primary-600 focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 outline-none transition-all shadow-sm bg-gray-50 focus:bg-white">
                        <input type="text" maxlength="1" class="otp-input w-10 md:w-12 h-12 md:h-14 border border-gray-300 rounded-lg text-center text-xl font-bold text-primary-600 focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 outline-none transition-all shadow-sm bg-gray-50 focus:bg-white">
                        <input type="text" maxlength="1" class="otp-input w-10 md:w-12 h-12 md:h-14 border border-gray-300 rounded-lg text-center text-xl font-bold text-primary-600 focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 outline-none transition-all shadow-sm bg-gray-50 focus:bg-white">
                    </div>

                    <button id="btnVerifyOtp" disabled class="w-full bg-gray-200 text-gray-400 font-bold py-3.5 rounded-xl transition-all duration-300 cursor-not-allowed shadow-sm">
                        Verify Code
                    </button>

                    <div class="text-center">
                        <p class="text-xs text-gray-500">Didn't receive code?</p>
                        <button class="text-xs font-bold text-primary-600 hover:text-primary-700 mt-1 transition">Resend OTP (30s)</button>
                    </div>
                </div>
            </div>

            <!-- STEP 3: PASSWORD (Returning User) -->
            <div id="step-password" class="step-content hidden-custom transition-opacity duration-300">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-12 h-12 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center text-xl font-bold border border-primary-100">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Welcome Back!</h3>
                        <p class="text-xs text-gray-500" id="welcomeUserPhone">+8801700000000</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 ml-1">Enter Password</label>
                        <div class="relative">
                            <input type="password" id="loginPassword" class="w-full border border-gray-300 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 transition outline-none text-gray-800" placeholder="••••••••">
                            <button class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600" onclick="auth.togglePassword('loginPassword')">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        <div class="flex justify-end mt-2">
                            <button class="text-xs text-gray-500 hover:text-primary-600 transition">Forgot Password?</button>
                        </div>
                    </div>

                    <button id="btnLogin" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3.5 rounded-xl transition-all duration-300 shadow-lg shadow-primary-500/30">
                        Secure Login
                    </button>
                </div>
            </div>

            <!-- STEP 4: NAME (New User) -->
            <div id="step-name" class="step-content hidden-custom transition-opacity duration-300">
                <h3 class="text-xl font-bold text-gray-900 mb-2">Create Account</h3>
                <p class="text-sm text-gray-500 mb-6">Looks like you're new here! Let's get you set up.</p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 ml-1">Full Name</label>
                        <input type="text" id="newName" class="w-full border border-gray-300 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 transition outline-none text-gray-800" placeholder="e.g. Rafiqul Islam">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5 ml-1">Set Password</label>
                        <div class="relative">
                            <input type="password" id="newPassword" class="w-full border border-gray-300 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 transition outline-none text-gray-800" placeholder="Min. 6 characters">
                            <button class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600" onclick="auth.togglePassword('newPassword')">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button id="btnRegister" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3.5 rounded-xl transition-all duration-300 shadow-lg shadow-primary-500/30">
                        Finish Setup
                    </button>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-8 py-4 text-center border-t border-gray-100">
            <p class="text-[10px] text-gray-400 leading-tight">
                By continuing, you agree to SlashMart's <a href="#" class="text-gray-600 hover:underline">Terms of Service</a> & <a href="#" class="text-gray-600 hover:underline">Privacy Policy</a>.
            </p>
        </div>
    </div>
</div>

<!-- Auth modal script -->
<script>
    /**
     * AUTHENTICATION CONTROLLER
     * Handles modal states, validation, and Unified Auth Flow (Login/Register split)
     */
    const auth = {
        // --- CONFIGURATION ---
        modalId: 'authModal',
        contentId: 'authBox',
        steps: ['step-phone', 'step-otp', 'step-password', 'step-name'],
        currentPhone: '',

        // --- INITIALIZATION ---
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

            // [NEW] Cache the final buttons
            this.btnLogin = document.getElementById('btnLogin');
            this.btnRegister = document.getElementById('btnRegister');

            this.triggerBtns = document.querySelectorAll('a[href="#"]');
        },

        bindEvents() {
            // ... existing listeners (close, phone input, send otp, verify otp) ...
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

            // [NEW] Bind final actions
            this.btnLogin.addEventListener('click', () => this.finalizeAuth(this.btnLogin, 'Logging in...', 'Login Successful!'));
            this.btnRegister.addEventListener('click', () => this.finalizeAuth(this.btnRegister, 'Creating Account...', 'Setup Complete!'));
        },

        // --- MODAL CONTROLS ---
        toggleModal(show) {
            if (show) {
                // Reset to start
                this.goToStep('step-phone');
                this.inputPhone.value = '';
                this.validatePhone('');

                this.modal.classList.remove('hidden-custom');
                // Animation delay
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
                    this.modal.classList.add('hidden-custom');
                }, 300);
            }
        },

        goToStep(stepId) {
            // Hide all steps
            this.steps.forEach(id => {
                document.getElementById(id).classList.add('hidden-custom');
                document.getElementById(id).classList.add('opacity-0');
            });

            // Show target step
            const target = document.getElementById(stepId === 'phone' ? 'step-phone' : stepId);
            target.classList.remove('hidden-custom');

            // Small delay for fade-in effect
            setTimeout(() => {
                target.classList.remove('opacity-0');
            }, 50);
        },

        // --- VALIDATION LOGIC ---
        validatePhone(value) {
            // Remove non-numeric
            const cleanVal = value.replace(/\D/g, '');
            this.inputPhone.value = cleanVal;

            // Regex: Starts with 01, followed by 3-9, and total 11 digits
            const isValid = /^01[3-9]\d{8}$/.test(cleanVal);

            if (isValid) {
                this.btnSendOtp.disabled = false;
                this.btnSendOtp.classList.remove('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
                this.btnSendOtp.classList.add('bg-primary-600', 'text-white', 'hover:bg-primary-700', 'shadow-lg', 'shadow-primary-500/30');
                this.phoneCheck.classList.remove('opacity-0');
                this.phoneError.classList.add('hidden');
                this.currentPhone = cleanVal;
            } else {
                this.btnSendOtp.disabled = true;
                this.btnSendOtp.classList.add('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
                this.btnSendOtp.classList.remove('bg-primary-600', 'text-white', 'hover:bg-primary-700', 'shadow-lg', 'shadow-primary-500/30');
                this.phoneCheck.classList.add('opacity-0');

                // Only show error if length is close to 11 but invalid
                if (cleanVal.length > 2 && !cleanVal.startsWith('01')) {
                    this.phoneError.classList.remove('hidden');
                } else if (cleanVal.length === 11 && !isValid) {
                    this.phoneError.classList.remove('hidden');
                } else {
                    this.phoneError.classList.add('hidden');
                }
            }
        },

        sendOtp() {
            // Simulate loading
            this.btnSendOtp.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            setTimeout(() => {
                this.btnSendOtp.innerHTML = 'Continue <i class="fas fa-arrow-right text-sm"></i>';
                document.getElementById('displayPhone').innerText = '+88' + this.currentPhone;
                this.goToStep('step-otp');
                // Auto focus first OTP box
                setTimeout(() => this.otpInputs[0].focus(), 100);
            }, 800);
        },

        // --- OTP HANDLING ---
        handleOtpInput(e, index) {
            const input = e.target;
            const value = input.value;

            // Ensure only number
            input.value = value.replace(/[^0-9]/g, '');

            if (input.value && index < this.otpInputs.length - 1) {
                this.otpInputs[index + 1].focus();
            }

            this.checkOtpComplete();
        },

        handleOtpKeydown(e, index) {
            if (e.key === 'Backspace') {
                if (!e.target.value && index > 0) {
                    this.otpInputs[index - 1].focus();
                }
            }
        },

        checkOtpComplete() {
            let code = '';
            this.otpInputs.forEach(input => code += input.value);

            if (code.length === 6) {
                this.btnVerifyOtp.disabled = false;
                this.btnVerifyOtp.classList.remove('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
                this.btnVerifyOtp.classList.add('bg-primary-600', 'text-white', 'hover:bg-primary-700', 'shadow-lg', 'shadow-primary-500/30');
            } else {
                this.btnVerifyOtp.disabled = true;
                this.btnVerifyOtp.classList.add('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
                this.btnVerifyOtp.classList.remove('bg-primary-600', 'text-white', 'hover:bg-primary-700', 'shadow-lg', 'shadow-primary-500/30');
            }
        },

        verifyOtp() {
            this.btnVerifyOtp.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';

            setTimeout(() => {
                this.btnVerifyOtp.innerHTML = 'Verify Code';

                // MOCK API LOGIC:
                // If number ends in "00", treat as EXISTING user.
                // Else treat as NEW user.
                if (this.currentPhone.endsWith('00')) {
                    document.getElementById('welcomeUserPhone').innerText = '+88' + this.currentPhone;
                    this.goToStep('step-password');
                } else {
                    this.goToStep('step-name');
                }
            }, 1000);
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

        // [NEW] Handles the final loading, success state, and page reload
        finalizeAuth(btnElement, loadingText, successText) {
            // 1. Disable button to prevent double clicks
            btnElement.disabled = true;

            // 2. Show Loading Spinner
            const originalText = btnElement.innerText;
            btnElement.innerHTML = `<i class="fas fa-circle-notch fa-spin mr-2"></i> ${loadingText}`;

            // 3. Simulate Network Delay (1.5 seconds)
            setTimeout(() => {
                // 4. Show Success State
                // Remove primary brand color, add Green success color
                btnElement.classList.remove('bg-primary-600', 'hover:bg-primary-700', 'shadow-primary-500/30');
                btnElement.classList.add('bg-green-600', 'shadow-green-500/30', 'cursor-default');

                btnElement.innerHTML = `<i class="fas fa-check-circle mr-2"></i> ${successText}`;

                // 5. Reload Page after short delay so user sees the checkmark
                setTimeout(() => {
                    window.location.reload();
                }, 1000);

            }, 1500);
        }
    };



    // Initialize logic
    document.addEventListener('DOMContentLoaded', () => {
        auth.init();

        //Attach to Login buttons from header (find the nav link)
        const headerLoginBtn = Array.from(document.querySelectorAll('a')).find(el => el.textContent.includes('Login'));
        if (headerLoginBtn) {
            headerLoginBtn.addEventListener('click', (e) => {
                e.preventDefault();
                auth.toggleModal(true);
            });
        }
    });
</script>
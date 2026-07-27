@extends('frontend.auth.seller-layout')

@section('content')
    <style>
        .seller-right-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: var(--space-6) var(--space-8);
            border-bottom: 1px solid var(--color-border-default);
            flex-shrink: 0;
        }
        .seller-right-header .step-label {
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--color-text-tertiary);
        }
        .seller-right-header .step-label strong { color: var(--color-text-primary); }
        .seller-right-header .close-link {
            font-size: 0.8125rem;
            color: var(--color-text-secondary);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: var(--space-2);
            transition: color var(--motion-fast) var(--motion-easing);
        }
        .seller-right-header .close-link:hover { color: var(--color-text-primary); }

        .seller-right-body {
            padding: var(--space-8) var(--space-9) var(--space-9);
            flex: 1;
            overflow-y: auto;
        }
        @media (max-width: 640px) {
            .seller-right-header { padding: var(--space-5) var(--space-6); }
            .seller-right-body { padding: var(--space-6) var(--space-6) var(--space-8); }
        }

        /* ---------- Labeled progress track ---------- */
        .progress-track {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: var(--space-3);
            margin-bottom: var(--space-9);
        }
        .progress-track__bar {
            height: 4px;
            border-radius: 999px;
            background: var(--color-border-default);
            overflow: hidden;
        }
        .progress-track__bar-fill {
            height: 100%;
            width: 0%;
            background: var(--color-brand-primary);
            border-radius: 999px;
            transition: width var(--motion-standard) var(--motion-easing);
        }
        .progress-track__item.completed .progress-track__bar-fill { width: 100%; }
        .progress-track__item.active .progress-track__bar-fill { width: 50%; }
        .progress-track__label {
            margin-top: var(--space-3);
            font-size: 0.6875rem;
            font-weight: 600;
            color: var(--color-text-tertiary);
        }
        .progress-track__item.active .progress-track__label,
        .progress-track__item.completed .progress-track__label { color: var(--color-text-primary); }

        .form-step { display: none; }
        .form-step.active {
            display: block;
            animation: step-in var(--motion-standard) var(--motion-easing);
        }
        @keyframes step-in {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .step-title {
            font-size: 1.375rem;
            font-weight: 800;
            color: var(--color-text-primary);
            letter-spacing: -0.01em;
            margin-bottom: var(--space-2);
        }
        .step-subtitle {
            font-size: 0.875rem;
            color: var(--color-text-secondary);
            margin-bottom: var(--space-8);
        }

        .form-group { margin-bottom: var(--space-7); }
        .form-label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--color-text-primary);
            margin-bottom: var(--space-3);
        }
        .form-label .required { color: var(--color-feedback-danger); margin-left: 2px; }

        .form-input {
            width: 100%;
            height: 46px;
            padding: 0 var(--space-6);
            border: 1.5px solid var(--color-border-default);
            border-radius: var(--radius-sm);
            font-size: 0.9375rem;
            font-family: var(--font-family-primary);
            color: var(--color-text-primary);
            background: var(--color-surface-base);
            transition: border-color var(--motion-fast) var(--motion-easing), box-shadow var(--motion-fast) var(--motion-easing);
            outline: none;
        }
        .form-input::placeholder { color: #b3b3b3; }
        .form-input:hover:not(:focus):not(:disabled) { border-color: var(--color-border-strong); }
        .form-input:focus { border-color: var(--color-brand-primary); box-shadow: var(--shadow-focus); }
        .form-input.error { border-color: var(--color-feedback-danger); box-shadow: 0 0 0 3px rgba(217,48,37,0.1); }
        .form-input:disabled { background: var(--color-surface-muted); cursor: not-allowed; opacity: 0.6; }

        textarea.form-input { height: auto; min-height: 96px; padding-top: var(--space-5); resize: vertical; }
        select.form-input {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23767676' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right var(--space-6) center;
            padding-right: var(--space-9);
        }

        .form-error {
            font-size: 0.75rem;
            color: var(--color-feedback-danger);
            margin-top: var(--space-3);
            display: none;
            font-weight: 500;
        }
        .form-error.visible { display: block; }
        .form-helper { font-size: 0.75rem; color: var(--color-text-tertiary); margin-top: var(--space-3); }

        .input-with-icon { position: relative; }
        .input-with-icon .form-input { padding-right: var(--space-9); }
        .input-icon {
            position: absolute; right: var(--space-5); top: 50%; transform: translateY(-50%);
            color: var(--color-text-tertiary); cursor: pointer; font-size: 1rem;
            background: none; border: none; padding: var(--space-3);
            border-radius: var(--radius-xs);
        }
        .input-icon:hover { color: var(--color-text-secondary); }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: var(--space-4);
            height: 48px; padding: 0 var(--space-8);
            border-radius: var(--radius-sm);
            font-size: 0.9375rem; font-weight: 700;
            font-family: var(--font-family-primary);
            border: none; cursor: pointer;
            transition: background var(--motion-fast) var(--motion-easing), border-color var(--motion-fast) var(--motion-easing), color var(--motion-fast) var(--motion-easing);
            text-decoration: none;
        }
        .btn-block { width: 100%; }
        .btn-primary { background: var(--color-brand-primary); color: #fff; }
        .btn-primary:hover:not(:disabled) { background: var(--color-brand-primary-deep); }
        .btn-primary:disabled { opacity: 0.5; cursor: not-allowed; }
        .btn-outline { background: #fff; color: var(--color-text-secondary); border: 1.5px solid var(--color-border-default); }
        .btn-outline:hover:not(:disabled) { border-color: var(--color-brand-primary); color: var(--color-brand-primary-deep); }
        .btn-outline:disabled { opacity: 0.5; cursor: not-allowed; }

        .btn .spinner {
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,0.35);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: none;
        }
        .btn-outline .spinner { border: 2px solid rgba(89,89,89,0.25); border-top-color: var(--color-text-secondary); }
        .btn.loading .spinner { display: inline-block; }
        .btn.loading .btn-text { display: none; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .btn-group { display: flex; gap: var(--space-5); margin-top: var(--space-8); }
        .btn-group .btn { flex: 1; }

        .shop-type-grid { display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-5); }
        .shop-type-card {
            border: 1.5px solid var(--color-border-default);
            border-radius: var(--radius-md);
            padding: var(--space-7) var(--space-6);
            text-align: center;
            cursor: pointer;
            transition: border-color var(--motion-fast) var(--motion-easing), background var(--motion-fast) var(--motion-easing), box-shadow var(--motion-fast) var(--motion-easing);
            background: #fff;
        }
        .shop-type-card:hover { border-color: var(--color-brand-primary); }
        .shop-type-card:focus-visible { outline: 2px solid var(--color-brand-primary-deep); outline-offset: 2px; }
        .shop-type-card.selected {
            border-color: var(--color-brand-primary);
            background: var(--color-brand-primary-tint);
            box-shadow: var(--shadow-focus);
        }
        .shop-type-card .icon {
            width: 40px; height: 40px; margin: 0 auto var(--space-5);
            border-radius: var(--radius-sm);
            background: var(--color-surface-muted);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.125rem; color: var(--color-brand-primary);
        }
        .shop-type-card.selected .icon { background: #fff; }
        .shop-type-card .title { font-size: 0.9375rem; font-weight: 700; color: var(--color-text-primary); }
        .shop-type-card .desc { font-size: 0.75rem; color: var(--color-text-tertiary); margin-top: var(--space-2); }

        .otp-group { display: flex; gap: var(--space-4); justify-content: center; }
        .otp-input {
            width: 46px; height: 52px; text-align: center;
            font-size: 1.25rem; font-weight: 700;
            font-family: var(--font-family-primary);
            border: 1.5px solid var(--color-border-default);
            border-radius: var(--radius-sm);
            outline: none;
            transition: border-color var(--motion-fast) var(--motion-easing), box-shadow var(--motion-fast) var(--motion-easing);
        }
        .otp-input:focus { border-color: var(--color-brand-primary); box-shadow: var(--shadow-focus); }
        .otp-input.error { border-color: var(--color-feedback-danger); }

        .otp-timer { font-size: 0.8125rem; color: var(--color-text-secondary); text-align: center; margin-top: var(--space-6); }
        .otp-timer .resend { color: var(--color-brand-primary-deep); cursor: pointer; font-weight: 700; text-decoration: none; }
        .otp-timer .resend:hover { text-decoration: underline; }
        .otp-timer .resend.disabled { color: var(--color-text-tertiary); cursor: not-allowed; text-decoration: none; }

        #form-error-summary {
            background: var(--color-feedback-danger-tint);
            border: 1px solid rgba(217,48,37,0.25);
            border-radius: var(--radius-sm);
            padding: var(--space-6);
            margin-bottom: var(--space-7);
        }

        @media (max-width: 640px) {
            .shop-type-grid { grid-template-columns: 1fr; }
            .progress-track { gap: var(--space-2); }
        }
    </style>

    <div class="seller-right-header">
        <span class="step-label">Step <strong id="step-number">1</strong> of 4</span>
        <a href="{{ url('/') }}" class="close-link">&larr; Back to home</a>
    </div>
    <div class="seller-right-body">
        <div class="progress-track" role="navigation" aria-label="Signup progress">
            <div class="progress-track__item active" id="progress-item-1">
                <div class="progress-track__bar"><div class="progress-track__bar-fill"></div></div>
                <div class="progress-track__label">Phone</div>
            </div>
            <div class="progress-track__item" id="progress-item-2">
                <div class="progress-track__bar"><div class="progress-track__bar-fill"></div></div>
                <div class="progress-track__label">Account</div>
            </div>
            <div class="progress-track__item" id="progress-item-3">
                <div class="progress-track__bar"><div class="progress-track__bar-fill"></div></div>
                <div class="progress-track__label">Shop</div>
            </div>
            <div class="progress-track__item" id="progress-item-4">
                <div class="progress-track__bar"><div class="progress-track__bar-fill"></div></div>
                <div class="progress-track__label">Documents</div>
            </div>
        </div>

        <form id="sellerForm" novalidate>
            <div id="form-error-summary" style="display:none" role="alert"></div>
            {{-- Step 1: Phone OTP Verification --}}
            <div class="form-step active" id="step-1" data-step="1">
                <div class="step-title">Verify your phone</div>
                <div class="step-subtitle">We'll send a one-time code to confirm your number</div>
                <div class="form-group">
                    <label class="form-label" for="phone">Phone Number <span class="required">*</span></label>
                    <input type="tel" id="phone" name="phone" class="form-input" placeholder="01XXXXXXXXX"
                        autocomplete="tel" maxlength="11" inputmode="numeric"
                        aria-describedby="phone-error phone-helper">
                    <div class="form-helper" id="phone-helper">Enter your mobile number to receive a verification code</div>
                    <div class="form-error" id="phone-error" role="alert"></div>
                </div>

                <div id="otp-section" style="display:none">
                    <div class="form-group">
                        <label class="form-label">Verification Code <span class="required">*</span></label>
                        <div class="otp-group" role="group" aria-label="Enter 6-digit verification code">
                            @for ($i = 0; $i < 6; $i++)
                                <input type="text" class="otp-input" id="otp-{{ $i }}" maxlength="1"
                                    inputmode="numeric" pattern="[0-9]" autocomplete="one-time-code"
                                    aria-label="Digit {{ $i + 1 }}">
                            @endfor
                        </div>
                        <input type="hidden" id="otp" name="otp">
                        <div class="form-error" id="otp-error" role="alert"></div>
                    </div>
                    <div class="otp-timer" id="otp-timer" aria-live="polite">
                        <span id="timer-text">Resend code in <strong id="countdown">60</strong>s</span>
                        <a href="#" class="resend disabled" id="resend-otp">Resend Code</a>
                    </div>
                </div>

                <div class="btn-group" id="step-1-actions">
                    <button type="button" class="btn btn-primary btn-block" id="send-otp-btn">
                        <span class="btn-text">Send Code</span>
                        <span class="spinner"></span>
                    </button>
                    <button type="button" class="btn btn-primary btn-block" id="verify-otp-btn" style="display:none">
                        <span class="btn-text">Verify Code</span>
                        <span class="spinner"></span>
                    </button>
                </div>
            </div>

            {{-- Step 2: Name, Email, Password --}}
            <div class="form-step" id="step-2" data-step="2">
                <div class="step-title">Create your account</div>
                <div class="step-subtitle">Set up your seller credentials</div>
                <div class="form-group">
                    <label class="form-label" for="name">Full Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name" class="form-input" placeholder="Your full name"
                        autocomplete="name" aria-describedby="name-error">
                    <div class="form-error" id="name-error" role="alert"></div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email Address <span class="required">*</span></label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="you@example.com"
                        autocomplete="email" aria-describedby="email-error email-helper">
                    <div class="form-helper" id="email-helper">We'll send a verification link to this email</div>
                    <div class="form-error" id="email-error" role="alert"></div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password <span class="required">*</span></label>
                    <div class="input-with-icon">
                        <input type="password" id="password" name="password" class="form-input" placeholder="Min. 5 characters"
                            autocomplete="new-password" minlength="5" aria-describedby="password-error">
                        <button type="button" class="input-icon" id="toggle-password" aria-label="Show password"
                            onclick="togglePass('password', this)">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <div class="form-error" id="password-error" role="alert"></div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirm Password <span class="required">*</span></label>
                    <div class="input-with-icon">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input"
                            placeholder="Re-enter password" autocomplete="new-password"
                            aria-describedby="password_confirmation-error">
                        <button type="button" class="input-icon" aria-label="Show password"
                            onclick="togglePass('password_confirmation', this)">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <div class="form-error" id="password_confirmation-error" role="alert"></div>
                </div>

                <div class="btn-group">
                    <button type="button" class="btn btn-outline" id="back-to-step-1">Back</button>
                    <button type="button" class="btn btn-primary" id="submit-step-2">
                        <span class="btn-text">Continue</span>
                        <span class="spinner"></span>
                    </button>
                </div>
            </div>

            {{-- Step 3: Shop Details --}}
            <div class="form-step" id="step-3" data-step="3">
                <div class="step-title">Shop details</div>
                <div class="step-subtitle">Tell us about your business</div>
                <div class="form-group">
                    <label class="form-label" for="business_name">Shop Name <span class="required">*</span></label>
                    <input type="text" id="business_name" name="business_name" class="form-input"
                        placeholder="Your shop name" autocomplete="organization"
                        aria-describedby="business_name-error business_name-helper">
                    <div class="form-helper" id="business_name-helper">This will be displayed to customers</div>
                    <div class="form-error" id="business_name-error" role="alert"></div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="business_email">Business Email <span class="required">*</span></label>
                    <input type="email" id="business_email" name="business_email" class="form-input"
                        placeholder="shop@example.com" autocomplete="email"
                        aria-describedby="business_email-error">
                    <div class="form-error" id="business_email-error" role="alert"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Shop Type <span class="required">*</span></label>
                    <div class="shop-type-grid" role="radiogroup" aria-label="Select shop type">
                        <div class="shop-type-card" data-value="individual" tabindex="0" role="radio"
                            aria-checked="false" onclick="selectShopType(this)">
                            <div class="icon"><i class="fa-solid fa-user"></i></div>
                            <div class="title">Individual</div>
                            <div class="desc">Sell as an individual seller</div>
                        </div>
                        <div class="shop-type-card" data-value="organization" tabindex="0" role="radio"
                            aria-checked="false" onclick="selectShopType(this)">
                            <div class="icon"><i class="fa-solid fa-building"></i></div>
                            <div class="title">Organization</div>
                            <div class="desc">Sell as a company or brand</div>
                        </div>
                    </div>
                    <input type="hidden" name="shop_type" id="shop_type">
                    <div class="form-error" id="shop_type-error" role="alert"></div>
                </div>

                <div class="btn-group">
                    <button type="button" class="btn btn-outline" id="back-to-step-2">Back</button>
                    <button type="button" class="btn btn-primary" id="submit-step-3">
                        <span class="btn-text">Continue</span>
                        <span class="spinner"></span>
                    </button>
                </div>
            </div>

            {{-- Step 4: Documents & Address --}}
            <div class="form-step" id="step-4" data-step="4">
                <div class="step-title">Verification &amp; documents</div>
                <div class="step-subtitle">Upload your documents to complete registration</div>
                <div class="form-group">
                    <label class="form-label" for="nid_no">NID Number <span class="required">*</span></label>
                    <input type="text" id="nid_no" name="nid_no" class="form-input" placeholder="National ID number"
                        maxlength="50" aria-describedby="nid_no-error">
                    <div class="form-error" id="nid_no-error" role="alert"></div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="business_address">Business Address <span class="required">*</span></label>
                    <textarea id="business_address" name="business_address" class="form-input" placeholder="Street, area, city"
                        maxlength="1000" aria-describedby="business_address-error"></textarea>
                    <div class="form-error" id="business_address-error" role="alert"></div>
                </div>

                <div class="form-group" style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-5)">
                    <div>
                        <label class="form-label" for="division_id">Division <span class="required">*</span></label>
                        <select id="division_id" name="division_id" class="form-input"
                            aria-describedby="division_id-error">
                            <option value="">Select</option>
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}">{{ $division->name }}</option>
                            @endforeach
                        </select>
                        <div class="form-error" id="division_id-error" role="alert"></div>
                    </div>
                    <div>
                        <label class="form-label" for="district_id">District <span class="required">*</span></label>
                        <select id="district_id" name="district_id" class="form-input"
                            aria-describedby="district_id-error">
                            <option value="">Select</option>
                        </select>
                        <div class="form-error" id="district_id-error" role="alert"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="trade_license_no">Trade License No <span class="required">*</span></label>
                    <input type="text" id="trade_license_no" name="trade_license_no" class="form-input"
                        placeholder="Trade license number" maxlength="100"
                        aria-describedby="trade_license_no-error">
                    <div class="form-error" id="trade_license_no-error" role="alert"></div>
                </div>

                <div class="btn-group" id="step-4-actions">
                    <button type="button" class="btn btn-outline" id="back-to-step-3">Back</button>
                    <button type="button" class="btn btn-primary" id="submit-step-4">
                        <span class="btn-text">Register</span>
                        <span class="spinner"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>{{-- /seller-right-body --}}

    @push('scripts')
    <script>
        var otpTimer = null;
        var otpSeconds = 60;
        var verifiedPhone = '';
        var currentStep = 1;

        function togglePass(id, btn) {
            var input = $('#' + id);
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                $(btn).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                $(btn).attr('aria-label', 'Hide password');
            } else {
                input.attr('type', 'password');
                $(btn).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                $(btn).attr('aria-label', 'Show password');
            }
        }

        function selectShopType(el) {
            $('.shop-type-card').removeClass('selected').attr('aria-checked', 'false');
            $(el).addClass('selected').attr('aria-checked', 'true');
            $('#shop_type').val($(el).data('value'));
            $('#shop_type-error').removeClass('visible').text('');
        }

        function goToStep(step) {
            currentStep = step;
            $('.form-step').removeClass('active');
            $('#step-' + step).addClass('active');
            $('.progress-track__item').removeClass('active').removeClass('completed');
            for (var i = 1; i < step; i++) {
                $('#progress-item-' + i).addClass('completed');
            }
            $('#progress-item-' + step).addClass('active');
            $('#step-number').text(step);
            $('.seller-right-body').scrollTop(0);
        }

        // OTP inputs auto-focus
        $(document).on('input', '.otp-input', function() {
            var val = $(this).val().replace(/\D/g, '');
            $(this).val(val);
            if (val && $(this).next('.otp-input').length) {
                $(this).next('.otp-input').focus();
            }
            var otp = '';
            $('.otp-input').each(function() { otp += $(this).val(); });
            $('#otp').val(otp);
            if (otp.length === 6) {
                $('#verify-otp-btn').click();
            }
        });

        $(document).on('keydown', '.otp-input', function(e) {
            if (e.key === 'Backspace' && !$(this).val() && $(this).prev('.otp-input').length) {
                $(this).prev('.otp-input').focus();
            }
        });

        // Send OTP
        $('#send-otp-btn').click(function() {
            var $btn = $(this);
            var phone = $('#phone').val().trim();
            if (!phone) {
                $('#phone-error').text('Please enter your phone number.').addClass('visible');
                $('#phone').addClass('error');
                return;
            }
            $('#phone-error').removeClass('visible').text('');
            $('#phone').removeClass('error');
            $btn.addClass('loading').prop('disabled', true);

            $.ajax({
                url: '{{ route("auth.checkPhone") }}',
                type: 'POST',
                data: { phone: phone },
                success: function(res) {
                    if (res.data && res.data.user_exists) {
                        $('#phone-error').text('This phone number is already registered.').addClass('visible');
                        $('#phone').addClass('error');
                        $btn.removeClass('loading').prop('disabled', false);
                        return;
                    }
                    startOtpTimer();
                    $('#otp-section').show();
                    $('#send-otp-btn').hide();
                    $('#verify-otp-btn').show();
                    showToast('OTP sent to your phone.');
                },
                error: function(xhr) {
                    var msg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Failed to send OTP.';
                    $('#phone-error').text(msg).addClass('visible');
                    $('#phone').addClass('error');
                },
                complete: function() {
                    $btn.removeClass('loading').prop('disabled', false);
                }
            });
        });

        // Resend OTP
        $('#resend-otp').click(function(e) {
            e.preventDefault();
            if ($(this).hasClass('disabled')) return;
            $('#send-otp-btn').click();
        });

        function startOtpTimer() {
            otpSeconds = 60;
            $('#resend-otp').addClass('disabled');
            $('#timer-text').show();
            $('#countdown').text(otpSeconds);
            if (otpTimer) clearInterval(otpTimer);
            otpTimer = setInterval(function() {
                otpSeconds--;
                $('#countdown').text(otpSeconds);
                if (otpSeconds <= 0) {
                    clearInterval(otpTimer);
                    $('#timer-text').hide();
                    $('#resend-otp').removeClass('disabled');
                }
            }, 1000);
        }

        // Verify OTP
        $('#verify-otp-btn').click(function() {
            var $btn = $(this);
            var otp = $('#otp').val();
            var phone = $('#phone').val().trim();
            if (otp.length !== 6) {
                $('#otp-error').text('Please enter the full 6-digit code.').addClass('visible');
                return;
            }
            $('#otp-error').removeClass('visible').text('');
            $btn.addClass('loading').prop('disabled', true);

            $.ajax({
                url: '{{ route("auth.verifyOtp") }}',
                type: 'POST',
                data: { phone: phone, otp: otp },
                success: function(res) {
                    verifiedPhone = phone;
                    showToast('Phone verified successfully!');
                    if (otpTimer) clearInterval(otpTimer);
                    goToStep(2);
                },
                error: function(xhr) {
                    var msg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Invalid or expired code.';
                    $('#otp-error').text(msg).addClass('visible');
                },
                complete: function() {
                    $btn.removeClass('loading').prop('disabled', false);
                }
            });
        });

        // Back buttons
        $('#back-to-step-1').click(function() { goToStep(1); });
        $('#back-to-step-2').click(function() { goToStep(2); });
        $('#back-to-step-3').click(function() { goToStep(3); });

        // Step 2 submit
        $('#submit-step-2').click(function() {
            var $btn = $(this);
            var data = {
                step: 1,
                name: $('#name').val().trim(),
                email: $('#email').val().trim(),
                phone: verifiedPhone,
                password: $('#password').val(),
                password_confirmation: $('#password_confirmation').val(),
            };
            if (!data.name) { return showFieldError('name', 'Name is required.'); }
            if (!data.email) { return showFieldError('email', 'Email is required.'); }
            if (data.password.length < 5) { return showFieldError('password', 'Password must be at least 5 characters.'); }
            if (data.password !== data.password_confirmation) { return showFieldError('password_confirmation', 'Passwords do not match.'); }

            $btn.addClass('loading').prop('disabled', true);
            clearErrors();

            $.ajax({
                url: '{{ route("seller.signup") }}',
                type: 'POST',
                data: data,
                success: function(res) {
                    if (res.data && res.data.next_step) {
                        goToStep(res.data.next_step);
                    }
                },
                error: function(xhr) {
                    handleValidationErrors(xhr);
                },
                complete: function() {
                    $btn.removeClass('loading').prop('disabled', false);
                }
            });
        });

        // Step 3 submit
        $('#submit-step-3').click(function() {
            var $btn = $(this);
            var data = {
                step: 2,
                business_name: $('#business_name').val().trim(),
                business_email: $('#business_email').val().trim(),
                shop_type: $('#shop_type').val(),
            };
            if (!data.business_name) { return showFieldError('business_name', 'Shop name is required.'); }
            if (!data.business_email) { return showFieldError('business_email', 'Business email is required.'); }
            if (!data.shop_type) { return showFieldError('shop_type', 'Please select a shop type.'); }

            $btn.addClass('loading').prop('disabled', true);
            clearErrors();

            $.ajax({
                url: '{{ route("seller.signup") }}',
                type: 'POST',
                data: data,
                success: function(res) {
                    if (res.data && res.data.next_step) {
                        goToStep(res.data.next_step);
                    }
                },
                error: function(xhr) {
                    handleValidationErrors(xhr);
                },
                complete: function() {
                    $btn.removeClass('loading').prop('disabled', false);
                }
            });
        });

        // Step 4 submit (final)
        $('#submit-step-4').click(function() {
            var $btn = $(this);
            var data = {
                step: 3,
                nid_no: $('#nid_no').val().trim(),
                business_address: $('#business_address').val().trim(),
                division_id: $('#division_id').val(),
                district_id: $('#district_id').val(),
                trade_license_no: $('#trade_license_no').val().trim(),
            };
            if (!data.nid_no) { return showFieldError('nid_no', 'NID number is required.'); }
            if (!data.business_address) { return showFieldError('business_address', 'Business address is required.'); }
            if (!data.division_id) { return showFieldError('division_id', 'Please select a division.'); }
            if (!data.district_id) { return showFieldError('district_id', 'Please select a district.'); }
            if (!data.trade_license_no) { return showFieldError('trade_license_no', 'Trade license number is required.'); }

            $('#form-error-summary').hide();
            $btn.addClass('loading').prop('disabled', true);
            clearErrors();

            $.ajax({
                url: '{{ route("seller.signup") }}',
                type: 'POST',
                data: data,
                success: function(res) {
                    window.location.href = '{{ route("frontend.message") }}';
                },
                error: function(xhr) {
                    handleValidationErrors(xhr);
                },
                complete: function() {
                    $btn.removeClass('loading').prop('disabled', false);
                }
            });
        });

        function showFieldError(field, msg) {
            $('#' + field + '-error').text(msg).addClass('visible');
            var $el = $('#' + field);
            if ($el.length) {
                $el.addClass('error');
                $('html, body').animate({ scrollTop: $el.offset().top - 120 }, 300);
            }
        }

        function clearErrors() {
            $('.form-error').removeClass('visible').text('');
            $('.form-input').removeClass('error');
        }

        function handleValidationErrors(xhr) {
            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                var errors = xhr.responseJSON.errors;
                var summary = '<strong>Please fix the following errors:</strong><ul style="margin-top:0.5rem;padding-left:1rem">';
                $.each(errors, function(field, msgs) {
                    showFieldError(field, msgs[0]);
                    summary += '<li>' + msgs[0] + '</li>';
                });
                summary += '</ul>';
                $('#form-error-summary').html(summary).show();
                $('html, body').animate({ scrollTop: $('#form-error-summary').offset().top - 100 }, 300);
            } else if (xhr.responseJSON && xhr.responseJSON.error) {
                showToast(xhr.responseJSON.error, 'error');
            }
        }

        function showToast(msg, type) {
            if (typeof showSuccessToast === 'function' && type !== 'error') {
                showSuccessToast(msg);
            } else if (typeof showErrorToast === 'function') {
                showErrorToast(msg);
            } else {
                alert(msg);
            }
        }

        $('#division_id').on('change', function() {
            var id = $(this).val();
            var $dist = $('#district_id');
            if (!id) { $dist.html('<option value="">Select</option>'); return; }
            $dist.html('<option value="">Loading...</option>');
            $.get('/get-districts/' + id, function(data) {
                var opts = '<option value="">Select</option>';
                $.each(data, function(k, v) { opts += '<option value="' + k + '">' + v + '</option>'; });
                $dist.html(opts);
            });
        });
    </script>
    @endpush
@endsection
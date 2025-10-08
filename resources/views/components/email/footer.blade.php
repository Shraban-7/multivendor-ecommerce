<tr>
    <td style="padding:24px 40px;background-color:#F7F9FC;border-top:1px solid #E0E6ED;">
        <p style="margin:0 0 12px;color:#5A6C7D;font-size:13px;line-height:1.5;text-align:center;">
            {{ $footerText ?? 'Need help?' }} Contact us at
            <a href="mailto:{{ $supportEmail ?? config('mail.support_address') }}" style="color:#FF6B35;text-decoration:none;">
                {{ $supportEmail ?? config('mail.support_address') }}
            </a>
        </p>
        <p style="margin:0;color:#8A9BA8;font-size:12px;text-align:center;">
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>
    </td>
</tr>
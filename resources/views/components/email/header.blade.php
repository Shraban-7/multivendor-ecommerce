<tr>
    <td style="background:linear-gradient(135deg,#FF6B35 0%,#E85A2A 100%);padding:32px 40px;text-align:center;">
        <h1 style="margin:0;color:#ffffff;font-size:28px;font-weight:700;letter-spacing:-0.5px;">
            {{ config('app.name') }}
        </h1>
        @if(isset($headerSubtitle))
        <p style="margin:8px 0 0;color:rgba(255,255,255,0.9);font-size:14px;font-weight:500;">
            {{ $headerSubtitle }}
        </p>
        @endif
    </td>
</tr>
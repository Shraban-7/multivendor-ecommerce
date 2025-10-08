@props([
    'bgColor' => '#E8F5E9',
    'textColor' => '#2E7D32'
])

<tr>
    <td style="padding:32px 40px 0;text-align:center;">
        <div style="display:inline-block;padding:8px 20px;background-color:{{ $bgColor }};border-radius:20px;">
            <span style="color:{{ $textColor }};font-size:14px;font-weight:600;">
                {{ $slot }}
            </span>
        </div>
    </td>
</tr>
@props([
    'url',
    'bgColor' => '#FF6B35',
    'textColor' => '#ffffff',
    'style' => 'box-shadow:0 4px 12px rgba(255,107,53,0.3);',
    'spacing' => 'padding-bottom:12px;'
])

<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td align="center" style="{{ $spacing }}">
            <a href="{{ $url }}" style="display:inline-block;padding:14px 32px;background-color:{{ $bgColor }};color:{{ $textColor }};text-decoration:none;border-radius:6px;font-weight:600;font-size:16px;{{ $style }}">
                {{ $slot }}
            </a>
        </td>
    </tr>
</table>
@props([
    'margin' => '0 0 24px',
    'padding' => '20px',
    'bgColor' => '#F7F9FC',
    'extraStyle' => ''
])

<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin:{{ $margin }};padding:{{ $padding }};background-color:{{ $bgColor }};border-radius:6px;{{ $extraStyle }}">
    <tr>
        <td>
            {{ $slot }}
        </td>
    </tr>
</table>
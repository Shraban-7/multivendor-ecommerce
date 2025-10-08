@props([
    'icon',
    'size' => '64px',
    'iconSize' => '32px',
    'bgColor' => '#FFF4F0'
])

<tr>
    <td style="padding:32px 40px 0;text-align:center;">
        <div style="display:inline-block;width:{{ $size }};height:{{ $size }};background-color:{{ $bgColor }};border-radius:50%;line-height:{{ $size }};">
            <span style="font-size:{{ $iconSize }};">{{ $icon }}</span>
        </div>
    </td>
</tr>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body style="margin:0;padding:0;font-family:'Inter','Segoe UI',Roboto,sans-serif;background-color:#F7F9FC;-webkit-font-smoothing:antialiased;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color:#F7F9FC;">
        <tr>
            <td style="padding:20px 0;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="margin:0 auto;background-color:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.05);">

                    @include('emails.components.header')

                    @yield('content')

                    @include('emails.components.footer')

                </table>
            </td>
        </tr>
    </table>
</body>

</html>
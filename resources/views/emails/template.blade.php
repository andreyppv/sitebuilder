<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $settings['site.title'] }}</title>
</head>
<body style="background-color:#ededed; padding:25px 0;">
    <table style="border:1px solid #CCCCCC; background:#ffffff;box-shadow:0 0 5px #B9B9B9;" align="center" border="0" width="760" cellpadding="5" cellspacing="0">
        <tbody>
            <tr>
                <td colspan="3" style="background: #87CEEB; display: block;text-align: center;" align="center"> 
                    <img moz-do-not-send="true" src="{{ asset('images/logo1.png') }}" alt="{{ $settings['site.title'] }}" height="90" width="200">
                </td>
            </tr>
            <tr>
                <td>
                    @yield('content')
                </td>
            </tr>
            <tr>
                <td colspan="3" style="background: none repeat scroll 0 0 #EDEDED; border-radius: 0 0 10px 10px; color: #393860; font-family: Trebuchet MS; font-size: 14px; font-weight: bold;padding: 10px 10px 10px 0; text-align: center;" align="left" valign="top">
                    <a moz-do-not-send="true" style="color:#ef3f77;text-decoration:none;" href="{{ url('/') }}">{{ url('/') }}</a>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100%;max-width:540px;border:2px solid #f3f3f3;text-align:left;font-family:sans-serif;">
    <tr>
        <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="width:100%!important">
                <tr>
                    <td width="100%" style="vertical-align:top;text-align:left;padding:15px 20px 10px;border-bottom:5px solid #81489c">
                        <a href="{{ env('APP_URL') }}" target="_blank">
                            <img src="{{ env('APP_URL') }}/assets/images/site-logo.svg" alt="{{ env('APP_NAME') }}" style="width:150px;"/>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style="padding:20px;font-size:14px;">Hi Admin,<br/>{!! $name !!} submit an enquiry on website.</td>
                </tr>
                <tr>
                    <td style="padding:10px 10px;font-size:20px;line-height:20px;color:#424242;background:#f6f6f6;text-align:center">Enquiry Details</td>
                </tr>
                <tr>
                    <td style="padding:5px 20px;line-height:20px;color:#626262">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td width="50%" style="color:#626262;font-size:14px;vertical-align:top;line-height:20px;padding:5px">Name: </td>
                                    <td width="50%" style="color:#222222;font-size:14px;vertical-align:top;line-height:20px;padding:5px">{!! $name !!}</td>
                                </tr>
                                <tr>
                                    <td width="50%" style="color:#626262;font-size:14px;vertical-align:top;line-height:20px;padding:5px">Company: </td>
                                    <td width="50%" style="color:#222222;font-size:14px;vertical-align:top;line-height:20px;padding:5px">{!! $company !!}</td>
                                </tr>
                                <tr>
                                    <td width="50%" style="color:#626262;font-size:14px;vertical-align:top;line-height:20px;padding:5px">Email: </td>
                                    <td width="50%" style="color:#222222;font-size:14px;vertical-align:top;line-height:20px;padding:5px">{!! $email !!}</td>
                                </tr>
                                <tr>
                                    <td width="50%" style="color:#626262;font-size:14px;vertical-align:top;line-height:20px;padding:5px">Phone: </td>
                                    <td width="50%" style="color:#222222;font-size:14px;vertical-align:top;line-height:20px;padding:5px">{!! $phone !!}</td>
                                </tr>
                                <tr>
                                    <td width="50%" style="color:#626262;font-size:14px;vertical-align:top;line-height:20px;padding:5px">Message: </td>
                                    <td width="50%" style="color:#222222;font-size:14px;vertical-align:top;line-height:20px;padding:5px">{!! $msg !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td colspan="2" style="color:#626262;font-size:12px;padding:5px 6px 5px 6px;text-align:center;background:#f9f9f9">Copyright &copy; {{ date('Y') }} {{ env('APP_NAME') }} | All rights Reserved.</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
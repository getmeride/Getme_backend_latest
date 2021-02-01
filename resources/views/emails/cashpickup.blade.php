<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>{{Setting::get('site_name','Tranxit')}}</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Londrina+Solid&display=swap" rel="stylesheet">
    <style>
        /* a{color: #382ca9;text-decoration: none;} */
        /*page { background: white; display: block; margin: 0 auto; margin-bottom: 0.5cm; box-shadow: 0 0 0.5cm rgba(0,0,0,0.5); padding: 1pt;}
        page[size="A4"] { width: 21cm; height: 29.7cm; box-sizing: border-box;}
        page[size="A4"][layout="landscape"] { width: 29.7cm; height: 21cm; } */
        /* p{margin: 0 0 3px 0;}
        ul li{margin-left: 0;}
        h1, h2{margin: 0 0 5px 0;} */
        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        /* .page { width: 210mm; min-height: 297mm; padding: 10mm; margin: 10mm auto; background: white; box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); }
        .subpage {height: 257mm;} */
        /*  img{max-width: 100%; display: block;}
        table{border-collapse: collapse;}
        .table-border th{background-color: #E0DFF4;}
        .table-border td,
        .table-border th{text-align: left;border-top: 1px solid #E0DFF4; padding: 8px;} */


        /* @page { size: A4; margin: 0; }
        @media print {
            html, body { width: 210mm; height: 297mm; }
            .page { margin: 0; border: initial; border-radius: initial; width: initial; min-height: initial; box-shadow: initial; background: #fff; page-break-after: always; }
        } */
    </style>
</head>

<body
    style="margin: 0; padding: 0;font-family: 'Londrina Solid', cursive;font-size: max(1rem, 1vw);color: #382ca9;">
    <table align="center" cellspacing="0" cellpadding="0" border="0" width="100%"
        style="max-width: 800px; margin: 30px auto;border: 1px solid #000;">
        <tr>
            <td>
                <table bgcolor="#E0DFF4" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td align="center" valign="middle">
                            <a href="https://getmeride.com" target="_blank"
                                style="color: #382ca9;text-decoration: none;"><img src="{{ Setting::get('site_logo', asset('logo-black.png')) }}" alt="getmeride"
                                    style="max-height: 120px;"></a>
                        </td>

                    </tr>
                    <tr>
                        <td height="10px"></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <table bgcolor="#fff" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td align="center" valign="middle">
                            <h3 style="font-size: max(1.5rem, 2vw);margin: 10px 0 0;text-transform: uppercase;">
                                welcome to getmeride</h3>
                            <p style="margin: 0;">Here's the Driver Cashout Details:</p>
                        </td>
                    </tr>
                    <tr>
                        <td height="10px" style="border-bottom: 1px solid #ddd;"></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td align="center">
                <table bgcolor="#fff" cellspacing="10" cellpadding="0" border="0" width="70%">
                    <tr>
                        <td height="15" colspan="2"></td>
                    </tr>
                    <tr>
                        <th style="text-align: left; padding-bottom: 8px;">Name: </th>
                        <td>{{$name}}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left; padding-bottom: 8px;">Email: </th>
                        <td>{{$email}}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left; padding-bottom: 8px;">Mobile: </th>
                        <td><a href="tel:9636984562" style="color: #382ca9;">{{$mobile}}</a>
                        </td>
                    </tr>
                    <tr>
                        <th valign="top" style="text-align: left; padding-bottom: 8px;">Address:
                        </th>
                        <td>
                            {{$address}}
                        </td>
                    </tr>
                     <tr>
                        <th valign="top" style="text-align: left; padding-bottom: 8px;">City:
                        </th>
                        <td>
                            {{$city}}
                        </td>
                    </tr>
                    <tr>
                        <th valign="top" style="text-align: left; padding-bottom: 8px;">Country:
                        </th>
                        <td>
                            {{$country}}
                        </td>
                    </tr>
                    <tr>
                        <th valign="top" style="text-align: left; padding-bottom: 8px;">Postal Code:
                        </th>
                        <td>
                            {{$postal_code}}
                        </td>
                    </tr>
                    <tr>
                        <th valign="top" style="text-align: left; padding-bottom: 8px;">Amount Request:
                        </th>
                        <td>
                            {{$amount}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
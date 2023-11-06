<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
</head>
<body>
<style>
    @media only screen and (max-width: 600px) {
        .inner-body {
            width: 100% !important;
        }

        .footer {
            width: 100% !important;
        }
    }

    @media only screen and (max-width: 500px) {
        .button {
            width: 100% !important;
        }
    }
</style>

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td align="center">
            <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td class="header d-flex justify-content-center"
                        style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';box-sizing:border-box;padding:0px 0;text-align:center;background:black">
                        <a href="https://fileserver.shalomcontrol.com/file-view/images/2023-10-31/MOGKT1PJIuWOjoRSp04blogo%20cesar%20lopez%20silva.png" style="text-decoration: none;">
                            <img class="d-block w-100"
                                 src="https://fileserver.shalomcontrol.com/file-view/images/2023-10-31/MOGKT1PJIuWOjoRSp04blogo%20cesar%20lopez%20silva.png"
                                 alt="First slide" style="width: 220px;height: 220px; margin: 0;">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="body" width="100%" style="height:180px" cellpadding="0" cellspacing="0">
                        <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0"
                               role="presentation">
                            <!-- Body content -->
                            <tr style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';box-sizing:border-box;padding:35px">
                                <td style="margin-top: 25px;">
                                    <h1 style="margin-top: 25px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';box-sizing:border-box;color:#3d4852;font-size:25px;font-weight:bold;text-align:center">
                                        CITA REGISTRADA
                                    </h1>
                                    <p style="font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';box-sizing:border-box;color:black;font-size:16px;line-height:1.5em;margin-top:0;text-align:left">
                                        Hola Cinthya el paciente:  <strong>{{$mail["paciente_nombre"]}}</strong>,
                                        <br/>
                                        <strong>ha registrado su cita correctamente en el C.M.I `CÉSAR LÓPEZ SILVA`</strong>
                                        <br/>
                                        <strong>MÉDICO:</strong> {{$mail["medico_nombre"]}}
                                        <br/>
                                        <strong>ESPECIALIDAD:</strong> {{$mail["especiality"]}}
                                        <br/>
                                        <strong>FECHA:</strong> {{date("Y-m-d", strtotime($mail["fecha"]))}}
                                        <br/>
                                        <strong>HORARIO:</strong> {{$mail["horario"]}}
                                        <br/>
                                        <strong>LUGAR:</strong> {{$mail["lugar"]}}
                                        <br/>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td>
                        <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0"
                               role="presentation">
                            <tr>
                                <td class="content-cell" align="center">
                                    <hr style="border-top: 1px solid rgb(63,81,181)"/>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>

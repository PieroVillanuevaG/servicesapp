<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FORMATO DE SALIDA</title>
    <style>

        *{
            margin: 0;
            padding: 0;
            text-decoration: none;
            border: none;
            outline: none;
        }

        .text-center {
            text-align: center;
        }

        .container {
            /* Arriba | Derecha | Abajo | Izquierda */
            margin: 12px 40px 0px 40px;
        }

        .padding {
            padding: 5px;
        }

        .izquierda {
            text-align: left;
        }

        .text-cabecera {
            font-size: 12px;
        }

        .text-introduccion {
            font-size: 15px;
            text-align: justify;
        }

        .introduccion {
            /* Arriba | Derecha | Abajo | Izquierda */
            margin: 18px 45px 0px 45px;
        }

        .cabecera-tabla {
            /* Arriba | Derecha | Abajo | Izquierda */
            margin: 18px 45px 0px 45px;
        }

        .cuerpo-hoja-1 {
            /* Arriba | Derecha | Abajo | Izquierda */
            margin: 15px 45px 0px 45px;
        }

        .firma_empleado {
            /* Arriba | Derecha | Abajo | Izquierda */
            margin: 15px 45px 0px 45px;
        }

        ul {
            text-align: justify;
        }

        p.saltodepagina {
            page-break-after: always;
        }

        table.footer {
            bottom: 0;
            margin-left: 10%;
            margin-right: 10%;

        }

        th {
            background-color: #ccc;
            padding: 5px;
        }

        td {
            padding: 5px;
        }

        .footer-section {
            position: fixed;
            bottom: 0;
            /* left: 0; */
            background-color: #ffffff; /* Ajusta el color de fondo según tus preferencias */
            /* padding: 20px 10%; */
            /* display: flex; */
            justify-content: space-between;
            margin-bottom: 50px;
        }
        .encargado {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <section class="cabecera">
        <table width="100%" style="border: 1px solid black">
            <tr>
                <td rowspan="4" width="150" style="border-right: 1px solid black">
                    <img class="text-center" src="https://fileserver.shalomcontrol.com/file-view/images/2023-11-08/wFJWLChoQ4Jcijt6iooJMinsa.png" alt="" width="100%">
                </td>
                <td >
                    <h4 class="text-center padding" style="border-bottom: 1px solid black">CENTRO DE SALUD LAS BRISAS</h4>
                </td>
            </tr>
            <tr>
                <td>
                    <h4 class="text-center padding">FORMATO DE SALIDA DE PRODUCTO</h4><br>
                    <strong>DOCUMENTO: </strong> {{$documento}}<br>
                    <strong>CLIENTE: </strong> {{$cliente}}<br>
                    <strong>TOTAL(CANTIDAD) SALIDA: </strong> {{$total_cantidad}}<br>
                    <strong>TOTAL(PRECIO) SALIDA: </strong> {{$total}}<br>
                </td>
            </tr>
        </table>
    </section><br>
    <section class="cabecera">
        <table width="100%" style="border: 1px solid black">
            <tr>
                <td style="background: #e7e3e3" width="90" class="text-center"><b>ALMACEN</b></td>
                <td style="background: #e7e3e3" width="90" class="text-center"><b>CÓDIGO</b></td>
                <td style="background: #e7e3e3" width="90" class="text-center"><b>NOMBRE</b></td>
                <td style="background: #e7e3e3" width="90" class="text-center"><b>CANTIDAD</b></td>
                <td style="background: #e7e3e3" width="90" class="text-center"><b>PRECIO</b></td>
            </tr>
            @foreach($productos as $product)
                <tr>
                    <td class="text-center" style="padding: 0px 10px 0px 10px; border-top: 1px solid black">{{$product["warehouse"]}}</td>
                    <td class="text-center" style="padding: 0px 10px 0px 10px; border-top: 1px solid black">{{$product["code"]}}</td>
                    <td class="text-center" style="padding: 0px 10px 0px 10px; border-top: 1px solid black">{{$product["name"]}}</td>
                    <td class="text-center" style="padding: 0px 10px 0px 10px; border-top: 1px solid black">{{$product["qty"]}}</td>
                    <td class="text-center" style="padding: 0px 10px 0px 10px; border-top: 1px solid black">{{$product["price_selling"]}}</td>
                </tr>
            @endforeach
        </table>
    </section>

    <section  class="footer-section" style="margin-top:100px; margin-left:10%; margin-right: 10%; flex-grow: 1;">
        <table class="center-content">
            <tr>
                <td style="width:20%;" >
                    <table>
                        <tr>
                            <td style="height: 60px"></td>
                        </tr>
                        <tr>
                            <td style="border-top: black 1px solid;padding: 0 20px">
                                <p class="text-center" style="font-size:11px">
                                    <b>{{$cliente}}</b><br>
                                    <b style="font-size:11px">{{$documento}}</b>
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width:60%;padding: 0 40px"></td>
                <td style="width:20%;">
                    <table>
                        <tr>
                            <td style="height: 60px"></td>
                        </tr>
                        <tr>
                            <td style="border-top: black 1px solid;padding: 0 20px">
                                <p class="text-center" style="font-size:11px">
                                    <b>LIC. ROCIO MARIA VILCAPOMA VEGA</b><br>
                                    <b style="font-size:11px">JEFA DEL PUESTO DE SALUD</b>
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </section>
</div>

</body>
</html>

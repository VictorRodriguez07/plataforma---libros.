<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitud de préstamo</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 30px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden;">

                    <!-- Header -->
                    <tr>
                        <td align="center" style="background-color: #4a90e2; padding: 20px;">
                            <img src="https://tqi.co/wp-content/uploads/2018/02/cropped-Gota.png" alt="Logo TQI" width="60" style="display: block; margin-bottom: 10px;">
                            <h1 style="color: #ffffff; font-size: 22px; margin: 0;">Biblioteca Virtual TQI</h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 30px; color: #333333; font-size: 15px;">
                            <h2 style="text-align: center; font-size: 20px; margin-bottom: 25px;">Solicitud de préstamo</h2>

                            <p style="margin-bottom: 15px;">
                                <strong>Solicitante:</strong> {{ $nombre_usu }}
                            </p>

                            <p style="margin-bottom: 15px;">
                                <strong>Correo Electrónico:</strong> {{ $email }}
                            </p>

                            <p style="margin-bottom: 15px;">
                                <strong>Libro:</strong> {{ $nombre_libro }} / {{ $id_libro }}
                            </p>

                            <div style="text-align: center; margin: 25px 0;">
                                <img src="{{ $img_libro }}" alt="Portada del libro" style="max-width: 120px; height: auto; border-radius: 6px;">
                            </div>

                            <p style="margin-bottom: 15px;">
                                <strong>Fecha de devolución:</strong> {{ $fechaDevolucion }}
                            </p>

                            <div style="text-align: center; margin-top: 30px;">
                                <a href="{{$url_prestamo_detalle_denegar}}" target="_blank" style="display: inline-block; background-color: #e74c3c; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 4px; margin-right: 10px;">
                                    Denegar
                                </a>
                                <a href="{{$url_prestamo_detalle}}" target="_blank" style="display: inline-block; background-color: #27ae60; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 4px; margin-left: 10px;">
                                    Aprobar
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f0f0f0; padding: 20px; text-align: center; color: #777777; font-size: 13px;">
                            ¿Necesitas ayuda? Contáctanos:<br>
                            <a href="mailto:victor.rodriguez@tqi.co" style="color: #4a90e2; text-decoration: none;">victor.rodriguez@tqi.co</a><br><br>
                            <em>Por seguridad, no compartas tus credenciales con nadie.</em>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>

<!DOCTYPE html>
<html lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <title>Préstamo de libro vencido</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding: 30px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden;">

                
                    <tr>
                        <td align="center" style="background-color: #e74c3c; padding: 20px;">
                            <img src="https://tqi.co/wp-content/uploads/2018/02/cropped-Gota.png" alt="Logo TQI" width="60" style="display: block; margin-bottom: 10px;">
                            <h1 style="color: #ffffff; font-size: 22px; margin: 0;">Biblioteca Virtual TQI</h1>
                        </td>
                    </tr>

             
                    <tr>
                        <td style="padding: 30px; color: #333333; font-size: 15px;">
                            <h2 style="text-align: center; font-size: 20px; margin-bottom: 25px;">
                                Notificación de préstamo vencido
                            </h2>

                            <p style="margin-bottom: 15px;">
                                Estimado/a <strong>{{ $nombre_usuario }}</strong>,
                            </p>

                            <p style="margin-bottom: 15px;">
                                Te informamos que el préstamo del siguiente libro ha vencido:
                            </p>

                            <p style="margin-bottom: 15px;">
                                <strong>Libro:</strong> {{ $nombre_libro }}
                            </p>

                            <div style="text-align: center; margin: 25px 0;">
                                <img src="{{ $img_libro }}" alt="Portada del libro" style="max-width: 120px; height: auto; border-radius: 6px;">
                            </div>

                            <p style="margin-bottom: 15px;">
                                <strong>Fecha de devolución esperada:</strong> {{ $fecha_devolucion }}
                            </p>

                            <p style="margin-top: 25px;">
                                Te pedimos por favor devolver el ejemplar a la brevedad o comunicarte con la biblioteca para resolver tu situación.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f0f0f0; padding: 20px; text-align: center; color: #777777; font-size: 13px;">
                            ¿Necesitas ayuda? Contáctanos:<br>
                            <a href="mailto:victor.rodriguez@tqi.co" style="color: #e74c3c; text-decoration: none;">victor.rodriguez@tqi.co</a><br><br>
                            <em>Este es un mensaje automático, por favor no respondas directamente.</em>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>

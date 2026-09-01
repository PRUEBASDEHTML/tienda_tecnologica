
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

function enviarCodigoVerificacion($correo, $codigo)
{
    $mail = new PHPMailer(true);

    try {

        // ========================================
        // CONFIGURACIÓN DE GMAIL
        // ========================================

        $mail->isSMTP();

        $mail->Host = 'smtp.gmail.com';

        $mail->SMTPAuth = true;

        // 👇 CAMBIA ESTO POR TU GMAIL
        $mail->Username = 'mp971620@gmail.com';

        // 👇 CAMBIA ESTO POR TU CONTRASEÑA
        // DE APLICACIÓN DE GOOGLE
        $mail->Password = 'oicg zlyo vitx gycx';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port = 587;


        // ========================================
        // DATOS DEL CORREO
        // ========================================

        $mail->setFrom(
            'mp971620@gmail.com',
            'Tienda Tecnologica'
        );

        $mail->addAddress($correo);

        $mail->isHTML(true);

        $mail->CharSet = 'UTF-8';

        $mail->Subject = 'Codigo de verificacion - Tienda Tecnologica';


        // ========================================
        // MENSAJE
        // ========================================

        $mail->Body = '

        <div style="
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 10px;
        ">

            <h2 style="color:#0d6efd;">
                💻 Tienda Tecnológica
            </h2>

            <p>
                Hola.
            </p>

            <p>
                Recibimos una solicitud para crear una cuenta
                en nuestra tienda.
            </p>

            <p>
                Tu código de verificación es:
            </p>

            <div style="
                font-size: 32px;
                font-weight: bold;
                letter-spacing: 8px;
                text-align: center;
                padding: 20px;
                background: #f1f3f5;
                border-radius: 8px;
            ">

                ' . $codigo . '

            </div>

            <p>
                Introduce este código en la página de
                verificación para completar tu registro.
            </p>

            <p style="color:#777;">
                Si tú no solicitaste esta cuenta,
                simplemente ignora este mensaje.
            </p>

            <hr>

            <small>
                © 2026 Tienda Tecnológica
            </small>

        </div>

        ';


        // Versión de texto

        $mail->AltBody =
            "Tu codigo de verificacion es: " . $codigo;


        // ========================================
        // ENVIAR
        // ========================================

        $mail->send();

        return true;

    } catch (Exception $e) {

        return false;
    }
}
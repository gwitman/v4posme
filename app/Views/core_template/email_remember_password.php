<?php
//posme:2026-11-09
// Correo de recuperacion de contraseña (core_acount::rememberpassword).
// Variables: $nickname, $password, $email, $companyName, $labelSistema, $urlLogin, $fecha
// Maquetado con tablas y estilos en linea para que se vea bien en Gmail, Outlook y telefonos.
$labelSistema = (isset($labelSistema) && $labelSistema != "") ? $labelSistema : "posMe";
$companyName  = isset($companyName) ? $companyName : "";
$urlLogin     = isset($urlLogin) ? $urlLogin : base_url();
$fecha        = isset($fecha) ? $fecha : "";
?>
<!DOCTYPE html>
<html lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <meta name="format-detection" content="telephone=no">
    <title>Tus datos de acceso</title>
    <!--[if mso]><style>table,td,p,a,span{font-family:Arial,Helvetica,sans-serif !important;}</style><![endif]-->
    <style>
        @media only screen and (max-width:600px){
            .wrap{width:100% !important;}
            .px{padding-left:20px !important;padding-right:20px !important;}
            .btn a{display:block !important;}
        }
    </style>
</head>
<body style="margin:0;padding:0;background-color:#f3f6f8;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;">

    <!-- Texto de vista previa en la bandeja de entrada -->
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;color:#f3f6f8;">
        Estos son tus datos para ingresar a <?php echo esc($labelSistema); ?>.
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f3f6f8;">
        <tr>
            <td align="center" style="padding:32px 12px;">

                <table role="presentation" class="wrap" width="560" cellpadding="0" cellspacing="0" border="0" style="width:560px;max-width:560px;">

                    <!-- Marca -->
                    <tr>
                        <td align="center" style="padding:0 0 20px 0;font-family:Arial,Helvetica,sans-serif;font-size:28px;font-weight:800;letter-spacing:-0.5px;color:#00628e;">
                            pos<span style="color:#3fae55;">Me</span>
                        </td>
                    </tr>

                    <!-- Tarjeta -->
                    <tr>
                        <td style="background-color:#ffffff;border:1px solid #d9e2e8;border-radius:14px;overflow:hidden;">

                            <!-- Franja superior -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="background-color:#00628e;height:6px;line-height:6px;font-size:0;border-radius:14px 14px 0 0;">&nbsp;</td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="px" style="padding:32px 40px 8px 40px;font-family:Arial,Helvetica,sans-serif;">
                                        <h1 style="margin:0 0 10px 0;font-size:22px;line-height:30px;color:#14232c;font-weight:700;">
                                            Tus datos de acceso
                                        </h1>
                                        <p style="margin:0;font-size:15px;line-height:24px;color:#5d6f7a;">
                                            Recibimos una solicitud para recuperar la contraseña de tu usuario
                                            <?php if($companyName != ""): ?>en <strong style="color:#14232c;"><?php echo esc($companyName); ?></strong><?php endif; ?>.
                                            Estos son los datos para ingresar:
                                        </p>
                                    </td>
                                </tr>

                                <!-- Credenciales -->
                                <tr>
                                    <td class="px" style="padding:20px 40px 8px 40px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f7fafb;border:1px solid #d9e2e8;border-radius:10px;">
                                            <tr>
                                                <td style="padding:16px 20px 8px 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:16px;color:#5d6f7a;text-transform:uppercase;letter-spacing:1px;">
                                                    Usuario
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:0 20px 14px 20px;font-family:Consolas,'Courier New',monospace;font-size:18px;line-height:24px;color:#14232c;font-weight:700;border-bottom:1px dashed #d9e2e8;">
                                                    <?php echo esc($nickname); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 20px 8px 20px;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:16px;color:#5d6f7a;text-transform:uppercase;letter-spacing:1px;">
                                                    Contraseña
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:0 20px 16px 20px;font-family:Consolas,'Courier New',monospace;font-size:18px;line-height:24px;color:#14232c;font-weight:700;">
                                                    <?php echo esc($password); ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Boton -->
                                <tr>
                                    <td class="px btn" align="center" style="padding:24px 40px 8px 40px;">
                                        <!--[if mso]>
                                        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" href="<?php echo esc($urlLogin, 'attr'); ?>" style="height:48px;v-text-anchor:middle;width:260px;" arcsize="20%" fillcolor="#00628e" stroke="f">
                                            <center style="color:#ffffff;font-family:Arial,sans-serif;font-size:16px;font-weight:bold;">Iniciar sesión</center>
                                        </v:roundrect>
                                        <![endif]-->
                                        <!--[if !mso]><!-->
                                        <a href="<?php echo esc($urlLogin, 'attr'); ?>" target="_blank" style="display:inline-block;background-color:#00628e;color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-size:16px;font-weight:700;line-height:48px;text-decoration:none;padding:0 36px;border-radius:10px;">
                                            Iniciar sesión
                                        </a>
                                        <!--<![endif]-->
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px" align="center" style="padding:10px 40px 0 40px;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:20px;color:#5d6f7a;word-break:break-all;">
                                        <a href="<?php echo esc($urlLogin, 'attr'); ?>" target="_blank" style="color:#00628e;text-decoration:underline;"><?php echo esc($urlLogin); ?></a>
                                    </td>
                                </tr>

                                <!-- Aviso de seguridad -->
                                <tr>
                                    <td class="px" style="padding:24px 40px 32px 40px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#fff8e6;border-radius:10px;">
                                            <tr>
                                                <td style="padding:14px 18px;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:20px;color:#7a5a00;">
                                                    <strong>¿No pediste este correo?</strong> Avisa al administrador de tu empresa.
                                                    No compartas estos datos con nadie y borra este mensaje después de ingresar.
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Pie -->
                    <tr>
                        <td align="center" style="padding:20px 12px 0 12px;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:18px;color:#8696a0;">
                            <?php if($fecha != ""): ?>Solicitud recibida el <?php echo esc($fecha); ?>.<br><?php endif; ?>
                            Este es un mensaje automático de <?php echo esc($labelSistema); ?>; no respondas a este correo.<br>
                            © <?php echo date("Y"); ?> <?php echo esc($labelSistema); ?>. Todos los derechos reservados.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>

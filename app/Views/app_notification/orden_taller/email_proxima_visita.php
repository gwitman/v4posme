<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordenes de taller - Proximas visitas</title>
</head>
<body style="margin:0; padding:0; background:#f4f5f7; font-family:Arial, 'Open Sans', sans-serif;">
    <table role="presentation" style="width:100%; background:#f4f5f7; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" style="width:760px; max-width:95%; background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.08);">
                    <!-- Header con logo -->
                    <tr>
                        <td style="background:#4e73df; padding:20px 24px; text-align:center;">
                            <?php if (isset($logoCID) && $logoCID != "") : ?>
                                <img src="cid:<?php echo $logoCID; ?>" alt="logo" style="max-height:70px; margin-bottom:8px;" />
                            <?php endif; ?>
                            <div style="color:#ffffff; font-size:20px; font-weight:bold;">
                                <?php echo isset($objCompany) ? htmlspecialchars($objCompany->name) : "posMe"; ?>
                            </div>
                            <div style="color:#e6ecff; font-size:13px; margin-top:4px;">
                                Recordatorio de ordenes de taller
                            </div>
                        </td>
                    </tr>
                    <!-- Saludo -->
                    <tr>
                        <td style="padding:20px 24px 0 24px; color:#333; font-size:14px;">
                            <p style="margin:0 0 6px 0;">Estimados,</p>
                            <p style="margin:0 0 12px 0;">
                                A continuacion el detalle de las ordenes de taller con su proxima visita programada.
                            </p>
                        </td>
                    </tr>
                    <!-- Contenido / tablas -->
                    <tr>
                        <td style="padding:0 24px 10px 24px;">
                            <?php echo isset($mensaje) ? $mensaje : "Sin ordenes por notificar"; ?>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="padding:16px 24px; border-top:1px solid #eee; color:#888; font-size:12px; text-align:center;">
                            Fecha de envio: <?php echo date("Y-m-d H:i:s"); ?><br>
                            Mensaje generado automaticamente por posMe
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

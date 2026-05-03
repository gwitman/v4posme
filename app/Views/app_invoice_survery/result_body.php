<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Resultado del Pedido</title>
  <link href="<?= base_url()?>/resource/css/bootstrap5/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      background: <?=getBahavioDB($key, 'app_invoice_survery', 'fondo_confirmado', "linear-gradient(135deg, #1a1a2e, #16213e, #0f3460)")?>;
      font-family: 'Montserrat', sans-serif;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .result-card {
      max-width: 480px;
      width: 100%;
      background: #fff;
      border-radius: 20px;
      padding: 40px 32px;
      text-align: center;
      box-shadow: 0 16px 48px rgba(0,0,0,0.35);
      position: relative;
      overflow: hidden;
    }

    /* Franja superior de color según resultado */
    .result-card::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 6px;
      background: var(--accent-color, #28a745);
    }

    .result-card.is-success { --accent-color: #28a745; }
    .result-card.is-error   { --accent-color: #e63946; }

    .result-icon {
      font-size: 4rem;
      margin-bottom: 12px;
      line-height: 1;
    }

    .result-label {
      font-size: 0.75rem;
      font-weight: 700;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: #999;
      margin-bottom: 6px;
    }

    .result-number {
      font-size: 2.6rem;
      font-weight: 700;
      margin-bottom: 10px;
      line-height: 1.1;
    }
    .result-number.success { color: #28a745; }
    .result-number.error   { color: #e63946; }

    .result-message {
      font-size: 1rem;
      color: #555;
      margin-bottom: 32px;
      line-height: 1.5;
    }
    .result-message.success { color: #28a745; font-weight: 600; }
    .result-message.error   { color: #e63946; font-weight: 600; }

    .btn-new-order {
      display: block;
      width: 100%;
      padding: 14px;
      border-radius: 12px;
      font-size: 1rem;
      font-weight: 700;
      letter-spacing: 0.5px;
      background: #e63946;
      border: none;
      color: #fff;
      text-decoration: none;
      transition: background 0.2s, transform 0.1s;
    }
    .btn-new-order:hover {
      background: #c1121f;
      color: #fff;
      transform: translateY(-1px);
    }
    .btn-new-order:active {
      transform: translateY(0);
    }

    .divider {
      border: none;
      border-top: 1px dashed #e0e0e0;
      margin: 24px 0;
    }

    .moto-strip {
      font-size: 1.8rem;
      letter-spacing: 6px;
      margin-bottom: 16px;
      opacity: 0.15;
    }

    @media (max-width: 480px) {
      .result-card { padding: 32px 20px; border-radius: 16px; }
      .result-number { font-size: 2rem; }
      .result-icon { font-size: 3.2rem; }
    }
  </style>
</head>
<body>

  <div class="result-card <?php echo $error ? 'is-error' : 'is-success'; ?>">

    <div class="moto-strip">🏍️ 🏍️ 🏍️</div>

    <div class="result-icon">
      <?php echo $error ? '❌' : '✅'; ?>
    </div>

    <div class="result-label">N° de Pedido</div>

    <div class="result-number <?php echo $error ? 'error' : 'success'; ?>">
      <?php echo htmlspecialchars($transactionNumber); ?>
    </div>

    <hr class="divider">

    <div class="result-message <?php echo $error ? 'error' : 'success'; ?>">
      <?php echo htmlspecialchars($message); ?>
    </div>

    <a href="index<?php echo "/key/".$key; ?>" class="btn-new-order">
      🛒 <?=getBahavioDB($key, 'app_invoice_survery', 'boton_confirmado', "Hacer Nuevo Pedido")?>
    </a>

  </div>

</body>
</html>

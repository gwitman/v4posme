<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Orden</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #FFA07A, #FF7F50);
      font-family: 'Montserrat', sans-serif;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .error-container {
      max-width: 500px;
      width: 100%;
      background: #fff;
      border-radius: 15px;
      padding: 30px;
      text-align: center;
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }

    .error-code {
      font-size: 3rem;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .error-message {
      font-size: 1.2rem;
      margin-bottom: 30px;
    }

    .btn-new-order {
      font-size: 1.1rem;
    }

    .success {
      color: #28a745;
    }

    .error {
      color: #dc3545;
    }

    @media (max-width: 768px) {
      .error-code {
        font-size: 2.5rem;
      }
      .error-message {
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>

  <div class="error-container">
    

    <div class="error-code <?php echo $error ? 'error' : 'success'; ?>">
      <?php echo htmlspecialchars($transactionNumber); ?>
    </div>
    <div class="error-message <?php echo $error ? 'error' : 'success'; ?>">
      <?php echo htmlspecialchars($message); ?>
    </div>
    <a href="index<?php echo "/key/".$key; ?>" class="btn btn-primary btn-new-order">🛒 Nueva Orden</a>
  </div>

</body>
</html>

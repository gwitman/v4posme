<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reporte de Tienda</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 20px;
      background-color: #f9f9f9;
      color: #333;
    }

    header {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
      border-bottom: 2px solid #ccc;
      padding-bottom: 10px;
    }

    header img {
      max-width: 100px;
      margin-right: 20px;
    }

    .titulo {
      display: flex;
      flex-direction: column;
    }

    .titulo h1 {
      margin: 0;
      font-size: 1.8em;
    }

    .titulo small {
      font-size: 0.9em;
      color: #666;
    }

    h2 {
      margin-top: 40px;
      color: #2c3e50;
      border-bottom: 1px solid #ccc;
      padding-bottom: 5px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      background-color: white;
      box-shadow: 0 0 8px rgba(0,0,0,0.05);
    }

    th, td {
      padding: 10px;
      border: 1px solid #ddd;
      text-align: left;
    }

    th {
      background-color: #3498db;
      color: white;
    }

    tfoot td {
      font-weight: bold;
      background-color: #ecf0f1;
    }

	.numero {
		  text-align: right;
	}
		
		
    @media screen and (max-width: 600px) {
      table, thead, tbody, th, td, tr {
        display: block;
      }

      th {
        position: absolute;
        left: -9999px;
      }

      td {
        position: relative;
        padding-left: 50%;
        border: none;
        border-bottom: 1px solid #eee;
      }

      td:before {
        position: absolute;
        left: 10px;
        font-weight: bold;
        white-space: nowrap;
      }
	
	  .numero {
		  text-align: right;
	  }
		
      
      td:nth-of-type(1):before { content: "Código"; }
      td:nth-of-type(2):before { content: "Nombre"; }
      td:nth-of-type(3):before { content: "Cantidad"; }
      td:nth-of-type(4):before { content: "Precio"; }
      td:nth-of-type(5):before { content: "Subtotal"; }
    }
  </style>
</head>
<body>
  <?php 
	$fecha 			= new DateTime();
	$formateada 	= $fecha->format('Y-m-d  h:i A');
	$totalVenta		= 0;
  ?>
  <header>
	<!--
    <img src="ruta-del-logo.png" alt="Logo de la tienda" />
	-->
    <div class="titulo">
      <h1>Reporte de Ventas e Inventario FARMA LEY</h1>
      <small>
        Tienda: <strong><?php echo $emailResponsability[0]; ?></strong><br />
        Fecha de generación: <strong><?php echo $formateada;  ?></strong>
      </small>
    </div>
  </header>

  <h2>📦 Productos Vendidos</h2>
  <table>
    <thead>
      <tr>
        <th>Código</th>
        <th>Nombre</th>
        <th>Cantidad</th>
        <th>Precio</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      
      
	   <?php 
			
			if($objDetail)
			foreach($objDetail as $item)
			{
				echo '<tr>';
					echo '<td>'.$item["itemNumber"].'</td>';
					echo '<td>'.$item["itemName"].'</td>';
					echo '<td class="numero" >C$ '.number_format($item["quantity"], 2, '.', ',').'</td>';
					echo '<td class="numero" >C$ '.number_format($item["unitaryPrice"], 2, '.', ',').'</td>';
					echo '<td class="numero" >C$ '.number_format($item["amount"], 2, '.', ',').'</td>';
				echo '</tr>';
				
				$totalVenta = $totalVenta + $item["amount"];
			}
		?>
		 
		 
    </tbody>
  </table>
  <h2 style="text-align:right">📦 Total: C$ <?php echo number_format($totalVenta, 2, '.', ','); ?></h2>

  <h2>📦 Inventario General</h2>
  <table>
    <thead>
      <tr>
        <th>Código</th>
        <th>Nombre</th>
        <th>Existencia Actual</th>
      </tr>
    </thead>
    <tbody>
	 <?php 
		
		if($objDetailInventory)
		foreach($objDetailInventory as $item)
		{
			echo '<tr>';
				echo '<td>'.$item["itemNumber"].'</td>';
				echo '<td>'.$item["itemName"].'</td>';
				echo '<td class="numero" >'.number_format($item["quantity"], 2, '.', ',')  .'</td>';
			echo '</tr>';
		}
	 ?>
    </tbody>
  </table>

</body>
</html>

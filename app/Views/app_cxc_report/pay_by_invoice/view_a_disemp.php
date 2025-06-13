<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Información del Cliente</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 p-4">
  <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-xl p-6">
    <h1 class="text-2xl font-bold text-center text-blue-600 mb-4">Información del Cliente</h1>

    <!-- Datos del cliente -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
      <div>
        <p class="font-semibold">Nombre:</p>
        <p><?php echo $objClient["customerName"];  ?></p>
      </div>
      <div>
        <p class="font-semibold">Cédula:</p>
        <p><?php echo $objClient["identification"];  ?></p>
      </div>
      <div>
        <p class="font-semibold">Teléfono:</p>
        <p><?php echo $objClient["phoneNumber"];  ?></p>
      </div>
      <div>
        <p class="font-semibold">Dirección:</p>
        <p><?php echo $objClient["location"];  ?></p>
      </div>
      <div class="sm:col-span-2">
        <p class="font-semibold">Identificación:</p>
        <p><?php echo $objClient["customerNumber"];  ?></p>
      </div>
	  
	  <div>
        <p class="font-semibold">Fecha desembolso:</p>
        <p><?php echo $objClient["fechaDesembolso"];  ?></p>
      </div>
      <div>
        <p class="font-semibold">Monto desembolso:</p>
        <p><?php echo $objClient["montoDesembolso"];  ?></p>
      </div>
	  
	  
	   <div>
        <p class="font-semibold">Interes:</p>
        <p><?php echo $objClient["interesDesembolso"];  ?></p>
      </div>
      <div>
        <p class="font-semibold">Plazo:</p>
        <p><?php echo $objClient["plazo"];  ?></p>
      </div>
	  <div>
        <p class="font-semibold">Frecuencia:</p>
        <p><?php echo $objClient["frecuenciaDesembolso"];  ?></p>
      </div>
	  
	  
    </div>

    <!-- Tabla de pagos -->
	<h2 class="text-xl font-bold text-gray-700 mb-2">Historial de Pagos</h2>
	<div class="overflow-x-auto">
	  <table class="min-w-full bg-white border border-gray-200 rounded-lg text-sm">
		<thead class="bg-blue-600 text-white text-left">
		  <tr>
			<th class="py-2 px-2 whitespace-nowrap">Fecha de Pago</th>
			<th class="py-2 px-2 whitespace-nowrap">Monto</th>
			<th class="py-2 px-2 whitespace-nowrap">Usuario</th>
			<th class="py-2 px-2 whitespace-nowrap">Saldo Final</th>
		  </tr>
		</thead>
		<tbody class="divide-y divide-gray-200">
		
		  <?php 
			if($objPayList)
			{
				foreach($objPayList as $item)
				{
					$isEmptyUser = empty(trim($item["userName"])) ? 'text-red-600' : '';
					 
					echo '<tr class="hover:bg-gray-100 '.$isEmptyUser.'">';
					echo '  <td class="py-2 px-2 whitespace-nowrap">'.(new DateTime($item["createdOn"]))->format('Y-m-d h:i A').'</td>';
					echo '  <td class="py-2 px-2 whitespace-nowrap">'.$item["MonedaDesembolso"]." ".number_format($item["Pago"], 2, '.', ',').'</td>';
					echo '  <td class="py-2 px-2 whitespace-nowrap">'.$item["userName"].'</td>';
					echo '  <td class="py-2 px-2 whitespace-nowrap">'.$item["MonedaDesembolso"]." ".number_format($item["SaldoNuevo"], 2, '.', ',').'</td>';
					echo '</tr>';
					
				}
			}
		  ?>
		  
		  
		</tbody>
	  </table>
	</div>

	
	
	
	
	
  </div>
</body>
</html>

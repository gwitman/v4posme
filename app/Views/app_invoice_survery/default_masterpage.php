<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>¡Vota u Ordena!</title>
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
    }
    .container {
      max-width: 800px;
      margin: 30px auto;
      background: #fff;
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }
    h1 {
      text-align: center;
      margin-bottom: 10px;
      color: #FF6347;
    }
    .tagline {
      text-align: center;
      font-size: 1.1rem;
      margin-bottom: 30px;
      color: #555;
    }
    .logo {
      display: block;
      margin: 0 auto 20px;
      max-width: 100px;
    }
    .form-label {
      color: #FF6347;
    }
    .quantity-row {
      display: none;
      margin-left: 30px;
      margin-top: 5px;
    }
    .summary-screen {
      display: none;
    }
    .table > :not(caption) > * > * {
      vertical-align: middle;
    }
    
    /* --- MODIFICADO: Estilos para las imágenes de producto --- */
    .product-image {
      width: 100px; /* Hacemos las imágenes más grandes */
      height: 100px; /* Hacemos las imágenes más grandes */
      object-fit: cover; /* Recorta la imagen para cubrir el área, manteniendo la proporción */
      border-radius: 15px; /* Bordes redondeados */
      border: 2px solid #FF6347; /* Borde para resaltarlas */
      padding: 5px; /* Pequeño padding interno */
      box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Sombra sutil */
    }
    
    /* --- INICIO: Estilos personalizados para el Checkbox --- */
    .product-selection-row {
      position: relative;
      display: flex;
      align-items: center;
      padding-left: 40px;
    }

    .form-check .option {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 28px;
        height: 28px;
        border: 2px solid #FF6347;
        border-radius: 6px;
        background-color: #f8f8f8;
        cursor: pointer;
        margin: 0;
        transition: background-color 0.2s, border-color 0.2s, box-shadow 0.2s;
        z-index: 1;
    }

    .form-check .option::before {
        content: '';
        display: block;
        width: 14px;
        height: 14px;
        background-color: white;
        border-radius: 2px;
        transform: translate(5px, 5px) scale(0);
        transition: transform 0.2s ease-in-out;
    }

    .form-check .option:checked {
        background-color: #FF6347;
        border-color: #FF6347;
    }

    .form-check .option:checked::before {
        transform: translate(5px, 5px) scale(1);
    }

    .form-check .option:not(:checked):hover {
        border-color: #FFA07A;
        background-color: #fff;
    }

    .form-check .option:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 99, 71, 0.5);
    }

    .product-details {
        display: flex;
        align-items: center;
        flex-grow: 1;
    }
    /* --- FIN: Estilos personalizados para el Checkbox --- */

    /* --- INICIO: Media Queries para Responsividad --- */
    @media (max-width: 768px) {
      .container {
        margin: 15px auto;
        padding: 20px;
      }
      h1 {
        font-size: 1.8rem;
      }
      .tagline {
        font-size: 1rem;
      }
      .product-image {
        width: 70px; /* Tamaño de imagen ligeramente más pequeño en móviles */
        height: 70px;
        border-radius: 10px; /* Bordes ligeramente menos redondeados en móviles */
      }
      .form-check-label {
        font-size: 1rem;
      }
      .product-selection-row {
        padding-left: 35px; /* Ajuste del padding para el checkbox en móviles */
      }
      .form-check .option {
        width: 24px; /* Tamaño del checkbox ligeramente más pequeño en móviles */
        height: 24px;
      }
      .form-check .option::before {
        width: 12px;
        height: 12px;
        transform: translate(4px, 4px) scale(0);
      }
      .form-check .option:checked::before {
        transform: translate(4px, 4px) scale(1);
      }
      .quantity-row {
        margin-left: 20px; /* Ajuste del margen en móviles */
      }
      .table th, .table td {
        font-size: 0.9rem; /* Tamaño de texto de tabla más pequeño */
      }
    }
    /* --- FIN: Media Queries para Responsividad --- */

  </style>
</head>
<body>
  <div class="container">
    <svg class="logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
      <circle cx="32" cy="32" r="30" fill="#FFA07A" stroke="#FF6347" stroke-width="2"/>
      <ellipse cx="32" cy="40" rx="18" ry="12" fill="#fff" stroke="#333" stroke-width="2"/>
      <circle cx="32" cy="24" r="8" fill="#fff" stroke="#333" stroke-width="2"/>
      <ellipse cx="26" cy="16" rx="3" ry="5" fill="#fff" stroke="#333" stroke-width="2"/>
      <ellipse cx="38" cy="16" rx="3" ry="5" fill="#fff" stroke="#333" stroke-width="2"/>
      <circle cx="30" cy="22" r="2" fill="#333"/>
      <circle cx="34" cy="26" r="1.5" fill="#333"/>
      <circle cx="32" cy="38" r="3" fill="#333"/>
      <circle cx="29" cy="22" r="1" fill="#000"/>
      <circle cx="35" cy="22" r="1" fill="#000"/>
      <ellipse cx="32" cy="26" rx="4" ry="2" fill="#FFC0CB" stroke="#333" stroke-width="1"/>
    </svg>
    <h1>🐄 ¡Vota u Ordena!</h1>
    <p class="tagline">¡Elige tus productos favoritos y confirma tu pedido!</p>

    <form id="orderForm" method="POST" action="<?php echo base_url()."/app_invoice_survery/insertElement"; ?>" >
      <div class="mb-3">
        <label for="name" class="form-label">Nombre:</label>
		<input type="hidden" name="key" id="key" value="<?php echo $key; ?>">
        <input type="text" class="form-control" id="name" name="name" placeholder="Ingresa tu nombre" required>
      </div>
      <div class="mb-3">
        <label for="address" class="form-label">Dirección:</label>
        <input type="text" class="form-control" id="address" name="address" placeholder="Ingresa tu dirección" required>
      </div>
      <div class="mb-3">
        <label for="phone" class="form-label">Teléfono:</label>
        <input type="tel" class="form-control" id="phone" name="phone" placeholder="Ingresa tu teléfono" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Selecciona tus productos:</label>


		<?php
			if($objListItem)
			{
				foreach($objListItem as $item)
				{
					?>
					<div class="form-check mb-2">
					  <div class="product-selection-row">
						<input class="form-check-input option" type="checkbox" value="<?php echo $item->name; ?>" data-price="<?php echo round($item->price1,2); ?>" data-img-src="desayuno.jpg" id="product<?php echo $item->itemID; ?>">
						<div class="product-details">
						  <img class="product-image me-2" src="desayuno.jpg" alt="<?php echo $item->name; ?>">
						  <label class="form-check-label" for="product<?php echo $item->itemID; ?>">
							<?php echo $item->name; ?> (C$<?php echo round($item->price1,2); ?>)
						  </label>
						</div>
					  </div>
					  <div class="quantity-row row g-0">
						<div class="col-auto">
						  <label class="col-form-label me-2">Cantidad:</label>
						</div>
						<div class="col-4">
						  <input name="itemID" value="<?php echo $item->itemID; ?>" type="hidden" />
						  <input name="price" value="<?php echo round($item->price1,2); ?>" type="hidden" />
						  <input name="quantity" type="number" class="form-control quantity" min="0" max="10" value="0">
						</div>
					  </div>
					</div>
					<?php
				}
			}
		?>
        

      </div>

      <button type="button" class="btn btn-primary w-100 mb-3" id="verifyBtn">Verificar Selección</button>
    </form>

    <div class="summary-screen">
      <h4>📋 Resumen de tu orden</h4>
      <p><strong>Nombre:</strong> <span id="summaryName"></span></p>
      <p><strong>Dirección:</strong> <span id="summaryAddress"></span></p>
      <p><strong>Teléfono:</strong> <span id="summaryPhone"></span></p>

      <table class="table table-bordered mt-3">
        <thead>
          <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody id="summaryOptions"></tbody>
        <tfoot>
          <tr>
            <th colspan="2">Total</th>
            <th id="summaryTotal">$0</th>
          </tr>
        </tfoot>
      </table>

      <div class="d-flex flex-column flex-md-row gap-2 mt-3">
        <button id="editOrderBtn" class="btn btn-secondary flex-fill">🔙 Editar Orden</button>
        <button id="confirmOrderBtn" class="btn btn-success flex-fill">✅ Confirmar Orden</button>
      </div>
    </div>
  </div>
  
  
  	<div id="modalValidSurvery" style="display:none">
		<h3>FALTAN DATOS</h3>
		<br/>
		Escribir todos los campos
	</div>
	<?php
		helper_getHtmlOfModalDialog("ModalValidSurvery","modalValidSurvery","fnAceptarModalValidSurvery",true,false);
	?>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
    $(document).ready(function() {
      $('.option').change(function() {
        if ($(this).is(':checked')) {
          $(this).closest('.form-check').find('.quantity-row').slideDown();
        } else {
          $(this).closest('.form-check').find('.quantity-row').slideUp();
        }
      });

      $(document).on('input', '.quantity', function() {
        let val = parseInt($(this).val());
        if (isNaN(val) || val < 1) {
          $(this).val(1);
        } else if (val > 10) {
          $(this).val(10);
        }
      });

      $('#verifyBtn').click(function() {
        let name = $('#name').val();
        let address = $('#address').val();
        let phone = $('#phone').val();
        let options = [];
        let total = 0;

        $('.option:checked').each(function() {
          let productName = $(this).val();
          let price = parseFloat($(this).data('price'));
          let qty = parseInt($(this).closest('.form-check').find('.quantity').val());
          qty = Math.min(Math.max(qty, 1), 10);
          let subtotal = qty * price;
          total += subtotal;

          options.push({
            name: productName,
            quantity: qty,
            price: price,
            subtotal: subtotal
          });
        });

        if (!name || !address || !phone || options.length === 0) {
          mostrarModal('ModalValidSurvery');
          return;
        }

        $('#summaryName').text(name);
        $('#summaryAddress').text(address);
        $('#summaryPhone').text(phone);

        $('#summaryOptions').empty();
        $.each(options, function(i, option) {
          $('#summaryOptions').append(
            `<tr>
              <td>${option.name}</td>
              <td>${option.quantity}</td>
              <td>$${option.subtotal.toFixed(2)}</td>
            </tr>`
          );
        });

        $('#summaryTotal').text('$' + total.toFixed(2));
        $('#orderForm').slideUp();
        $('.summary-screen').slideDown();
      });

      $('#editOrderBtn').click(function() {
        $('.summary-screen').slideUp();
        $('#orderForm').slideDown();
      });

      $('#confirmOrderBtn').click(function() {
        $('#orderForm').submit();
      });
    });
  </script>
</body>
</html>
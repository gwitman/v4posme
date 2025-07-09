<?php
$showPrice      = getBahavioDB($key, 'app_invoice_survery', 'mostrar_precio', 'true');
$showSubTotal   = getBahavioDB($key, 'app_invoice_survery', 'mostrar_subtotal', 'true');
$showTotal      = getBahavioDB($key, 'app_invoice_survery', 'mostrar_total', 'true');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>¡Vota u Ordena!</title>
  <link rel="icon" type="image/x-icon" href="<?= APP_URL_RESOURCE_CSS_JS ?>/resource/img/favicon.ico"/>
  <link
    href="<?= base_url()?>/resource/css/bootstrap5/bootstrap.min.css"
    rel="stylesheet"
  />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
  <style>
    body {
      background: <?=getBahavioDB($key, 'app_invoice_survery', 'fondo_encuesta', "linear-gradient(135deg, #FFA07A, #FF7F50)")?>;
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
    <div class="logo">
        <img class="img-fluid" src="<?=base_url()?>/resource/img/<?= getBahavioDB($key, 'app_invoice_survery', 'img_logo', 'cow.png')?>" alt="">
    </div>
    <h1>
        <img width="40px" class="img-fluid" src="<?=base_url()?>/resource/img/<?= getBahavioDB($key, 'app_invoice_survery', 'img_titulo', 'cow.png')?>" alt="">
        <?= getBahavioDB($key, 'app_invoice_survery', 'titulo', '¡Vota u Ordena!')?>
    </h1>
    <p class="tagline"><?= getBahavioDB($key, 'app_invoice_survery', 'subtitulo', '¡Elige tus productos favoritos y confirma tu pedido!')?></p>

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
						  <img class="product-image me-2" src="<?=base_url()?>/resource/img/<?= getBahavioDB($key, 'app_invoice_survery', 'img_item', 'cow.png')?>" alt="<?php echo $item->name; ?>">
						  <label class="form-check-label" for="product<?php echo $item->itemID; ?>">
							<?php echo $item->name; ?> <?= $showPrice=='true' ?  '(C$'.round($item->price1,2).')' : ''; ?>
						  </label>
						</div>
					  </div>
					  <div class="quantity-row row g-0">
						<div class="col-auto">
						  <label class="col-form-label me-2">Cantidad:</label>
						</div>
						<div class="col-4">
						  <input name="itemID[]" value="<?php echo $item->itemID; ?>" type="hidden" />
						  <input name="price[]" value="<?= round($item->price1,2) ?>" type="hidden" />
						  <input name="quantity[]" type="number" class="form-control quantity" min="0" max="10" value="0" />
						</div>
					  </div>
					</div>
					<?php
				}
			}
		?>
        

      </div>

      <button type="button" class="btn btn-primary w-100 mb-3" id="verifyBtn">
          <?= getBahavioDB($key, 'app_invoice_survery', 'boton_verificar', 'Verificar Selección')?>
      </button>
    </form>

    <div class="summary-screen">
      <h4>📋 <?= getBahavioDB($key, 'app_invoice_survery', 'titulo_resumen', 'Resumen de tu orden')?></h4>
      <p><strong>Nombre:</strong> <span id="summaryName"></span></p>
      <p><strong>Dirección:</strong> <span id="summaryAddress"></span></p>
      <p><strong>Teléfono:</strong> <span id="summaryPhone"></span></p>

      <table class="table table-bordered mt-3">
        <thead>
          <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th class="trSubtotal">Subtotal</th>
          </tr>
        </thead>
        <tbody id="summaryOptions"></tbody>
        <tfoot>
          <tr id="trTotal">
            <th colspan="2">Total</th>
            <th id="summaryTotal">C$ 0</th>
          </tr>
        </tfoot>
      </table>

      <div class="d-flex flex-column flex-md-row gap-2 mt-3">
        <button id="editOrderBtn" class="btn btn-secondary flex-fill">🔙 <?= getBahavioDB($key, 'app_invoice_survery', 'boton_editar', 'Editar Orden')?></button>
        <button id="confirmOrderBtn" class="btn btn-success flex-fill">✅ <?= getBahavioDB($key, 'app_invoice_survery', 'boton_confirmar', 'Confirmar Orden')?></button>
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

  <script src="<?= base_url()?>/resource/js/jquery-3.7.1.min.js"></script>
  <script src="<?= base_url()?>/resource/js/bootstrap5/bootstrap.bundle.min.js"></script>
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
              <td class="trSubtotal">C$${option.subtotal.toFixed(2)}</td>
            </tr>`
          );
        });
       <?php if($showTotal == 'false') echo "$('#trTotal').hide();" ?>
       <?php if($showSubTotal == 'false') echo "$('.trSubtotal').hide();" ?>

        $('#summaryTotal').text('C$' + total.toFixed(2));
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
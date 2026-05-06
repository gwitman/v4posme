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
  <link href="<?= base_url()?>/resource/css/bootstrap5/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      background: <?=getBahavioDB($key, 'app_invoice_survery', 'fondo_encuesta', "linear-gradient(135deg, #1a1a2e, #16213e, #0f3460)")?>;
      font-family: 'Montserrat', sans-serif;
      min-height: 100vh;
      overflow-x: hidden;
    }
    .container {
      max-width: 800px;
      width: 100%;
      margin: 30px auto;
      background: #fff;
      border-radius: 18px;
      padding: 30px;
      box-shadow: 0 12px 32px rgba(0,0,0,0.3);
      box-sizing: border-box;
    }
    h1 {
      text-align: center;
      margin-bottom: 10px;
      color: #e63946;
      font-weight: 700;
    }
    .tagline {
      text-align: center;
      font-size: 1rem;
      margin-bottom: 28px;
      color: #666;
    }
    .logo {
      display: block;
      margin: 0 auto 20px;
      <?= getBahavioDB($key, 'app_invoice_survery', 'sizeImageSurvary', 'max-width: 100px;')?>
    }
    .form-label {
      color: #e63946;
      font-weight: 600;
    }
    /* ---- Tarjeta de producto ---- */
    .product-card {
      background: #fafafa;
      border: 1.5px solid #eee;
      border-radius: 14px;
      padding: 14px;
      margin-bottom: 14px;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    .product-card.selected {
      border-color: #e63946;
      box-shadow: 0 4px 16px rgba(230,57,70,0.15);
      background: #fff5f5;
    }
    .product-header {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .product-image {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 12px;
      border: 2px solid #e63946;
      flex-shrink: 0;
    }
    .product-info {
      flex-grow: 1;
    }
    .product-name {
      font-weight: 700;
      font-size: 1rem;
      color: #222;
      margin-bottom: 2px;
    }
    .product-price {
      font-size: 0.9rem;
      color: #e63946;
      font-weight: 600;
    }
    /* Checkbox personalizado */
    .custom-check {
      width: 26px;
      height: 26px;
      border: 2.5px solid #e63946;
      border-radius: 7px;
      background: #f8f8f8;
      cursor: pointer;
      appearance: none;
      -webkit-appearance: none;
      flex-shrink: 0;
      transition: background 0.2s, border-color 0.2s;
      position: relative;
    }
    .custom-check:checked {
      background: #e63946;
      border-color: #e63946;
    }
    .custom-check:checked::after {
      content: '✓';
      color: #fff;
      font-size: 15px;
      font-weight: 700;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }
    /* Botón ver foto */
    .btn-specs {
      font-size: 0.78rem;
      padding: 4px 10px;
      border-radius: 20px;
      border: 1.5px solid #e63946;
      color: #e63946;
      background: transparent;
      cursor: pointer;
      transition: background 0.2s, color 0.2s;
      white-space: nowrap;
    }
    .btn-specs:hover {
      background: #e63946;
      color: #fff;
    }
    /* Área de opciones del producto (combo + cantidad + comentario) */
    .product-options {
      display: none;
      margin-top: 12px;
      padding-top: 12px;
      border-top: 1px dashed #ddd;
    }
    .product-options .form-label {
      font-size: 0.85rem;
      margin-bottom: 4px;
    }
    .quantity-row {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .quantity-row label {
      color: #e63946;
      font-weight: 600;
      font-size: 0.85rem;
      white-space: nowrap;
    }
    /* Resumen */
    .summary-screen { display: none; }
    .table > :not(caption) > * > * { vertical-align: middle; }
    /* Modal de producto */
    .modal-product-img {
      width: 100%;
      max-height: 300px;
      object-fit: contain;
      border-radius: 12px;
      margin-bottom: 16px;
    }
    .spec-badge {
      display: inline-block;
      background: #fff0f0;
      color: #e63946;
      border: 1px solid #e63946;
      border-radius: 20px;
      padding: 3px 12px;
      font-size: 0.82rem;
      margin: 3px 2px;
      font-weight: 600;
    }
    /* Buscador y filtros */
    .search-filter-bar {
      margin-bottom: 16px;
    }
    .search-input-wrap {
      position: relative;
    }
    .search-input-wrap input {
      padding-left: 36px;
      border-radius: 20px;
      border: 1.5px solid #ddd;
      font-size: 0.9rem;
    }
    .search-input-wrap input:focus {
      border-color: #e63946;
      box-shadow: 0 0 0 0.15rem rgba(230,57,70,0.15);
      outline: none;
    }
    .search-icon {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: #aaa;
      font-size: 0.9rem;
      pointer-events: none;
    }
    .category-pills {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
      margin-top: 10px;
    }
    .pill {
      padding: 4px 14px;
      border-radius: 20px;
      border: 1.5px solid #e63946;
      color: #e63946;
      background: transparent;
      font-size: 0.78rem;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.2s, color 0.2s;
      white-space: nowrap;
    }
    .pill.active, .pill:hover {
      background: #e63946;
      color: #fff;
    }
    .no-results {
      text-align: center;
      color: #aaa;
      font-size: 0.9rem;
      padding: 20px 0;
      display: none;
    }
    /* Responsive */
    @media (max-width: 576px) {
      .container { margin: 10px; padding: 16px; border-radius: 12px; }
      h1 { font-size: 1.5rem; }
      .product-image { width: 64px; height: 64px; }
      .product-name { font-size: 0.92rem; }
    }
    @media (max-width: 720px) {
      .container {
        margin: 0;
        border-radius: 0;
        padding: 16px;
      }
      .product-header {
        flex-wrap: wrap;
      }
      .product-info {
        flex: 1 1 0;
        min-width: 0;
      }
      .btn-specs {
        order: 3;
        width: 100%;
        margin-top: 8px;
        text-align: center;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo">
      <img class="img-fluid" src="<?=base_url()?>/resource/img/<?= getBahavioDB($key, 'app_invoice_survery', 'img_logo', 'cow.png')?>" alt="Logo">
    </div>
    <h1>
      <?= getBahavioDB($key, 'app_invoice_survery', 'img_titulo', '<img width="36px" class="img-fluid" src="/resource/img/cow.png" alt="">')?>
      <?= getBahavioDB($key, 'app_invoice_survery', 'titulo', '¡Vota u Ordena!')?>
    </h1>
    <p class="tagline"><?= getBahavioDB($key, 'app_invoice_survery', 'subtitulo', '¡Elige tus productos favoritos y confirma tu pedido!')?></p>

    <form id="orderForm" method="POST" action="<?php echo base_url()."/app_invoice_survery/insertElement"; ?>">
      <input type="hidden" name="key" id="key" value="<?php echo $key; ?>">

      <div class="mb-3">
        <label for="name" class="form-label">Nombre:</label>
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

        <!-- Buscador y filtro por categoría -->
        <div class="search-filter-bar">
          <div class="search-input-wrap">
            <span class="search-icon">🔍</span>
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar producto...">
          </div>
          <?php
            // Recolectar categorías únicas no vacías
            $categories = [];
            if($objListItem) {
              foreach($objListItem as $it) {
                $cat = trim($it->categoryName ?? '');
                if($cat !== '' && !in_array($cat, $categories)) {
                  $categories[] = $cat;
                }
              }
            }
          ?>
          <?php if(!empty($categories)): ?>
          <div class="category-pills" id="categoryPills">
            <button type="button" class="pill active" data-cat="">Todos</button>
            <?php foreach($categories as $cat): ?>
            <button type="button" class="pill" data-cat="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></button>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>

        <div id="noResults" class="no-results">No se encontraron productos.</div>

        <?php if($objListItem): foreach($objListItem as $item):
          //$itemImg    = !empty($item->urlImage) ? $item->urlImage : base_url().'/resource/img/'.getBahavioDB($key, 'app_invoice_survery', 'img_item', 'cow.png');
          $itemImg    = base_url()."/resource/file_company/company_2/component_33/component_item_".$item->itemID."/default_public.jpg";
          $itemDesc   = !empty($item->description) ? $item->description : '';
          $condoRaw   = !empty($item->realStateReferenceCondominio) ? $item->realStateReferenceCondominio : '';
          $condoOpts  = array_filter(array_map('trim', explode("\n", $condoRaw)));
        ?>
        <div class="product-card" id="card<?php echo $item->itemID; ?>"
          data-name="<?php echo strtolower(htmlspecialchars($item->name)); ?>"
          data-category="<?php echo strtolower(htmlspecialchars(trim($item->categoryName ?? ''))); ?>">
          <div class="product-header">
            <!-- Checkbox -->
            <input class="custom-check option" type="checkbox"
              value="<?php echo htmlspecialchars($item->name); ?>"
              data-price="<?php echo round($item->price1,2); ?>"
              data-item-id="<?php echo $item->itemID; ?>"
              id="product<?php echo $item->itemID; ?>">

            <!-- Imagen -->
            <img class="product-image"
              src="<?php echo $itemImg; ?>"
              alt="<?php echo htmlspecialchars($item->name); ?>">

            <!-- Info -->
            <div class="product-info">
              <div class="product-name"><?php echo htmlspecialchars($item->name); ?></div>
              <?php if($showPrice == 'true'): ?>
              <div class="product-price">C$ <?php echo number_format(round($item->price1,2),2); ?></div>
              <?php endif; ?>
            </div>

            <!-- Botón ver foto/specs -->
            <button type="button" class="btn-specs"
              data-bs-toggle="modal"
              data-bs-target="#modalProduct<?php echo $item->itemID; ?>">
              🏍️ Ver foto
            </button>
          </div>

          <!-- Opciones: combo + cantidad + comentario (se muestran al seleccionar) -->
          <div class="product-options" id="opts<?php echo $item->itemID; ?>">

            <?php if(!empty($condoOpts)): ?>
            <div class="mb-2">
              <label class="form-label">Variante / Referencia:</label>
              <select class="form-select form-select-sm combo-option" name="combo_reference[<?php echo $item->itemID; ?>]">
                <option value="">-- Selecciona una opción --</option>
                <?php foreach($condoOpts as $opt): ?>
                <option value="<?php echo htmlspecialchars($opt); ?>"><?php echo htmlspecialchars($opt); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <?php endif; ?>

            <div class="<?= getBahavioDB($key, 'app_invoice_survery','ocultar_cantidad','')?>">
              <div class="quantity-row mb-2">
                <label>Cantidad:</label>
                <input name="itemID[]" value="<?php echo $item->itemID; ?>" type="hidden"/>
                <input name="price[]" value="<?= round($item->price1,2) ?>" type="hidden"/>
                <input name="quantity[]" type="number" class="form-control form-control-sm quantity"
                  style="max-width:90px"
                  min="<?= getBahavioDB($key, 'app_invoice_survery','cantidad_default','0')?>"
                  max="10"
                  value="<?= getBahavioDB($key, 'app_invoice_survery','cantidad_default','0')?>"/>
              </div>
            </div>

            <div class="mb-1">
              <label class="form-label">Comentario (opcional):</label>
              <textarea class="form-control form-control-sm item-comment"
                name="comment_reference[<?php echo $item->itemID; ?>]"
                rows="2"
                placeholder="Ej: color rojo, talla M, instrucciones especiales..."></textarea>
            </div>
          </div>
        </div>

        <!-- Modal de foto / especificaciones del producto -->
        <div class="modal fade" id="modalProduct<?php echo $item->itemID; ?>" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px; overflow:hidden;">
              <div class="modal-header" style="background:#e63946; color:#fff; border:none;">
                <h5 class="modal-title fw-bold">🏍️ <?php echo htmlspecialchars($item->name); ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body text-center">
                <img class="modal-product-img"
                  src="<?php echo $itemImg; ?>"
                  alt="<?php echo htmlspecialchars($item->name); ?>">

                <?php if($showPrice == 'true'): ?>
                <p class="fw-bold" style="color:#e63946; font-size:1.2rem;">
                  C$ <?php echo number_format(round($item->price1,2),2); ?>
                </p>
                <?php endif; ?>

                <?php if(!empty($itemDesc)): ?>
                <p class="text-muted" style="font-size:0.9rem; text-align:left;"><?php echo nl2br(htmlspecialchars($itemDesc)); ?></p>
                <?php endif; ?>

                <?php if(!empty($condoOpts)): ?>
                <div class="text-start mt-2">
                  <p class="form-label mb-1">Variantes disponibles:</p>
                  <?php foreach($condoOpts as $opt): ?>
                  <span class="spec-badge"><?php echo htmlspecialchars($opt); ?></span>
                  <?php endforeach; ?>
                </div>
                <?php endif; ?>
              </div>
              <div class="modal-footer" style="border:none;">
                <button type="button" class="btn btn-danger w-100" data-bs-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
        </div>

        <?php endforeach; endif; ?>
      </div>

      <button type="button" class="btn btn-danger w-100 mb-3 fw-bold" id="verifyBtn" style="border-radius:10px; padding:12px; font-size:1rem;">
        🛒 <?= getBahavioDB($key, 'app_invoice_survery', 'boton_verificar', 'Verificar Selección')?>
      </button>
    </form>

    <!-- Pantalla de resumen -->
    <div class="summary-screen">
      <h4>📋 <?= getBahavioDB($key, 'app_invoice_survery', 'titulo_resumen', 'Resumen de tu orden')?></h4>
      <p><strong>Nombre:</strong> <span id="summaryName"></span></p>
      <p><strong>Dirección:</strong> <span id="summaryAddress"></span></p>
      <p><strong>Teléfono:</strong> <span id="summaryPhone"></span></p>

      <table class="table table-bordered mt-3">
        <thead class="table-danger">
          <tr>
            <th>Producto</th>
            <th>Variante</th>
            <th>Cant.</th>
            <th>Comentario</th>
            <th class="trSubtotal">Subtotal</th>
          </tr>
        </thead>
        <tbody id="summaryOptions"></tbody>
        <tfoot>
          <tr id="trTotal">
            <th colspan="4">Total</th>
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
    Escribir todos los campos requeridos y seleccionar al menos un producto.
  </div>
  <?php helper_getHtmlOfModalDialog("ModalValidSurvery","modalValidSurvery","fnAceptarModalValidSurvery",true,false); ?>

  <script src="<?= base_url()?>/resource/js/jquery-3.7.1.min.js"></script>
  <script src="<?= base_url()?>/resource/js/bootstrap5/bootstrap.bundle.min.js"></script>
  <script>
    $(document).ready(function() {

      // Mostrar/ocultar opciones al marcar checkbox
      $('.option').change(function() {
        let itemId = $(this).data('item-id');
        let card   = $('#card' + itemId);
        let opts   = $('#opts' + itemId);
        if ($(this).is(':checked')) {
          card.addClass('selected');
          opts.slideDown(200);
        } else {
          card.removeClass('selected');
          opts.slideUp(200);
        }
      });

      // Validar rango de cantidad
      $(document).on('input', '.quantity', function() {
        let val = parseInt($(this).val());
        if (isNaN(val) || val < 1) $(this).val(1);
        else if (val > 10) $(this).val(10);
      });

      // Verificar selección
      $('#verifyBtn').click(function() {
        let name    = $('#name').val().trim();
        let address = $('#address').val().trim();
        let phone   = $('#phone').val().trim();
        let options = [];
        let total   = 0;

        $('.option:checked').each(function() {
          let itemId      = $(this).data('item-id');
          let productName = $(this).val();
          let price       = parseFloat($(this).data('price'));
          let optsDiv     = $('#opts' + itemId);
          let qty         = parseInt(optsDiv.find('.quantity').val()) || 1;
          qty = Math.min(Math.max(qty, 1), 10);
          let subtotal    = qty * price;
          total += subtotal;

          let comboVal  = optsDiv.find('.combo-option').val() || '';
          let commentVal = optsDiv.find('.item-comment').val().trim() || '';

          options.push({ name: productName, quantity: qty, price: price, subtotal: subtotal, combo: comboVal, comment: commentVal });
        });

        if (!name || !address || !phone || options.length === 0) {
          mostrarModal('ModalValidSurvery');
          return;
        }

        $('#summaryName').text(name);
        $('#summaryAddress').text(address);
        $('#summaryPhone').text(phone);

        $('#summaryOptions').empty();
        $.each(options, function(i, o) {
          let comboCell   = o.combo   ? `<span class="spec-badge">${o.combo}</span>`   : '<span class="text-muted">—</span>';
          let commentCell = o.comment ? `<small>${o.comment}</small>` : '<span class="text-muted">—</span>';
          $('#summaryOptions').append(
            `<tr>
              <td><strong>${o.name}</strong></td>
              <td>${comboCell}</td>
              <td>${o.quantity}</td>
              <td>${commentCell}</td>
              <td class="trSubtotal">C$ ${o.subtotal.toFixed(2)}</td>
            </tr>`
          );
        });

        <?php if($showTotal == 'false') echo "$('#trTotal').hide();"; ?>
        <?php if($showSubTotal == 'false') echo "$('.trSubtotal').hide();"; ?>

        $('#summaryTotal').text('C$ ' + total.toFixed(2));
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

      // --- Buscador y filtro por categoría ---
      let activeCat = '';

      function filterProducts() {
        let text = $('#searchInput').val().toLowerCase().trim();
        let visible = 0;
        $('.product-card').each(function() {
          let name = $(this).data('name') || '';
          let cat  = $(this).data('category') || '';
          let matchText = text === '' || name.includes(text);
          let matchCat  = activeCat === '' || cat === activeCat;
          if (matchText && matchCat) {
            $(this).show();
            visible++;
          } else {
            $(this).hide();
          }
        });
        $('#noResults').toggle(visible === 0);
      }

      $('#searchInput').on('input', filterProducts);

      $(document).on('click', '#categoryPills .pill', function() {
        $('#categoryPills .pill').removeClass('active');
        $(this).addClass('active');
        activeCat = $(this).data('cat').toLowerCase();
        filterProducts();
      });
    });
  </script>
</body>
</html>

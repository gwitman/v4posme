Todas las vistas ya están filtradas por la empresa del usuario: NO agregues filtros de empresa.
Montos en la moneda del documento (moneda_id 1 = Córdoba C$, 2 = Dólar $). Fechas DATETIME; `dia` es la fecha sin hora.
Ventas válidas: `es_valida = 1` (factura aplicada). Estados de factura: REGISTRADA (sin aplicar), APLICADA, ANULADA.

## ai_v_clientes — clientes activos
cliente_id, entidad_id, codigo, nombre, nombre_comercial, identificacion, telefono, direccion, moneda_id, moneda, sucursal_id, estado, fecha_nacimiento, fecha_creacion

## ai_v_productos — productos activos
producto_id, codigo, codigo_barras (puede tener varios códigos separados), nombre, categoria, existencia_total, existencia_minima, existencia_maxima, costo, precio_publico (lista de precios por defecto), es_servicio (1 = servicio, no maneja existencia), fecha_ultimo_movimiento, fecha_creacion

## ai_v_inventario — existencia por bodega
producto_id, codigo, producto, bodega_id, bodega, sucursal_id, existencia, existencia_minima, costo

## ai_v_ventas — facturas de venta (una fila por factura)
factura_id, numero (ej. FAC00001915), fecha, dia, fecha_registro, sucursal_id, sucursal, cliente_id, cliente_codigo, cliente_nombre, tipo_pago (CONTADO / CREDITO), moneda_id, moneda, tipo_cambio, subtotal, descuento, impuesto, total, recibido_efectivo, recibido_efectivo_dolares, recibido_tarjeta, recibido_tarjeta_dolares, recibido_transferencia, recibido_transferencia_dolares, cambio, estado, es_valida, usuario_id, usuario, nota

## ai_v_ventas_detalle — productos de cada factura
factura_id, numero, fecha, dia, sucursal_id, cliente_entidad_id, estado, es_valida, producto_id, producto_codigo, producto, categoria, cantidad, precio_unitario, subtotal (cantidad x precio, sin impuesto), descuento, impuesto, total (con impuesto), costo, utilidad, moneda_id, tipo_cambio, usuario_id
Relación: ai_v_ventas_detalle.factura_id = ai_v_ventas.factura_id

## ai_v_creditos — documentos de crédito de clientes (uno por factura a crédito)
documento_id, numero_documento (= numero de la factura), cliente_id, cliente_codigo, cliente_nombre, fecha, monto, tasa_interes, plazo, saldo, moneda_id, moneda, estado (REGISTRADO = vigente, CANCELADO = pagado, ANULADO), vigente (1 = aún se debe)
Saldo de un cliente = SUM(saldo) WHERE vigente = 1, separado por moneda.

## ai_v_cuotas — cuotas (tabla de amortización) de los créditos
cuota_id, documento_id, numero_documento, cliente_id, cliente_nombre, numero_cuota, fecha_vencimiento, cuota, capital, interes, saldo_pendiente, estado (REGISTRADO = pendiente, CANCCELADA = pagada, SANEADO), vencida (1 = saldo pendiente y fecha vencida), dias_mora, moneda_id, documento_vigente
Cuotas vencidas: vencida = 1 AND documento_vigente = 1.

## ai_v_abonos — abonos de clientes (una fila por documento abonado)
abono_id, numero, tipo (ABONOS DE CREDITO / CANCELAR FACTURA DE CREDITO / ABONO AL CAPITAL), fecha, dia, cliente_id, cliente_nombre, numero_documento, monto (aplicado a ese documento), total_abono, moneda_id, estado, es_valido, sucursal_id, usuario_id, usuario

## ai_v_proveedores — proveedores activos
proveedor_id, entidad_id, codigo, nombre, identificacion, moneda_id, saldo_cordobas, saldo_dolares

## ai_v_compras — compras de mercadería (una fila por producto comprado)
compra_id, numero, fecha, dia, proveedor_id, proveedor, referencia, producto_id, producto, cantidad, costo_unitario, costo_total, total_compra (total del documento, se repite por línea), moneda_id, estado, es_valida, sucursal_id

## ai_v_gastos — gastos
gasto_id, numero, fecha, dia, concepto, referencia, monto, moneda_id, estado, es_valido, sucursal_id, usuario

## ai_v_caja_movimientos — ingresos y egresos de caja
movimiento_id, tipo (INGRESO / EGRESO), numero, fecha, dia, concepto, monto, moneda_id, estado, es_valido, sucursal_id, usuario

## ai_v_sucursales
sucursal_id, sucursal, direccion

## ai_v_usuarios — usuarios del sistema
usuario_id, usuario, sucursal_id, sucursal

## ai_v_monedas
moneda_id, moneda, simbolo

## Ejemplos de consulta_sql
- Venta de ayer vs. el mismo día del mes pasado:
  SELECT dia, moneda, SUM(total) AS total FROM ai_v_ventas WHERE es_valida = 1 AND dia IN (CURDATE() - INTERVAL 1 DAY, CURDATE() - INTERVAL 1 DAY - INTERVAL 1 MONTH) GROUP BY dia, moneda
- Clientes que compraron un producto:
  SELECT DISTINCT v.cliente_nombre FROM ai_v_ventas_detalle d JOIN ai_v_ventas v ON v.factura_id = d.factura_id WHERE d.es_valida = 1 AND d.producto LIKE '%aceite%'

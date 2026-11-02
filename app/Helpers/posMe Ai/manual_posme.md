# Manual de uso de posMe (borrador para el asistente Jordania)

Este manual lo consulta Jordania con la herramienta buscar_en_manual. Cada sección empieza con `## Título`.
Las rutas de menú son las del menú estándar; el menú real de cada usuario depende de sus permisos
(Jordania usa la herramienta ruta_menu para confirmarlo). Lo marcado con REVISAR debe completarlo el equipo de posMe.

## Cómo funcionan las pantallas de posMe (listas y registros)
Ruta: cualquier opción del menú.
1. Al entrar a una opción se muestra la lista de registros (búsqueda en la parte superior).
2. Para crear un registro use el botón **Nuevo** de la lista.
3. Para ver o modificar un registro haga clic sobre él en la lista; se abre la pantalla de edición.
4. En la pantalla de edición use **Guardar** para guardar los cambios y **Atras** para volver a la lista.
5. Los documentos (facturas, abonos, ajustes, entradas, salidas) tienen un campo **Estado**: mientras están en REGISTRADO se pueden modificar; al pasar a APLICADO afectan inventario, caja o saldos.

## Facturar (crear una factura de venta)
Ruta: Menú > FACTURACION > FACTURAR (o FACTURACION > NUEVA FACTURA).
1. Entre a FACTURACION > FACTURAR y presione **Nuevo** (o use NUEVA FACTURA para ir directo).
2. Seleccione el cliente (o deje el cliente de contado / predeterminado).
3. Elija el tipo de pago: CONTADO o CREDITO. Si es a crédito, complete la pestaña **Info de Credito.** (plazo, frecuencia de pago, interés).
4. Agregue los productos con **AGREGAR PRO** (o escaneando el código de barras) e indique cantidad y precio.
5. Para quitar un producto use **ELIMINAR PRO**.
6. Presione **PROCESAR PAGO**, registre lo recibido (efectivo, tarjeta, transferencia, córdobas o dólares) y aplique la factura.
7. Use **IMPRIMIR** para imprimir el ticket o factura.
Notas: algunas cajas exigen tener la caja abierta (**ABRIR CAJA**) antes de facturar. <!-- REVISAR: confirmar el orden exacto de PROCESAR PAGO / aplicar segun la configuracion -->

## Anular una factura
Ruta: Menú > FACTURACION > FACTURAR.
1. Busque la factura en la lista y ábrala.
2. Presione **ELIMINAR FAC** y confirme.
3. Si la factura ya estaba APLICADA, posMe genera automáticamente un documento de reversión: devuelve el inventario y, si era a crédito, anula el documento de crédito. La factura queda en estado ANULADA.
4. Si la factura estaba solo REGISTRADA (sin aplicar), simplemente se elimina.
Restricciones: no se puede anular una factura de crédito que ya tiene abonos, ni una factura de un periodo contable cerrado. Se requiere permiso de eliminar en FACTURAR.

## Registrar un abono de un cliente
Ruta: Menú > CAJA > ABONO.
1. Entre a CAJA > ABONO y presione **Nuevo**.
2. Seleccione el cliente.
3. Presione **Agregar factura** y elija el documento (factura de crédito) al que se aplicará el abono.
4. Escriba el monto en **Total del abono** y lo recibido (Recibido NAC / Recibido EXT). posMe muestra Saldo inicial y Saldo final.
5. Presione **Guardar**. Al aplicarse, el abono se distribuye en las cuotas según la configuración de la empresa (vencidas primero).
6. Use **Ver movimientos** para ver los movimientos del documento.
Ver también: CAJA > CANC FAC (cancelar el total de una factura de crédito) y CAJA > ABONO CAP (abono directo al capital).

## Cancelar una factura de crédito (pago total)
Ruta: Menú > CAJA > CANC FAC.
1. Presione **Nuevo** y seleccione el cliente.
2. Presione **Agregar documento** y elija la factura a cancelar.
3. Revise el monto y presione **Guardar**.

## Abono al capital
Ruta: Menú > CAJA > ABONO CAP.
1. Presione **Nuevo**, seleccione el cliente y el documento.
2. Indique el monto a abonar al capital y presione **Guardar**. <!-- REVISAR: efecto sobre la tabla de cuotas segun parametros -->

## Ver el estado de cuenta de un cliente
Ruta: Menú > CXC > REPORTES (Estado de cuenta / Estado del cliente).
1. Entre a CXC > REPORTES y elija el reporte de estado de cuenta del cliente.
2. Indique el código del cliente y el rango de fechas.
3. Presione el botón para ver el reporte; se puede imprimir o exportar.
También puede preguntarle a Jordania: "¿Cuánto me debe <cliente>?".
<!-- REVISAR: nombre exacto del reporte en el menu de reportes de CXC -->

## Crear un cliente
Ruta: Menú > CXC > CLIENTES.
1. Presione **Nuevo**.
2. En la pestaña **Informacion** complete nombre, apellido, identificación, categoría y estado.
3. En las pestañas **Telefonos.**, **Email.** y **Direccion** agregue los datos de contacto.
4. Si el cliente comprará a crédito, configure la pestaña **CXC Lineas.** (línea y límite de crédito).
5. Presione **Guardar**.

## Crear un producto
Ruta: Menú > INVENTARIO > PRODUCTO.
1. Presione **Nuevo**.
2. En **Informacion** complete *Nombre, *UM (unidad de medida), categoría, código de barras y *Estado.
3. En la pestaña **Bodegas** use **Agregar bodega** para indicar en qué bodegas existe.
4. En la pestaña **Precios** escriba los precios (público, por mayor, crédito...).
5. Opcional: pestaña **Sku** para presentaciones (caja, paquete) con **Agregar SKU**.
6. Presione **Guardar**.

## Cambiar precios de productos
Ruta: Menú > FACTURACION > PRECIOS.
1. Busque la lista de precios y el producto.
2. Modifique el precio por tipo (público, por mayor, crédito) y guarde.
<!-- REVISAR: pasos exactos de la pantalla de precios -->

## Ajustar el inventario
Ruta: Menú > INVENTARIO > AJUSTE.
1. Presione **Nuevo**, elija la bodega y la fecha.
2. Use **Agregar producto** para cada producto e indique la cantidad.
3. Presione **Guardar** y aplique el documento para que afecte la existencia.
Otras opciones: INVENTARIO > O. ENTRADAS (otras entradas), INVENTARIO > O. SALIDAS (otras salidas), INVENTARIO > T. SALIDA y T. ENTRADA (traslados entre bodegas).

## Registrar una compra de mercadería
Ruta: Menú > INVENTARIO > COMPRA.
1. Presione **Nuevo**, seleccione el proveedor, la bodega y la fecha.
2. Agregue los productos con su cantidad y costo.
3. Guarde y aplique la compra para que aumente el inventario. <!-- REVISAR -->

## Registrar un gasto
Ruta: Menú > CXP > GASTOS.
1. Presione **Nuevo**, indique fecha, concepto, monto y moneda.
2. Presione **Guardar**. <!-- REVISAR -->

## Abrir caja
Ruta: Menú > CAJA > ABRIR.
1. Presione **Nuevo**.
2. Seleccione la **Caja**, la moneda y el **Monto** inicial.
3. Presione **Guardar**.

## Cerrar caja (cierre / arqueo)
Ruta: Menú > CAJA > CERRAR.
1. Presione **Nuevo**.
2. Seleccione la **Caja**, la moneda y escriba el **Monto** contado.
3. Presione **Guardar**.
Para revisar lo cobrado use CAJA > REPORTES (abonos, resumen de abonos, cierre de operaciones).

## Ingresos y egresos de caja
Ruta: Menú > CAJA > ING A CAJA o CAJA > EGS A CAJA.
1. Presione **Nuevo**, indique fecha, moneda, monto y descripción.
2. Presione **Guardar**.

## Reportes de ventas
Ruta: Menú > VENTAS > REPORTES (también FACTURACION > REPORTES).
1. Elija el reporte: detalle de ventas, resumen de ventas, ventas por cliente, utilidad, etc.
2. Indique el rango de fechas y los filtros.
3. Vea, imprima o exporte el reporte.
También puede preguntarle a Jordania: "¿Cuánto vendí ayer?" o "¿Cuáles son los productos más vendidos este mes?".

## Reportes de inventario
Ruta: Menú > INVENTARIO > REPORTES.
Lista de productos, existencias por bodega, kardex, compras, productos por transacción.

## Reportes de cuentas por cobrar y cobranza
Ruta: Menú > CXC > REPORTES y COBRANZA > REPORTES.
Cartera de clientes, documentos de crédito, pagos, estado de cuenta, clientes con mora.

## Usuarios y contraseñas
Ruta: Menú > SEGURIDAD > USUARIOS.
1. Presione **Nuevo** para crear un usuario o abra uno existente.
2. Indique usuario, contraseña, sucursal y rol.
3. Presione **Guardar**.
Para cambiar su propia contraseña: SEGURIDAD > EDITAR PERFIL.

## Roles y permisos (incluye dar acceso a Jordania)
Ruta: Menú > SEGURIDAD > ROLES.
1. Abra el rol o cree uno nuevo con **Nuevo**.
2. En **Permisos por pantalla** marque las pantallas a las que tendrá acceso (ver, agregar, editar, eliminar). Hay botones rápidos: Acceso total, Solo ver, Quitar todo.
3. Para usar el asistente IA marque la opción **Asistente IA**.
4. Presione **Guardar**. Los usuarios del rol deben cerrar sesión y volver a entrar para ver los cambios.

## Sucursales
Ruta: Menú > SEGURIDAD > SUCURSAL.
Permite crear y editar las sucursales de la empresa.

## Parámetros de la empresa
Los parámetros controlan el comportamiento de posMe por empresa (por ejemplo, orden de aplicación de abonos, lista de precios por defecto, formato de impresión).
Los parámetros contables se editan en CONTABILIDAD > PARAMETROS; el resto los configura el equipo de soporte de posMe.
<!-- REVISAR: pantalla para que el cliente edite parametros generales -->

## Soporte
Si no encuentra cómo hacer algo o hay un error en el sistema, contacte al equipo de soporte de posMe. <!-- REVISAR: telefono / correo de soporte -->

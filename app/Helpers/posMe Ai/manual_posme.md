# Manual de uso de posMe (borrador para el asistente Jordania)

Este manual lo consulta Jordania con la herramienta buscar_en_manual. Cada sección empieza con `## Título`.
Las rutas de menú son las del menú estándar; el menú real de cada usuario depende de sus permisos
(Jordania usa la herramienta ruta_menu para confirmarlo). Lo marcado con REVISAR debe completarlo el equipo de posMe.
Este archivo es la fuente principal del manual de usuario: lo mantiene el agente posme-documentacion, que copia cada cambio
a public/resource/file_manual/manual_usuario_posme.txt. No use encabezados # ni ### en el cuerpo.

## Cómo funcionan las pantallas de posMe (listas y registros)
Ruta: cualquier opción del menú.
1. Al entrar a una opción se muestra la lista de registros (búsqueda en la parte superior).
2. Para crear un registro use el botón **Nuevo** de la lista.
3. Para ver o modificar un registro haga clic sobre él en la lista; según la pantalla se abre la edición directamente o debe presionar **Editar**.
4. En la pantalla de edición use **Guardar** para guardar los cambios y **Atras** para volver a la lista.
5. Los documentos (facturas, abonos, ajustes, entradas, salidas) tienen un campo **Estado**: mientras están en REGISTRADO se pueden modificar; al pasar a APLICADO afectan inventario, caja o saldos.
Notas: en las pantallas con encabezado de acciones y tarjetas, en el teléfono el botón **Guardar** está en la barra inferior y los botones **Buscar** abren la búsqueda en una ventana dentro de la misma pantalla. **Limpiar** borra lo elegido.

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
Ver también: Mayorizar y cerrar un ciclo contable.

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

## Asignar clientes a un gestor de cobranza
Ruta: Menú > COBRANZA > GESTORES.
Sirve para indicar qué clientes visita cada gestor (cobrador o asesor) y en qué orden de visita (ruta de cobro).
1. En la lista de gestores presione **Nuevo**. Se abre **Agregar Cliente a un Asesor**.
2. En la tarjeta **Asignación**, en **Colaborador** presione **Buscar** y elija el gestor. Con **Limpiar** borra lo elegido.
3. En **Cliente** presione **Buscar** y elija el cliente que se le asigna.
4. Defina el orden de visita: escriba el número en **Orden No**, o en **Aplicar orden después de** presione **Buscar** y elija el cliente detrás del cual debe ir. Si elige un cliente en **Aplicar orden después de**, se usa ese en lugar del número.
5. Opcional: en la tarjeta **Referencias** complete **Referencia FAC**, **Referido**, **Tasa**, **Referencia 4**, **Referencia 5** y **Descripción**. <!-- REVISAR: uso de negocio de Referencia FAC, Referido y Tasa -->
6. Presione **Guardar**. En el teléfono el botón **Guardar** está en la barra inferior de la pantalla.
Mensajes de validación:
* "El Colaborador es requerido" / "El Cliente es requerido".
* "No puede ingresar valores negativos en el orden".
* "El Cliente es identico al siguiente orden indicado": el cliente no puede ser el mismo de **Aplicar orden después de**.
Notas: si deja **Orden No** vacío el cliente se agrega sin un número de orden fijo. Según la configuración de su empresa los nombres pueden cambiar (por ejemplo **Ruta** en lugar de **Colaborador** y "CLIENTES POR RUTAS" como título de la lista). <!-- REVISAR: confirmar si con Orden No vacio el cliente queda de ultimo en la ruta -->
Ver también: Cambiar el orden de visita o quitar un cliente de un gestor.

## Cambiar el orden de visita o quitar un cliente de un gestor
Ruta: Menú > COBRANZA > GESTORES.
Cambiar el gestor o el orden de visita de un cliente:
1. En la lista de gestores escriba en el buscador y presione el botón de la lupa para filtrar.
2. Seleccione la fila y presione **Editar**. Se abre **Editar Cliente a un Asesor**. Si no seleccionó una fila aparece "Debe de Seleccionar el Registros, para editarlo...".
3. Cambie **Colaborador**, **Cliente**, **Orden No** o **Aplicar orden después de** con los botones **Buscar** y **Limpiar**.
4. Presione **Guardar** (en el teléfono, en la barra inferior).
Al editar es obligatorio indicar el orden: si **Orden No** queda vacío o en 0 y no eligió **Aplicar orden después de**, aparece "Debe indicar el orden a ingresar el Cliente o debe ser mayor que 0". También se validan los mismos mensajes de la asignación.
Quitar un cliente de un gestor:
1. En la lista seleccione la fila y presione **Eliminar**, o dentro de **Editar Cliente a un Asesor** presione **Eliminar**.
2. Confirme el mensaje "Desea eliminar este Registro...".
Ver también: Asignar clientes a un gestor de cobranza.

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

## Qué contiene el menú CAJA
Ruta: Menú > CAJA.
Entre otras, el menú CAJA tiene estas opciones:
- **PROVISION**: provisiones de caja.
- **ING A CAJA** y **EGS A CAJA**: ingresos y egresos de caja.
- **ASISTENCIA**: asistencia de clientes.
- **ADM CAJA**: crear y administrar las cajas.
- **ABRIR** y **CERRAR**: apertura y cierre de caja.
Las listas tienen los botones **Vistas**, **Editar**, **Eliminar** y **Nuevo**. Seleccione la fila antes de presionar **Editar** o **Eliminar**; si no lo hace aparece "Seleccionar el Registro...".
Buscar en la lista:
- En ING A CAJA, EGS A CAJA, ASISTENCIA, ABRIR y CERRAR escriba en **Buscar por número…** y presione el botón de la lupa o Enter. No busca mientras escribe.
- En ADM CAJA, **Buscar caja…** filtra la lista mientras escribe.
Notas: en el teléfono el botón **Guardar** está en la barra inferior. Lo que ve cada usuario depende de los permisos de su rol. <!-- REVISAR: el buscador de la lista de PROVISION tiene un defecto conocido sin corregir; documentarlo cuando se corrija -->
Ver también: Abrir caja, Cerrar caja (cierre / arqueo), Ingresos y egresos de caja.

## Abrir caja
Ruta: Menú > CAJA > ABRIR. La lista se llama **LISTA DE APERTURA A CAJA**.
1. Presione **Nuevo**. Se abre **APERTURA:#**.
2. En la tarjeta **Información** indique **Fecha**, **Estado**, **Sucursal**, **Moneda**, **Caja**, **Tipo** y **Categoría**. Opcional: **Referencia 1**, **Referencia 2** y **Referencia 3**.
3. En la tarjeta **Denominaciones** escriba la cantidad de cada denominación. El **Monto** se calcula solo y no se escribe a mano.
4. Opcional: en la tarjeta **Comentario** escriba la **Descripción**.
5. Presione **Guardar** (en el teléfono, en la barra inferior).
Mensajes de validación: "Establecer Fecha al Documento" y "El monto no puede ser 0".
Para modificarla, selecciónela en la lista y presione **Editar**. Arriba tiene **Nuevo**, **Atras**, **Archivos**, **Imprimir**, **Eliminar** y **Guardar**. **Eliminar** pide confirmar "Desea eliminar este Registro...".
<!-- REVISAR: efecto de pasar el Estado a APLICADO; uso de negocio de Tipo, Categoría y Cambio -->
Ver también: Cerrar caja (cierre / arqueo).

## Cerrar caja (cierre / arqueo)
Ruta: Menú > CAJA > CERRAR. La lista se llama **LISTA DE CIERRE DE CAJA**.
1. Presione **Nuevo**. Se abre **CIERRE:#**.
2. En la tarjeta **Información** indique **Fecha**, **Estado**, **Sucursal**, **Moneda**, **Caja**, **Tipo** y **Categoría**. Opcional: **Referencia 1**, **Referencia 2** y **Referencia 3**.
3. En la tarjeta **Denominaciones** escriba la cantidad contada de cada denominación. El **Monto** se calcula solo y no se escribe a mano.
4. Opcional: en la tarjeta **Comentario** escriba la **Descripción**.
5. Presione **Guardar** (en el teléfono, en la barra inferior).
Mensajes de validación: "Establecer Fecha al Documento" y "El monto no puede ser 0".
Para modificarlo, selecciónelo en la lista y presione **Editar**. Arriba tiene **Nuevo**, **Atras**, **Archivos**, **Imprimir**, **Eliminar** y **Guardar**. **Eliminar** pide confirmar "Desea eliminar este Registro...".
Para revisar lo cobrado use CAJA > REPORTES (abonos, resumen de abonos, cierre de operaciones).
<!-- REVISAR: efecto de pasar el Estado a APLICADO; uso de negocio de Tipo, Categoría y Cambio -->
Ver también: Abrir caja.

## Ingresos y egresos de caja
Ruta: Menú > CAJA > ING A CAJA o CAJA > EGS A CAJA. Las listas se llaman **LISTA DE INGRESOS A CAJA** y **LISTA DE EGRESOS A CAJA**.
1. Presione **Nuevo**. Se abre **INGRESO:#** o **EGRESO:#**.
2. En la tarjeta **Información** indique **Fecha**, **Estado**, **Sucursal**, **Moneda**, **Caja**, **Tipo** y **Categoría**. Opcional: **Referencia 1**, **Referencia 2** y **Referencia 3**.
3. Indique el monto según el **Tipo**:
   - En un ingreso con **Tipo** Apertura, o un egreso con **Tipo** Cierre, aparece la tarjeta **Denominaciones**: escriba las cantidades y el **Monto** se calcula solo.
   - Con otro **Tipo** esa tarjeta se oculta y usted escribe el **Monto** a mano.
4. Opcional: en la tarjeta **Comentario** escriba la **Descripción**.
5. Presione **Guardar** (en el teléfono, en la barra inferior).
Mensajes de validación: "Establecer Fecha al Documento" y "El monto no puede ser 0".
Para modificarlo, selecciónelo en la lista y presione **Editar**. Arriba tiene **Nuevo**, **Atras**, **Archivos**, **Imprimir**, **Eliminar** y **Guardar**. **Eliminar** pide confirmar "Desea eliminar este Registro...".
<!-- REVISAR: efecto de pasar el Estado a APLICADO; uso de negocio de Categoría y Cambio -->

## Crear o administrar cajas (ADM CAJA)
Ruta: Menú > CAJA > ADM CAJA. La lista se llama **LISTA DE CAJAS**.
1. Presione **Nuevo**. Se abre **CAJA: #**.
2. En la tarjeta **Información** complete **Nombre de Caja**, **Estado** y **Descripción de Caja**.
3. Presione **Guardar** (en el teléfono, en la barra inferior).
Mensajes de validación: "Nombre de la caja no puede estar vacío" y "Descripción de la caja no puede estar vacía".
Para modificarla, selecciónela en la lista y presione **Editar**. Arriba tiene **Nuevo**, **Atras**, **Eliminar** y **Guardar**. **Eliminar** pide confirmar "Desea eliminar este Registro...".
Para encontrar una caja escriba en **Buscar caja…**: la lista se filtra mientras escribe.

## Registrar una provisión de caja
Ruta: Menú > CAJA > PROVISION. La lista se llama **LISTA DE PROVISIONES**.
1. Presione **Nuevo**. Se abre **PROVISION:#**.
2. En la tarjeta **Información** indique **Fecha** y **Estado**.
3. En la tarjeta **Referencias** complete **Persona Ref.**, **ID Persona Ref.** y **Referencia**.
4. Opcional: en la tarjeta **Comentario** escriba la **Descripción**.
5. En la tarjeta **Detalle** presione **Agregar documento** y elíjalo en la búsqueda. Solo se admite un documento. Revise el monto en la columna **Provisión**.
6. En la tarjeta **Pago** escriba **Ingreso**; se muestran **Total** y **Cambio**.
7. Presione **Guardar** (en el teléfono, en la barra inferior).
Quitar un documento: use el botón de quitar de su fila, o en la computadora márquelo y presione **Quitar marcados**.
Mensajes de validación:
- "Establecer Fecha al Documento" / "Seleccionar documento".
- "El Documento no puede tener mas de un item" / "El Documento ya esta agregado".
Para modificarla, selecciónela en la lista y presione **Editar**. Arriba tiene **Atras**, **Archivos**, **Eliminar** y **Guardar**. En la edición los campos se llaman **Cliente Ref.** e **ID Cliente Ref.**, y además se muestra **Cliente**.
<!-- REVISAR: para qué sirve la provisión, qué documentos se eligen y efecto de pasar el Estado a APLICADO; buscador de la lista con defecto conocido sin corregir -->

## Registrar la asistencia de un cliente
Ruta: Menú > CAJA > ASISTENCIA. La lista se llama **LISTA DE ASISTENCIA**.
1. Presione **Nuevo**. Se abre **ASISTENCIA:#**.
2. En la tarjeta **Información** indique **Fecha**, **Prioridad** y **Estado**.
3. Indique el cliente: en **Cliente** presione **Buscar**, o en **Huella** use el menú **Lectura** > **Abrir Sensor**. Con **Limpiar** borra lo elegido.
4. Revise **Solvencia**, **Próximo Pago**, **Días Próx. Pago** y **Vencimiento**.
5. Presione **Guardar** (en el teléfono, en la barra inferior).
Mensajes:
- "Establecer Fecha al Documento" / "Seleccione el cliente".
- "Cliente no encontrado" / "Cliente sin membresia" / "Cliente con Mora".
Para modificarla, selecciónela en la lista y presione **Editar**. Arriba tiene **Nuevo**, **Nuevo e Imprimir**, **Atras**, el menú **Imprimir** (**Imprimir preview** e **Imprimir direct**), **Eliminar** y **Guardar**. En la edición no aparece **Huella**.
<!-- REVISAR: funcionamiento de Huella y del sensor; significado de Solvencia; efecto de pasar el Estado a APLICADO; si "Cliente con Mora" impide guardar -->

## Qué contiene el menú BANCO
Ruta: Menú > BANCO.
- **CUENT. BANK**: cuentas bancarias.
- **CHEQUERA**: chequeras.
- **EMITIR CHEQUE**: emisión de cheques.
- **BANK NC**: notas de crédito de banco.
- **BANK ND**: notas de débito de banco.
Las listas tienen los botones **Vistas**, **Editar**, **Eliminar** y **Nuevo**. Seleccione la fila antes de presionar **Editar** o **Eliminar**; si no lo hace aparece "Seleccionar el Registro...".
Buscar en la lista:
- En CUENT. BANK, **Buscar en la lista…** filtra la lista cargada mientras escribe.
- En las demás opciones escriba en **Buscar por número…** y presione el botón de la lupa o Enter.
Notas: en el teléfono el botón **Guardar** está en la barra inferior y los botones **Buscar** abren la búsqueda en una ventana dentro de la misma pantalla. Lo que ve cada usuario depende de los permisos de su rol.
Ver también: Crear una cuenta bancaria, Crear una chequera, Emitir un cheque, Notas de crédito y débito de banco.

## Crear una cuenta bancaria
Ruta: Menú > BANCO > CUENT. BANK. La lista se llama **LISTA DE BANCOS**.
1. Presione **Nuevo**. Se abre **Formulario de Datos**.
2. En la tarjeta **Información** complete **Nombre**, **Moneda** y **Estado**. En **Administrador** use **Buscar** y **Limpiar**. **Balance** no se escribe a mano.
3. En la tarjeta **Tarjeta y comisiones** complete **Numero de tarjeta asociada**, **Fecha de expiracion**, **Comision POS** y **Comision Ahorro**.
4. En la tarjeta **Referencias** complete **Referencia 1**, **Referencia 2** y **Comentario**.
5. En la tarjeta **Acceso al banco** complete **URL de banco**, **Usuario**, **Contraseña** y **Pin**. **Contraseña** y **Pin** se escriben ocultos.
6. Presione **Guardar** (en el teléfono, en la barra inferior).
Mensajes de validación: "El nombre es requerido", "La moneda es requerida" y "El estado es requerido".
Para modificarla, selecciónela en la lista y presione **Editar**. Arriba tiene **Atras**, **Eliminar** y **Guardar**. **Eliminar** pide confirmar "Desea eliminar este Registro...".
<!-- REVISAR: cómo cambia el Balance; uso de Comision POS y Comision Ahorro; qué campos son obligatorios además de los validados -->
Ver también: Crear una chequera.

## Crear una chequera
Ruta: Menú > BANCO > CHEQUERA. La lista se llama **LISTA DE CHEQUERAS**.
1. Presione **Nuevo**. Se abre **CHEQUERA:#**.
2. En la tarjeta **Información** complete **Nombre**, **Estado** y **Moneda**. En **Banco** y **Empleado** use **Buscar** y **Limpiar**.
3. En la tarjeta **Numeración** complete **Serie**, **Valor Inicial** y **Valor Final**. **Valor Corriente** no se escribe a mano.
4. Presione **Guardar** (en el teléfono, en la barra inferior).
Mensajes de validación:
- "Ingrese el Nombre" / "Ingrese la Serie".
- "El valor Inicial es invalido" / "El valor Final es Invalido".
- "Seleccione el Banco" / "Seleccione el Empleado".
Para modificarla, selecciónela en la lista y presione **Editar**. Arriba tiene **Nuevo**, **Atras**, **Eliminar** y **Guardar**. **Eliminar** pide confirmar "Desea eliminar este Registro...".
<!-- REVISAR: uso de Serie y Valor Corriente; para qué se asigna un Empleado; efecto del Estado -->
Ver también: Emitir un cheque.

## Emitir un cheque
Ruta: Menú > BANCO > EMITIR CHEQUE. La lista se llama **LISTA DE EMISION DE CHEQUE**.
1. Presione **Nuevo**. Se abre **Emision De Cheque:#**.
2. En la tarjeta **Información** indique **Fecha**, **Estado** y **Moneda**.
3. En la tarjeta **Chequera**, en **Chequera** use **Buscar** y **Limpiar**. **Numero de Cheque** no se escribe a mano.
4. En la tarjeta **Pago** complete **Monto** y **Pagar A:**.
5. Opcional: en la tarjeta **Referencias** complete **Referencia 1**, **Referencia 2** y **Referencia 3**.
6. Presione **Guardar** (en el teléfono, en la barra inferior).
Mensajes de validación:
- "Ingrese la Fecha" / "Seleccione la chequera" / "Ingrese el beneficiario".
- "Ingrese el Monto": aparece también si el monto es 0.
- "Se ha alcanzdo el Limite de la Chequera".
Para modificarlo, selecciónelo en la lista y presione **Editar**. Arriba tiene **Nuevo**, **Atras**, **Archivos**, **Imprimir**, **Eliminar** y **Guardar**. **Eliminar** pide confirmar "Desea eliminar este Registro...".
<!-- REVISAR: de dónde sale el Numero de Cheque; efecto de pasar el Estado a APLICADO y efecto en el saldo del banco -->
Ver también: Crear una chequera.

## Notas de crédito y débito de banco
Ruta: Menú > BANCO > BANK NC o BANCO > BANK ND. Las listas se llaman **LISTA DE NOTAS DE CREDITO DE BANCO** y **LISTA DE NOTAS DE DEBITO DE BANCO**.
1. Presione **Nuevo**. Se abre **Nota de Credito:#** o **Nota de Debito:#**.
2. En la tarjeta **Información** indique **Fecha**, **Estado**, **Moneda** y **Monto**. En **Banco** use **Buscar** y **Limpiar**.
3. Opcional: en la tarjeta **Referencias** complete **Referencia 1**, **Referencia 2** y **Referencia 3**.
4. Opcional: en la tarjeta **Comentario** escriba el **Comentario**.
5. Presione **Guardar** (en el teléfono, en la barra inferior).
Mensajes de validación: "Ingrese la Fecha", "El monto no puede ser 0" y "Seleccione el Banco".
Para modificarla, selecciónela en la lista y presione **Editar**. Arriba tiene **Nuevo**, **Atras**, **Archivos**, **Imprimir**, **Eliminar** y **Guardar**. **Eliminar** pide confirmar "Desea eliminar este Registro...".
<!-- REVISAR: signo con que cada nota afecta el saldo del banco, por confirmar con el negocio; efecto de pasar el Estado a APLICADO -->
Ver también: Crear una cuenta bancaria.

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
Ver también: Asignar clientes a un gestor de cobranza.

## Ver estadísticas de inmuebles
Ruta: Menú > ESTADISTICA > INMUEBLE.
1. Entre a ESTADISTICA > INMUEBLE. Se abre **Estadísticas de inmuebles**, un tablero de Google Data Studio con el comportamiento de las propiedades.
2. Revise la información directamente en el tablero.
3. Para verlo en pantalla completa presione **Abrir en pestaña nueva**.
Notas: el tablero se carga desde internet; si no se muestra, revise su conexión o use **Abrir en pestaña nueva**. <!-- REVISAR: el enlace del tablero de Data Studio es fijo en la vista; confirmar si es el mismo para todas las empresas y si muestra solo los datos de la empresa -->
Ver también: Ver estadísticas de clientes.

## Ver estadísticas de clientes
Ruta: Menú > ESTADISTICA > CLIENTES.
La pantalla **Estadísticas de clientes** muestra fuentes de contacto, intereses, agentes y cierres de clientes en un rango de fechas.
1. En la tarjeta **Rango de fechas** indique **Inicio** y **Fin** (formato AAAA-MM-DD). Al entrar se muestra el mes actual.
2. Presione **Filtrar**. El rango consultado aparece junto al título.
3. Revise los indicadores: **Total de contactos**, **Clientes cerrados**, **Agentes activos** e **Interés principal**.
4. Revise los gráficos: **Fuente de contacto**, **Interés del cliente**, **Tipo de propiedad**, **Clientes asignados**, **Clasificación por agente** y **Cierre de clientes**.
Si un gráfico no tiene información muestra "Sin datos en el rango seleccionado".
Mensajes al filtrar:
* "La fecha de inicio debe tener el formato AAAA-MM-DD" / "La fecha de fin debe tener el formato AAAA-MM-DD".
* "La fecha de inicio no puede ser mayor que la fecha de fin".
Notas: para entrar a esta pantalla su rol necesita acceso a ESTADISTICA > INMUEBLE. <!-- REVISAR: customer_realstate valida el permiso de real_state (INMUEBLE); confirmar si es intencional -->
Ver también: Ver estadísticas de inmuebles.

## Ver el calendario de programación
Ruta: Menú > CALENDARIO > PROGRAMACION.
La pantalla **Programación** muestra en un calendario las tareas, visitas y citas programadas, y además eventos automáticos de documentos: facturas, órdenes de taller (fecha de siguiente visita) y abonos.
1. Use las flechas para ir al periodo anterior o siguiente (en PC también al año anterior o siguiente) y **Hoy** para volver a la fecha actual.
2. En PC cambie la vista con **Mes**, **Semana**, **Día** o **Año**. En el teléfono se muestra la vista de lista de la semana.
3. En PC pase el mouse sobre un evento para ver su descripción.
4. Haga clic en un evento para abrirlo.
Eventos automáticos: al hacer clic en una factura, orden de taller o abono se abre una ventana con la información del documento (Código, Cliente, Teléfono, Activo fijo o Descripción) y los botones **Imprimir**, **Abrir documento** (lo abre en una pestaña nueva) y **Cerrar**. Estos eventos no se modifican desde el calendario.
Notas: qué facturas aparecen (registradas o aplicadas, con o sin hora) depende de la configuración de su empresa.
Ver también: Programar una tarea o visita en el calendario; Modificar, eliminar o imprimir un evento del calendario.

## Programar una tarea o visita en el calendario
Ruta: Menú > CALENDARIO > PROGRAMACION.
Sirve para agendar una tarea, visita, cita o recordatorio.
1. Presione **Agregar** o, en PC, haga clic sobre un día del calendario. En el teléfono use siempre el botón **Agregar** (arriba a la derecha). Se abre **Nueva programación**.
2. En el bloque **Detalle** escriba el **Título del evento** y la **Descripción del evento**, y en **Etiqueta** elija el tipo (tag) de la programación; no deje "Seleccione…".
3. En el bloque **Cuándo** indique la **Fecha** y, si desea, la **Hora (opcional)**. Con **Agregar** la fecha propuesta es hoy; al hacer clic en un día, la de ese día.
4. Presione **Guardar**.
La ventana se cierra solo si la programación se guardó. Si ocurre un error se muestra el mensaje y la ventana queda abierta con sus datos para corregir o volver a guardar. **Cerrar** o la X salen sin guardar.
Mensajes de validación (debajo de cada campo):
* "El título del evento es obligatorio."
* "La descripción del evento es obligatoria."
* "La fecha del evento es obligatoria."
* "Debe seleccionar una etiqueta."
Ver también: Modificar, eliminar o imprimir un evento del calendario.

## Modificar, eliminar o imprimir un evento del calendario
Ruta: Menú > CALENDARIO > PROGRAMACION.
Modificar una programación:
1. Haga clic sobre la programación en el calendario. Se abre **Editar programación** con su código.
2. Cambie los datos de **Detalle** y **Cuándo** y presione **Guardar**.
Eliminar una programación:
1. Ábrala y presione **Eliminar**.
2. Confirme el mensaje "¿Desea eliminar la programación seleccionada? Esta acción no se puede deshacer.".
Imprimir:
* Una programación: ábrala y presione **Imprimir**; se abre en una pestaña nueva.
* Todos los eventos de un día (en PC): cambie a la vista **Día**, vaya a la fecha y presione **Imprimir** en la barra del calendario. En otra vista aparece "Cambie a la vista Día para imprimir".
* Una factura, orden de taller o abono: haga clic en el evento y use **Imprimir** o **Abrir documento**.
Notas: **Eliminar** e **Imprimir** solo aparecen al editar una programación existente. Si la programación ya no existe aparece "No se encontró la programación seleccionada".
Ver también: Ver el calendario de programación.

## Qué contiene el menú CONTABILIDAD
Ruta: Menú > CONTABILIDAD.
El menú CONTABILIDAD agrupa las opciones para llevar la contabilidad de la empresa:
- **CUENTAS**: catálogo de cuentas contables.
- **NIVEL DE CUENTA**: formato y longitud de los códigos de cuenta.
- **NATURALEZA**: naturaleza deudora (D) o acreedora (C) de las cuentas.
- **PERIODOS**: periodos contables y sus ciclos.
- **CENTRO DE COSTO**: centros de costo.
- **MONEDAS**: monedas de la empresa y la **Herramienta** de tipo de cambio.
- **COMPROBANTES**: asientos contables (débitos y créditos).
- **PROCESOS**: cerrar ciclo, mayorizar, contabilizar documentos, tipo de cambio y notificaciones.
- **PARAMETROS**: cuentas principales, monedas y razones financieras.
- **INDICADORES**: indicadores financieros.
Orden recomendado al empezar: niveles, naturaleza, centros de costo, monedas, cuentas, periodos y ciclos; después comprobantes y procesos.
Notas: en el teléfono el botón **Guardar** está en la barra inferior de la pantalla. Lo que ve cada usuario depende de los permisos de su rol.

## Configurar los niveles de cuenta
Ruta: Menú > CONTABILIDAD > NIVEL DE CUENTA.
El nivel define cómo se escriben los códigos de las cuentas (por ejemplo 1, 1-01, 1-01-001).
1. En **NIVELES DE CUENTAS** presione **Nuevo**. Se abre **Nuevo nivel de cuenta**.
2. En **Datos del nivel** complete:
   - **Nombre** y **Descripción**.
   - **Separador**: carácter que separa los grupos del código (por ejemplo -).
   - **Longitud total**: cantidad total de caracteres del código, incluidos los separadores.
   - **Longitud del grupo**: cantidad de caracteres del último grupo del código.
   - **Es operativa**: márquelo si las cuentas de este nivel reciben movimientos en los comprobantes (cuentas de detalle).
3. Presione **Guardar**.
Para modificarlo ábralo en la lista (**Nivel de cuenta**); para darlo de baja presione **Eliminar**.
Ver también: Crear una cuenta contable.

## Configurar la naturaleza de las cuentas
Ruta: Menú > CONTABILIDAD > NATURALEZA.
La naturaleza indica si la cuenta aumenta con el débito (deudora, D: activos, costos, gastos) o con el crédito (acreedora, C: pasivos, capital, ingresos).
1. En **NATURALEZA DE CUENTAS** presione **Nuevo**. Se abre **Nueva naturaleza de cuenta**.
2. En **Datos de la naturaleza** escriba el **Nombre** (por ejemplo ACTIVO), la **Naturaleza (C|D)** y la **Descripción**.
3. Presione **Guardar**.
Para modificarla ábrala en la lista (**Naturaleza de cuenta**); para darla de baja presione **Eliminar**.

## Centros de costo
Ruta: Menú > CONTABILIDAD > CENTRO DE COSTO.
Los centros de costo permiten separar los movimientos por área, sucursal o proyecto.
1. En **LISTA DE CENTRO DE COSTOS** presione **Nuevo**. Se abre **Nuevo centro de costo**.
2. En **Datos del centro de costo** complete el **Código**, el **Código padre (centro de costo superior)** si depende de otro, el **Nivel** y la **Descripción**.
3. Presione **Guardar**.
Para modificarlo ábralo en la lista (**Centro de costo**); para darlo de baja presione **Eliminar**.
El centro de costo se asigna a la cuenta (campo **Centro de costo**) y a cada línea del comprobante (columna **CC**).

## Crear una cuenta contable
Ruta: Menú > CONTABILIDAD > CUENTAS.
1. En **CATALOGO DE CUENTAS** presione **Nuevo**. Se abre **Nueva cuenta contable**.
2. En **Datos de la cuenta** escriba el **Código**, el **Código padre** (cuenta superior; vacío si es una cuenta principal), el **Nombre** y la **Descripción**.
3. En **Clasificación** elija el **Nivel**, la **Naturaleza / Tipo**, el **Centro de costo** y la **Moneda**.
4. Marque **Es operativa** si la cuenta recibirá movimientos en los comprobantes.
5. Presione **Guardar**.
Restricciones:
- El código no puede repetirse: "EL CODIGO DE LA CUENTA YA ESTA RESERVADO...".
- El código debe tener la longitud total y la longitud del último grupo definidas en el nivel elegido; si no, "EL CODIGO DE LA CUENTA TIENE UN FORMATO INCORRECTO".
- **Es operativa** debe coincidir con lo definido en el nivel; si no, "OPERATIVIDAD DE LA CUENTA NO ES VALIDA".
Ver también: Configurar los niveles de cuenta.

## Modificar o eliminar una cuenta contable
Ruta: Menú > CONTABILIDAD > CUENTAS.
1. Busque la cuenta con **Buscar cuenta…** y ábrala. Se abre **Cuenta contable**.
2. Cambie los datos y presione **Guardar**. Se aplican las mismas validaciones que al crearla (código único, formato del nivel y **Es operativa**).
3. En **Histórico de balances** vea en un gráfico los últimos 12 saldos de la cuenta (o **Sin balances** si aún no tiene).
4. Para darla de baja presione **Eliminar** y confirme.
Restricciones: no se puede eliminar una cuenta que tiene subcuentas ("ELIMINAR PRIMERAMENTE LAS CUENTAS DE MAS BAJO NIVEL") ni una cuenta con saldo o movimientos. Si su permiso es **Propios**, solo puede eliminar las cuentas que usted creó.

## Configurar monedas
Ruta: Menú > CONTABILIDAD > MONEDAS.
1. En **MONEDAS** presione **Nuevo**. Se abre **Nueva moneda**.
2. En **Moneda** elija la **Moneda** que usará la empresa y escriba su **Símbolo** (por ejemplo C$ o $).
3. Presione **Guardar**.
Para cambiar el símbolo abra la moneda en la lista y presione **Guardar**; para quitarla presione **Eliminar**.
Botones de la lista: **Herramienta** (abre la herramienta de tipo de cambio) y **Archivos** (subir el archivo de tasas de cambio).
Ver también: Actualizar el tipo de cambio (herramienta de monedas).

## Actualizar el tipo de cambio (herramienta de monedas)
Ruta: Menú > CONTABILIDAD > MONEDAS > **Herramienta**.
Se abre **Herramientas de monedas** con tres tarjetas:
- **Actualizar tipo de cambio**: escriba el **Valor** (cuánto vale 1 unidad de la moneda extranjera en moneda local, por ejemplo córdobas por dólar), la **Fecha inicial** y la **Fecha final**, y presione **Actualizar tipo de cambio**. posMe guarda ese valor en todos los días del rango.
- **Reporte de tipo de cambio**: indique **Fecha inicial** y **Fecha final** y presione **Ver reporte**; se abre en una pestaña nueva.
- **Procesar archivo**: suba antes el archivo con el botón **Archivos** de la lista de MONEDAS, escriba su nombre en **Archivo** y presione **Procesar archivo**. El archivo (CSV) lleva las columnas FECHA (AAAA-MM-DD), MONEDA_LOCAL, TASA_DE_CAMBIO, MONEDA_EXTRANJERA y TIPO; si una línea está mal, posMe indica el número de línea a revisar.
También puede descargar el tipo de cambio del BCN en CONTABILIDAD > PROCESOS, tarjeta **Tipo de cambio**: elija **Periodo** y **Ciclo** y presione **Actualizar tipo de cambio** (requiere internet y que el servicio esté configurado para su empresa).
Notas: los comprobantes necesitan tipo de cambio en su fecha.

## Crear periodos y ciclos contables
Ruta: Menú > CONTABILIDAD > PERIODOS.
Un periodo es normalmente un año contable y se divide en ciclos (normalmente meses).
1. En **PERIODOS CONTABLES** presione **Nuevo**. Se abre **Periodo contable**.
2. En **Periodo** escriba el **Nombre**, elija el **Estado**, la **Fecha inicial**, la **Fecha final** y la **Descripción**. El **Código de periodo** lo asigna posMe.
3. En **Ciclos del periodo** presione **Agregar ciclo** una vez por cada ciclo. posMe calcula las fechas **Inicio** y **Fin** mes a mes desde la fecha inicial del periodo; puede corregirlas.
4. Elija el **Estado** de cada ciclo.
5. Presione **Guardar**.
Para quitar ciclos márquelos y presione **Quitar marcados**. Para modificar el periodo ábralo en la lista; para darlo de baja presione **Eliminar**.
Restricciones:
- El periodo no puede solaparse con otro periodo contable.
- Cada ciclo debe tener estado, fecha inicial y fecha final, quedar dentro de las fechas del periodo y no solaparse con otro ciclo.
- No se puede quitar un ciclo que tiene comprobantes o que está cerrado, ni eliminar un periodo que tiene comprobantes.
<!-- REVISAR: nombres exactos de los estados de periodo y ciclo (abierto / cerrado) -->

## Abrir y cerrar periodos contables
Ruta: Menú > CONTABILIDAD > PERIODOS y CONTABILIDAD > PROCESOS.
- Abrir un periodo: créelo en PERIODOS con sus ciclos en estado abierto (ver Crear periodos y ciclos contables).
- Cerrar un mes (ciclo): use PROCESOS > **Cerrar ciclo** (ver Mayorizar y cerrar un ciclo contable).
- Cerrar el periodo (año): se cierra solo al cerrar su último ciclo.
- Antes de cerrar el último ciclo del año cree el periodo siguiente, porque el cierre exige que exista el ciclo siguiente.
Notas: con el ciclo cerrado no se pueden registrar ni anular documentos con fechas de ese ciclo.

## Registrar un comprobante contable
Ruta: Menú > CONTABILIDAD > COMPROBANTES.
1. En **LISTA DE COMPROBANTES** presione **Nuevo**. Se abre **Nuevo comprobante**.
2. En **Datos del comprobante** indique **Fecha**, **Estado**, **Tipo**, **Moneda** y **Beneficiario** (obligatorio). El **Tipo de cambio** se muestra solo.
3. Opcional: en **Referencias y plantilla** complete **Referencia 1**, **Referencia 2**, **Referencia 3**, **Comentario** y **Descripción**.
4. En **Movimientos** presione **Agregar cuenta**, elija la cuenta y escriba el **Débito** o el **Crédito**; en **CC** elija el centro de costo si aplica. Para quitar líneas márquelas y presione **Quitar marcadas**.
5. Revise **Estado del comprobante**: debe decir **Cuadrado** (Total débito igual a Total crédito). Si dice **Descuadrado** muestra la diferencia.
6. Presione **Guardar**.
Usar una plantilla: presione **Abrir plantilla** y elija un comprobante modelo; posMe crea el comprobante nuevo a partir de él.
Restricciones: el comprobante debe estar cuadrado; la fecha debe estar dentro de un ciclo contable abierto ("TODO COMPROBANTE DEBE DE PERTENECER A UN CICLO" o "EL CICLO ESTA CERRADO..."); y debe existir tipo de cambio para esa fecha ("NO EXISTE LA TASA DE CAMBIO PARA: ...").
Ver también: Actualizar el tipo de cambio (herramienta de monedas).

## Buscar, modificar, imprimir o eliminar un comprobante
Ruta: Menú > CONTABILIDAD > COMPROBANTES.
1. Escriba el número en **Buscar por número de comprobante…** y presione Enter o la lupa; también puede abrirlo desde la lista.
2. Se abre **Comprobante #**. Use **Anterior** y **Siguiente** para pasar al comprobante anterior o siguiente.
3. Modifique los datos o los **Movimientos** y presione **Guardar** (debe seguir **Cuadrado**).
4. **Imprimir** muestra el comprobante para imprimir; **Auditoría** muestra su historial; **Archivos** adjunta documentos de soporte. En el teléfono **Nuevo**, **Auditoría** e **Imprimir** están en **Más**.
5. Marque **Es plantilla** y escriba el **Título de la plantilla** para reutilizarlo con **Abrir plantilla**.
6. Para eliminarlo presione **Eliminar** y confirme.
Restricciones: no se puede modificar ni eliminar un comprobante de un ciclo cerrado ("EL CICLO ESTA CERRADO, EL COMPROBANTE NO PUEDE SER EDITADO") ni pasarlo a un ciclo cerrado; el estado del comprobante debe permitir eliminarlo. Si el comprobante venía de un documento (factura, abono...), al eliminarlo el documento queda pendiente de contabilizar.
<!-- REVISAR: contenido exacto de la pantalla Auditoría -->

## Contabilizar documentos
Ruta: Menú > CONTABILIDAD > PROCESOS, tarjeta **Contabilizar documentos**.
Genera los comprobantes contables de los documentos (facturas, abonos, compras, etc.) que aún no tienen comprobante.
1. En **Transacción** elija el tipo de documento o **TODAS**.
2. Presione **Contabilizar** y confirme.
3. Revise los comprobantes generados en CONTABILIDAD > COMPROBANTES.
Notas: las cuentas que usa cada documento dependen de la configuración de su empresa.
Otra tarjeta de PROCESOS, **Notificaciones**: elija la **Notificación** y presione **Ejecutar** para correr ese proceso de notificación, o **Limpiar** para borrar las notificaciones de esa etiqueta.
<!-- REVISAR: qué tipos de documento se contabilizan y si exigen ciclo abierto -->

## Mayorizar y cerrar un ciclo contable
Ruta: Menú > CONTABILIDAD > PROCESOS.
Mayorizar:
1. En la tarjeta **Mayorizar** elija el **Periodo** y el **Ciclo**.
2. Presione **Mayorizar** y confirme. posMe traslada los movimientos de los comprobantes del ciclo a los saldos del libro mayor.
Cerrar ciclo:
1. En la tarjeta **Cerrar ciclo** elija el **Periodo** y el **Ciclo**.
2. Presione **Cerrar ciclo** y confirme.
Reglas del cierre: el ciclo anterior debe estar cerrado; debe existir el ciclo siguiente y no estar cerrado; los débitos y créditos del ciclo deben ser iguales. Al cerrar el último ciclo del periodo, el periodo también queda cerrado.
Después del cierre no se pueden registrar, modificar ni eliminar comprobantes de ese ciclo, ni registrar o anular facturas y otros documentos con fecha de ese periodo cerrado.
Notas: en **Ciclo** solo aparecen los ciclos abiertos; "Sin ciclos abiertos en este periodo" indica que todos sus ciclos ya están cerrados. Mayorice el ciclo antes de cerrarlo.
<!-- REVISAR: confirmar si el cierre exige mayorizar antes y qué comprobante de cierre genera -->

## Configurar los parámetros contables
Ruta: Menú > CONTABILIDAD > PARAMETROS.
Se abre **Parámetros contables** con estas tarjetas:
- **Cuentas principales**: código de la **Cuenta de Activo**, **Cuenta de Pasivo**, **Cuenta de Capital**, **Cuenta de Ingresos**, **Cuenta de Costos**, **Cuenta de Gastos** y **Cuentas de Resultado**.
- **Utilidades**: **Cuenta de Utilidad del periodo**, **Cuenta de Utilidades acumuladas** y **Fórmula de utilidades**.
- **Moneda y tipo de cambio**: **Moneda de la empresa**, **Moneda de reporte**, **Dólares compra** y **Dólares venta**.
- **Razones financieras**: **Razón del circulante**, **Endeudamiento**, **Utilidad mensual**, **Utilidad anual**, **Rentabilidad anual** y **Rentabilidad mensual**.
Presione **Guardar**.
Notas: el cierre de ciclo usa la cuenta de utilidades acumuladas. Cambie estos valores solo con su contador o con el soporte de posMe.
<!-- REVISAR: formato esperado de Fórmula de utilidades, de las monedas y de las razones financieras -->

## Indicadores contables
Ruta: Menú > CONTABILIDAD > INDICADORES.
Los indicadores son valores financieros calculados que posMe muestra, por ejemplo, en la aplicación móvil.
1. En **LISTA DE INDICADOR** presione **Nuevo**. Se abre **Nuevo indicador**.
2. En **Indicador** complete **Código**, **Nombre**, **Etiqueta** (texto que se muestra), **Orden**, **Prefijo** y **Posfijo** (por ejemplo C$ o %) y **Descripción**.
3. En **Fórmula** escriba el **Script** que calcula el valor.
4. Presione **Guardar**.
Para modificarlo ábralo en la lista (**Indicador #**); para darlo de baja presione **Eliminar**.
Notas: el **Script** es técnico; pídalo al equipo de soporte de posMe.
<!-- REVISAR: dónde más se muestran los indicadores y en qué lenguaje se escribe el Script -->

## Qué contiene el menú ACTIVOS FIJOS
Ruta: Menú > ACTIVOS FIJOS.
- **ACTIVOS F.**: catálogo de activos fijos.
- **ENT. ACTIVOS**: entradas de activos.
- **SAL. ACTIVOS**: salidas de activos.
- **DEPRECIACION**: depreciación de activos.
- **ACT VALORACION**: valoración de activos.
- **TRA. PIEZA**: traslado o transferencia de piezas.
- **REPORTES**: reportes de activos fijos.
Las listas tienen los botones **Vistas**, **Editar**, **Eliminar** y **Nuevo**. Seleccione la fila antes de presionar **Editar** o **Eliminar**; si no lo hace aparece "Seleccionar el Registro...".
Buscar en la lista:
- En ACTIVOS F., **Buscar en la lista…** filtra mientras escribe.
- En las demás opciones escriba en **Buscar por número…** y presione el botón de la lupa o Enter. No busca mientras escribe.
Notas: en el teléfono el botón **Guardar** está en la barra inferior y los botones **Buscar** abren la búsqueda en una ventana dentro de la misma pantalla. Lo que ve cada usuario depende de los permisos de su rol.
Ver también: Crear un activo fijo.

## Crear un activo fijo
Ruta: Menú > ACTIVOS FIJOS > ACTIVOS F. La lista se llama **LISTA DE ACTIVOS FIJOS**.
1. Presione **Nuevo**. Se abre **Activo Fijo**.
2. En la pestaña **Informacion General** complete **Nombre**, **Estado**, **Marca**, **Modelo**, **Categoria**, **Tipo**, **Es Foraneo** y **Descripcion**. Según la configuración de su empresa los nombres pueden cambiar o aparecer otros campos (por ejemplo **Color** o **Chasis No**).
3. En la pestaña **Ubicacion** complete **Pais**, **Departamento**, **Municipio**, **Direccion**, **Area**, **Proyecto**, **Asignado A.**, **Cliente**, **Sucursal** y **Duracion**. En **Area**, **Proyecto**, **Asignado A.** y **Cliente** use **Buscar** y **Limpiar**.
4. En la pestaña **Valores** complete **Tipo de Activo**, **Año**, **Fecha de Ingreso**, **Años de Utilidad**, **Moneda**, **Precio Inicial**, **Ratio**, **Tipo de Calculo**, **Monto de liquidacion** y **Monto Corriente**. **Fecha de Ingreso** abre vacía: selecciónela.
5. En la pestaña **Propiedades** complete los campos que se muestren.
6. Presione **Guardar** (en el teléfono, en la barra inferior).
Mensajes de validación: "Seleccionar la Categoria", "Seleccionar el Tipo" y "Escribir el Nombre".
<!-- REVISAR: significado de Ratio, Tipo de Calculo, Monto de liquidacion y Monto Corriente -->
Ver también: Modificar o eliminar un activo fijo.

## Modificar o eliminar un activo fijo
Ruta: Menú > ACTIVOS FIJOS > ACTIVOS F.
1. En la lista escriba en **Buscar en la lista…** para filtrar, seleccione la fila y presione **Editar**.
2. Cambie los datos en las pestañas **Informacion General**, **Ubicacion**, **Valores** y **Propiedades**.
3. Presione **Guardar** (en el teléfono, en la barra inferior).
Arriba tiene **Atras**, **Archivos**, **Eliminar** y **Guardar**. **Eliminar** pide confirmar "Desea eliminar este Registro...".
En la edición aparece además la pestaña **Expediente**, con el historial de órdenes de taller del activo.
Ver también: Crear un activo fijo.

## Registrar una entrada de activos
Ruta: Menú > ACTIVOS FIJOS > ENT. ACTIVOS. La lista se llama **LISTA DE ENTRADA DE ACTIVOS FIJOS**.
1. Presione **Nuevo**. Se abre **Entrada de Activo Fijo**.
2. En la tarjeta **Datos del documento** indique **Fecha**, **Estado** y **Causal**. Opcional: **Referencia 1**, **Referencia 2** y **Referencia 3**.
3. En la tarjeta **Asignación** elija con **Buscar** **Asignado A.**, **Area Asignada** y **Proyecto Asignado** (**Limpiar** borra lo elegido). Opcional: **Comentario**.
4. En la tarjeta **Activos** presione **Agregar activo** y elíjalo en la búsqueda. Repita por cada activo.
5. Presione **Guardar** (en el teléfono, en la barra inferior).
Quitar un activo: marque su casilla (en el teléfono, la casilla **Quitar**) y presione **Quitar marcados**.
Mensajes de validación:
- "Ingrese la Fecha" / "Seleccione el Colaborador" / "Seleccione el Area" / "Seleccione el Proyecto".
- "Seleccione Un Activo a Asignar" / "El Activo Fijo ya existe en el detalle".
Para modificarla, selecciónela en la lista y presione **Editar**. Arriba tiene **Atras**, **Nuevo**, **Archivos**, **Imprimir**, **Eliminar** y **Guardar**.
<!-- REVISAR: opciones y uso de Causal; efecto de pasar el Estado a APLICADO -->

## Registrar una salida de activos
Ruta: Menú > ACTIVOS FIJOS > SAL. ACTIVOS. La lista se llama **LISTA DE SALIDAS DE ACTIVOS FIJOS**.
1. Presione **Nuevo**. Se abre **Salida de Activo Fijo**.
2. En la tarjeta **Datos del documento** indique **Fecha**, **Estado** y **Causal**. Opcional: **Referencia 1**, **Referencia 2** y **Referencia 3**.
3. En la tarjeta **Retiro** elija con **Buscar** **Retirado de**, **Area de Retiro** y **Proyecto de Retiro** (**Limpiar** borra lo elegido). Opcional: **Comentario**.
4. En la tarjeta **Activos** presione **Agregar activo** y elíjalo en la búsqueda. Repita por cada activo.
5. Presione **Guardar** (en el teléfono, en la barra inferior).
Quitar un activo: marque su casilla (en el teléfono, la casilla **Quitar**) y presione **Quitar marcados**.
Mensajes de validación:
- "Ingrese la Fecha" / "Seleccione el Colaborador" / "Seleccione el Area" / "Seleccione el Proyecto".
- "Seleccione Un Activo a Retirar" / "El Activo Fijo ya existe en el detalle".
Para modificarla, selecciónela en la lista y presione **Editar**. Arriba tiene **Atras**, **Nuevo**, **Archivos**, **Imprimir**, **Eliminar** y **Guardar**.
<!-- REVISAR: opciones y uso de Causal; efecto de pasar el Estado a APLICADO -->

## Registrar la depreciación de activos
Ruta: Menú > ACTIVOS FIJOS > DEPRECIACION. La lista se llama **LISTA DE DEPRECIACION DE ACTIVOS FIJOS**.
1. Presione **Nuevo**. Se abre **Depreciación de Activo Fijo**.
2. En la tarjeta **Datos del documento** indique **Fecha** y **Estado**. Opcional: **Referencia 1**, **Referencia 2**, **Referencia 3** y **Comentario**.
3. En la tarjeta **Período y monto** indique **Año**, **Mes** y **Moneda**. **Monto Total** no se escribe a mano.
4. En la tarjeta **Activos** presione **Agregar activo** y elíjalo en la búsqueda. Escriba el **Ratio** de cada activo; **Monto corriente** y **Monto de liquidación** solo se muestran.
5. Presione **Guardar** (en el teléfono, en la barra inferior).
Quitar un activo: marque su casilla (en el teléfono, la casilla **Quitar**) y presione **Quitar marcados**.
Mensajes de validación:
- "Ingrese la Fecha" / "Seleccione el Año" / "Seleccione el Mes" / "Seleccione Un Activo a Depreciar".
- "El Ratio no puede ser cero o vacio" / "El Ratio no puede ser mayor que la liquidacion".
- "El Ratio mas el Monto Corriente no pueden ser mayor que la liquidacion".
- "El Activo Fijo ya existe en el detalle".
Para modificarla, selecciónela en la lista y presione **Editar**. Arriba tiene **Atras**, **Nuevo**, **Archivos**, **Imprimir**, **Eliminar** y **Guardar**.
<!-- REVISAR: significado de Ratio; efecto de pasar el Estado a APLICADO -->

## Registrar la valoración de activos
Ruta: Menú > ACTIVOS FIJOS > ACT VALORACION. La lista se llama **LISTA DE VALORACION DE ACTIVOS FIJOS**.
1. Presione **Nuevo**. Se abre **Valoración de Activo Fijo**.
2. En la tarjeta **Datos del documento** indique **Fecha** y **Estado**. Opcional: **Referencia 1**, **Referencia 2**, **Referencia 3** y **Comentario**.
3. En la tarjeta **Período y monto** indique **Año**, **Mes** y **Moneda**. **Monto Total** no se escribe a mano.
4. En la tarjeta **Activos** presione **Agregar activo** y elíjalo en la búsqueda. Escriba el **Ratio** de cada activo; **Monto corriente** y **Monto de liquidación** solo se muestran.
5. Presione **Guardar** (en el teléfono, en la barra inferior).
Quitar un activo: marque su casilla (en el teléfono, la casilla **Quitar**) y presione **Quitar marcados**.
Mensajes de validación:
- "Ingrese la Fecha" / "Seleccione el Año" / "Seleccione el Mes" / "Seleccione Un Activo a Valorar".
- "El Ratio no puede ser cero o vacio" / "El Activo Fijo ya existe en el detalle".
Para modificarla, selecciónela en la lista y presione **Editar**. Arriba tiene **Atras**, **Nuevo**, **Archivos**, **Imprimir**, **Eliminar** y **Guardar**.
<!-- REVISAR: significado de Ratio; efecto de pasar el Estado a APLICADO -->

## Trasladar piezas entre activos (traslado o transferencia de piezas)
Ruta: Menú > ACTIVOS FIJOS > TRA. PIEZA. La lista se llama **LISTA DE TRANSFERENCIA DE PIEZAS**.
1. Presione **Nuevo**. Se abre **Transferencia de Piezas**.
2. En la tarjeta **Datos del documento** indique **Fecha** y **Estado**. Opcional: **Referencia 1**, **Referencia 2** y **Comentario**.
3. En la tarjeta **Activos y responsable** elija con **Buscar** **Activo Origen** y **Activo Destino** (al menos uno) y **Tecnico**. **Limpiar** borra lo elegido. Según la configuración de su empresa **Tecnico** puede tener otro nombre.
4. En la tarjeta **Piezas** presione **Agregar pieza** y complete **Nombre de la pieza**, **Cantidad**, **Acción** y **Comentario**. Repita por cada pieza.
5. Presione **Guardar** (en el teléfono, en la barra inferior).
Quitar una pieza: márquela y presione **Quitar marcadas**.
Mensajes de validación:
- "Ingrese la Fecha" / "Seleccione el Estado" / "Agregue al menos una pieza".
- "Seleccione una accion para cada pieza" / "Seleccione el nombre de cada pieza".
- "Seleccione al menos un Activo (Origen o Destino)".
Para modificarla, selecciónela en la lista y presione **Editar**. Arriba tiene **Atras**, **Nuevo**, **Eliminar** y **Guardar**.
<!-- REVISAR: significado de cada Acción; efecto de pasar el Estado a APLICADO -->

## Qué contiene el menú PLANILLA
Ruta: Menú > PLANILLA.
- **P. PLANILLA**: planillas de pago.
- **ADELANTOS**: adelantos a colaboradores.
- **LIQUIDACION**: liquidación de colaboradores.
- **PERMISO**: permisos de colaboradores.
Seleccione la fila de la lista y presione **Editar** para abrir un registro; si no selecciona una aparece "Seleccionar el Registro...". Para crear uno presione **Nuevo**. En ADELANTOS, LIQUIDACION y PERMISO, **Eliminar** pide confirmar "Desea eliminar este Registro...".
Buscar en la lista:
- En P. PLANILLA, **Buscar en la lista…** filtra la lista cargada mientras escribe.
- En ADELANTOS, LIQUIDACION y PERMISO escriba en **Buscar por número…** y presione el botón de la lupa o Enter.
Al editar, las cuatro pantallas tienen arriba solo **Atras** y **Guardar**.
Notas: en el teléfono el botón **Guardar** está en la barra inferior y los botones **Buscar** abren la búsqueda en una ventana dentro de la misma pantalla. Lo que ve cada usuario depende de los permisos de su rol. <!-- REVISAR: Eliminar de la lista de P. PLANILLA no funciona hoy (defecto conocido sin corregir); documentarlo cuando se corrija -->
Ver también: Crear una planilla de pago, Registrar adelantos a colaboradores, Registrar la liquidación de un colaborador, Registrar un permiso de un colaborador.

## Crear una planilla de pago
Ruta: Menú > PLANILLA > P. PLANILLA. La lista se llama **LISTA DE PLANILLAS**.
1. Presione **Nuevo**. Se abre **PLANILLA:#**.
2. En la tarjeta **Datos de la planilla** indique **Ciclo**, **Quincena**, **Nombre** y **Estado**.
3. En la tarjeta **Clasificación** indique **Tipo** y **Moneda**.
4. Opcional: en la tarjeta **Comentario** escriba la **Descripción**.
5. En la tarjeta **Colaboradores** presione **Agregar colaborador** y elíjalo en la búsqueda. Repita por cada colaborador. En el teléfono cada colaborador se ve como una tarjeta.
6. Complete en cada colaborador **+Comision**, **+Bono Quincenal**, **-Adelantos**, **-Deducciones Por Prestamos** y **-Deducciones Por Llegada Tarde**. **Vacaciones Acumuladas**, **INSS Patronal**, **+Salario**, **INSS**, **Ahorro**, **IR** y **Salario Neto** no se escriben a mano.
7. Revise el **Total** en la tarjeta **Resumen** y presione **Guardar** (en el teléfono, en la barra inferior).
Quitar un colaborador: marque su casilla (en el teléfono, la casilla **Quitar**) y presione **Quitar marcados**.
Mensajes de validación: "Ingrese el Nombre" y "El Colaborador ya existe en el detalle".
Para modificarla, selecciónela en la lista y presione **Editar**.
<!-- REVISAR: uso de Ciclo, Quincena y Tipo; cómo se calculan salario, INSS, Ahorro, IR y Salario Neto; efecto de pasar el Estado a APLICADO -->
Ver también: Registrar adelantos a colaboradores.

## Registrar adelantos a colaboradores
Ruta: Menú > PLANILLA > ADELANTOS. La lista se llama **LISTA DE ADELANTOS**.
1. Presione **Nuevo**. Se abre **ADELANTO:#**.
2. En la tarjeta **Datos del adelanto** indique **Ciclo** y **Estado**.
3. En la tarjeta **Clasificación** indique **Tipo** y **Moneda**.
4. Opcional: en la tarjeta **Comentario** escriba la **Descripción**.
5. En la tarjeta **Colaboradores** presione **Agregar colaborador**, elíjalo en la búsqueda y escriba el **Monto**. Repita por cada colaborador.
6. Revise el **Total** en la tarjeta **Resumen** y presione **Guardar** (en el teléfono, en la barra inferior).
Quitar un colaborador: marque su casilla (en el teléfono, la casilla **Quitar**) y presione **Quitar marcados**.
Mensajes de validación: "El Colaborador ya existe en el detalle".
Para modificarlo, selecciónelo en la lista y presione **Editar**.
<!-- REVISAR: uso de Ciclo y Tipo; si el adelanto se descuenta en la planilla y cuándo; efecto de pasar el Estado a APLICADO -->
Ver también: Crear una planilla de pago.

## Registrar la liquidación de un colaborador
Ruta: Menú > PLANILLA > LIQUIDACION. La lista se llama **LISTA DE LIQUIDACION**.
1. Presione **Nuevo**. Se abre **LIQUIDACION:#**.
2. En la tarjeta **Colaborador**, en **Colaborador** use **Buscar** y **Limpiar**. Se muestran **Codigo**, **Nombre Completo** y **Area**. Indique el **Estado**. **Liquidación Neta** se muestra y no se escribe a mano.
3. En la tarjeta **Trabajo** indique **Fecha de Fin**. **Fecha de Inicio**, **Años Laborados**, **Salario Devengado** y **Vacaciones Acumuladas** no se escriben a mano.
4. En la tarjeta **Ingresos** complete **Comision** y **Bono**. **Salario Proporcional**, **Ahorro Acumulado**, **Pago Vacaciones Acumuladas**, **Pago por mes 13** y **Pago por Antiguedad** no se escriben a mano.
5. En la tarjeta **Deducciones** complete **Prestamos**, **Llegadas Tarde** y **Adelantos**. **INSS**, **IR** e **IR Por Vacaciones** no se escriben a mano.
6. Presione **Guardar** (en el teléfono, en la barra inferior).
Mensajes de validación: "Seleccione el colaborador", y "El dato ... no es una entrada Valida" si un monto no es válido.
Para modificarla, selecciónela en la lista y presione **Editar**.
<!-- REVISAR: cómo se calculan los montos y la Liquidación Neta, y su base legal; efecto de pasar el Estado a APLICADO -->

## Registrar un permiso de un colaborador
Ruta: Menú > PLANILLA > PERMISO. La lista se llama **LISTA DE PERMISOS**.
1. Presione **Nuevo**. Se abre **PERMISO:#**.
2. En la tarjeta **Colaborador**, en **Colaborador** use **Buscar** y **Limpiar**. Se muestran **Codigo**, **Nombre Completo** y **Area**.
3. En la tarjeta **Detalles del permiso** indique **Tipo de Permiso**, **Fecha de Inicio**, **Fecha de Fin**, **Fecha de Integracion** y **Estado**. **Dias de Vacaciones** no se escribe a mano.
4. Opcional: escriba la **Nota**. Según la configuración de su empresa este campo puede tener otro nombre.
5. Presione **Guardar** (en el teléfono, en la barra inferior).
Mensajes de validación:
- "Seleccione el colaborador".
- "La Fecha Final del Permiso Esta Mal Configurada".
- "La Fecha de Integracion del Colaborador Esta Mal Configurada".
Para modificarlo, selecciónelo en la lista y presione **Editar**.
<!-- REVISAR: opciones de Tipo de Permiso; efecto en Dias de Vacaciones; significado de Fecha de Integracion; efecto de pasar el Estado a APLICADO -->

## Seguridad: qué contiene el menú SEGURIDAD
Ruta: Menú > SEGURIDAD.
El menú SEGURIDAD agrupa las opciones para controlar quién entra a posMe y qué puede hacer:
- **SALIR**: cerrar la sesión.
- **ROLES**: crear roles y definir a qué pantallas tienen acceso.
- **USUARIOS**: crear los usuarios, asignarles rol, sucursal, bodegas y cajas.
- **EDITAR PERFIL**: cambiar su propia contraseña y correo.
- **CONFIG. NOTI**: configurar recordatorios periódicos (notificaciones).
- **SUCURSAL**: crear y editar las sucursales de la empresa.
- **LOG SESSION**: ver las últimas sesiones y la actividad de un usuario.
- **ACTUALIZAR**: uso exclusivo del equipo de soporte de posMe.
Lo que cada persona ve en el menú depende de los permisos de su rol.

## Iniciar sesión en posMe
Ruta: pantalla de inicio de posMe.
1. Escriba su **Usuario** y su **Contraseña**.
2. Marque **Recordarme** si quiere que el navegador recuerde el usuario en ese equipo.
3. Presione **Ingresar**.
Notas: si posMe pide **PERMISO DE LOCALIZACION REQUERIDO**, debe permitir la ubicación en el navegador para poder entrar. Si los datos son incorrectos se muestra un mensaje y no se ingresa. Si no tiene usuario, pídalo al administrador de su empresa.

## Olvidé mi contraseña (reenviar contraseña)
Ruta: pantalla de inicio de posMe > **Reenviar Contraseña?**
1. En la pantalla de inicio presione **Reenviar Contraseña?**.
2. Escriba el correo registrado en su usuario y confirme.
3. Revise su correo.
Si no recibe el correo, pida al administrador de su empresa que le asigne una nueva contraseña en SEGURIDAD > USUARIOS.
<!-- REVISAR: en core_acount::rememberpassword el envio del correo ($this->email->send()) esta comentado; hoy no se envia el correo aunque la pantalla diga PASSWORD REENVIADA -->

## Cerrar sesión (salir, salgo de posMe)
Ruta: Menú > SEGURIDAD > SALIR.
1. Entre a SEGURIDAD > SALIR. posMe cierra la sesión y vuelve a la pantalla de inicio.
Así es como se sale de posMe o se cambia de usuario.
Cierre siempre la sesión al terminar, sobre todo en equipos compartidos.

## Cambiar mi contraseña o mi correo (editar perfil)
Ruta: Menú > SEGURIDAD > EDITAR PERFIL.
1. Entre a SEGURIDAD > EDITAR PERFIL. Se abre **Mi perfil** con su **Codigo** y el **Colaborador** asociado.
2. En **Datos de acceso** cambie el **Password** y/o el **Email**. El **Nickname** es su nombre de usuario.
3. Presione **Guardar**.
4. Cierre sesión y vuelva a entrar con la nueva contraseña.
El botón **Imprimir** imprime los datos del perfil.

## Crear un usuario (creo un usuario nuevo)
Ruta: Menú > SEGURIDAD > USUARIOS.
1. En la **Lista de Usuarios** presione **Nuevo**. Se abre **Nuevo usuario**.
2. Pestaña **Informacion General**:
   - **Datos de acceso**: **Nickname** (nombre con el que entrará), **Password** y **Email**.
   - **Rol y ubicacion**: elija el **Rol** (define sus permisos), la **Sucursal** y la **Caja**.
   - **Entidad**: presione **Buscar** y elija el colaborador (empleado) al que pertenece el usuario.
   - Marque **Usa telefono (Mobile)** si trabajará desde un teléfono.
3. Pestaña **Bodegas**: presione **Agregar** y elija de la **Lista de Bodegas** las bodegas con las que podrá trabajar.
4. Pestaña **Cajas**: presione **Agregar** y elija de la **Lista de Cajas** las cajas que podrá usar.
5. Presione **Guardar**.
Notas: para quitar una bodega o caja, toque la fila para seleccionarla y presione **Quitar seleccionada**. El usuario solo ve el menú que permite su rol (ver Roles).

## Modificar o eliminar un usuario
Ruta: Menú > SEGURIDAD > USUARIOS.
1. Busque el usuario en la lista (campo **Buscar usuario…**) y ábralo.
2. Cambie lo necesario en **Informacion General**, **Bodegas**, **Cajas** o **Notificaciones** (pestaña para asignarle notificaciones con **Agregar**).
3. Presione **Guardar**.
4. Para dar de baja al usuario presione **Eliminar** y confirme; ya no podrá entrar a posMe.
Para cambiar la contraseña de otro usuario escriba la nueva en **Password** y guarde.
Restricciones: se requiere permiso de editar o eliminar en USUARIOS; si su permiso es **Propios**, solo puede modificar o eliminar los usuarios que usted creó.

## Crear un rol
Ruta: Menú > SEGURIDAD > ROLES.
Un rol es un perfil de permisos (por ejemplo CAJERO, VENDEDOR, CONTADOR) que se asigna a los usuarios.
1. Presione **Nuevo**. Se abre **Nuevo rol**.
2. En **Datos del rol** escriba el **Nombre** (obligatorio) y la **Descripción**.
3. **Url por defecto**: pantalla que se abre al iniciar sesión (por ejemplo core_dashboards/index).
4. **Tipo de app**: déjelo en el valor indicado por soporte (normalmente default).
5. Defina los **Permisos por pantalla** y las **Autorizaciones** (ver las secciones siguientes).
6. Presione **Guardar**.
Notas: los cambios en el menú se ven cuando el usuario vuelve a iniciar sesión.

## Permisos por pantalla de un rol (ver, crear, editar, eliminar)
Ruta: Menú > SEGURIDAD > ROLES > abrir el rol.
1. En **Permisos por pantalla** active el interruptor de cada pantalla a la que el rol tendrá acceso. Use **Buscar pantalla…** para encontrarla y **Solo con acceso** para ver solo las que ya tienen acceso.
2. Para cada pantalla elija el nivel de **Ver**, **Crear**, **Editar** y **Eliminar**:
   - **Ninguno**: no puede hacerlo.
   - **Todos**: sobre todos los registros.
   - **Sucursal**: solo registros de su sucursal.
   - **Propios**: solo los registros que creó el mismo usuario.
3. Botones rápidos sobre las pantallas visibles: **Acceso total**, **Solo ver** y **Quitar todo**. También hay botones por módulo.
4. Presione **Guardar** y pida a los usuarios del rol cerrar sesión y volver a entrar.
Notas: si el rol es administrador tiene acceso a todo y estos permisos no se aplican. Si edita su propio rol y le quita el acceso a ROLES, perderá acceso a esa pantalla.

## Autorizaciones de un rol
Ruta: Menú > SEGURIDAD > ROLES > abrir el rol > **Autorizaciones**.
Las autorizaciones son permisos especiales que no dependen de una pantalla (por ejemplo ADMINISTRADOR, CAJERO, CONTADOR, o autorizaciones de usuario interno o externo de taller). Según la configuración de la empresa, habilitan acciones como aprobar documentos o ver información restringida.
1. Abra el rol y vaya a **Autorizaciones**.
2. Marque las que correspondan (**Marcar todas** / **Ninguna** para marcar o desmarcar todo).
3. Presione **Guardar**.
<!-- REVISAR: describir que habilita cada autorizacion en cada empresa -->

## Dar acceso al asistente Jordania (Asistente IA)
Ruta: Menú > SEGURIDAD > ROLES > abrir el rol.
1. En **Permisos por pantalla** busque **Asistente IA** y active el acceso.
2. Presione **Guardar**.
3. Los usuarios del rol deben cerrar sesión y volver a entrar; verán el botón del asistente en la pantalla.
Notas: además del permiso, la empresa debe tener habilitado el asistente (lo configura soporte de posMe).

## Sucursales
Ruta: Menú > SEGURIDAD > SUCURSAL.
1. Para crear una sucursal presione **Nuevo**. Se abre **Nueva sucursal**.
2. En **Datos de la sucursal** escriba el **Nombre** y la **Dirección**.
3. Presione **Guardar**.
Para modificarla, ábrala en la lista, cambie los datos y presione **Guardar**; para darla de baja presione **Eliminar**.
Después asigne la sucursal a los usuarios en SEGURIDAD > USUARIOS (campo **Sucursal**).

## Notificaciones y recordatorios (CONFIG. NOTI)
Ruta: Menú > SEGURIDAD > CONFIG. NOTI.
Permite configurar recordatorios periódicos que posMe muestra o envía a los usuarios.
1. Presione **Nuevo**. Se abre **Nueva notificacion**.
2. En **Datos de la notificacion** complete **Titulo**, **Tag** (tipo de notificación), **Periodo** y **Dias**, y el **Estado**.
3. Marque **Temporal** si es una notificación de una sola vez.
4. Escriba la **Descripcion** y el **Mensaje** que verá el usuario.
5. Presione **Guardar**.
Para que un usuario reciba la notificación, asígnela en SEGURIDAD > USUARIOS > pestaña **Notificaciones**.
Ver también: Programar una tarea o visita en el calendario.
<!-- REVISAR: significado exacto de Periodo/Dias, Temporal y Desde archivo -->

## Ver quién entró: sesiones y actividad de un usuario (LOG SESSION)
Ruta: Menú > SEGURIDAD > LOG SESSION.
1. En **Usuario** elija el usuario y presione **Consultar**.
2. posMe muestra:
   - **Total Transacciones** registradas por el usuario y **Transacciones por Tipo** (facturas, abonos, etc.).
   - **Últimas 5 Sesiones** con **IP**, **Plataforma** (navegador/dispositivo), **Último Movimiento** y **Detalle**.
Útil para revisar quién entró al sistema, desde dónde y cuánto trabajo ha registrado.

## Actualizar (uso de soporte)
Ruta: Menú > SEGURIDAD > ACTUALIZAR.
Opción técnica para que el equipo de soporte de posMe aplique actualizaciones al sistema. No la use salvo que soporte se lo indique.

## Parámetros de la empresa
Los parámetros controlan el comportamiento de posMe por empresa (por ejemplo, orden de aplicación de abonos, lista de precios por defecto, formato de impresión).
Los parámetros contables se editan en CONTABILIDAD > PARAMETROS; el resto los configura el equipo de soporte de posMe.
Ver también: Configurar los parámetros contables.
<!-- REVISAR: pantalla para que el cliente edite parametros generales -->

## Soporte
Si no encuentra cómo hacer algo o hay un error en el sistema, contacte al equipo de soporte de posMe. <!-- REVISAR: telefono / correo de soporte -->

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

## Adjuntar, subir o ver archivos de un documento (botón Archivos)
Ruta: dentro de un documento (factura, abono, caja, gasto, compra, cheque, activo fijo, comprobante, etc.) > **Archivos**.
1. Abra el documento ya guardado y presione **Archivos** (en algunas pantallas es una pestaña u opción del menú de acciones).
2. Se abre en una pestaña nueva la ventana **Documentos**. Cada documento tiene su propia carpeta: lo que suba queda solo en ese documento.
3. Para subir archivos arrástrelos desde su computadora a la ventana o use el botón **Subir** de la barra. Los archivos grandes se suben por partes y se ve una barra de progreso.
4. Para abrir un archivo haga doble clic sobre él: se abre en una pestaña nueva.
5. Para verlo sin abrirlo selecciónelo y presione la barra espaciadora o el botón de **Vista rápida** (imágenes, PDF y texto).
6. Para descargarlo selecciónelo y use **Descargar**.
Botones de la barra: atrás y adelante, recargar, inicio y subir un nivel, nueva carpeta, subir, abrir, descargar, vista rápida, información, copiar, cortar y pegar, eliminar, duplicar, renombrar, redimensionar o rotar imagen, buscar, vista de lista o de iconos, ordenar, pantalla completa y ayuda. También puede hacer clic derecho sobre un archivo o en el espacio vacío para ver el menú con estas opciones.
Notas: ya no aparece abajo un cuadro de subida aparte; todo se hace en la misma ventana.
Ver también: Archivos permitidos y avisos en la ventana Documentos.

## Archivos permitidos y avisos en la ventana Documentos
Ruta: dentro de un documento > **Archivos** (ventana **Documentos**).
Tipos de archivo permitidos: imágenes (jpg, png, gif, webp, bmp), PDF, txt, csv, xml, Word, Excel, PowerPoint, documentos OpenDocument y zip.
Restricciones:
- Cada archivo puede pesar como máximo 20 MB.
- No se permiten archivos cuyo nombre empiece con punto ni archivos ejecutables o de script (php, exe, bat, html, svg, js y similares). Tampoco se puede renombrar un archivo para darle una de esas extensiones.
- No están disponibles comprimir o descomprimir, editar el archivo en línea, crear un archivo vacío ni ocultar archivos.
- El archivo plantilla_de_tasa_de_cambio.csv (de MONEDAS) es de solo lectura y no se puede borrar.
Si aparece el aviso "Solicitud inválida, recargue", presione F5 para recargar la ventana **Documentos** y repita la acción.
Ver también: Adjuntar, subir o ver archivos de un documento (botón Archivos).

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
Ver también: Imprimir una factura (Preview, A4 o directo a la impresora).

## Imprimir una factura (Preview, A4 o directo a la impresora)
Ruta: Menú > FACTURACION > FACTURAR (nueva factura o edición de una factura).
1. Con la factura guardada, presione **IMPRIMIR**.
2. Según la configuración de su empresa, se abre la ventana **Opciones de impresion**. Presione el formato que desea: **Preview** (documento), **Preview A4** o **Preview A4 Blanco**. Los botones visibles y sus nombres dependen de la configuración de su empresa. **Cerrar** cierra la ventana sin imprimir.
3. Normalmente se abre una pestaña nueva con la vista de impresión; imprima desde allí con el diálogo del navegador.
Impresión directa con QZ Tray: si su empresa tiene activada la impresión con QZ Tray, al presionar **Preview** la factura se envía directo a la impresora configurada para su empresa (o a la impresora predeterminada del equipo), sin abrir la vista ni el diálogo de impresión. Aparece el aviso "Documento enviado a la impresora: ..." con el nombre de la impresora.
Si no se puede imprimir con QZ Tray aparece el aviso "No se pudo imprimir con QZ Tray: (motivo). Se abrirá la vista de impresión." y se abre la vista de impresión de siempre, para imprimir de la forma normal.
Notas: **Preview A4** y **Preview A4 Blanco** siguen abriendo la vista de impresión. Si QZ Tray no está activado para su empresa, todo funciona como antes.
Ver también: Preparar un equipo para imprimir facturas con QZ Tray; Avisos al imprimir facturas con QZ Tray y qué hacer.

## Preparar un equipo para imprimir facturas con QZ Tray
Lo realiza el técnico o implementador de posMe, una vez en cada computadora que imprime facturas.
1. Instalar el programa QZ Tray (versión 2.2) y dejarlo abierto; se ve como un ícono en la bandeja, junto al reloj de Windows.
2. Copiar el certificado de la empresa en la carpeta de instalación de QZ Tray con el nombre override.crt y reiniciar QZ Tray.
3. Verificar que la impresora esté instalada en Windows con el nombre exacto configurado para la empresa, o que sea la impresora predeterminada del equipo.
4. En el navegador, permitir las ventanas emergentes del sitio de posMe. Si se bloquean, la vista de impresión de respaldo no se abre.
5. Hacer una factura de prueba y presionar **IMPRIMIR** > **Preview**. La primera vez QZ Tray puede pedir permiso: marque "Remember this decision" y presione **Allow**. Desde entonces imprime sin preguntar.
Notas: la activación de QZ Tray y el nombre de la impresora se configuran por empresa. Los pasos técnicos completos están en el instructivo QZ_TRAY_INSTALACION.md, que tiene el técnico.
Ver también: Imprimir una factura (Preview, A4 o directo a la impresora).

## Avisos al imprimir facturas con QZ Tray y qué hacer
Ruta: Menú > FACTURACION > FACTURAR > **IMPRIMIR** > **Preview**.
Cuando aparece un aviso de QZ Tray, la factura no se envía a la impresora y se abre la vista de impresión normal para imprimir de la forma de siempre.
- "QZ Tray no está instalado o no está abierto en este equipo": abra QZ Tray (o pida al técnico que lo instale) y vuelva a imprimir.
- "No se encontró la impresora ..." o "No hay impresora predeterminada configurada": la impresora no existe en Windows con ese nombre o el equipo no tiene impresora predeterminada. Avise al técnico.
- "La vista de impresión no devolvió un PDF" (o "devolvió un PDF vacío"): el formato de impresión configurado para su empresa no genera PDF. Avise al administrador o al técnico.
- QZ Tray muestra "Untrusted website" (sitio no confiable) o falla la firma: el equipo no tiene el certificado override.crt o no es el de su empresa. Avise al técnico.
- Falla QZ Tray y no se abre ninguna vista de impresión: el navegador bloqueó la ventana emergente. Permita las ventanas emergentes del sitio de posMe.
Ver también: Preparar un equipo para imprimir facturas con QZ Tray.

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
Ruta: Menú > CXC > CLIENTES. La lista se llama **LISTA DE CLIENTES**. Según la configuración de su empresa, títulos, campos y pestañas pueden cambiar.
1. Presione **Nuevo**.
2. Pestaña **Informacion**: en **Datos generales** complete **Nombres**, **Apellidos**, **Nombre completo**, **Nombre comercial**, **Sexo**, **Tipo Identificacion**, **Identificacion** y **Telefono**. En **Clasificacion** elija **Estado** y **Tipo**.
3. Pestañas **Telefonos.** y **Email.**: **Agregar**, complete la ventana y presione **Aceptar**.
4. Pestaña **Referencias.**: **Agente** y **Cliente Referente** usan **Buscar** y **Limpiar**.
5. Si comprará a crédito: en **CXC.** indique **Moneda**, **Tipo de Pago**, **Condicion de Pago** y **Limite Credito US$**. En **CXC Lineas.** presione **Agregar**, complete **Linea**, **Limite de Credito**, **Balance**, **Interes Anual (%)**, **Frecuencia de Pago**, **Plan** y **No Pagos**, y presione **Aceptar**.
6. En **Mas** están **Direccion** y **Recordatorio**.
7. Presione **Guardar** (en el teléfono, en la barra inferior).
Mensajes: "Escribir la Identificacion", "Escribir el sexo", "Escribir Primer Nombre", "Escribir Segundo Nombre", "Escribir Nombre Legal", "Escribir Nombre Comercial", "La linea ya esta configurada" y, según la configuración, "Error identificacion del cliente ya existe.".
Notas: **Limite de Credito** y **Balance** traen un valor inicial alto: revíselos. Si no agrega líneas, se asigna una predeterminada con límite 300,000.00.
Para modificarlo, selecciónelo y presione **Editar**. Si el balance supera el límite aparece "BALANCE NO PUEDE SER MAYOR QUE EL LIMITE EN LA LINEA".
Ver también: Crear una línea de crédito.
<!-- REVISAR: Forma de Pago pide datos de tarjeta y CVC; uso sin documentar -->

## Crear un producto
Ruta: Menú > INVENTARIO > PRODUCTO.
1. Presione **Nuevo**.
2. En **Informacion** complete *Nombre, *UM (unidad de medida), categoría, código de barras y *Estado.
3. En la pestaña **Bodegas** use **Agregar bodega** para indicar en qué bodegas existe.
4. En la pestaña **Precios** escriba los precios (público, por mayor, crédito...).
5. Opcional: pestaña **Sku** para presentaciones (caja, paquete) con **Agregar SKU**.
6. Presione **Guardar**.

## Cambiar precios de productos
Ruta: Menú > FACTURACION > PRECIOS. La lista se llama **LISTA DE PRECIO**. Aquí se cambian los precios de toda una lista con un archivo.
Para un solo producto use Menú > INVENTARIO > PRODUCTO, pestaña **Precios** (lista predeterminada de su empresa).
1. Seleccione la lista y presione **Editar**. Para una lista nueva presione **Nuevo**, indique **Fecha Inicial**, **Fecha Final**, **Nombre** y **Estado**, y presione **Guardar**.
2. En la tarjeta **Archivo de precios** marque **Generar Archivo** y presione **Guardar** (en el teléfono, en la barra inferior). Se crea un archivo con todos los productos.
3. En **Mas** > **Archivos** descargue el archivo que empieza con list_price_default_ seguido de la fecha.
4. Cambie los precios y comisiones. No modifique los títulos de columna ni **Codigo**. Escriba los números sin comas: 1500.25.
5. Suba el archivo en **Mas** > **Archivos**.
6. En **Actualizar Precios** escriba el nombre exacto del archivo con su extensión (precios.csv) y presione **Guardar**.
Mensajes: "Las fechas son obligatorias"; "NO EXISTE EL ARCHIVO PARA IMPORTAR LOS PRECIOS..."; "Las columnas de valor no son numericas".
Notas:
- Importar reemplaza todos los precios de la lista: un producto que no venga en el archivo pierde sus precios en ella.
- No use el archivo list_price_default.csv, creado al guardar una lista nueva: sus columnas son distintas.
- La columna **Costo** no cambia el costo del producto.
- Según su estado, la lista puede no permitir cambios o permitir solo cambiar el **Estado**; en ese caso no se importa ni se genera el archivo.
<!-- REVISAR: Imprimir abre una página en blanco; el cuadro Buscar de la lista no tiene acción -->
<!-- REVISAR: efecto de Fecha Inicial, Fecha Final y Estado al facturar -->

## Crear una tarjeta de fidelidad
Ruta: Menú > VENTAS > FIDELIDAD. La lista se llama **LISTA DE TARJETAS DE FIDELIDAD**.
1. Presione **Nuevo**. Se abre la pantalla de la tarjeta.
2. En la tarjeta **Tarjeta** indique **Fecha** (arranca en hoy), **Estado**, **Meta**, **Puntos** y **Telefono Cliente**. **Meta** es obligatoria y no puede ser 0.
3. En la tarjeta **Premio y referencias** complete **Premio**. Opcional: **Referencia 1**, **Referencia 2** y **Comentario**.
4. Presione **Guardar** (en el teléfono, en la barra inferior). La tarjeta queda abierta con su número.
Mensajes de validación: "Ingrese la Fecha", "La meta no puede estar vacia" y "Escribir numero de telefono".
Notas:
- **Puntos** no puede superar **Meta**: si escribe más, el campo vuelve a su valor anterior sin mostrar mensaje.
- Los puntos se escriben a mano en la tarjeta. Al facturar no se suman solos.
- **Imprimir** genera la tarjeta en PDF.
Para modificarla, selecciónela en la lista y presione **Editar**, o escriba su número en **Buscar por número…** y presione el botón de buscar. Arriba tiene **Nuevo**, **Atras**, **Eliminar**, **Imprimir** y **Guardar**; en **Mas** > **Archivos** adjunta documentos en una ventana nueva. Según su estado, la tarjeta puede no permitir cambios, permitir solo cambiar el **Estado** o no permitir eliminarla.
<!-- REVISAR: nombres de los estados de la tarjeta y efecto de cada uno -->
<!-- REVISAR: Buscar por número no filtra por tipo de documento; puede abrir otro documento con el mismo número -->

## Ajustar el inventario
Ruta: Menú > INVENTARIO > AJUSTE.
1. Presione **Nuevo**, elija la bodega y la fecha.
2. Use **Agregar producto** para cada producto e indique la cantidad.
3. Presione **Guardar** y aplique el documento para que afecte la existencia.
Otras opciones: INVENTARIO > O. ENTRADAS (otras entradas), INVENTARIO > O. SALIDAS (otras salidas), INVENTARIO > T. SALIDA y T. ENTRADA (traslados entre bodegas).

## Imprimir códigos de barra de productos (OPERACIONES o acciones sobre productos)
Ruta: Menú > INVENTARIO > OPERACIONES. La lista se llama **ACCIONES SOBRE PRODUCTOS**.
1. En la fila de cada producto escriba cuántas etiquetas quiere imprimir. Deje vacío o en 0 los que no quiera imprimir.
2. Presione **Imprimir codigo de barra**.
Según la configuración de su empresa, las etiquetas se abren en una pestaña nueva para imprimirlas o se envían directamente a la impresora de etiquetas, con o sin precio.
Si no escribió ninguna cantidad aparece "Seleccionar el Registro...".
Notas:
- Si un producto no tiene código de barra, al imprimir el sistema le asigna uno (la letra B más su código) y lo guarda en el producto.
- Escriba las cantidades e imprima sin cambiar de página ni filtrar la tabla: solo se toman los productos que se ven en ese momento.
Ver también: Crear un producto.
<!-- REVISAR: nombre de la columna de cantidad y demás columnas de la lista; el cuadro Buscar… de esta lista no tiene ninguna acción programada; confirmar si se ve el cuadro Filtro: de la tabla -->

## Ejecutar procesos de inventario (salidas por fórmulas y XML para Encuentra24)
Ruta: Menú > INVENTARIO > PROCESOS. La pantalla se llama **Procesos de inventario** y tiene dos tarjetas.
Salidas de inventario por fórmulas: crea la salida de inventario de los productos que componen a los productos formulados vendidos en el ciclo elegido.
1. En la tarjeta **Salidas de inventario por fórmulas** elija **Periodo**. Solo aparecen los periodos sin cerrar.
2. Elija **Ciclo**. La lista se llena al elegir el periodo.
3. Presione **Aceptar** y confirme "Desea Realizar la salidas de inventarios de las facturas con productos formulados.".
Al terminar aparece "success". Si aparece "problemas en el proceso de cierre", el proceso no terminó bien; por ejemplo, cuando el ciclo ya está cerrado.
Notas: toma solo facturas en estado APLICADO. La salida se registra en la bodega predeterminada de la configuración de su empresa. Elija siempre **Periodo** y **Ciclo** antes de **Aceptar**.
XML para Encuentra24:
1. En la tarjeta **XML para Encuentra24** presione **Aceptar**. Se abre una pestaña nueva y se descarga un archivo XML con los inmuebles.
<!-- REVISAR: sin Periodo ni Ciclo la pantalla no avisa y el proceso podría tomar facturas de todos los periodos; confirmar si repetir el proceso duplica salidas; el XML solo incluye un inmueble y lleva datos de contacto fijos en el código -->

## Ver, reactivar o eliminar inmuebles desactivados (INMUEBLES DESAC)
Ruta: Menú > INVENTARIO > INMUEBLES DESAC. La lista se llama **LISTA DE INMUEBLES DESACTIVADOS**.
Reactivar un inmueble:
1. Seleccione la fila en la lista.
2. Presione **Editar**. Se abre la pantalla de edición del inmueble.
Importante: **Editar** reactiva el inmueble en ese momento, sin pedir confirmación y aunque luego no presione **Guardar**.
Si no seleccionó ninguna fila aparece "Seleccionar el Registro...".
Volver a desactivarlo: en la pantalla de edición presione **Eliminar** y confirme "Desea eliminar este Registro...". El botón puede no aparecer según la configuración de su empresa. Según su empresa, si tiene existencias aparece "EL REGISTRO NO PUEDE SER ELIMINADO, SU CANTIDAD ES MAYOR QUE 0". En esta lista no hay botón para borrar un inmueble definitivamente.
Notas:
- El cuadro **Buscar…** busca por código solo entre los registros activos. Con un inmueble desactivado responde "NO SE ENCONTRO EL REGISTRO"; ubíquelo en la lista.
- Si escribe el código de un registro activo, se abre su edición.
Ver también: Crear un producto.
<!-- REVISAR: confirmar en la BD que la vista de esta lista muestra solo registros desactivados, y sus columnas -->

## Qué contiene el menú TRANSA
Ruta: Menú > TRANSA.
El menú TRANSA reúne estas opciones (cada usuario ve las que su rol tenga permitidas):
- **DOCUMENTOS**: configura los documentos del sistema, sus causales y sus cuentas contables.
- **VENCIMIENTO**: pantalla **FECHA DE EXPIRACION**. Descarga, sube y procesa el archivo con las fechas de vencimiento de la bodega seleccionada.
- **ENDOSOS**: cambia un dato de un documento ya registrado, dejando constancia del valor anterior y del nuevo.
- **PRODUCCION**: registra órdenes de producción con sus insumos y resultados.
- **NOTAS**: registra y consulta las calificaciones de los alumnos.
- **BUILDER REPORT**: Constructor de Reportes. Arma un reporte eligiendo vista, columnas, agrupación, orden y filtros; guarda consultas para usarlas de nuevo y exporta a Excel.
Notas: la opción NOTAS de este menú es para calificaciones. No es una nota de crédito ni de débito: esas están en CXC y en CXP.
Ver también: Configurar documentos del sistema y causales; Registrar un endoso; Registrar calificaciones de un alumno; Crear un reporte con el Constructor de Reportes (Builder Report).

## Configurar documentos del sistema y causales
Ruta: Menú > TRANSA > DOCUMENTOS. La lista se llama **LISTA DE DOCUMENTOS**. Aquí no se crean ni se eliminan documentos: solo se configuran.
1. Seleccione el documento y presione **Editar**.
2. En **Datos del documento** complete **Tipo de Comprobante**, **Referencia 1**, **Referencia 2**, **Referencia 3** y **Descripcion**. Marque **Contabiliza** para que el documento aparezca en CONTABILIDAD > PROCESOS. **Nombre**, **Workflow** y **Afectar el Inventario** no se pueden cambiar.
3. En la pestaña **Causales** presione **Agregar**. En **Agregar causal** complete **Sucursal** y **Nombre** (obligatorios), **Bodega Origen**, **Bodega Destino** y **Es Principal**, y presione **Aceptar**.
4. Para quitar un causal, toque la fila, presione **Eliminar** y confirme.
5. Presione **Guardar** (en el teléfono, en la barra inferior). Los causales agregados o quitados se graban en ese momento.
6. Para sus cuentas contables, toque el causal ya guardado y abra la pestaña **Configuracion**. Elija **Concepto**, **D|C** (Debito o Credito), **Cuenta** y **Centro de Costo**, y presione **Agregar**. Esta tabla se graba al instante, sin **Guardar**. Para quitar una fila, tóquela, presione **Eliminar** y confirme.
Mensaje de validación: "Siempre el documento tiene que tener un Causal Principal."
Notas: si falta **Concepto**, **Cuenta** o el causal, o en **Agregar causal** falta **Sucursal** o **Nombre**, el botón no hace nada ni muestra aviso. Un causal recién agregado no carga configuración hasta presionar **Guardar**.
Ver también: Contabilizar documentos.

## Registrar calificaciones de un alumno
Ruta: Menú > TRANSA > NOTAS. La pantalla se llama **Notas**.
1. En **Filtrar por** elija **Grado**, **Año**, **Meses** y **Días** (**Meses** se habilita al elegir el año).
2. Con **Buscar** seleccione **Alumno**, **Colaborador** y **Materia**.
3. Elija **Calificación Cualitativa** y escriba **Calificación Cuantitativa**, que debe estar dentro del rango de esa calificación. **Referencia 1** y **Referencia 2** son opcionales.
4. Presione **Guardar** (en el teléfono, en la barra inferior). Si ya hay una nota con el mismo grado, alumno, colaborador, materia y fecha, se actualiza en lugar de crear otra.
Consultar: complete **Grado**, **Año**, **Meses**, **Días** y **Alumno**, y presione **Mostrar**. La tabla **Calificaciones** muestra las notas de esa fecha; **Colaborador** y **Materia** acotan el resultado.
Eliminar: con los mismos filtros, más **Colaborador** y **Materia**, presione **Eliminar** y confirme. Luego compruebe el resultado con **Mostrar**.
Certificado: con **Grado**, **Año** y **Alumno**, presione **Certificado**.
Mensajes de validación: "Debe seleccionar un Grado", "Debe seleccionar un año", "Debe seleccionar un mes", "Debe seleccionar el dia para aplicar", "Debe seleccionar un alumno" (al consultar o imprimir), "Debe seleccionar un Alumno" (al guardar o eliminar), "Debe seleccionar un Profesor", "Debe seleccionar una Materia", "Debe seleccionar un valor cualitativo" y "Debe especificar un valor cuantitativo".
Notas: al cambiar la calificación cualitativa se borra la cuantitativa. Si el número sale del rango, se avisa y se borra.

## Registrar un endoso
Ruta: Menú > TRANSA > ENDOSOS. La lista se llama **LISTA DE ENDOSOS**.
Un endoso cambia un dato de una factura, una compra o un activo fijo y guarda el valor anterior y el nuevo.
1. Presione **Nuevo**. Se abre **ENDOSO:#**.
2. En **Documento** revise **Fecha** (arranca en hoy) y **Estado** (al crear, solo REGISTRADO). Elija **Tipo de Transacción** y, con **Buscar**, el **Documento**.
3. En **Motivo y referencias** escriba **Comentario** (el motivo) y, si aplica, **Referencia 1**, **Referencia 2** y **Referencia 3**.
4. En **Valor a endosar** elija **Valor a Modificar**. Sus opciones dependen del tipo de documento; si no hay, dice "No hay datos disponibles". **Valor anterior** se carga solo; complete **Valor Nuevo**.
5. Presione **Guardar** (en el teléfono, en la barra inferior). El cambio aún no se aplica al documento.
6. Para aplicarlo, en la pantalla de edición cambie **Estado** a APLICADO y presione **Guardar**. El documento toma el valor nuevo.
Mensaje de validación: "Seleccionar un valor nuevo para endoso".
Para modificarlo, búsquelo por número o selecciónelo en la lista y presione **Editar**. Arriba tiene **Nuevo**, **Atras**, **Imprimir**, **Eliminar** y **Guardar**. **Eliminar** pide confirmar "Desea eliminar este Registro...".
Restricciones: un endoso APLICADO ya no se puede modificar ni eliminar. Tampoco se puede modificar si su ciclo contable está cerrado ("EL DOCUMENTO NO PUEDE ACTUALIZARCE, EL CICLO CONTABLE ESTA CERRADO").
<!-- REVISAR: con que Tipo de Transaccion se llega a un activo fijo en Buscar. -->
<!-- REVISAR: otros tipos con opciones configuradas dan error al cargar Valor anterior. -->

## Registrar una orden de producción (insumos y resultados)
Ruta: Menú > TRANSA > PRODUCCION. La lista se llama **LISTA DE ORDENES DE PRODUCCION**.
1. Presione **Nuevo**. Se abre **Agregar Orden De Produccion**.
2. En la pestaña **General** indique **Fecha** y **Moneda**. Opcional: **Solicitado Por** y **Elaborado Por** (con **Buscar**), **Referencia 1**, **Referencia 2**, **Referencia 3** y **Comentario**.
3. En la pestaña **Insumos** presione **Agregar**. Elija primero **Bodega Origen** (al cambiarla se limpia el producto). Con **Buscar** elija **Producto Origen** (lo que se consume) y **Producto Destino** (lo que se fabrica). Escriba **Cantidad** y presione **Salvar**.
4. En la pestaña **Resultados** presione **Agregar**. Elija **Bodega Destino**, **Producto Destino** y **Cantidad**, y presione **Salvar**. El costo sale de los insumos de ese producto; agregue primero los insumos.
5. Presione **Guardar** (en el teléfono, en la barra inferior).
Cada **Producto Destino** de los insumos debe estar en Resultados, y viceversa. **Monto Total** suma el costo de los insumos.
Mensajes de validación: "Ingrese la Fecha", "Debe ingresar al menos un insumo", "Debe ingresar al menos un producto resultante" y "Los Insumos no coinciden con los Resultados".
Quitar o cambiar una línea: márquela y presione **Eliminar** o **Editar**.
Para modificarla, selecciónela en la lista y presione **Editar**, o use **Buscar por número…**. Arriba tiene **Nuevo**, **Atras**, **Eliminar**, **Imprimir** y **Guardar**; adjuntos en **Mas** > **Archivos**.
El inventario se mueve al guardar en estado APLICADO: sale cada insumo de su **Bodega Origen** y entra cada resultado en su **Bodega Destino**.
<!-- REVISAR: confirmar en una prueba real el movimiento de inventario al aplicar -->

## Registrar el vencimiento de productos (fecha de expiración)
Ruta: Menú > TRANSA > VENCIMIENTO. La pantalla se llama **FECHA DE EXPIRACION**.
Las fechas se cambian con un archivo: se descarga, se corrige en el equipo, se sube y se procesa.
1. En **Bodega** elija la bodega (solo aparecen las asignadas a su usuario).
2. Presione **Descargar**. Se descarga el archivo expired_items.csv con los productos de esa bodega que ya tienen fecha de expiración.
3. Corrija en el archivo las columnas **Expiracion** y **Cantidad**. No cambie las demás columnas ni el nombre del archivo. Para varias fechas de un mismo producto, repita su línea con el mismo código en **Sistema**.
4. Presione **Subir Datos**. En la pestaña que se abre, suba el archivo corregido.
5. Vuelva a la pantalla y presione **Procesar**. Debe aparecer "ARCHIVO PROCESADO CORRECTAMENTE".
Importante:
- Si borra todas las líneas de un producto, se borran sus fechas en esa bodega.
- Un producto que no estaba en el archivo descargado no se agrega, aunque escriba su línea.
- **Descargar** reemplaza el archivo guardado. No lo presione entre **Subir Datos** y **Procesar**, o perderá sus cambios.
Mensajes:
- "Seleccione una bodega".
- "NO SE ENCONTRARON REGISTROS EXPIRADOS EN LA BODEGA": la bodega no tiene productos con fecha de expiración.
- "NO SE ENCONTRO EL ARCHIVO expired_items.csv": no hay archivo con ese nombre en la bodega.
- "EL ARCHIVO expired_items.csv NO TIENE EL FORMATO CORRECTO": alguna línea tiene columnas de más o de menos.
El separador de columnas del archivo depende de la configuración de su empresa.
<!-- REVISAR: de dónde salen las primeras fechas de expiración de un producto; formato de fecha aceptado al editar en Excel -->

## Crear un reporte con el Constructor de Reportes (Builder Report)
Ruta: Menú > TRANSA > BUILDER REPORT. La pantalla tiene las pestañas **1. Vistas**, **2. Configurar** y **3. Mis consultas**.
1. En **1. Vistas** elija una vista; **Buscar vista** filtra la lista.
2. En **Mostrar** agregue campos con **Agregar columna...** o **Todas**. En cada columna puede cambiar **Título**, **Tipo** y **Alineación**, moverla o quitarla.
3. Para resumir, en **Agrupar / calcular** elija **Agrupar por este campo** en unas columnas y **Suma**, **Conteo**, **Promedio**, **Mínimo** o **Máximo** en las demás. Da una fila por grupo; no hay subtotales ni total general.
4. Opcional: **Agregar orden** y **Agregar filtro**. Un filtro sin valor no se aplica. Puede filtrar el mismo campo más de una vez.
5. Presione **Generar**. Vea el **Resultado**; cambie **Filas por página** o avance con las flechas.
Listas: **Mostrar**, **Ordenar** y **Filtrar** tienen su propia barra de desplazamiento. Los combos de **Mostrar** y **Ordenar** no repiten los campos ya agregados (salvo los que llevan un cálculo, como Suma); al quitar uno vuelve a aparecer.
Teléfono: el resultado se ve en tarjetas y **Generar** queda en la barra inferior.
Ver también: Vistas, datos y límites del Constructor de Reportes; Filtrar por fechas que se calculan solas en el Constructor de Reportes; Abrir el resultado del Constructor de Reportes en una nueva página; Guardar una consulta de reporte para usarla de nuevo.
<!-- REVISAR: confirmar el rótulo del menú en pantalla -->

## Vistas, datos y límites del Constructor de Reportes
Ruta: Menú > TRANSA > BUILDER REPORT.
Vistas: aparecen las registradas por el administrador para su empresa y el tipo de negocio (flavor) de esta, siempre que usted tenga permitido su menú de origen. Por ejemplo, **Ventas por cliente** incluye solo facturas aplicadas y exige acceso a Facturación.
Datos: según el permiso de su rol ve toda la empresa, su sucursal o solo lo que usted creó.
Límites, según la configuración de su empresa (valores sugeridos): 100 filas por página, 5,000 filas en pantalla y 30 segundos por consulta.
Mensajes:
- "Agregue al menos una columna en "Mostrar"."
- "Al agrupar, la columna "..." debe agruparse o llevar un cálculo."
- "La consulta tardo demasiado y fue cancelada. Reduzca el rango de fechas o agregue filtros."
- "No tiene vistas disponibles para reportes."
Notas: tras asignarle el permiso, el usuario debe cerrar sesión y volver a entrar.
Ver también: Crear un reporte con el Constructor de Reportes (Builder Report).

## Filtrar por fechas que se calculan solas en el Constructor de Reportes (inicio del mes, hoy)
Ruta: Menú > TRANSA > BUILDER REPORT, pestaña **2. Configurar**, bloque **Filtrar**.
En un filtro de un campo de fecha, cada fecha tiene un combo para elegir cómo se toma:
1. **Fecha manual**: escriba o elija la fecha en el calendario. Queda fija.
2. Una fecha que se calcula cada vez que se ejecuta el reporte, según el día de hoy:
   - **Desde**: **Inicio del día**, **Inicio del mes** o **Inicio del año**.
   - **Hasta**: **Fin del día**, **Día de hoy**, **Fin del mes** o **Fin del año**.
   - En los demás tipos de filtro (**Valor**) se ofrecen según la condición elegida.
Ejemplo "Ventas del mes": filtre la fecha con **Entre**, **Desde** = **Inicio del mes** y **Hasta** = **Fin del mes**. Al guardarla, cada mes muestra solo las ventas de ese mes sin cambiar nada.
Las fechas calculadas se respetan igual al **Generar**, al **Exportar a Excel**, en **Abrir en nueva página** y en las tarjetas de los dashboards.
Notas: puede filtrar el mismo campo más de una vez (por ejemplo, una condición "mayor o igual" y otra "menor o igual"). **Fin del día** y **Día de hoy** dan el mismo resultado.
Ver también: Crear un reporte con el Constructor de Reportes (Builder Report).

## Abrir el resultado del Constructor de Reportes en una nueva página
Ruta: Menú > TRANSA > BUILDER REPORT, pestaña **2. Configurar**.
1. Configure el reporte o abra una consulta guardada.
2. Presione **Abrir en nueva página**. Se abre otra pestaña del navegador con solo el **Resultado**, a todo el ancho de la pantalla.
3. Use **Filas por página** y las flechas **Anterior** y **Siguiente** igual que en el constructor.
Se usan las columnas, el orden y los filtros que hay en pantalla en ese momento, aunque no estén guardados.
Restricciones: el botón se habilita cuando la configuración está completa (las mismas reglas que **Generar**).
Mensajes:
- "No hay datos para los criterios seleccionados."
- "No se recibió la configuración del reporte. Vuelva a abrirlo desde el constructor.": vuelva a presionar el botón desde el constructor.
- Si aparece un error, presione **Reintentar**.
Ver también: Crear un reporte con el Constructor de Reportes (Builder Report).

## Guardar una consulta de reporte para usarla de nuevo
Ruta: Menú > TRANSA > BUILDER REPORT, pestaña **2. Configurar**.
1. Configure el reporte (columnas, agrupación, orden y filtros).
2. Presione **Guardar como consulta recurrente**. Se abre la ventana **Guardar consulta**.
3. Escriba **Nombre de la consulta** (obligatorio).
4. Elija **Visibilidad**: **Solo para mí** o **Para todos los usuarios** (esta opción solo la ven los administradores de compañía).
5. Opcional: **Tipo de vista** (**Gráfico** o **Reporte**), **Dashboard** donde se mostrará y **Orden**. Con **— Ninguno —** en **Dashboard** la consulta no aparece en ningún dashboard.
6. Presione **Guardar**. Aparece "Consulta guardada." y queda en **3. Mis consultas**. **Cancelar** cierra la ventana sin guardar.
Un filtro sin valor se guarda y se llena al usar la consulta; mientras esté vacío no se aplica.
Usar una consulta guardada:
1. En **3. Mis consultas** haga clic en su nombre. Se abre en modo **Consulta**, con el resumen de lo que muestra, y solo se pueden cambiar los valores de los filtros.
2. Presione **Generar** o **Exportar a Excel**. La consulta no se ejecuta sola al abrirla.
Los valores que cambie en modo **Consulta** aplican a esa ejecución y no se guardan. Para conservarlos presione **Editar configuración** y luego **Guardar como consulta recurrente**.
Restricciones: en **3. Mis consultas** cada usuario ve solo las consultas que creó.
Mensajes:
- "Escriba un nombre para guardar la consulta."
- "Ya tiene una consulta con ese nombre."
- "El filtro "..." necesita los dos valores de "Entre"." (al generar con un solo extremo lleno)
Ver también: Compartir una consulta de reporte con todos los usuarios; Mostrar una consulta de reporte como tarjeta en un dashboard.

## Compartir una consulta de reporte con todos los usuarios
Ruta: Menú > TRANSA > BUILDER REPORT > **Guardar como consulta recurrente** > ventana **Guardar consulta**.
1. En **Visibilidad** elija **Para todos los usuarios**.
2. Elija el **Dashboard** donde se mostrará y configure la tarjeta.
3. Presione **Guardar**. En **3. Mis consultas** la consulta lleva la marca **Compartida**.
Qué ven los demás: una consulta compartida aparece como tarjeta en el dashboard elegido para todos los usuarios de la empresa, con los datos de toda la compañía. No aparece en su **3. Mis consultas** y no la pueden modificar ni eliminar.
Una consulta **Solo para mí** solo la ve quien la creó, también en los dashboards.
Restricciones: solo un usuario con un rol marcado como **Administrador de compañía** ve la opción **Para todos los usuarios**. Si otro usuario modifica una consulta que estaba compartida, se le avisa: "Esta consulta estaba compartida; solo un administrador puede compartirla, al guardar quedará solo para usted."
Mensaje: "Solo un administrador de la compania puede compartir consultas con todos."
Ver también: Marcar un rol como administrador de compañía; Ver las tarjetas de reportes en el dashboard.

## Mostrar una consulta de reporte como tarjeta en un dashboard (gráfico, tabla o indicador)
Ruta: Menú > TRANSA > BUILDER REPORT > **Guardar como consulta recurrente** > ventana **Guardar consulta**.
1. En **Dashboard** elija **Dashboard principal** o el dashboard de productos. Aparece **Presentación en el dashboard**.
2. **Orden**: número de posición entre las tarjetas (0 o mayor; las de menor número van primero).
3. **Tipo de tarjeta**: **Gráfico**, **Tabla** o **Indicador**. **Ancho**: **20%**, **50%** o **100%**.
4. **Título**: si lo deja vacío se usa el nombre de la consulta.
5. Si es **Gráfico**: **Tipo de gráfico** (Barras, Circular, Columna o Línea), **Campo etiqueta**, **Campos valor** (uno o varios) y **Campo serie (opcional)**.
6. Si es **Indicador**: **Campo valor principal** y **Formato** (**Número** o **Moneda**).
7. Presione **Guardar**.
Los campos salen de las columnas de **Mostrar**, incluidas las de **Suma** o **Conteo**. Si quita una columna usada por la tarjeta, se limpia y se avisa: "Se quitó una columna usada en la presentación del dashboard (...). Revísela al guardar."
Mensajes: "Seleccione el tipo de tarjeta.", "Seleccione el ancho de la tarjeta.", "Seleccione el tipo de gráfico.", "Seleccione el campo etiqueta del gráfico.", "Seleccione al menos un campo valor del gráfico.", "Seleccione el campo valor principal del indicador."
Notas: cada consulta se muestra en un solo dashboard. Para verla en otro, use **Duplicar** y elija el otro dashboard en la copia.
<!-- REVISAR: nombres exactos de los tipos de gráfico y del dashboard de productos en los catálogos de la BD -->

## Modificar, duplicar o eliminar una consulta de reporte guardada
Ruta: Menú > TRANSA > BUILDER REPORT, pestaña **3. Mis consultas**. La lista muestra **Nombre**, **Vista**, **Modificada** y **Acciones**.
Solo aparecen las consultas que usted creó. Bajo el nombre se ven marcas: **Compartida**, el tipo de vista y el dashboard con su orden. Las consultas compartidas por otros usuarios no aparecen aquí; solo se ven en los dashboards.
Modificar:
1. Presione **Editar** en la fila. Desde una consulta abierta, use **Editar configuración**.
2. Cambie lo que necesite y presione **Guardar como consulta recurrente**; revise la ventana **Guardar consulta** y presione **Guardar**. Se actualiza la misma consulta; no se crea otra.
Duplicar: presione **Duplicar**. Se crea una copia llamada "Copia de" más el nombre original, **Solo para mí**, con el mismo dashboard, y se abre para editarla. Úsela para mostrar la consulta en otro dashboard.
Eliminar: presione **Eliminar** y confirme "¿Desea eliminar la consulta "..."?". Aparece "Consulta eliminada.". No se puede recuperar desde la pantalla.
Notas: si tiene cambios sin guardar, al abrir otra consulta se le avisa: "Tiene cambios sin guardar que se perderán. ¿Desea continuar?".
Si un campo ya no está disponible, se quita de la consulta y se muestra un aviso.
Mensaje: "La consulta solicitada no existe o no esta disponible.": la consulta se eliminó o usted ya no tiene acceso a su vista.
Restricciones: crear, duplicar, modificar y eliminar dependen de los permisos de su rol.
Ver también: Guardar una consulta de reporte para usarla de nuevo.

## Exportar a Excel un reporte del Constructor de Reportes
Ruta: Menú > TRANSA > BUILDER REPORT, pestaña **2. Configurar**.
1. Configure el reporte o abra una consulta guardada.
2. Presione **Exportar a Excel**. Se descarga un archivo .xlsx con el nombre de la vista y aparece "Archivo generado.".
El archivo trae todas las filas que cumplen los filtros, no solo la página en pantalla, con los títulos de sus columnas.
Se exporta la configuración que hay en la pantalla en ese momento, aunque no haya presionado **Generar**. Exportar no cambia el **Resultado** que se ve.
Límite: según la configuración de su empresa (valor sugerido: 50,000 filas). Si el resultado lo supera, el archivo se corta y aparece "El archivo se limitó al máximo de filas permitido."; su última fila dice "Resultado limitado a" y el máximo de filas.
Notas: un texto que empieza con = + - @ se muestra en Excel con un apóstrofo inicial. Es normal.
Mensaje: "La consulta tardo demasiado y fue cancelada. Reduzca el rango de fechas o agregue filtros."
Ver también: Crear un reporte con el Constructor de Reportes (Builder Report).

## Ver las tarjetas de reportes en el dashboard
Ruta: Dashboard principal o dashboard de inventario (productos).
Las consultas del Constructor de Reportes guardadas con un **Dashboard** aparecen como tarjetas AL FINAL de la información habitual del dashboard (después de los paneles que ya existen), en el **Orden** configurado.
Tipos de tarjeta:
- **Indicador**: el título y un número grande con formato 1,000.00.
- **Gráfico**: el gráfico elegido, con los valores en formato 1,000.00.
- **Tabla**: las primeras 10 filas del resultado.
Ancho: en computadora cada tarjeta usa su ancho (20%, 50% o 100%); en tablet las de 20% se ven al 50%; en teléfono todas se ven a lo ancho.
Quién ve cada tarjeta:
- Consulta compartida (**Para todos los usuarios**): la ven todos los usuarios, con los datos de toda la compañía.
- Consulta **Solo para mí**: solo la ve quien la creó, con los datos que su rol le permite ver.
Las fechas calculadas (por ejemplo **Inicio del mes**) se calculan cada vez que se abre el dashboard.
Notas: mientras carga, cada tarjeta muestra "Cargando...". Si una tarjeta falla, solo esa muestra el aviso (por ejemplo "No se pudieron cargar los datos."); las demás y el resto del dashboard siguen normales. Si no hay datos se ve "Sin datos para mostrar.". Si no hay tarjetas, el dashboard se ve igual que siempre.
Ver también: Mostrar una consulta de reporte como tarjeta en un dashboard (gráfico, tabla o indicador); Compartir una consulta de reporte con todos los usuarios.
<!-- REVISAR: ruta de menú exacta del dashboard de inventario (app_inventory_dashboards) -->

## Registrar una compra de mercadería
Ruta: Menú > INVENTARIO > COMPRA.
1. Presione **Nuevo**, seleccione el proveedor, la bodega y la fecha.
2. Agregue los productos con su cantidad y costo.
3. Guarde y aplique la compra para que aumente el inventario. <!-- REVISAR -->
Pantalla: la información está en tarjetas de color: **Documento**, **Proveedor**, **Resumen** y **Detalle**. El total se muestra con fondo de color y las filas del detalle se ven alternadas para leerlas mejor.
Resumen siempre visible: en computadora o laptop, al bajar en la pantalla el **Resumen** (Sub total, Descuento, IVA, ISC y Total) queda fijo como una franja en la parte de arriba. En el teléfono los totales siguen en la barra de abajo, como siempre.
Ver también: Importar el detalle de una compra desde un archivo CSV; Errores y códigos repetidos al importar una compra.

## Importar el detalle de una compra desde un archivo CSV
Ruta: Menú > INVENTARIO > COMPRA, abra una compra ya guardada (la importación se hace al editar, no en la compra nueva).
1. Al guardar una compra nueva, en la pestaña **Archivos** queda una plantilla llamada ejemplo_(fecha).csv. Descárguela y llénela con las columnas: Codigo, Nombre, Cantidad, Costo, Precio, Lote, Vencimiento, IVA, ISC, Categoria (el separador de columnas depende de la configuración de su empresa).
2. En la pestaña **Archivos** suba su archivo .csv.
3. En la tarjeta **Detalle**, elija el archivo en la lista que está junto al botón **Importar**. La lista muestra los archivos .csv subidos en **Archivos**, del más nuevo al más antiguo, con su fecha. Si acaba de subir uno y no aparece, pulse el botón de actualizar que está a la derecha de la lista.
4. Presione **Importar** (o **Guardar**).
5. El sistema primero revisa el archivo. Si está bien, lo importa por lotes de 200 filas y muestra la ventana **Importando archivo** con una barra de avance.
6. Al terminar, la compra se guarda sola con el detalle importado.
Notas: no cierre ni recargue la página mientras avanza la barra, sobre todo con archivos grandes (más de 1000 líneas). Si el código de una fila ya existe en el inventario, se usa ese producto. Si elige otro archivo, debe importar de nuevo.
Ver también: Errores y códigos repetidos al importar una compra; Registrar una compra de mercadería.

## Errores y códigos repetidos al importar una compra
Ruta: Menú > INVENTARIO > COMPRA > botón **Importar** del detalle.
Antes de importar, el sistema revisa el archivo .csv. Si encuentra errores, muestra la fila de cada error y no importa nada. Se revisa:
- Que estén todas las columnas de la plantilla.
- Filas sin código ni nombre.
- Cantidad, Costo, Precio, IVA o ISC que no sean números o que sean negativos.
- Fechas de Vencimiento inválidas. Formatos aceptados: AAAA-MM-DD, AAAA/MM/DD, DD/MM/AAAA y DD-MM-AAAA.
Corrija el archivo, súbalo otra vez en **Archivos** y presione **Importar**.
Códigos repetidos: si un mismo código aparece varias veces en el archivo, se abre una ventana con la lista de códigos repetidos, su nombre y las filas donde aparecen. Elija:
- **Omitir repetidos**: solo se importa la primera fila de cada código.
- **Generar codigo nuevo**: la primera fila conserva el código; las demás se crean como productos nuevos con código automático del sistema.
- **Cancelar**: no se importa nada.
Notas: un código que ya existe en el inventario no cuenta como repetido; se usa ese producto. Si la importación se detiene por un error, el aviso indica la fila; al importar de nuevo se reemplaza todo el detalle.
Ver también: Importar el detalle de una compra desde un archivo CSV.

## Qué contiene el menú ADM TALLER
Ruta: Menú > ADM TALLER.
- **TALLER**: órdenes de taller o de trabajo.
- **PEDIDOS**: pedidos de piezas o productos para un cliente.
- **ZONA DE CLIENTE**: registros de equipos de clientes en la zona de cliente.
- **GARANTIA**: garantías.
- **DEV. COMPRA**: devoluciones de compra a proveedores.
- **ORD. COMPRA**: órdenes de compra.
Las listas de TALLER, PEDIDOS y ZONA DE CLIENTE tienen **Vistas**, **Editar**, **Eliminar** y **Nuevo**; PEDIDOS tiene además **Imprimir**. Seleccione la fila antes de presionar **Editar**, **Eliminar** o **Imprimir**; si no lo hace aparece "Seleccionar el Registro...".
Buscar en la lista: escriba en **Buscar por número…** y presione la lupa o Enter; si encuentra el número, abre el registro.
Notas: en el teléfono **Guardar** está en la barra inferior y **Buscar** abre la búsqueda en una ventana dentro de la misma pantalla. Lo que ve cada usuario depende de los permisos de su rol. Según la configuración de su empresa, el menú y los campos pueden tener otros nombres.
Ver también: Registrar una orden de taller, Registrar un pedido, Registrar una zona de cliente.

## Registrar una orden de taller
Ruta: Menú > ADM TALLER > TALLER. La lista se llama **LISTA DE TALLER**.
1. Presione **Nuevo**.
2. En **Informacion**, tarjeta **Documento**: revise **Fecha** y hora (arrancan en la actual), **Sucursal** y **Estado**. Al crear solo se ofrece el estado inicial.
3. En **Personas y factura** elija con **Buscar** **Cliente**, **Tecnico** y, si aplica, **Factura** (**Limpiar** borra lo elegido).
4. En **Datos del registro** complete **Estado del equipo**, **Articulo**, **Desc. otros**, **Marca**, **Modelo**, **Serie** y **Accesorios**.
5. En **Detalle** complete **Moneda**, **Problema**, **Nota**, **Contraseña**, **Solucion** y **Monto**.
6. Opcional: en **Referencias** escriba la **Descripción**, elija la **Situación** y presione **Agregar**; en **Datos** indique **Fecha de siguiente orden** y **Activo Fijo**; en **Archivos** adjunte documentos.
7. Presione **Guardar**.
Mensajes de validación: "Establecer Fecha al Documento", "Seleccionar cliente" y "Seleccionar tecnico". Según la configuración de su empresa pueden pedirse otros campos.
Para modificarla, selecciónela en la lista y presione **Editar**. Arriba tiene **Nuevo**, **Atras**, **Eliminar**, **Imprimir E.**, **Imprimir S.**, **Opciones** (**Imprimir Sticker** y **Crear orden**) y **Guardar**.
Crear orden: indique antes **Fecha de siguiente orden**, abra **Opciones** > **Crear orden**, revise **Problema** y **Nota** y acepte. La nueva orden queda en esa fecha, con estado inicial y monto 0.
Notas: al guardar se puede enviar un WhatsApp al cliente según el **Estado del equipo**; si no se envía, la orden igual se guarda y se avisa en pantalla.
Ver también: Sincronizar las órdenes de taller con Google Calendar.
<!-- REVISAR: el aviso de WhatsApp no enviado dice "Pedido" y un nombre tecnico -->

## Sincronizar las órdenes de taller con Google Calendar (agenda de citas y visitas)
Ruta: Menú > ADM TALLER > TALLER.
Según la configuración de su empresa, posMe pasa las órdenes de taller a un calendario de Google Calendar. La activa y configura el equipo de soporte de posMe; si no está activa, no se toca ningún calendario.
1. Al guardar una orden nueva se crea un evento en la **Fecha** y hora de la orden. Si indicó **Fecha de siguiente orden**, se crea otro evento para esa visita.
2. Los eventos se titulan "Orden <número> - <cliente>" y "Visita: Orden <número> - <cliente>". La descripción lleva el cliente, el número de orden, el **Problema** y la **Nota**.
3. Al editar y presionar **Guardar**: si cambia la **Fecha**, la **Fecha de siguiente orden**, el **Problema**, la **Nota** o el **Cliente**, los eventos se actualizan. Si agrega la fecha de siguiente orden se crea su evento; si la quita, se elimina. Si la orden no tenía evento (se creó antes de activar Google Calendar), se crea.
4. Al eliminar la orden con **Eliminar**, se borran sus eventos del calendario.
5. Con **Opciones** > **Crear orden** se borra el evento de visita de la orden original y se crea el evento de la orden nueva.
Notas: cada evento dura 60 minutos, salvo que su empresa tenga configurada otra duración. Una hora 00:00 se agenda a esa hora, no como evento de todo el día. Si el estado de la orden solo permite cambiar el estado, el calendario no se actualiza.
Si Google Calendar falla (credenciales o sin internet), la orden se guarda igual y aparece "Orden guardada; no se sincronizó con Google Calendar: ..." con el motivo. Avise al equipo de soporte de posMe.
Ver también: Registrar una orden de taller, Ver el calendario de programación.

## Registrar un pedido
Ruta: Menú > ADM TALLER > PEDIDOS. La lista se llama **LISTA DE PEDIDOS**.
1. Presione **Nuevo**.
2. En **Informacion**, tarjeta **Documento**: **Fecha pedido** y **Fecha bodega** (abren vacías y son opcionales), **Sucursal** y **Status de pedido**.
3. En **Factura y personas** elija con **Buscar** **Factura**, **Cliente** y **Tecnico** (**Limpiar** borra lo elegido). Al elegir la factura, el cliente se llena solo; puede cambiarlo.
4. En **Producto** complete **Informacion adicional**, **Marca**, **Modelo** y **Numero de parte**.
5. En **Precio y envio** complete **Moneda**, **Precio de venta**, **Pieza**, **Otros**, **Envio**, **Tracking** y **Precio de compra**. Según la configuración de su empresa también puede ver **Estado** o no ver **Precio de compra**.
6. Opcional: en **Archivos** adjunte documentos.
7. Presione **Guardar** (en el teléfono, en la barra inferior).
Mensajes de validación: "El monto no puede ser 0", "Seleccionar cliente" y "Seleccionar tecnico". Si falta la factura aparece "Escribir una nota" al crear y "Seleccionar factura" al modificar; según la configuración de su empresa la factura puede no ser obligatoria.
Para modificarlo, selecciónelo en la lista y presione **Editar**. Arriba tiene **Nuevo**, **Atras**, **Eliminar**, **Imprimir** y **Guardar**.
Notas: según la configuración de su empresa, al guardar cambios se puede enviar un WhatsApp al cliente; si no se envía, el pedido igual se guarda y se avisa en pantalla.
<!-- REVISAR: "Escribir una nota" pide en realidad la Factura; confirmar si se unifica el mensaje -->
<!-- REVISAR: una fecha vacia se guarda como 1900-01-01; confirmar como se ve en lista e impresion -->
<!-- REVISAR: las pestanas Referencias y Comentario aparecen vacias en esta pantalla -->

## Registrar una zona de cliente
Ruta: Menú > ADM TALLER > ZONA DE CLIENTE. La lista se llama **LIST ZONA DE CLIENTE**.
1. Presione **Nuevo**.
2. En **Informacion**, tarjeta **Documento**: revise **Fecha** (arranca en hoy), **Sucursal** y **Estado**. Al crear solo se ofrece el estado inicial.
3. En **Personas** elija con **Buscar** **Cliente** y **Tecnico** (**Limpiar** borra lo elegido).
4. En **Datos del registro** complete **Numero**, **Estado del equipo**, **Articulo**, **Marca**, **Modelo**, **Serie**, **Accesorios**, **Tipo** y **Cotizacion**; con **Buscar** elija **Factura** si aplica.
5. En **Detalle** complete **Moneda**, **Problema**, **Nota**, **Contraseña**, **Solucion** y **Monto**.
6. Opcional: en **Referencias** escriba la **Descripción**, elija la **Situación** y presione **Agregar**; en **Archivos** adjunte documentos.
7. Presione **Guardar** (en el teléfono, en la barra inferior).
Mensajes de validación: "Establecer Fecha al Documento" y "Seleccionar cliente". Al modificar también se exige el técnico: "Seleccionar tecnico". Según la configuración de su empresa pueden pedirse otros campos, y los nombres y campos visibles pueden cambiar.
Para modificarla, selecciónela en la lista y presione **Editar**. Arriba tiene **Nuevo**, **Atras**, **Eliminar**, **Auditoria**, **Imprimir E.**, **Imprimir S.** y **Guardar**. **Auditoria** muestra el historial de cambios: fecha, usuario, campo, valor anterior y valor nuevo.
<!-- REVISAR: para que usa el negocio la zona de cliente; la pantalla se basa en la de taller -->
<!-- REVISAR: Tecnico es opcional al crear y obligatorio al modificar; confirmar si es intencional -->
<!-- REVISAR: la pestana Comentario aparece vacia en esta pantalla -->

## Registrar una garantía
Ruta: Menú > ADM TALLER > GARANTIA. La lista se llama **LISTA DE GARANTIAS**.
1. Presione **Nuevo**.
2. En la tarjeta **Documento** indique **Fecha**, **Sucursal** y **Status de Garantia**. Según la configuración de su empresa también ve **Aplicado**, **Cambio** y **Estado**.
3. En **Personas y factura** elija con **Buscar** el **Cliente**, el **Tecnico** y la **Factura** (**Limpiar** borra lo elegido). Elegir la factura no llena el cliente.
4. En **Articulo** complete **Articulo**, **Sello**, **Modelo** y **Serie**.
5. En **Detalle de la garantia** complete **Deja cargador**, **Problema** y **Solucion**. Según la configuración también ve **Moneda** y **Monto**.
6. Presione **Guardar** (en el teléfono, en la barra inferior). La garantía queda abierta en edición.
Mensajes de validación: "Establecer Fecha al Documento", "Seleccionar factura", "Seleccionar cliente" y "Seleccionar tecnico".
Para modificarla: seleccione la fila y presione **Editar**, o escriba en **Buscar por número…** y presione la lupa. Arriba tiene **Nuevo**, **Atras**, **Eliminar**, **Imprimir**, **Imprimir S.**, **Imprimir Sticker** y **Guardar**. En edición, el encabezado dice **INGRESO** y **Articulo** se llama **Producto**. La pestaña **Archivos** abre los archivos del documento.
Notas: según la configuración, al guardar se envía un WhatsApp al cliente. Si aparece "Garantia guardado correctamente, WhatsApp no enviado...", la garantía sí se guardó. Con el ciclo contable cerrado no se puede crear, modificar ni eliminar.
<!-- REVISAR: Eliminar parece rechazar los estados marcados como eliminables (condición invertida) -->
<!-- REVISAR: al crear con Articulo "Otros" y WhatsApp activo puede fallar el texto del mensaje -->
Ver también: Registrar una orden de compra.

## Registrar una devolución de compra a proveedor
Ruta: Menú > ADM TALLER > DEV. COMPRA. La lista se llama **DEVOLUCION DE COMPRA**.
1. Presione **Nuevo**.
2. En **Datos del documento** indique **Fecha** y elija con **Buscar** el **Proveedor** (**Limpiar** lo borra).
3. En **Estado y bodega** revise **Estado** y **Bodega**: vienen con una opción ya elegida.
4. Opcional: pestaña **Referencias** (**Referencia1**, **Referencia2** y **Referencia3**) y pestaña **Comentario** (**Nota**).
5. En la tarjeta **Detalle** presione **Agregar**, elija el producto y escriba la **Cantidad**. Repita por cada producto.
6. Para quitar un producto, marque su casilla (en el teléfono, **Quitar**) y presione **Eliminar** de esa tarjeta.
7. El botón para terminar es **Guardar** (en el teléfono, en la barra inferior).
Mensajes de validación: "Seleccione un proveedor" (también al presionar **Agregar** sin proveedor), "Establecer Estado", "Escriba la Fecha del Documento", "La cantidad de cada producto debe ser mayor que 0" y "El Item ya esta agregado".
Para modificarla: seleccione la fila y presione **Editar**, o use **Buscar por número…** y la lupa. Arriba tiene **Atras**, **Eliminar**, **Imprimir** y **Guardar**. Lo que puede cambiar o eliminar depende del estado.
Notas: al pasar de REGISTRADA a APLICADA, los productos salen del inventario de la **Bodega**. Si la cantidad supera la existencia: "La cantidad de ... es mayor que la disponible en bodega". Con el ciclo contable cerrado no se puede modificar.
Ver también: Registrar una orden de compra.

## Registrar una orden de compra
Ruta: Menú > ADM TALLER > ORD. COMPRA. La lista se llama **ORDEN DE COMPRA**.
1. Presione **Nuevo**.
2. En **Datos del documento** indique **Fecha** y elija con **Buscar** el **Proveedor** (**Limpiar** lo borra).
3. En la tarjeta **Estado** revise **Estado**: viene con una opción ya elegida.
4. Opcional: pestaña **Referencias** (**Referencia1**, **Referencia2** y **Referencia3**) y pestaña **Comentario** (**Nota**).
5. En la tarjeta **Detalle** presione **Agregar**, elija el producto y escriba la **Cantidad**. La búsqueda usa el proveedor elegido. Repita por cada producto.
6. Para quitar un producto, marque su casilla (en el teléfono, **Quitar**) y presione **Eliminar** de esa tarjeta.
7. Presione **Guardar** (en el teléfono, en la barra inferior). La orden queda abierta en edición.
Mensajes de validación: "Seleccione un proveedor" (también al presionar **Agregar** sin proveedor), "Establecer Estado", "Escriba la Fecha del Documento" y "El Item ya esta agregado". Al guardar en edición, sin productos: "Agregar el Detalle del Documento".
Para modificarla: seleccione la fila y presione **Editar**, o escriba en **Buscar por número…** y presione la lupa. Arriba tiene **Atras**, **Eliminar**, **Imprimir** y **Guardar**. **Eliminar** pide confirmar "Desea eliminar este Registro...". La pestaña **Archivos** abre los archivos del documento. Lo que puede cambiar o eliminar depende del estado.
Notas: la orden de compra no mueve el inventario.
Aviso: en edición aparecen **Archivo a importar** y **Importar**, pero hoy ese botón no realiza ninguna acción.
<!-- REVISAR: nombres de los estados; Importar no tiene acción en la pantalla -->
Ver también: Registrar una compra de mercadería, Registrar una devolución de compra a proveedor.

## Qué contiene el menú CXC
Ruta: Menú > CXC. Aquí se administran los clientes y sus créditos.
- **DOCUMENTOS**: consultar o ajustar los documentos de crédito de los clientes.
- **BURO CREDITO**: consultar el récord de crédito de una persona.
- **LINEA D. CREDITO**: catálogo de líneas de crédito.
- **NOTA D. CREDITO**: al aplicarla suma el monto al saldo del cliente.
- **NOTA D. DEBITO**: al aplicarla resta el monto del saldo del cliente.
- **CLIENTES**: catálogo de clientes.
- **PROCESOS**: pantalla **Procesos CxC**. En **Subir carga SIN Riesgo**, **Aceptar** ejecuta la carga en una pestaña nueva.
- **REPORTES**: reportes de cuentas por cobrar.
Lo que ve cada usuario depende de los permisos de su rol.
Notas: en el teléfono el botón **Guardar** está en la barra inferior, y los botones **Buscar** y **Agregar** de los formularios abren una ventana dentro de la misma pantalla. Los montos se muestran con formato 1,500.25.
Ver también: Crear un cliente; Crear una línea de crédito; Consultar o ajustar documentos de crédito de un cliente; Consultar el buró de crédito de un cliente.
<!-- REVISAR: en DOCUMENTOS, CLIENTES y LINEA D. CREDITO la caja de busqueda de la lista no tiene accion programada; no se documenta como funcional -->

## Consultar o ajustar documentos de crédito de un cliente
Ruta: Menú > CXC > DOCUMENTOS. La lista se llama **LISTA DE DOCUMENTOS** y solo tiene **Vistas** y **Editar**: aquí no se crean ni eliminan documentos.
1. Seleccione la fila y presione **Editar** (sin selección aparece "Seleccionar el Registro...").
2. Pestaña **ENTIDAD**: datos del cliente, **Balance US$** y, en **DETALLES DE LINEA DE CREDITO**, el **Balance** de cada línea.
3. Pestaña **Documento**: **Numero** y **Monto Inicial** no se editan. Puede cambiar **Saldo**, **Estado** y **Fecha**. En **Entidades relacionadas** use **Agregar** o marque la fila y use **Editar** o **Eliminar**; **Mostrar** elige las columnas visibles. En la ventana de la entidad complete los datos y presione **Aceptar**.
4. Pestaña **Amortizacion**: en cada cuota puede cambiar **Fecha**, **Remanente**, **Dias Atrazados**, **Nota**, **Estado** y **Abono al Capital**.
5. Presione **Guardar** (en el teléfono, en la barra inferior).
Mensaje de validación: "Ingresar Balance".
Notas:
- Los valores se guardan tal como los escribe: no registran un abono ni recalculan las demás cuotas. Para registrar un pago use la sección de abonos.
- Si el balance del cliente o de una línea supera su límite, se guarda igual al límite.
- El cambio de **Moneda** no se guarda.
- Lo agregado, cambiado o eliminado en **Entidades relacionadas** se aplica al presionar **Guardar**.
Ver también: Registrar un abono de un cliente; Abono al capital.
<!-- REVISAR: la validacion "Escribir Saldo" revisa un campo que no existe en la pantalla, nunca se dispara. El filtro de fecha y Buscar de la lista no tienen accion programada. Falta confirmar desde que operaciones se crean los documentos -->

## Consultar el buró de crédito de un cliente
Ruta: Menú > CXC > BURO CREDITO. La pantalla se llama **Consultar Record de Credito Historico y Actual**. El uso depende de la configuración de su empresa.
1. En la caja de búsqueda escriba la identificación (formato 000-000000-0000X) y presione el botón de la lupa.
2. Revise las pestañas:
   - **Informacion General.**: **Identificación del Cliente**, **Listado de Telefonos** y **Lista de Direcciones del cliente**.
   - **Historial de Creditos.**: **Detalle de operaciones de credito** y **Detalle de operaciones de creditos cancelados**, con montos, saldo, mora y cuota.
   - **Consultas.**: fechas y entidades donde el cliente ha solicitado crédito.
3. **Imprimir** abre el reporte de la consulta en otra pestaña. **Atras** vuelve a la lista de clientes. **Regresar al cliente** aparece según la configuración de su empresa.
Arriba se muestra **ULTIMA CONSULTA** con su fecha y el nombre del archivo.
Restricciones: cada búsqueda por identificación consume una consulta del cupo de su empresa. Al agotarse aparece "NO PUEDE SEGUIR CONSULTADO, CREDITOS AGOTADOS".
Desde el cliente: según la configuración de su empresa, al editarlo tiene **Acciones** > **001) Ir a Buro** y, en la pestaña **Informacion**, **Ver Buro** con las consultas anteriores.
Ver también: Crear un cliente.

## Crear una línea de crédito
Ruta: Menú > CXC > LINEA D. CREDITO. La lista se llama **LISTA DE LINEA DE CREDITO**.
Aquí se crea el nombre de la línea. El límite, el interés y el plazo se configuran en cada cliente.
1. Presione **Nuevo**. Se abre **Agregar Linea de Credito**.
2. En la tarjeta **Datos de la línea** escriba **Nombre** (obligatorio) y, si desea, **Descripcion**.
3. Presione **Guardar** (en el teléfono, en la barra inferior). La línea queda abierta en edición y disponible en el campo **Linea** de la pestaña **CXC Lineas.** del cliente.
Para modificarla, selecciónela en la lista y presione **Editar** (se abre **Editar Linea de Credito**). **Codigo** no se puede cambiar. La descripción aparece en edición con el nombre **Direccion**.
Para eliminarla, presione **Eliminar** en la lista o en la edición y confirme "Desea eliminar este Registro...". En la lista, sin selección aparece "Seleccionar el Registro...".
Ver también: Crear un cliente.
<!-- REVISAR: eliminar no valida si la linea esta asignada a clientes; confirmar el efecto. La etiqueta "Direccion" en edicion parece un error de texto -->

## Registrar una nota de crédito de cliente
Ruta: Menú > CXC > NOTA D. CREDITO. La lista se llama **LISTA DE NOTAS DE CREDITO DE CLIENTES**.
1. Presione **Nuevo**. Se abre **Nota de crédito**.
2. En la tarjeta **Datos de la nota** revise **Fecha** (arranca en hoy), **Estado** (al crear, el único es REGISTRADO) y **Moneda**. Escriba el **Monto** y elija el **Cliente** con **Buscar** (**Limpiar** borra lo elegido).
3. Opcional, en la tarjeta **Referencias y comentario**: **Referencia 1**, **Referencia 2**, **Referencia 3** y **Comentario**.
4. Presione **Guardar** (en el teléfono, en la barra inferior). La nota queda REGISTRADA y todavía no cambia el saldo del cliente.
5. Para aplicarla, ábrala en edición, cambie **Estado** a APLICADO y presione **Guardar**. Solo la pantalla de edición permite este paso. Al aplicarla, el monto se suma al saldo del cliente en la moneda elegida (**Balance Cordobas** o **Balance Dolares** en su ficha).
Mensajes: "Establecer Fecha al Documento", "El monto no puede ser 0", "El monto no puede ser negativo" y "Seleccione el cliente".
Para modificarla, escriba el número en **Buscar por número…** o seleccione la fila y presione **Editar**. Arriba tiene **Nuevo**, **Atras**, **Eliminar**, **Imprimir** y **Guardar**. En **Mas** > **Archivos** adjunta documentos.
Si el estado no lo permite, verá "EL REGISTRO NO PUEDE SER EDITADO POR SU ESTADO ACTUAL" o "EL REGISTRO NO PUEDE SER ELIMINADO POR SU ESTADO ACTUAL".
Notas: esta pantalla es para clientes; las notas de proveedor están en CXP y las de banco en su propio módulo.
<!-- REVISAR: confirmar si una nota APLICADA admite editarse o eliminarse; eliminar no revierte el saldo del cliente -->
Ver también: Registrar una nota de débito de cliente; Notas de crédito y débito de banco.

## Registrar una nota de débito de cliente
Ruta: Menú > CXC > NOTA D. DEBITO. La lista se llama **LISTA DE NOTAS DE DEBITO DE CLIENTES**.
1. Presione **Nuevo**. Se abre **Nota de débito**.
2. En la tarjeta **Datos de la nota** revise **Fecha** (arranca en hoy), **Estado** (al crear, el único es REGISTRADO) y **Moneda**. Escriba el **Monto** y elija el **Cliente** con **Buscar** (**Limpiar** borra lo elegido).
3. Opcional, en la tarjeta **Referencias y comentario**: **Referencia 1**, **Referencia 2**, **Referencia 3** y **Comentario**.
4. Presione **Guardar** (en el teléfono, en la barra inferior). La nota queda REGISTRADA y todavía no cambia el saldo del cliente.
5. Para aplicarla, ábrala en edición, cambie **Estado** a APLICADO y presione **Guardar**. Solo la pantalla de edición permite este paso. Al aplicarla, el monto se resta del saldo del cliente en la moneda elegida (**Balance Cordobas** o **Balance Dolares** en su ficha).
Mensajes: "Establecer Fecha al Documento", "El monto no puede ser 0", "El monto no puede ser negativo" y "Seleccione el cliente".
Para modificarla, escriba el número en **Buscar por número…** o seleccione la fila y presione **Editar**. Arriba tiene **Nuevo**, **Atras**, **Eliminar**, **Imprimir** y **Guardar**. En **Mas** > **Archivos** adjunta documentos.
Si el estado no lo permite, verá "EL REGISTRO NO PUEDE SER EDITADO POR SU ESTADO ACTUAL" o "EL REGISTRO NO PUEDE SER ELIMINADO POR SU ESTADO ACTUAL".
Notas: esta pantalla es para clientes; las notas de proveedor están en CXP y las de banco en su propio módulo.
<!-- REVISAR: confirmar si una nota APLICADA admite editarse o eliminarse; eliminar no revierte el saldo del cliente -->
Ver también: Registrar una nota de crédito de cliente; Notas de crédito y débito de banco.

## Ejecutar procesos de CxC: descargar el archivo de créditos para SIN Riesgo
Ruta: Menú > CXC > PROCESOS. La pantalla se llama **Procesos CxC**.
1. En la tarjeta **Subir carga SIN Riesgo** presione **Aceptar**.
2. Se abre una pestaña nueva y se descarga un archivo CSV con los datos de los créditos. El nombre incluye la fecha del día, por ejemplo `user_20261105.csv`.
Notas:
- Aunque la pantalla habla de carga, hoy el proceso solo descarga el archivo: no envía los datos a SIN Riesgo. Esa entrega debe hacerse aparte.
- El separador de columnas del archivo depende de la configuración de su empresa.
- Necesita permiso sobre esta opción del menú. Si ocurre un error, el mensaje aparece en la pestaña nueva.
<!-- REVISAR: qué créditos incluye el archivo y cómo se entrega a SIN Riesgo -->

## Registrar un gasto
Ruta: Menú > CXP > GASTOS. La lista se llama **LISTA DE GASTOS**.
1. Presione **Nuevo**. Se abre **Gasto:#**.
2. En **Informacion**, tarjeta **Datos del documento**: **Fecha** (arranca en hoy), **Sucursal**, **Estado** y, si quiere, las referencias. **Cambio** y **Aplicado** no se cambian a mano.
3. En la tarjeta **Clasificación del gasto** elija **Tipo**, **Categoria** (cambia según el **Tipo**) y **Clasificación**.
4. En **Referencias**, tarjeta **Montos**: **Moneda**, **Monto** (por ejemplo 1,500.25) e **IVA**. **Total** (Monto + IVA) se calcula solo.
5. Opcional, tarjeta **Proveedor y factura**: con **Buscar** elija **Proveedor** y luego **Factura**, que muestra su saldo pendiente. Cambiar la **Moneda** borra la factura elegida.
6. Opcional: en **Comentario** escriba la **Descripcion**.
7. Presione **Guardar** (en el teléfono, en la barra inferior).
Según la configuración de su empresa, las referencias pueden tener otro nombre, algunos campos pueden ocultarse o los montos pueden aparecer en **Informacion**.
Mensajes: "Establecer Fecha al Documento", "El monto no puede ser 0", "Categoria de Gasto es obligatorio", "Seleccione el proveedor" y, con el ciclo contable cerrado, "EL DOCUMENTO NO PUEDE INGRESAR, EL CICLO CONTABLE ESTA CERRADO".
Al pasar **Estado** a APLICADO en la edición, si eligió **Factura** el **Total** se abona a esa factura.
Para modificarlo, use **Editar**. Arriba: **Nuevo**, **Atras**, **Eliminar**, **Imprimir** y **Guardar**; **Archivos** adjunta documentos. Un gasto aplicado con proveedor solo se elimina si es el último de ese proveedor: "SOLO PUEDE ELIMINAR EL ULTIMO GASTO DEL CLIENTE...".
<!-- REVISAR: efecto contable de aplicar -->
Ver también: Crear un proveedor.

## Qué contiene el menú CXP
Ruta: Menú > CXP (cuentas por pagar).
- **RETENCION**: retenciones aplicadas a facturas de proveedores.
- **PROVEEDORES**: catálogo de proveedores.
- **NOTA D. DEBITO**: notas de débito de proveedor; al aplicarlas restan del saldo del proveedor.
- **NOTA D. CREDITO**: notas de crédito de proveedor; al aplicarlas suman al saldo del proveedor.
- **GASTOS**: registro de gastos.
Las listas tienen los botones **Vistas**, **Editar**, **Eliminar** y **Nuevo**. Seleccione la fila antes de presionar **Editar** o **Eliminar**; si no lo hace aparece "Seleccionar el Registro...". **Eliminar** pide confirmar "Desea eliminar este Registro...".
Buscar en la lista: en RETENCION, NOTA D. DEBITO, NOTA D. CREDITO y GASTOS escriba en **Buscar por número…** y presione el botón de la lupa o Enter.
<!-- REVISAR: el buscador "Buscar..." de la lista de PROVEEDORES no tiene acción programada en la pantalla; documentarlo cuando funcione -->
Notas: en el teléfono el botón **Guardar** está en la barra inferior y los botones **Buscar** abren la búsqueda en una ventana dentro de la misma pantalla. Lo que ve cada usuario depende de los permisos de su rol.
Ver también: Registrar una retención de proveedor, Crear un proveedor, Registrar una nota de débito de proveedor, Registrar una nota de crédito de proveedor, Registrar un gasto.

## Registrar una retención de proveedor
Ruta: Menú > CXP > RETENCION. La lista se llama **LISTA DE RETENCIONES DE PROVEEDORES**.
1. Presione **Nuevo**. Se abre **Retencion:#**.
2. En la tarjeta **Datos de la retención** indique **Fecha** (arranca en hoy), **Estado** y **Moneda**. En **Proveedor** use **Buscar** (**Limpiar** borra lo elegido). Escriba el número en **Factura** y elija el **Impuesto**.
3. En la tarjeta **Montos** escriba **Monto de Factura** (por ejemplo 1,500.25). **Monto de Retencion** se calcula solo con el porcentaje del impuesto y no se escribe a mano.
4. Opcional: en la tarjeta **Referencias y comentario** complete **Referencia 1**, **Referencia 2** y **Comentario**.
5. Presione **Guardar** (en el teléfono, en la barra inferior).
Mensajes de validación: "Establecer Fecha al Documento", "Seleccione el Proveedor", "Establecer el número de Factura", "El monto no puede ser negativo" y "Establecer Monto de la Factura".
Para modificarla, selecciónela en la lista y presione **Editar**. Arriba tiene **Nuevo**, **Atras**, **Eliminar**, **Imprimir** y **Guardar**. En **Mas** > **Archivos** puede adjuntar documentos.
Notas: la retención no cambia el saldo del proveedor. Al crearla solo está disponible el estado inicial; los demás estados se eligen en la edición.
<!-- REVISAR: uso posterior del monto retenido -->
Ver también: Qué contiene el menú CXP.

## Crear un proveedor
Ruta: Menú > CXP > PROVEEDORES. La lista se llama **LISTA DE PROVEEDORES**.
1. Presione **Nuevo**.
2. En **Informacion**, tarjeta **Datos generales**, escriba al menos uno de **Primer Nombre**, **Segundo Nombre**, **Nombre Legal** o **Nombre Comercial**, y **Identificacion**. Tarjeta **Clasificacion**: revise los combos.
3. En **Referencias.**, tarjeta **Ubicacion y balance**, elija **Pais**, **Departamento** y **Municipio**. **Balance Cordobas** y **Balance Dolares** no se escriben a mano.
4. Opcional, con **Agregar**: **Telefonos.**, **Email.** y **CXC Lineas.**. Confirme con **Aceptar**. Para quitar, marque la fila y presione **Eliminar**. En **Mas** está **Direccion**.
5. Presione **Guardar** (en el teléfono, en la barra inferior).
Mensajes de validación: "Seleccionar el Pais", "Seleccionar el Departamento", "Seleccionar el Municipio", "Escribir la Identificacion", "Escribir el Nombre" y "La linea ya esta configurada".
Si no agrega una línea de crédito, se crea una con los valores predeterminados de su empresa.
Para modificarlo, selecciónelo en la lista y presione **Editar**. Arriba tiene **Atras**, **Eliminar** y **Guardar**. En **CXC Lineas.** aparece también **Editar**, y en **Mas** > **Archivos** puede adjuntar documentos. El proveedor predeterminado del sistema no se elimina: "No es posible eliminar el proveedor, edite el nombre".
<!-- REVISAR: el botón Imprimir de la edición no tiene acción programada -->
<!-- REVISAR: significado de Distancia, No. Dias y No. Dias # en la pestaña Referencias. -->
Ver también: Registrar un gasto.

## Registrar una nota de débito de proveedor
Ruta: Menú > CXP > NOTA D. DEBITO. La lista se llama **LISTA DE NOTAS DE DEBITO DE PROVEEDORES**.
1. Presione **Nuevo**. Se abre **Nota de débito:#**.
2. En la tarjeta **Datos de la nota** indique **Fecha** (arranca en hoy), **Estado**, **Moneda** y **Monto** (por ejemplo 1,500.25). En **Proveedor** use **Buscar** (**Limpiar** borra lo elegido).
3. Opcional: en la tarjeta **Referencias y comentario** complete **Referencia 1**, **Referencia 2**, **Referencia 3** y **Comentario**.
4. Presione **Guardar** (en el teléfono, en la barra inferior). Se abre la nota para editarla.
5. Para que afecte el saldo, cambie **Estado** a APLICADO y presione **Guardar**. Al crearla solo está disponible el estado inicial: APLICADO se elige en la edición.
Efecto: al aplicarla, el **Monto** se resta del saldo del proveedor en la moneda elegida (**Balance Cordobas** o **Balance Dolares**).
Mensajes de validación: "Establecer Fecha al Documento", "El monto no puede ser 0", "El monto no puede ser negativo" y "Seleccione el Proveedor".
Para modificarla, selecciónela en la lista y presione **Editar**. Arriba tiene **Nuevo**, **Atras**, **Eliminar**, **Imprimir** y **Guardar**. En **Mas** > **Archivos** puede adjuntar documentos.
<!-- REVISAR: confirmar que una nota APLICADA no se puede guardar otra vez (volvería a mover el saldo) y que eliminarla no devuelve el saldo -->
Ver también: Registrar una nota de crédito de proveedor, Crear un proveedor.

## Registrar una nota de crédito de proveedor
Ruta: Menú > CXP > NOTA D. CREDITO. La lista se llama **LISTA DE NOTAS DE CREDITO DE PROVEEDORES**.
1. Presione **Nuevo**. Se abre **Nota de crédito:#**.
2. En la tarjeta **Datos de la nota** indique **Fecha** (arranca en hoy), **Estado**, **Moneda** y **Monto** (por ejemplo 1,500.25). En **Proveedor** use **Buscar** (**Limpiar** borra lo elegido). Según la configuración de su empresa, **Proveedor** puede aparecer con otro nombre.
3. Opcional: en la tarjeta **Referencias y comentario** complete **Referencia 1**, **Referencia 2**, **Referencia 3** y **Comentario**.
4. Presione **Guardar** (en el teléfono, en la barra inferior). Se abre la nota para editarla.
5. Para que afecte el saldo, cambie **Estado** a APLICADO y presione **Guardar**. Al crearla solo está disponible el estado inicial: APLICADO se elige en la edición.
Efecto: al aplicarla, el **Monto** se suma al saldo del proveedor en la moneda elegida (**Balance Cordobas** o **Balance Dolares**).
Mensajes de validación: "Establecer Fecha al Documento", "Debe ingresar un monto", "El monto no puede ser 0", "El monto no puede ser negativo" y "Seleccione el proveedor".
Para modificarla, selecciónela en la lista y presione **Editar**. Arriba tiene **Nuevo**, **Atras**, **Eliminar**, **Imprimir** y **Guardar**. En **Mas** > **Archivos** puede adjuntar documentos.
<!-- REVISAR: confirmar que una nota APLICADA no se puede guardar otra vez (volvería a mover el saldo) y que eliminarla no devuelve el saldo -->
Ver también: Registrar una nota de débito de proveedor, Crear un proveedor.

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
Ver también: Registrar la asistencia de un colaborador.

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
Ver también: Crear un reporte con el Constructor de Reportes (Builder Report).

## Reportes de inventario
Ruta: Menú > INVENTARIO > REPORTES.
Lista de productos, existencias por bodega, kardex, compras, productos por transacción.
Ver también: Crear un reporte con el Constructor de Reportes (Builder Report).

## Reportes de cuentas por cobrar y cobranza
Ruta: Menú > CXC > REPORTES y COBRANZA > REPORTES.
Cartera de clientes, documentos de crédito, pagos, estado de cuenta, clientes con mora.
Ver también: Asignar clientes a un gestor de cobranza; Crear un reporte con el Constructor de Reportes (Builder Report).

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
Ver también: Modificar, eliminar o imprimir un evento del calendario, Crear una tarea para un colaborador.

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
Botones de la lista: **Herramienta** (abre la herramienta de tipo de cambio) y **Archivos** (subir el archivo de tasas de cambio en la ventana **Documentos**).
Ver también: Actualizar el tipo de cambio (herramienta de monedas); Adjuntar, subir o ver archivos de un documento (botón Archivos).

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
Seleccione la fila de la lista y presione **Editar** para abrir un registro; si no selecciona una aparece "Seleccionar el Registro...". Para crear uno presione **Nuevo**. En ADELANTOS, LIQUIDACION y PERMISO, **Eliminar** pide confirmar "Desea eliminar este Registro...". P. PLANILLA no tiene botón **Eliminar** en la lista.
Buscar en la lista:
- En P. PLANILLA, **Buscar en la lista…** filtra la lista cargada mientras escribe.
- En ADELANTOS, LIQUIDACION y PERMISO escriba en **Buscar por número…** y presione el botón de la lupa o Enter.
Al editar, las cuatro pantallas tienen arriba solo **Atrás** y **Guardar**.
Notas: en el teléfono el botón **Guardar** está en la barra inferior y los botones **Buscar** abren la búsqueda en una ventana dentro de la misma pantalla. Lo que ve cada usuario depende de los permisos de su rol.
Ver también: Crear una planilla de pago, Registrar adelantos a colaboradores, Registrar la liquidación de un colaborador, Registrar un permiso de un colaborador.

## Crear una planilla de pago
Ruta: Menú > PLANILLA > P. PLANILLA. La lista se llama **LISTA DE PLANILLAS**.
1. Presione **Nuevo**. Se abre **PLANILLA:#**.
2. En la tarjeta **Datos de la planilla** indique **Ciclo**, **Quincena**, **Nombre** y **Estado**.
3. En la tarjeta **Clasificación** indique **Tipo** y **Moneda**.
4. Opcional: en la tarjeta **Comentario** escriba la **Descripción**.
5. En la tarjeta **Colaboradores** presione **Agregar colaborador** y elíjalo en la búsqueda. Repita por cada colaborador. En el teléfono cada colaborador se ve como una tarjeta.
6. Complete en cada colaborador **+Comisión**, **+Bono Quincenal**, **-Adelantos**, **-Deducciones Por Préstamos** y **-Deducciones Por Llegada Tarde**. **Vacaciones Acumuladas**, **INSS Patronal**, **+Salario**, **INSS**, **Ahorro**, **IR** y **Salario Neto** no se escriben a mano.
7. Revise el **Total** en la tarjeta **Resumen** y presione **Guardar** (en el teléfono, en la barra inferior).
Quitar un colaborador: marque su casilla (en el teléfono, la casilla **Quitar**) y presione **Quitar marcados**.
No se puede guardar una planilla sin colaboradores.
Mensajes de validación: "Ingrese el Nombre", "Agregue al menos un colaborador" y "El Colaborador ya existe en el detalle".
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
No se puede guardar un adelanto sin colaboradores ni con un **Monto** en 0.
Mensajes de validación: "Agregue al menos un colaborador", "Ingrese el monto de cada colaborador" y "El Colaborador ya existe en el detalle".
Para modificarlo, selecciónelo en la lista y presione **Editar**.
<!-- REVISAR: uso de Ciclo y Tipo; si el adelanto se descuenta en la planilla y cuándo; efecto de pasar el Estado a APLICADO -->
Ver también: Crear una planilla de pago.

## Registrar la liquidación de un colaborador
Ruta: Menú > PLANILLA > LIQUIDACION. La lista se llama **LISTA DE LIQUIDACIÓN**.
1. Presione **Nuevo**. Se abre **LIQUIDACIÓN:#**.
2. En la tarjeta **Colaborador**, en **Colaborador** use **Buscar** y **Limpiar**; el campo muestra el código y el nombre. Se muestran también **Código**, **Nombre Completo** y **Área**. Indique el **Estado**. **Liquidación Neta** se muestra y no se escribe a mano.
3. En la tarjeta **Trabajo** indique **Fecha de Fin**. **Fecha de Inicio**, **Años Laborados**, **Salario Devengado** y **Vacaciones Acumuladas** no se escriben a mano.
4. En la tarjeta **Ingresos** complete **Comisión** y **Bono**. **Salario Proporcional**, **Ahorro Acumulado**, **Pago Vacaciones Acumuladas**, **Pago por mes 13** y **Pago por Antigüedad** no se escriben a mano.
5. En la tarjeta **Deducciones** complete **Préstamos**, **Llegadas Tarde** y **Adelantos**. **INSS**, **IR** e **IR Por Vacaciones** no se escriben a mano.
6. Presione **Guardar** (en el teléfono, en la barra inferior).
Mensajes de validación: "Seleccione el colaborador", y "El dato ... no es una entrada Válida" si un monto no es válido.
Para modificarla, selecciónela en la lista y presione **Editar**.
<!-- REVISAR: cómo se calculan los montos y la Liquidación Neta, y su base legal; efecto de pasar el Estado a APLICADO -->

## Registrar un permiso de un colaborador
Ruta: Menú > PLANILLA > PERMISO. La lista se llama **LISTA DE PERMISOS**.
1. Presione **Nuevo**. Se abre **PERMISO:#**.
2. En la tarjeta **Colaborador**, en **Colaborador** use **Buscar** y **Limpiar**; el campo muestra el código y el nombre. Se muestran también **Código**, **Nombre Completo** y **Área**.
3. En la tarjeta **Detalles del permiso** indique **Tipo de Permiso**, **Fecha de Inicio**, **Fecha de Fin**, **Fecha de Integración** y **Estado**. **Días** no se escribe a mano.
4. Opcional: escriba la **Nota**. Según la configuración de su empresa este campo puede tener otro nombre.
5. Presione **Guardar** (en el teléfono, en la barra inferior).
Mensajes de validación:
- "Seleccione el colaborador".
- "La Fecha Final del Permiso Está Mal Configurada".
- "La Fecha de Integración del Colaborador Está Mal Configurada".
Para modificarlo, selecciónelo en la lista y presione **Editar**.
<!-- REVISAR: opciones de Tipo de Permiso; efecto en Días; significado de Fecha de Integración; efecto de pasar el Estado a APLICADO -->

## Qué contiene el menú de colaboradores (RRHH)
Ruta: Menú > **COLABORADORES**, **ASIS. COL**, **GPS** y **TASK**.
- **COLABORADORES**: datos de cada colaborador.
- **ASIS. COL**: asistencia de los colaboradores.
- **GPS**: ubicación de los colaboradores en el mapa.
- **TASK**: tareas de los colaboradores, en lista o en tablero Kanban.
En COLABORADORES, ASIS. COL y TASK seleccione la fila de la lista y presione **Editar**; si no selecciona una aparece "Seleccionar el Registro...". Para crear uno presione **Nuevo**. **Eliminar** pide confirmar "Desea eliminar este Registro...".
Buscar en la lista: en ASIS. COL y TASK escriba en **Buscar por número…** y presione el botón de la lupa o Enter. La lista de COLABORADORES no tiene buscador.
Notas: en el teléfono el botón **Guardar** está en la barra inferior y los botones **Buscar** abren la búsqueda en una ventana dentro de la misma pantalla. Lo que ve cada usuario depende de los permisos de su rol.
<!-- REVISAR: menú padre y nombres exactos de las cuatro opciones; no están en el código ni en seeds del repositorio y no se consultó la BD -->
Ver también: Crear un colaborador, Registrar la asistencia de un colaborador, Ver la ubicación de los colaboradores en el mapa (GPS), Crear una tarea para un colaborador.

## Crear un colaborador
Ruta: Menú > COLABORADORES. La lista se llama **LISTA DE COLABORADORES**; según la configuración de su empresa puede tener otro nombre.
1. Presione **Nuevo**. Se abre **CODIGO:#**, con todos los datos en tarjetas en una sola pantalla.
2. En la tarjeta **Datos personales** indique **Primer Nombre**, **Apellidos**, **Tipo Identificacion**, **Identificacion** e **INSS**.
3. En la tarjeta **Su trabajo** indique **Estado**, **Clasificacion**, **Contrato**, **Grado**, **Departamento de la empresa** y **Area**. En **Jefe Inmediato** use **Buscar** y **Limpiar**. **Fecha Inicio** trae la fecha de hoy; cámbiela si hace falta. Indique **Fecha Fin** si corresponde.
4. En la tarjeta **Dónde vive** indique **Pais**, **Departamento del pais**, **Municipio** y **Direccion**.
5. En la tarjeta **Pago y vacaciones** indique **Costo/H**. **Vacaciones Acumuladas** no se escribe a mano.
6. Opcional: en la tarjeta **Referencias** complete **Referencia1** y **Referencia2**. Según la configuración de su empresa, **Referencia1** puede tener otro nombre.
7. Opcional: agregue datos en las tarjetas **Teléfonos** y **Correos**.
8. Presione **Guardar** (en el teléfono, en la barra inferior).
Mensajes de validación:
- "Seleccionar el Pais" / "Seleccionar el Departamento" / "Seleccionar el Municipio".
- "Escribir la Identificacion" / "Escribir el Nombre".
<!-- REVISAR: uso de Clasificacion, Contrato, Grado y Referencia2; cómo se calculan las Vacaciones Acumuladas -->
Ver también: Agregar teléfonos y correos de un colaborador, Modificar o eliminar un colaborador.

## Modificar o eliminar un colaborador
Ruta: Menú > COLABORADORES.
1. Seleccione el colaborador en la lista y presione **Editar**. Arriba tiene **Atras**, **Eliminar** y **Guardar**.
2. Cambie los datos en las tarjetas y presione **Guardar** (en el teléfono, en la barra inferior).
Archivos: en la tarjeta **Archivos** presione **Abrir archivos**. Esta tarjeta solo aparece al editar.
Eliminar: use **Eliminar** en la lista o al editar, y confirme "Desea eliminar este Registro...". Si aparece "No es posible eliminar el colaborador, edite el nombre", ese colaborador no se puede eliminar; solo se puede editar.
Según la configuración de su empresa también aparece el botón **Url Menu**.
<!-- REVISAR: uso de Url Menu (datos de acceso al menú digital) y qué colaborador produce el mensaje de que no se puede eliminar -->
Ver también: Crear un colaborador, Agregar teléfonos y correos de un colaborador.

## Agregar teléfonos y correos de un colaborador
Ruta: Menú > COLABORADORES > Nuevo o Editar.
Agregar un teléfono:
1. En la tarjeta **Teléfonos** presione **Agregar**. Se abre **Agregar teléfono**.
2. Indique **Tipo** y **Telefono**, y marque **Es Primario** si corresponde.
3. Presione **Aceptar** (o **Cancelar** para salir sin agregarlo).
Agregar un correo:
1. En la tarjeta **Correos** presione **Agregar**. Se abre **Agregar correo**.
2. Escriba el **E-Mail** y marque **Es Primario** si corresponde.
3. Presione **Aceptar** (o **Cancelar** para salir sin agregarlo).
Quitar: marque la casilla de la fila y presione **Eliminar** en esa misma tarjeta.
Presione **Guardar** en la pantalla del colaborador para conservar los cambios.
Restricciones: el teléfono y el correo no aceptan vacío. Si los deja en blanco aparece "Escriba el número de teléfono." o "Escriba el correo.".

## Registrar la asistencia de un colaborador
Ruta: Menú > ASIS. COL. La lista se llama **LISTA DE ASISTENCIA COLABORADOR**.
1. Presione **Nuevo**. Se abre **ASISTENCIA:#**.
2. Primero revise la tarjeta **Registro**: **Fecha** (trae la de hoy), **Prioridad** y **Estado**.
3. En la tarjeta **¿Quién?**, en **Colaborador**, presione **Buscar** y elíjalo.
Importante: al crear una asistencia, elegir el colaborador con **Buscar** la guarda de inmediato, sin presionar **Guardar**. La pantalla lo avisa.
Con **Limpiar** borra el colaborador elegido.
Mensajes de validación: "Establecer Fecha al Documento" y "Seleccione el colaborador".
Para modificarla, selecciónela en la lista y presione **Editar**. Arriba tiene **Nuevo**, **Atras**, **Eliminar** y **Guardar**. Al editar, cambiar el colaborador no guarda solo: presione **Guardar** (en el teléfono, en la barra inferior).
Huella: en PC, al crear, se muestra el campo **Huella**; en el teléfono no aparece. Use **Buscar** para elegir al colaborador.
Notas: esta pantalla no tiene **Imprimir**. Eliminar puede no estar permitido según el estado del registro.
<!-- REVISAR: la lectura de huella tiene un fallo conocido sin corregir; no documentarla como operativa hasta confirmarla. Además: uso de Prioridad y efecto de cada Estado -->
Ver también: Registrar la asistencia de un cliente.

## Ver la ubicación de los colaboradores en el mapa (GPS)
Ruta: Menú > GPS. La pantalla se llama **Google Maps**.
1. En la tarjeta **Filtro de búsqueda** elija la **Compañia** y el **Colaborador**, o déjelos en TODAS para ver todos. Al cambiar la compañía se actualiza la lista de colaboradores.
2. Presione **Ver mapa**.
3. En la tarjeta **Ubicaciones** vea la cantidad de ubicaciones y el mapa con un marcador por cada una. Al pasar el cursor por un marcador se muestran el nombre, la compañía y la fecha.
Mientras la pantalla está abierta, los marcadores se actualizan cada 10 segundos.
Mensajes:
- "No hay ubicaciones para este filtro.": se muestra este aviso en lugar del mapa.
- "Completar los Parametros".
<!-- REVISAR: de dónde sale cada ubicación (aplicación móvil) y qué debe hacer el colaborador para que se registre; qué compañías debe ver cada usuario -->
Ver también: Qué contiene el menú de colaboradores (RRHH).

## Crear una tarea para un colaborador
Ruta: Menú > TASK. La lista se llama **LISTA DE TAREAS**.
1. Presione **Nuevo**. Se abre **TAREA:#**.
2. En la tarjeta **La tarea** escriba el **Titulo** y la **Descripcion**.
3. En la tarjeta **Personas**, en **Emisor** y en **Asignado**, use **Buscar** y **Limpiar**.
4. En la tarjeta **Fechas y estado** revise **Fecha registro** y **Fecha de entrega** (traen la de hoy) e indique **Estado**, **Prioridad** y **Categoria**.
5. Opcional: en la tarjeta **Seguimiento** escriba la **Descripción**, elija la **Situación** y presione **Agregar**. Repita por cada comentario.
6. Opcional: en la tarjeta **Comentario** escriba el **Comentario**.
7. Opcional: en la tarjeta **Más datos** complete **Referencia 1**, **Despues de** y **Orden**.
8. Presione **Guardar** (en el teléfono, en la barra inferior).
Mensajes de validación:
- "Establecer un titulo para la tarea" / "Establecer Fecha al Documento".
- "Seleccione el emisor de la tarea" / "Seleccione a quién asignará la tarea".
- "Este campo no puede estar vacío": al presionar **Agregar** en **Seguimiento** sin descripción.
<!-- REVISAR: significado de Despues de y de Referencia 1; opciones de Situación; efecto de cada Estado -->
Ver también: Modificar o eliminar una tarea, Ver las tareas en el tablero Kanban, Programar una tarea o visita en el calendario.

## Modificar o eliminar una tarea
Ruta: Menú > TASK.
1. Seleccione la tarea en la lista y presione **Editar**. Se abre en otra pestaña. Arriba tiene **Nuevo**, **Atras**, **Eliminar** y **Guardar**.
2. Cambie los datos en las tarjetas y presione **Guardar** (en el teléfono, en la barra inferior).
Comentarios: en la tarjeta **Seguimiento** agregue filas con **Agregar** y quítelas con el botón **Quitar** de cada fila.
Restricciones: si quita todos los comentarios y guarda, los anteriores no se borran. La pantalla lo avisa: "Si guarda sin ninguna fila, los comentarios anteriores no se borran. Para quitar un comentario, deje al menos otra fila."
Archivos: en la tarjeta **Archivos** presione **Abrir archivos**. Esta tarjeta solo aparece al editar.
Eliminar: use **Eliminar** en la lista o al editar, y confirme "Desea eliminar este Registro...". Puede no estar permitido según el estado de la tarea.
<!-- REVISAR: en qué estados se permite eliminar; depende del flujo configurado en la BD -->
Ver también: Crear una tarea para un colaborador, Ver las tareas en el tablero Kanban.

## Ver las tareas en el tablero Kanban
Ruta: Menú > TASK > botón **Kanban**. Se abre **Tablero Kanban**, con una columna por responsable.
Cada tarea muestra título, descripción, **Prioridad**, **Entrega**, **Estado**, **Orden**, el botón **Editar** y el número de documento. El número es solo una etiqueta, no un botón.
Reordenar: solo se puede dentro de la misma columna.
- En PC, arrastre la tarea a su nueva posición.
- En teléfonos y pantallas táctiles, use **Subir** y **Bajar**.
No se puede mover a otro responsable: al soltarla en otra columna aparece "No se puede mover la tarea a otro responsable. Cambie el asignado desde Editar."
Otros botones:
- **Filtrar por Responsable**: muestra solo la columna de esa persona.
- **Agregar Tarea**: abre en otra pestaña la pantalla normal para crear la tarea.
- **Editar**: abre en otra pestaña la pantalla normal de esa tarea.
- **Lista Tarea**: vuelve a **LISTA DE TAREAS**.
- **Actualizar**: vuelve a cargar el tablero.
Mensajes: "No hay tareas pendientes.", "No hay tareas asignadas a ..." y "No se pudo guardar el orden."
<!-- REVISAR: qué tareas considera el tablero (por ejemplo, si excluye las terminadas) -->
Ver también: Crear una tarea para un colaborador, Modificar o eliminar una tarea.

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
2. Marque **Recordarme** si quiere que posMe guarde su usuario y su contraseña en ese equipo (se guardan cifrados durante 30 días).
3. Presione **Ingresar**.
Notas: si posMe pide **Permiso de ubicación requerido**, debe permitir la ubicación en el navegador para poder entrar. Si los datos son incorrectos se muestra un mensaje y no se ingresa. Si no tiene usuario, pídalo al administrador de su empresa.
Con **Recordarme** activo, la próxima vez el usuario aparece escrito y la contraseña figura como **Guardada**: solo presione **Ingresar**. La contraseña guardada no se puede ver con el ojo. Para usar otra contraseña, bórrela y escriba la nueva; para que posMe deje de recordarlo, desmarque **Recordarme** e ingrese. No use **Recordarme** en equipos compartidos.
Para pagar la suscripción, active **Pagar suscripción**, elija la **Cantidad de meses** y presione **Pagar**.

## Olvidé mi contraseña (reenviar contraseña)
Ruta: pantalla de inicio de posMe > **¿Olvidaste tu contraseña?**
1. En la pantalla de inicio presione **¿Olvidaste tu contraseña?**.
2. En la ventana **Recuperar contraseña** escriba el correo registrado en su usuario y presione **Enviar**.
3. Cuando aparezca **Revisa tu correo**, abra el mensaje: trae su usuario, su contraseña y el botón **Iniciar sesión**.
Si no llega en unos minutos, revise la carpeta de spam. Si el correo no está registrado, posMe lo indica. Si aun así no recibe el correo, pida al administrador de su empresa que le asigne una nueva contraseña en SEGURIDAD > USUARIOS.

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
5. **Administrador de compañía**: elija **No** o **Sí** (solo aparece si su propio rol es administrador o administrador de compañía; ver la sección siguiente).
6. Defina los **Permisos por pantalla** y las **Autorizaciones** (ver las secciones siguientes).
7. Presione **Guardar**.
Para modificar un rol, ábralo en la lista, cambie lo necesario y presione **Guardar**. El **Tipo de app** se conserva tal como está guardado.
Notas: los cambios en el menú se ven cuando el usuario vuelve a iniciar sesión.

## Marcar un rol como administrador de compañía
Ruta: Menú > SEGURIDAD > ROLES > **Nuevo** o abrir el rol.
El campo **Administrador de compañía** identifica a los roles que administran la empresa. posMe lo usa en otras funciones del sistema para reconocer a esos administradores; por ejemplo, solo ellos pueden compartir consultas del Constructor de Reportes **Para todos los usuarios**.
1. En **Datos del rol** busque **Administrador de compañía**.
2. Elija **Sí** para marcar el rol como administrador de compañía, o **No** para quitarlo.
3. Presione **Guardar**.
Restricciones: el campo solo lo ve y lo puede cambiar un usuario cuyo propio rol sea administrador o administrador de compañía. Si otro usuario edita el rol, el valor que ya tenía se conserva.
Notas: marcar un rol como administrador de compañía no le da acceso a pantallas; el acceso se sigue definiendo en **Permisos por pantalla**.
Ver también: Crear un rol; Permisos por pantalla de un rol; Compartir una consulta de reporte con todos los usuarios.

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
Notas: los niveles y el acceso se guardan exactamente como los eligió y se muestran igual al volver a abrir el rol, aunque tenga cientos de pantallas. Los permisos de pantallas que no aparecen en la lista (por ejemplo, módulos que su empresa no tiene) se conservan al guardar. Si el rol es administrador tiene acceso a todo y estos permisos no se aplican. Si edita su propio rol y le quita el acceso a ROLES, perderá acceso a esa pantalla.

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

## Despliegue remoto: qué es y quién puede usarlo (administración del sistema)
Ruta: Administración del sistema > Despliegue remoto. No aparece en el menú: se abre escribiendo core_merge/deploy después de la dirección de posMe.
La pantalla **Sincronización de instalación remota** actualiza la instalación de posMe de un cliente (VPS u hosting compartido) desde la instalación local de posMe. Sincroniza tres partes que se eligen por separado:
- **Archivos de la aplicación** (paquete ZIP).
- **Estructura de la base de datos** (tablas, columnas, índices, restricciones, vistas, funciones, procedimientos y triggers).
- **Datos de configuración** (tablas de configuración y scripts de parámetros).
La pantalla tiene tres pestañas: **Sincronización** (lo anterior), **Limpieza de servidor** (liberar espacio) y **Configuración y logos** (editar los archivos de app/Config y cargar los logos de la compañía en el servidor del cliente).
Restricciones: es una herramienta técnica solo para el administrador de posMe, no para el usuario del negocio. Requiere un usuario con rol administrador y que la instalación local tenga habilitada la consola (constante DEPLOY_CONSOLE_ENABLED de la configuración local). En las instalaciones de los clientes está apagada. Solo se permite un despliegue a la vez por compañía.
Ver también: Despliegue remoto: preparar la consola y el agente (primera vez); Despliegue remoto: errores frecuentes; Despliegue remoto: editar la configuración (app/Config); Despliegue remoto: cargar los logos.

## Despliegue remoto: preparar la consola y el agente (primera vez)
Ruta: Administración del sistema > Despliegue remoto (core_merge/deploy).
Una sola vez en la instalación local:
1. Cree la tabla tb_deploy_target en la base de datos local (su definición está en public/resource/file_kiro_instruction/posme_bd_structure.sql).
2. Habilite la consola con la constante DEPLOY_CONSOLE_ENABLED de la configuración local.
Por cada cliente:
3. Elija la compañía, complete la **Conexión remota** y presione **Guardar conexión**.
4. En la pestaña **Agente** presione **Generar secreto**. El secreto se muestra **una sola vez**: cópielo antes de cerrar la ventana. Si genera otro, el agente del cliente deja de aceptar la consola hasta que se instale el nuevo.
5. Instale el agente en el servidor del cliente de una de estas formas:
   - Con **Instalar agente por SFTP/FTP** y luego **Instalar**: sube writable/posme_agent/agent_config.php (con el secreto) y su protección. Marque **Subir también el código del agente** si el cliente aún no lo tiene.
   - A mano: cree en el servidor el archivo writable/posme_agent/agent_config.php con el contenido que muestra la ventana del secreto.
6. Presione **Probar conexiones** para confirmar que el agente responde.
Ver también: Despliegue remoto: elegir la compañía y configurar la conexión.

## Despliegue remoto: elegir la compañía y configurar la conexión
Ruta: Administración del sistema > Despliegue remoto (core_merge/deploy).
1. En **Compañía a sincronizar** elija la compañía. La lista sale de los scripts de public/resource/file_sql/clientes, uno por compañía (no aparecen 001_default ni los scripts -custom-php). La base de datos esperada se toma de la cabecera /*BD: ... */ del script.
2. En **Conexión remota** complete las pestañas:
   - **Agente**: **URL de la instalación remota** (la misma con la que se abre posMe en el navegador; debe usar HTTPS) y el secreto del agente.
   - **SFTP / FTP**: **Protocolo** (**SFTP (recomendado)**, **FTPS (FTP con TLS)**, **FTP sin cifrado** o **No usar**), **Servidor**, **Puerto**, **Usuario**, **Contraseña**, **Directorio raíz remoto** y **Directorio de la aplicación**.
   - **MySQL directo**: en **Ejecución de estructura y datos** deje **Por el agente (recomendado, la BD no se expone)**; use **MySQL directo** solo en un VPS con el puerto abierto.
3. Presione **Guardar conexión**.
4. Presione **Probar conexiones**: muestra el agente, la base esperada contra la real, extensiones, permisos de escritura, espacio libre, despliegues en curso, FTP y MySQL directo.
Notas: las contraseñas se guardan cifradas y nunca se vuelven a mostrar; déjelas vacías para conservar las guardadas. Si el servidor responde con una base de datos distinta a la esperada, el despliegue se bloquea.

## Despliegue remoto: subir y validar el paquete ZIP (actualizar archivos)
Ruta: Administración del sistema > Despliegue remoto (core_merge/deploy).
1. En **Paquete de actualización (ZIP)** elija el **Archivo ZIP con la carpeta app/**. La carpeta app/ puede venir dentro de una carpeta raíz.
2. Presione **Validar paquete**. Si no quiere armar el ZIP a mano, presione **Comprimir en ZIP la carpeta app/**: el sistema comprime la carpeta app/ de este equipo (solo Routes.php y las carpetas autorizadas) y la valida en un solo clic, sin adjuntar archivo.
3. Revise el resultado: cuántos archivos se actualizan, se conservan, se ignoran y se rechazan, los errores de sintaxis PHP y el SHA-256 del paquete.
4. En **Opciones de sincronización** marque **Actualizar archivos de la aplicación**: envía el paquete validado, lo verifica, respalda los archivos que se reemplazan y actualiza solo los autorizados.
Solo se actualizan app/Config/Routes.php y las carpetas app/Controllers, Models, Libraries, Helpers y Views. Se conservan en el cliente app/Config (salvo Routes.php), .env, writable, vendor y public (incluidos js y css).
Restricciones: si hay errores de sintaxis PHP no se puede desplegar. Los archivos con rutas peligrosas se rechazan.
Notas: las tres opciones (archivos, estructura y datos) son casillas independientes; puede marcar solo las que necesite.

## Despliegue remoto: sincronizar la estructura de la base de datos
Ruta: Administración del sistema > Despliegue remoto (core_merge/deploy).
1. En **Opciones de sincronización** marque **Sincronizar estructura de la base de datos**.
2. Presione **Analizar diferencias**. Se muestra la lista de cambios, cada uno con su casilla; los cambios de riesgo quedan desmarcados.
3. Revise el SQL de cada cambio y deje marcados solo los que quiera aplicar.
Notas: nunca elimina tablas ni columnas del destino; los objetos que solo existen en la instalación del cliente no se eliminan. Si la estructura remota ya coincide con la local, se indica que no hay cambios. Al ejecutar, los cambios se aplican en lotes y se detienen en el primer error.
Ver también: Despliegue remoto: ejecutar la sincronización, ver el avance y revertir.

## Despliegue remoto: sincronizar los datos de configuración
Ruta: Administración del sistema > Despliegue remoto (core_merge/deploy).
1. Marque **Sincronizar datos de configuración**.
2. Elija el **Modo** de cada tabla (tablas de los perfiles Navicat 01 y 03):
   - **Completa**: inserta, actualiza y elimina.
   - **Insertar y actualizar**: no elimina.
   - **Solo insertar**: modo por defecto de tb_counter; los contadores nuevos nacen en 0.
   - **No sincronizar**: la tabla no se toca.
3. Botones rápidos: **Todas: completa**, **Todas: sin eliminar** y **Restablecer perfiles**.
4. Marque **Ejecutar scripts de parámetros** para ejecutar 001_default.sql y luego el script de la compañía seleccionada.
5. Presione **Analizar datos**: muestra por tabla las filas a insertar, actualizar y eliminar, y advierte si los scripts tienen sentencias UPDATE sin WHERE.
Orden: tablas, luego 001_default y luego el script de la compañía, todo en una transacción: si algo falla, se revierte todo.
Advertencia: el modo **Completa** reemplaza tb_company_parameter; los ajustes del cliente que no estén en su script se pierden.

## Despliegue remoto: ejecutar la sincronización, ver el avance y revertir
Ruta: Administración del sistema > Despliegue remoto (core_merge/deploy).
1. Revise el **Resumen de operaciones**: lista lo que se hará y lo que falta.
2. Marque **Revisé los cambios**.
3. Presione **Ejecutar sincronización**, escriba el nombre de la compañía para confirmar y presione **Aceptar**. Si el nombre no coincide no se ejecuta nada.
4. En **Ejecución** siga las 8 etapas, con su estado, la barra de progreso y el registro:
   - **Validación de conexiones** y **Preparación del paquete**.
   - **Transferencia**: por bloques, verificados con hash.
   - **Descompresión y verificación**.
   - **Actualización de archivos**: con respaldo de lo reemplazado.
   - **Estructura de BD**: en lotes; se detiene en el primer error.
   - **Datos de configuración** y **Verificación final**.
5. Presione **Informe** para descargar el registro del despliegue.
Para deshacer: **Revertir archivos** o **Revertir datos** restauran el respaldo tomado en ese despliegue.
Restricciones: solo un despliegue a la vez por compañía.

## Despliegue remoto: errores frecuentes
Ruta: Administración del sistema > Despliegue remoto (core_merge/deploy).
- **Firma inválida o vencida**: el secreto guardado en la consola no es el mismo del agente, o el reloj del equipo está desfasado más de 5 minutos. Genere e instale de nuevo el secreto, o corrija la hora del equipo.
- **El agente solo acepta HTTPS**: la URL de la instalación remota debe empezar con https://.
- **Agente no habilitado**: falta el archivo writable/posme_agent/agent_config.php en el servidor del cliente. Instálelo con **Instalar agente por SFTP/FTP** o créelo a mano.
- **La base remota no coincide**: el servidor respondió con una base de datos distinta a la de la cabecera /*BD: ... */ del script de la compañía. Revise la compañía elegida; el despliegue queda bloqueado.
- **Ya hay otro despliegue en curso**: espere a que termine; el candado caduca solo a las 2 horas.
Ver también: Despliegue remoto: preparar la consola y el agente (primera vez).

## Despliegue remoto: limpieza de servidor (liberar espacio e inodos)
Ruta: Administración del sistema > Despliegue remoto (core_merge/deploy) > pestaña **Limpieza de servidor** (entre **Sincronización** y **Configuración y logos**).
1. En **Agente que hará la limpieza** elija la **Compañía con conexión guardada** (con el agente instalado y actualizado). Su agente revisa la carpeta donde está instalado y limpia las instalaciones posMe vecinas del mismo hosting. Para otro hosting, elija una compañía de ese hosting.
2. En **Qué limpiar** deje marcado lo que desea limpiar (todo viene marcado): **Logs**, **Caché**, **Debugbar**, **Uploads**, **Sesiones** (todas: los usuarios deberán volver a iniciar sesión), **Despliegues del agente** (respaldos, staging y log; esos despliegues ya no se podrán revertir) y **Archivos de compañía** (vacía las carpetas component_* de cada compañía: se pierden imágenes, adjuntos, reportes y respaldos .sql; no se puede revertir).
3. Presione **Analizar** (no borra nada). En **Instalaciones del hosting** verá los MB y archivos de cada categoría por instalación; los valores con + son parciales por tiempo. Marque una, varias o todas; las que dicen **despliegue en curso** no se pueden marcar.
4. En **Confirmar limpieza** escriba LIMPIAR, presione **Limpiar** y confírmelo en la ventana.
5. En **Avance** siga la limpieza instalación por instalación; si una tiene muchos archivos sigue por tramos sola. Al terminar presione **Analizar** para ver el espacio actual.
Notas: nunca se borran el código, .env, app/Config, la base de datos, agent_config.php ni los index.html/.htaccess de protección. Reemplaza a la antigua URL core_merge/merge_of_posme_merge_to_posme_initialize/2, que sigue existiendo.
Ver también: Despliegue remoto: errores frecuentes.

## Despliegue remoto: editar la configuración (app/Config)
Ruta: Administración del sistema > Despliegue remoto (core_merge/deploy) > pestaña **Configuración y logos**.
1. En **Compañía con conexión guardada** elija la compañía. Si no tiene conexión, configúrela en **Sincronización**.
2. En **Archivos de configuración (app/Config)** aparecen los archivos .php de app/Config del servidor (no las subcarpetas). Routes.php es de **solo lectura** (se actualiza con Sincronización). Constants, Database, App, Autoload y Filters están marcados como **crítico**.
3. Presione **Abrir** (o **Ver**) en el archivo y edítelo en **Contenido del archivo**. Si hay **Cambios sin guardar**, se pide confirmar antes de cambiar de archivo, de compañía o salir.
4. Presione **Guardar** y confirme. **Descartar y recargar** vuelve al contenido del servidor.
Al guardar: se valida la sintaxis PHP (si hay error no se envía y se indica la línea); se respalda el archivo en writable/posme_agent/files_backup/<fecha>/ del servidor (se conservan los 20 últimos) y se comprueba que el sistema remoto siga respondiendo. Si no responde, se restaura el original por FTP/SFTP (sus cambios quedan en el editor). Se conservan los saltos de línea originales.
Restricciones: si el archivo cambió en el servidor desde que lo abrió, se rechaza: vuelva a abrirlo. Si la compañía no tiene FTP/SFTP y el archivo es crítico, escriba GUARDAR SIN RESPALDO para confirmar: no habrá restauración automática; se restaura a mano desde el respaldo.
Notas: requiere agente 1.1.0 o superior; si es anterior aparece un aviso. Actualícelo en **Sincronización** con **Instalar agente por SFTP/FTP** marcando **Subir también el código del agente** (también sube el Routes.php local).
Ver también: Despliegue remoto: cargar los logos.

## Despliegue remoto: cargar los logos
Ruta: Administración del sistema > Despliegue remoto (core_merge/deploy) > pestaña **Configuración y logos** > **Logos de la compañía**.
1. Elija la compañía en **Compañía con conexión guardada**.
2. Adjunte la imagen solo en las casillas que desea reemplazar: logo-micro-finanza.png, logo-micro-finanza.jpg, direct-ticket-logo-micro-finanza.png y direct-ticket-logo-micro-finanza.jpg (carpeta public/resource/img/logos del servidor). Verá una vista previa; **Quitar** la descarta.
3. Si adjuntó exactamente una imagen, puede marcar **Usar esta misma imagen para los 4 nombres**.
4. Presione **Enviar logos**. El resultado muestra cada archivo como guardado o error, con su tamaño y medidas.
Restricciones: PNG o JPG de hasta 1 MB y hasta 4000 x 4000 px. Solo se envían las imágenes adjuntadas.
Notas: el sistema convierte cada imagen al formato del nombre (.png o .jpg; al pasar de PNG a JPG usa fondo blanco) y respalda el logo anterior. Avisa si el logo direct-ticket mide más de 576 px de ancho (impresora térmica) y si la ruta de archivos configurada en el servidor del cliente apunta a otra carpeta (los PDF no tomarían el logo). El logo nuevo puede tardar en verse por la caché del navegador.
Ver también: Despliegue remoto: editar la configuración (app/Config).

## Parámetros de la empresa
Los parámetros controlan el comportamiento de posMe por empresa (por ejemplo, orden de aplicación de abonos, lista de precios por defecto, formato de impresión).
Los parámetros contables se editan en CONTABILIDAD > PARAMETROS; el resto los configura el equipo de soporte de posMe.
Google Calendar de las órdenes de taller: APP_GOOGLE_CLENDAR_ENABLE (true la activa), GOOGLE_CALENDAR_ID, GOOGLE_CALENDAR_SERVICE_EMAIL y GOOGLE_CALENDAR_PRIVATE_KEY; opcionales GOOGLE_CALENDAR_TIMEZONE (por defecto America/Managua) y GOOGLE_CALENDAR_DURATION_MINUTES (duración de cada evento, por defecto 60 minutos).
Ver también: Configurar los parámetros contables.
<!-- REVISAR: pantalla para que el cliente edite parametros generales -->

## Soporte
Si no encuentra cómo hacer algo o hay un error en el sistema, contacte al equipo de soporte de posMe. <!-- REVISAR: telefono / correo de soporte -->

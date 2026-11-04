<style>
	/* ==========================================================
	   Planilla - complementos del estandar pv2 (prefijo pla)
	   Usada por: app_planilla_employee_pay, _adelantos, _settlement y _leave.
	   Requiere: core_template/posme_v2_style, posme_v2_common y
	   app_afx_fixedassent/afx_v2_common.
	   ========================================================== */

	/* Detalle de 14 columnas: en PC conserva un ancho minimo y se desplaza dentro de .pv2-table-wrap.
	   En telefono no aplica porque la tabla pasa a tarjetas */
	@media (min-width:768px) {
		.pv2 #tb_transaction_master_detail.pla-table-ancha { min-width:1700px; }
		.pv2 #tb_transaction_master_detail.pla-table-ancha td { padding:6px; }
		.pv2 #tb_transaction_master_detail.pla-table-ancha .form-control { padding-left:6px; padding-right:6px; }
	}
</style>
<script>
	/* ==========================================================
	   Planilla - funciones de interfaz compartidas (prefijo pla)
	   No contiene logica de negocio ni calculos de planilla.
	   ========================================================== */

	/* Inicializa una fecha sin inventar valor: pv2InitDate rellena hoy cuando el campo esta vacio,
	   y aqui una fecha vacia debe seguir vacia. No dispara change, asi que no recalcula al abrir. */
	function plaInitDate(id){
		var input = document.getElementById(id);
		if(!input) return;

		var fechaOriginal = $.trim(input.value);
		pv2InitDate(id,false);
		if(fechaOriginal == "") $(input).val("");
	}

	/* Reemplazo seguro de refreschChecked: pv2MobileSelects quita la clase select2 y deja form-control,
	   por lo que sin esta exclusion uniform envolveria los combos nativos del telefono.
	   La casilla de quitar filas tambien se excluye para que siga visible al tacto. */
	function plaRefreshChecked(){
		$("[type='checkbox'], [type='radio'], [type='file'], select").not(".toggle, .select2, .multiselect, .form-control, .txtCheckedIsActive").uniform();
	}

	/* Deja los montos como numero puro antes de .submit(). Un monto vacio se envia como 0,
	   igual que lo trata pv2Num en los calculos de pantalla. */
	function plaLimpiarMontos(formSelector){
		$(formSelector).find("input.afx-monto").each(function(){
			if($.trim(this.value) == "") this.value = "0";
		});
		afxLimpiarMontos(formSelector);
	}
</script>

<!-- ./ page heading -->
<script>	
	var objTableDetailTransaction = {};
	$(document).ready(function(){					
		//Inicializar Controles		
		$('#txtTransactionOn').datepicker({format:"yyyy-mm-dd"});
		
		objTableDetailTransaction = $("#tb_transaction_master_detail").dataTable({
			"bPaginate"		: false,
			"bFilter"		: false,
			"bSort"			: false,
			"bInfo"			: false,
			"bAutoWidth"	: false,
			"aaData": [		
				<?php 
					$listrow = [];
					foreach($objTMD as $i)
					{
						$listrow[] = "[0,".$i->componentItemID.",".$i->transactionMasterDetailID.",'".$i->itemNumber."','".$i->itemName."','".$i->unitMeasureName."',fnFormatNumber(".$i->quantity.",2),fnFormatNumber(".$i->unitaryCost.",2),fnFormatNumber(".($i->unitaryCost * $i->quantity).",2),'".$i->lote."','".$i->expirationDate."','mas informacion']";
					}
					echo implode(",",$listrow);
				?>
			],
			"aoColumnDefs": [ 
						{
							"aTargets"	: [ 0 ],//checked
							"mRender"	: function ( data, type, full ) {								
								if (data == false){
									var str = '<input type="checkbox"  class="classCheckedDetail"  value="0" ></span>';																		
									return str;
								}
								else{
									var str = '<input type="checkbox"  class="classCheckedDetail" checked="checked" value="0" ></span>';
									return str;
								}
								
							}
						},
						{
							"aTargets"		: [ 1 ],//itemID
							"bVisible"		: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="hidden" value="'+data+'" name="txtDetailItemID[]" />';
							}
						},
						{
							"aTargets"		: [2],//transactionMasterDetailID
							"bVisible"  	: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"		: function ( data, type, full ) {
								return '<input type="hidden" value="'+data+'" name="txtDetailTransactionDetailID[]" />';
							}
						},
						{
							"aTargets"	: [4], //nombre
							"sWidth"	: "40%"
						},
						{
							"aTargets"	: [ 6 ],//cantidad
							"mRender"	: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtDetailQuantity txt-numeric" value="'+data+'" name="txtDetailQuantity[]" />';
							}
						},
						{
							"aTargets"	: [ 7 ],//costo
							"mRender"	: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtDetailCost txt-numeric" value="'+data+'" name="txtDetailCost[]" />';
							}
						},
						{
							"aTargets"	: [ 8 ],//total
							"mRender"	: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtDetailTotal txt-numeric"" value="'+data+'" name="txtDetailTotal[]" readonly="true"  />';
							}
						},
						{
							"aTargets"		: [ 9 ],//lote
							"bVisible"		: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"	: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtDetailLote txt-numeric"" value="'+data+'" name="txtDetailLote[]" readonly="true" />';
							}
						},
						{
							"aTargets"		: [ 10 ],//fecha expiracion
							"bVisible"		: true,
							"sClass" 		: "hidden",
							"bSearchable"	: false,
							"mRender"	: function ( data, type, full ) {
								return '<input type="text" class="col-lg-12 txtDetailVencimiento txt-numeric"" value="'+data+'" name="txtDetailVencimiento[]" readonly="true" />';
							}
						},
						{
							"aTargets"	: [ 11 ],//mas informacion
							"mRender"	: function ( data, type, full ) {								
								if (data == false){									
									var str = '<a class="btn btn-primary btnMasInformcion" data-itemid="'+full[1]+'" data-transactionmasterdetailid="'+full[2]+'"  href="#" >Mas informacion</a>';
									return str;
								}
								else{									
									var str = '<a class="btn btn-primary btnMasInformcion" data-itemid="'+full[1]+'" data-transactionmasterdetailid="'+full[2]+'"  href="#" >Mas informacion</a>';
									return str;
								}
								
							}
						}
			]							
		});
		refreschChecked();
		
		//Ir a Lista
		$(document).on("click","#btnBack",function(){
				fnWaitOpen();
		});
		$(document).on("click","#btnPrinter",function(){
			fnWaitOpen();
			window.open("<?php echo base_url(); ?>/app_inventory_otherinput/viewRegister/companyID/<?php echo $objTM->companyID;?>/transactionID/<?php echo $objTM->transactionID;?>/transactionMasterID/<?php echo $objTM->transactionMasterID;?>","_blank");
			fnWaitClose();
		});
		//Eliminar el Documento
		$(document).on("click","#btnDelete",function(){
			fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
				fnWaitOpen();
				$.ajax({									
					cache       : false,
					dataType    : 'json',
					type        : 'POST',
					url  		: "<?php echo base_url(); ?>/app_inventory_otherinput/delete",
					data 		: {companyID : <?php echo $objTM->companyID;?>, transactionID : <?php echo $objTM->transactionID;?> , transactionMasterID : <?php echo $objTM->transactionMasterID;?>  },
					success:function(data){
						console.info("complete delete success");
						fnWaitClose();
						if(data.error){
							fnShowNotification(data.message,"error");
						}
						else{
							window.location = "<?php echo base_url(); ?>/app_inventory_otherinput/index";
						}
					},
					error:function(xhr,data){	
						console.info("complete delete error");									
						fnWaitClose();
						fnShowNotification("Error 505","error");
					}
				});
			});
		});
		//Guardar Documento
		$(document).on("click","#btnAcept",function(){
				$( "#form-new-transaction" ).attr("method","POST");
				$( "#form-new-transaction" ).attr("action","<?php echo base_url(); ?>/app_inventory_otherinput/save/edit");
				
				if(validateForm()){
					fnWaitOpen();
					$( "#form-new-transaction" ).submit();
				}
				
		});	
		
		//Agregar Item
		$(document).on("click","#btnNewDetailTransaction",function(){	
			if($("#txtWarehouseSourceID").val()==""){
				fnShowNotification("Seleccione la Bodega","error",1000);
				return;
			}
			
			var url_request = "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $componentItemID; ?>/onCompleteItem/SELECCIONAR_ITEM_TO_INPUT/true/" +encodeURI("{\"warehouseID\"|\""+$("#txtWarehouseSourceID").val()+"\"}")+ "/false/not_redirect_when_empty"; 
			window.open(url_request,"MsgWindow","width=900,height=450");
			window.onCompleteItem = onCompleteItem; 
		});
		$(document).on("click",".btnMasInformcion",function(){
			
			var itemID 						= $(this).data("itemid");
			var transactionMasterDetailID 	= $(this).data("transactionmasterdetailid");
			var tr 							= $(this).parent().parent()[0];
			var index 						= objTableDetailTransaction.fnGetPosition(tr);
			var objdat_ 					= objTableDetailTransaction.fnGetData(index);		
			var lote 						= objdat_[9];
			var vencimiento 				= objdat_[10];
			vencimiento 					= vencimiento.replace(" 00:00:00","");
			
			if(lote == "") lote = "0";
			if(vencimiento == "") vencimiento = moment().format("YYYY-MM-DD");
			
			var url_request = "<?php echo base_url(); ?>/app_inventory_otherinput/add_masinformacion/onCompleteUpdateMasInformacion/"+itemID+"/"+transactionMasterDetailID+"/"+index; 
			url_request = url_request + "/"+lote+"/"+vencimiento;
			
			window.open(url_request,"MsgWindow","width=900,height=500");
			window.onCompleteUpdateMasInformacion = onCompleteUpdateMasInformacion; 
		});
		//Eliminar Item
		$(document).on("click","#btnDeleteDetailTransaction",function(){
			var listRow = objTableDetailTransaction.fnGetData();							
			var length 	= listRow.length;
			var i 		= 0;
			var j 		= 0;
			while (i< length ){
				if(listRow[i][0] == true){
				objTableDetailTransaction.fnDeleteRow( j,null,true );
				j--;
				}
				i++;
				j++;
			}
			
			fnRecalcularTableSummary(objTableDetailTransaction,"#txtTotal",6 , 7, "*");		
			refreschChecked();
		});
		//Cambio en el Costo
		$(document).on("blur",".txtDetailCost",function(){
			var objrow_ = $(this).parent().parent()[0];
			var objind_ = objTableDetailTransaction.fnGetPosition(objrow_);
			var objdat_ = objTableDetailTransaction.fnGetData(objind_);		
			var cantidad = fnFormatFloat(objdat_[6]);
			var total = cantidad * fnFormatFloat($(this).val());
			total = fnFormatNumber(total,2);
			objTableDetailTransaction.fnUpdate(  $(this).val(), objind_, 7 );
			objTableDetailTransaction.fnUpdate(  total, objind_, 8 );			
			fnRecalcularTableSummary(objTableDetailTransaction,"#txtTotal",6 , 7, "*");		
			refreschChecked();
		})
		//Cambio en la cantidades
		$(document).on("blur",".txtDetailQuantity",function(){
			var objrow_ = $(this).parent().parent()[0];
			var objind_ = objTableDetailTransaction.fnGetPosition(objrow_);
			var objdat_ = objTableDetailTransaction.fnGetData(objind_);		
			var costo = fnFormatFloat(objdat_[7]);
			var total = costo * fnFormatFloat($(this).val());
			total = fnFormatNumber(total,2);
			objTableDetailTransaction.fnUpdate( $(this).val(), objind_, 6 );
			objTableDetailTransaction.fnUpdate( total, objind_, 8 );
			fnRecalcularTableSummary(objTableDetailTransaction,"#txtTotal",6 , 7, "*");		
			refreschChecked();
		})
		
		
		//Seleccionar Checke 
		$(document).on("click",".classCheckedDetail",function(){
			var objrow_ = $(this).parent().parent().parent().parent()[0];
			var objind_ = objTableDetailTransaction.fnGetPosition(objrow_);
			var objdat_ = objTableDetailTransaction.fnGetData(objind_);								
			objTableDetailTransaction.fnUpdate( !objdat_[0], objind_, 0 );
			refreschChecked();
		});
		//Cambio de Bodega
		$(document).on("change","#txtWarehouseSourceID",function(s,e){
			console.info("call changeWarehouse");
			//Limpiar la Lista
			objTableDetailTransaction.fnClearTable();
		});
	});
	
	//Funciones
	////////////////////////////
	////////////////////////////
	function validateForm(){
		var result 				= true;
		var timerNotification 	= 15000;
		
		//Nombre
		if($("#txtWarehouseSourceID").val()==""){
			fnShowNotification("Seleccionar la Bodega","error",timerNotification);
			result = false;
		}
		//Validar Estado
		if($("#txtStatusID").val() == ""){
			fnShowNotification("Establecer Estado","error",timerNotification);
			result = false;
		}
		//Fecha
		if($("#txtTransactionOn").val() == ""){
			fnShowNotification("Escriba la Fecha del Documento","error",timerNotification);
			result = false;
		}
		
		//Validar Fecha
		if(moment($("#txtTransactionOn").val(),"YYYY-MM-DD").toDate()  > moment().toDate()){
			fnShowNotification("La Fecha no Puede ser Mayor a la Fecha Actual","error",timerNotification);
			result = false;
		}
		
		//Detalle
		var lengthRow = objTableDetailTransaction.fnGetData().length;
		if(lengthRow == 0){
			fnShowNotification("Agregar el Detalle del Documento","error",timerNotification);
			result = false;
		}
		return result;
	}
	function onCompleteUpdateMasInformacion(objResponse){
			var index 		= objResponse.txtPosition;
			var vencimiento = objResponse.txtVencimiento;
			var lote 		= objResponse.txtLote;
		
			
			var objdat_ = objTableDetailTransaction.fnGetData(index);		
			objTableDetailTransaction.fnUpdate( lote, index, 9 );
			objTableDetailTransaction.fnUpdate(  vencimiento, index, 10 );			
			
			
	}
	function onCompleteItem(objResponse){
		console.info("CALL onCompleteItem");
		var objRow 						= {};
		objRow.checked 					= false;
		objRow.itemID 					= objResponse[0][1];
		objRow.transactionMasterDetail 	= 0;
		objRow.itemNumber 				= objResponse[0][2];
		objRow.itemName 				= objResponse[0][3];
		objRow.itemUM 					= objResponse[0][4];
		objRow.quantity 				= 0;
		objRow.cost 					= fnFormatNumber(objResponse[0][6],2); 
		objRow.lote 					= "";
		objRow.vencimiento				= "";
		objRow.total					= 0;
		objRow.masinfor					= "";
		
		//Berificar que el Item ya esta agregado 
		if(jLinq.from(objTableDetailTransaction.fnGetData()).where(function(obj){ return obj[1] == objRow.itemID;}).select().length > 0 ){
			fnShowNotification("El Item ya esta agregado","error");
			return;
		}
		
		objTableDetailTransaction.fnAddData([
		objRow.checked,objRow.itemID,objRow.transactionMasterDetail,objRow.itemNumber,objRow.itemName,
		objRow.itemUM,objRow.quantity,objRow.cost,
		objRow.total,
		objRow.lote,objRow.vencimiento,objRow.masinfor]);
		refreschChecked();
		
	}
	function refreschChecked(){
		$("[type='checkbox'], [type='radio'], [type='file'], select").not('.toggle, .select2, .multiselect').uniform();
		$('.txtDetailQuantity').mask('000,000.00', {reverse: true});
		$('.txtDetailCost').mask('000,000.00', {reverse: true});
	}
	
</script>
<script>  (function(g,u,i,d,e,s){g[e]=g[e]||[];var f=u.getElementsByTagName(i)[0];var k=u.createElement(i);k.async=true;k.src='https://static.userguiding.com/media/user-guiding-'+s+'-embedded.js';f.parentNode.insertBefore(k,f);if(g[d])return;var ug=g[d]={q:[]};ug.c=function(n){return function(){ug.q.push([n,arguments])};};var m=['previewGuide','finishPreview','track','identify','triggerNps','hideChecklist','launchChecklist'];for(var j=0;j<m.length;j+=1){ug[m[j]]=ug.c(m[j]);}})(window,document,'script','userGuiding','userGuidingLayer','744100086ID'); </script>
<script>
	//window.userGuiding.identify(userId*, attributes)
	  
	// example with attributes
	window.userGuiding.identify('<?php echo get_cookie("email"); ?>', {
	  email: '<?php echo get_cookie("email"); ?>',
	  name: '<?php echo get_cookie("email"); ?>',
	  created_at: 1644403436643,
	});
	// or just send userId without attributes
	//window.userGuiding.identify('1Ax69i57j0j69i60l4')
</script>

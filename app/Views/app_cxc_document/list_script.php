<script>
    $(document).ready(function() {
        $(document).on("click","#btnView",function(){
			window.open("<?php echo base_url(); ?>/core_view/chooseview/"+componentID,"MsgWindow","width=900,height=450");
			window.fn_aceptCallback = fn_aceptCallback; 
		});	
        $(document).on("click", "#btnEdit", function() {
            if (objRowTableListView != undefined) {

                fnWaitOpen();

                var data = objTableListView.fnGetData(objRowTableListView);
                
                window.location = "<?php echo base_url(); ?>/app_cxc_document/edit/customerCreditDocumentID/" + data[0];
            } 
            else 
            {
                fnShowNotification("Seleccionar el Registro...", "error");
            }
        });
    });
</script>
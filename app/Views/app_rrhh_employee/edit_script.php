<!-- ./ page heading -->
<script>
    var varUseMobile = '<?php echo isset($useMobile) ? $useMobile : "0"; ?>';

    $(document).ready(function(){
        //Las fechas guardadas se respetan; si estan vacias siguen vacias
        plaInitDate("txtStartOn");
        plaInitDate("txtEndOn");

        <?php
            //Filas de la base de datos como JSON: un apostrofo no rompe el script
            $emailRows = [];
            if($objEntityListEmail)
            foreach($objEntityListEmail as $i)
            {
                $emailRows[] = [0, $i->entityEmailID, $i->email, (int)$i->isPrimary];
            }
            $phoneRows = [];
            if($objEntityListPhone)
            foreach($objEntityListPhone as $i)
            {
                $phoneRows[] = [0, $i->entityPhoneID, $i->typeID, $i->typeIDDescription, $i->number, (int)$i->isPrimary];
            }
            $jsonFlags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
        ?>
        empInitForm({
            saveUrl         : "<?php echo base_url(); ?>/app_rrhh_employee/save/edit",
            searchParentUrl : "<?php echo base_url(); ?>/core_view/showviewbyname/<?php echo $objComponent->componentID; ?>/onCompleteEmployee/SELECCIONAR_EMPLOYEE/true/empty/false/not_redirect_when_empty",
            emailRows       : <?php echo json_encode($emailRows, $jsonFlags); ?>,
            phoneRows       : <?php echo json_encode($phoneRows, $jsonFlags); ?>
        });

        $('#btnLinkMenu').click(function(e){
            e.preventDefault();
            mostrarModal('ModalMenuDigital');
        });

        $(".copiar").click(function(){
            var url = $(this).data("url");
            var tempInput = $("<input>");
            $("body").append(tempInput);
            tempInput.val(url).select();
            document.execCommand("copy");
            tempInput.remove();
            $(this).text("✅ Copiado");
            var btn = $(this);
            setTimeout(function(){ btn.text("📋 Copiar"); }, 1500);
        });

        //Eliminar el Documento
        $(document).on("click","#btnDelete",function(e){
            e.preventDefault();
            fnShowConfirm("Confirmar..","Desea eliminar este Registro...",function(){
                fnWaitOpen();
                $.ajax({
                    cache       : false,
                    dataType    : 'json',
                    type        : 'POST',
                    url         : "<?php echo base_url(); ?>/app_rrhh_employee/delete",
                    data        : {companyID : <?php echo $objEmployee->companyID;?>, branchID : <?php echo $objEmployee->branchID;?> , entityID : <?php echo $objEmployee->entityID;?>  },
                    success:function(data){
                        console.info("complete delete success");
                        fnWaitClose();
                        if(data.error){
                            fnShowNotification(data.message,"error");
                        }
                        else{
                            window.location = "<?php echo base_url(); ?>/app_rrhh_employee/index";
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

        $(document).on("click","#btnClickArchivo",function(e){
            e.preventDefault();
            window.open("<?php echo base_url()."/core_elfinder/index/componentID/".$objComponent->componentID."/componentItemID/".$objEmployee->entityID; ?>","blanck");
        });
    });
</script>

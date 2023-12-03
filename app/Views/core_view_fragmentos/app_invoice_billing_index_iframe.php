<div class="panel panel-default iframePreviewPdf" style="display:none" id="iframeWork<?php echo $transactionMasterID ?>">
	<div class="panel-heading">
		<div class="icon"><i class="icon20 i-table"></i></div> 
		<h4><a target="_new"  href="<?php echo base_url()."/".$urlPrinterDocumentDirect."/companyID/2/transactionID/19/transactionMasterID/".$transactionMasterID ?>">Imprimir PDF<a></h4>
		<a href="#" class="minimize"></a>
	</div>                            
	<div class="panel-body" >
		<iframe id="iframePreview" src="
			<?php 
				if($exiteFileInFolder)
				{
					echo base_url().'/resource/file_company/'.'company_2/component_48/component_item_'.$transactionMasterID."/".$fileName; 
				}
				else
				{
					echo base_url().'/'.$urlPrinterDocument.'/'.'companyID/2/transactionID/19/transactionMasterID/'.$transactionMasterID; 
				}
			?>#page=1&zoom=150" title="factura" style="width:100%; height:800px;  " >
		</iframe>
	</div>
</div>
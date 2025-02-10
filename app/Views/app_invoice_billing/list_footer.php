<?php if($useMobile != "1"){?>
			</div>
		</div>
	</div>
	
	<?php 
		if($iframePreviewReport != "" )
		{
			?>
				<div class="col-lg-5">	
					<?php echo $iframePreviewReport; ?>									
				</div>
			<?php 
		}
	?>
	
</div>
<?php }?>

<div class="modal fade" tabindex="-1" id="modalDialogClaveMesero" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Código Mesero</h4>
      </div>
      <div class="modal-body">
	  	<input type="input" id="txtClaveMesero" autocomplete="off" class="form-control" placeholder="Código mesero" />
		<span id="errorMessage" style="color: red; display: none;">Por favor, ingresa un código.</span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <a href="javascript:void(0)" class="btn btn-primary" id="btnAceptarClaveMesero">Aceptar</a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

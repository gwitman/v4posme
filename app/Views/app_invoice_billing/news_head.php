				<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/sweetalert2/sweetalert2.min.css" rel="stylesheet" />
				<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/datepicker.css" rel="stylesheet" />
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/bootstrap-datepicker.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/moment.min.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/mask/jquery.mask.min.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/jLinq-2.2.1.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/jquery.number.min.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/decimal.js"></script>
                <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/isotope.pkgd.js"></script>
                <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/sweetalert2/sweetalert2.all.min.js"></script>

                <style>
					input:focus {
						
					  color:black;					  
					  font-weight: bold;
					  background: 	#00cb72;
					  
					  /*width: 100%;*/
					  /*padding: 0px 50px;*/
					  /*margin: 8px 0;*/
					  /*box-sizing: border-box;*/
					  
					}
				</style>
				
				<style>
					@media only screen and (max-width: 480px) 
					{
						#heading{
							margin:0px 0px 0px 0px !important
						}
					}
				</style>
				
				<div id="heading" class="page-header"  >
							<h1><i class="icon20  i-bag-2"></i> Agregar <?php echo getBehavio($company->type,"app_invoice_billing","labelTitlePageNew","Factura"); ?> </h1>
				</div> 
				<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/css/sweetalert2/sweetalert2.min.css" rel="stylesheet" />
				<link href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/datepicker.css" rel="stylesheet" />
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/datepicker/bootstrap-datepicker.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/moment.min.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/forms/mask/jquery.mask.min.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/jLinq-2.2.1.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/charts/pie-chart/jquery.easy-pie-chart.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/plugins/ui/range-slider/rangeslider.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-genyx/js/pages/ui-elements.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/jquery.number.min.js"></script>
				<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/decimal.js"></script>
                <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/isotope.pkgd.js"></script>
                <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/sweetalert2/sweetalert2.all.min.js"></script>

                <style>
					#divLoandingCustom{
						display: none;
					}
					input:focus {
						
					  color			:black;					  
					  font-weight	: bold;
					  background	: 	#00cb72;
					  
					  /*width		: 100%;*/
					  /*padding		: 0px 50px;*/
					  /*margin		: 8px 0;*/
					  /*box-sizing	: border-box;*/
					  
					}
					/*.td-center {
						text-align		: center;
						vertical-align	: middle;
					}*/
					.td-center input[type="checkbox"] {
						display	: inline-block;
						margin	: auto;
					}

					.td-center{
						display			: flex !important;
						justify-content	: center !important;
						align-items		: center !important;
						width			: 100% !important;
						height			: 100% !important;
						min-height		: 37px !important;
					}
					.switch{
                        z-index: 0 !important;
                    }
                    @media only screen and (max-width: 480px)
					{
						#heading{
							margin:0px 0px 0px 0px !important
						}
					}
					.label-sku {
						cursor: pointer;
						color: blue;
						text-decoration: underline;
						font-family: 'Roboto', sans-serif;
						padding: 7.5px;
						-webkit-box-shadow: -0.25px 0.75px 9px -0.25px rgba(85,85,85,0.36);
						-moz-box-shadow: -0.25px 0.75px 9px -0.25px rgba(85,85,85,0.36);
						-box-shadow: -0.25px 0.75px 9px -0.25px rgba(85,85,85,0.36);
					}
					.td-sku{
						vertical-align: middle !important; 
						text-align: left !important; 
					}
				</style>
				
				<div id="heading" class="page-header"  >
							<h1><i class="icon20  i-bag-2"></i> Agregar <?php echo getBehavio($company->type,"app_invoice_billing","labelTitlePageNew","Factura"); ?> </h1>
				</div> 
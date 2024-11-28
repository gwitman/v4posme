<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta name="description" content="posMe">
  <meta name="author" content=""> 
  <title>::posMe::</title>
  
  <link rel="apple-touch-icon" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/assets/images/apple-touch-icon.png">
  <link rel="shortcut icon" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/assets/images/favicon.ico">
  
  <!-- Stylesheets -->
  <link rel="stylesheet" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/css/bootstrap-extend.min.css">
  <link rel="stylesheet" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/assets/css/site.min.css">
  <!-- Plugins -->

  <link rel="stylesheet" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/animsition/animsition.css">
  <link rel="stylesheet" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/asscrollable/asScrollable.css">
  <link rel="stylesheet" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/switchery/switchery.css">
  <link rel="stylesheet" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/intro-js/introjs.css">
  <link rel="stylesheet" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/slidepanel/slidePanel.css">
  <link rel="stylesheet" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/flag-icon-css/flag-icon.css">
  <link rel="stylesheet" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/waves/waves.css">
  <link rel="stylesheet" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/assets/examples/css/pages/login-v3.css">
  
  <!-- Fonts -->
  <link rel="stylesheet" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/fonts/material-design/material-design.min.css">
  <link rel="stylesheet" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/fonts/brand-icons/brand-icons.min.css">
  <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
  
  <!--[if lt IE 9]>
    <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/html5shiv/html5shiv.min.js"></script>
    <![endif]-->
  <!--[if lt IE 10]>
    <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/media-match/media.match.min.js"></script>
    <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/respond/respond.min.js"></script>
    <![endif]-->
  <!-- Scripts -->
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/breakpoints/breakpoints.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/js/genyx-fn.js"></script>
  <script>
  Breakpoints();
  </script>
</head>
<body class="animsition page-login-v3 layout-full">
  <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
  <!-- Page -->
  <div class="page vertical-align text-xs-center" data-animsition-in="fade-in" data-animsition-out="fade-out">
    <div class="page-content vertical-align-middle">
      <div class="panel">
        <div class="panel-body">
          <div class="brand">
            
			<!--
			<img class="brand-img" src="data:image/jpeg;base64,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" width="250" height="200" alt="...">
			-->
			
			
			<img class="brand-img" src="<?php echo base_url(); ?>/resource/img/logos/posme.svg" width="250" height="200" alt="...">
			
			
            <h2 class="brand-text font-size-18" id="lablTrheePoint"><?php echo $parameterLabelSistem;  ?></h2>
          </div>
          <form method="POST" action="<?php echo base_url(); ?>/core_acount/login" id="login-form" autocomplete="off">
            <div class="form-group form-material floating" data-plugin="formMaterial">
              <input type="text" class="form-control" name="txtNickname" id="txtNickname" />
              <label class="floating-label">Usuario</label>
            </div>
            <div class="form-group form-material floating" data-plugin="formMaterial">
              <input type="password" class="form-control" name="txtPassword" id="txtPassword"  />
              <label class="floating-label">Contraseña</label>
            </div>
            <div class="form-group clearfix">
              <div class="checkbox-custom checkbox-inline checkbox-primary checkbox-lg pull-xs-left">
                <input type="checkbox" id="inputCheckbox" name="remember">
                <label for="inputCheckbox">Recordarme</label>
              </div>
              <a class="pull-xs-right" href="forgot-password.html">Reenviar Contraseña? 				
			  </a>
            </div>
            <div class="form-group clearfix">
              <div class="checkbox-custom checkbox-inline checkbox-primary checkbox-lg pull-xs-left">
                <input type="checkbox" id="inputCheckboxPayment" name="inputCheckBoxPayment">
                <label for="inputCheckboxPayment">Pagar</label>
              </div>
              
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg m-t-40">Ingresar</button>
            
            <div class="form-group form-material floating hidden-lg-up" id="divPagosMeses" data-plugin="formMaterial">
              <select class="form-control" id="txtPagarCantidadDe" name="txtPagarCantidadDe">
                  <option value="">  Seleccionar</option>
                  <option value="1"> <?php echo "$ ".round($parameterPrice * 1,2); ?></option>
                  <option value="2"> <?php echo "$ ".round($parameterPrice * 2,2); ?></option>
                  <option value="3"> <?php echo "$ ".round($parameterPrice * 3,2); ?></option>
                  <option value="4"> <?php echo "$ ".round($parameterPrice * 4,2); ?></option>
                  <option value="5"> <?php echo "$ ".round($parameterPrice * 5,2); ?></option>
                  <option value="6"> <?php echo "$ ".round($parameterPrice * 6,2); ?></option>
                  <option value="7"> <?php echo "$ ".round($parameterPrice * 7,2); ?></option>
                  <option value="8"> <?php echo "$ ".round($parameterPrice * 8,2); ?></option>
                  <option value="9"> <?php echo "$ ".round($parameterPrice * 9,2); ?></option>
                  <option value="10"><?php echo "$ ".round($parameterPrice * 10,2); ?></option>
                  <option value="11"><?php echo "$ ".round($parameterPrice * 11,2); ?></option>
                  <option value="12"><?php echo "$ ".round($parameterPrice * 12,2); ?></option>
              </select>              
            </div>
            <button type="submit" class="btn btn-success btn-block btn-lg m-t-40 hidden-lg-up" id="divPagosMesesBoton" >Pagar</button>
          </form>
          <!--
          <p>Still no account? Please go to <a href="register-v3.html">Sign up</a></p>
          -->
        </div>
      </div>
      

    

      <footer class="page-copyright page-copyright-inverse">
		<?php echo $message; ?>
        <p>Aplicación elaborada por <?php echo $parameterLabelSistem; ?></p>
        <p>© 2019. Todos los derechos reservados</p>
        <div class="social">
          <a class="btn btn-icon btn-pure" href="javascript:void(0)">
            <i class="icon bd-twitter" aria-hidden="true"></i>
          </a>
          <a class="btn btn-icon btn-pure" href="https://www.facebook.com/profile.php?id=100085523680343">
            <i class="icon bd-facebook" aria-hidden="true"></i>
          </a>
          <a class="btn btn-icon btn-pure" href="javascript:void(0)">
            <i class="icon bd-google-plus" aria-hidden="true"></i>
          </a>
        </div>
      </footer>
    </div>
  </div>
  <!-- End Page -->
  <!-- Core  -->
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/babel-external-helpers/babel-external-helpers.js"></script>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/jquery/jquery.js"></script>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/tether/tether.js"></script>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/bootstrap/bootstrap.js"></script>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/animsition/animsition.js"></script>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/mousewheel/jquery.mousewheel.js"></script>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/asscrollbar/jquery-asScrollbar.js"></script>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/asscrollable/jquery-asScrollable.js"></script>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/waves/waves.js"></script>
  <!-- Plugins -->
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/switchery/switchery.min.js"></script>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/intro-js/intro.js"></script>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/screenfull/screenfull.js"></script>>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/slidepanel/jquery-slidePanel.js"></script>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/vendor/jquery-placeholder/jquery.placeholder.js"></script>
  <!-- Scripts -->
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/js/State.js"></script>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/js/Component.js"></script>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/js/Plugin.js"></script>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/js/Base.js"></script>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/js/Config.js"></script>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/assets/js/Section/Menubar.js"></script>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/assets/js/Section/Sidebar.js"></script>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/assets/js/Section/PageAside.js"></script>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/assets/js/Plugin/menu.js"></script>
  <!-- Config -->
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/js/config/colors.js"></script>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/assets/js/config/tour.js"></script>
  <script>
  Config.set('assets', '<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/assets');
  </script>
  <!-- Page -->
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/assets/js/Site.js"></script>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/js/Plugin/asscrollable.js"></script>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/js/Plugin/slidepanel.js"></script>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/js/Plugin/switchery.js"></script>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/js/Plugin/jquery-placeholder.js"></script>
  <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/theme-remark/global/js/Plugin/material.js"></script>
  <script>
  (function(document, window, $) {
    'use strict';
    var Site = window.Site;
    $(document).ready(function() {
		
		
		var passWord 		= localStorage.getItem("objUserPassword");		
		var passNickname 	= localStorage.getItem("objUserName");		
		if(passNickname != null)
		{
			 $("#txtNickname").val(passNickname) ;
			 $("#txtPassword").val(passWord) ;
		}
		
        Site.run();
		
    });
	
	$("#lablTrheePoint").on("click",function(){
		
		localStorage.setItem("objUserName", $("#txtNickname").val() );
		localStorage.setItem("objUserPassword", $("#txtPassword").val()  );
	});
	
    $("#inputCheckboxPayment").on("click",function(){
		
        var checked = $("#inputCheckboxPayment").is(':checked');
        if(checked){
          $("#divPagosMeses").removeClass("hidden-lg-up");
          $("#divPagosMesesBoton").removeClass("hidden-lg-up");
        }
        else{
          $("#divPagosMeses").addClass("hidden-lg-up");
          $("#divPagosMesesBoton").addClass("hidden-lg-up");
        }
          
    });

  })(document, window, jQuery);
  </script>


<script>
    
    $("#login-form").on("submit",function(event){
      userName      = $("#txtNickname").val();
			userPassword  = $("#txtPassword").val();
      fnGetUsersCurrentLocation(userName,userPassword,'https://posme.net/v4posme/demo/app_mobile_api/setPositionGps');
    });
</script> 

</body>
</html>
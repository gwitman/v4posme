<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta name="description" content="bootstrap admin template">
  <meta name="author" content=""> 
  <title>::posMe::</title>
  
  <link rel="apple-touch-icon" href="<?php echo base_url(); ?>/resource/theme-remark/assets/images/apple-touch-icon.png">
  <link rel="shortcut icon" href="<?php echo base_url(); ?>/resource/theme-remark/assets/images/favicon.ico">
  
  <!-- Stylesheets -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>/resource/theme-remark/global/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>/resource/theme-remark/global/css/bootstrap-extend.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>/resource/theme-remark/assets/css/site.min.css">
  <!-- Plugins -->
  
  <link rel="stylesheet" href="<?php echo base_url(); ?>/resource/theme-remark/global/vendor/animsition/animsition.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>/resource/theme-remark/global/vendor/asscrollable/asScrollable.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>/resource/theme-remark/global/vendor/switchery/switchery.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>/resource/theme-remark/global/vendor/intro-js/introjs.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>/resource/theme-remark/global/vendor/slidepanel/slidePanel.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>/resource/theme-remark/global/vendor/flag-icon-css/flag-icon.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>/resource/theme-remark/global/vendor/waves/waves.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>/resource/theme-remark/assets/examples/css/pages/login-v3.css">
  
  <!-- Fonts -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>/resource/theme-remark/global/fonts/material-design/material-design.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>/resource/theme-remark/global/fonts/brand-icons/brand-icons.min.css">
  <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
  
  <!--[if lt IE 9]>
    <script src="<?php echo base_url(); ?>/resource/theme-remark/global/vendor/html5shiv/html5shiv.min.js"></script>
    <![endif]-->
  <!--[if lt IE 10]>
    <script src="<?php echo base_url(); ?>/resource/theme-remark/global/vendor/media-match/media.match.min.js"></script>
    <script src="<?php echo base_url(); ?>/resource/theme-remark/global/vendor/respond/respond.min.js"></script>
    <![endif]-->
  <!-- Scripts -->
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/vendor/breakpoints/breakpoints.js"></script>
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
            <img class="brand-img" src="<?php echo base_url(); ?>/resource/img/logos/posme.svg" width="250" height="200" alt="...">
            <h2 class="brand-text font-size-18">...</h2>
          </div>
          <form method="POST" action="<?php echo base_url(); ?>/core_acount/login"  autocomplete="off">
            <div class="form-group form-material floating" data-plugin="formMaterial">
              <input type="text" class="form-control" name="txtNickname" />
              <label class="floating-label">Usuario</label>
            </div>
            <div class="form-group form-material floating" data-plugin="formMaterial">
              <input type="password" class="form-control" name="txtPassword" />
              <label class="floating-label">Contraseña</label>
            </div>
            <div class="form-group clearfix">
              <div class="checkbox-custom checkbox-inline checkbox-primary checkbox-lg pull-xs-left">
                <input type="checkbox" id="inputCheckbox" name="remember">
                <label for="inputCheckbox">Recordarme</label>
              </div>
              <a class="pull-xs-right" href="forgot-password.html">Reenviar Contraseña?</a>
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
        <p>Aplicación elaborada por teamDS2</p>
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
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/vendor/babel-external-helpers/babel-external-helpers.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/vendor/jquery/jquery.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/vendor/tether/tether.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/vendor/bootstrap/bootstrap.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/vendor/animsition/animsition.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/vendor/mousewheel/jquery.mousewheel.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/vendor/asscrollbar/jquery-asScrollbar.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/vendor/asscrollable/jquery-asScrollable.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/vendor/waves/waves.js"></script>
  <!-- Plugins -->
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/vendor/switchery/switchery.min.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/vendor/intro-js/intro.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/vendor/screenfull/screenfull.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/vendor/slidepanel/jquery-slidePanel.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/vendor/jquery-placeholder/jquery.placeholder.js"></script>
  <!-- Scripts -->
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/js/State.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/js/Component.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/js/Plugin.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/js/Base.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/js/Config.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/assets/js/Section/Menubar.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/assets/js/Section/Sidebar.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/assets/js/Section/PageAside.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/assets/js/Plugin/menu.js"></script>
  <!-- Config -->
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/js/config/colors.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/assets/js/config/tour.js"></script>
  <script>
  Config.set('assets', '<?php echo base_url(); ?>/resource/theme-remark/assets');
  </script>
  <!-- Page -->
  <script src="<?php echo base_url(); ?>/resource/theme-remark/assets/js/Site.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/js/Plugin/asscrollable.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/js/Plugin/slidepanel.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/js/Plugin/switchery.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/js/Plugin/jquery-placeholder.js"></script>
  <script src="<?php echo base_url(); ?>/resource/theme-remark/global/js/Plugin/material.js"></script>
  <script>
  (function(document, window, $) {
    'use strict';
    var Site = window.Site;
    $(document).ready(function() {
        Site.run();
       
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
</body>
</html>
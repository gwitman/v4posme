<?php
header("Content-type: text/css");

$color_primary 		= "#006E98";//#363840
$color_secundary 	= "#006E98";//#4e525d
$color_3 			= "#006E98";//#3a3f4b
$color_4 			= "#006E98";//#5c6778
$color_5 			= "#006E98";//black;


?>

/* Genyx admin app.css ver1.2 by SuggeElson  */

/* -------------------------------------------------- 
   Table of Contents
-----------------------------------------------------
:: Shared Styles
:: Typography
:: Bootstrap custom styles
:: Page structure
:: Off canvas styles
:: Custom.panels
:: Plugin custom styles
:: Css Animations
:: Login page
:: Error pages & offline page
:: Media Queries
*/

/* -----------------------------------------
   Shared Styles
----------------------------------------- */

/* html,body {min-height:100%;height:auto !important;height:100%;} */
html {background: url(../images/patterns/debut_light.png) repeat; height: 100%; }
body {min-height: 100%;}
body {display: inline-block; overflow-x: hidden;}

* { -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; outline: 0 !important; }

.clear {clear:both;}
.center {text-align: center;}

/*Colors*/
.white {color: #ffffff;}
.dark {color: #6f7a8a;}
.red {color: #f40a0a;}
.red-smooth {color: #d8605f;}
.blue {color: #62aeef;}
.green {color: #72b110;}
.yellow {color: #f7cb38;}
.orange {color: #F88C00;}

/*Text Shadows*/
.tshadow {text-shadow: 0px 1px 0px #ffffff; filter: dropshadow(color=#ffffff, offx=0, offy=1);}
.tshadow1 {text-shadow: 0px 1px 0px #000000;filter: dropshadow(color=#000000, offx=0, offy=1);}
/*Box Shadows*/
.bshadow {-webkit-box-shadow:  0px 1px 0px 0px rgba(255, 255, 255, 1);box-shadow:  0px 1px 0px 0px rgba(255, 255, 255, 1);}
.bshadow2 {
	-webkit-box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, 1), 0px 1px 0px 0px rgba(255, 255, 255, 1);
	box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, 1), 0px 1px 0px 0px rgba(255, 255, 255, 1);
}

/* Gaps ( margins ) */

.gap0 {margin: 0;}
.gap5 {margin: 5px;}
.gap10 {margin: 10px;}
.gap15 {margin: 15px;}
.gap20 {margin: 20px;}

.gap-top0 {margin-top:0;}
.gap-top5 {margin-top:5px;}
.gap-top10 {margin-top: 10px;}
.gap-top15 {margin-top: 15px;}
.gap-top20 {margin-top: 20px;}

.gap-right0 {margin-right:0;}
.gap-right5 {margin-right:5px;}
.gap-right10 {margin-right: 10px;}
.gap-right15 {margin-right: 15px;}
.gap-right20 {margin-right: 20px;}

.gap-left0 {margin-left:0;}
.gap-left5 {margin-left:5px;}
.gap-left10 {margin-left: 10px;}
.gap-left15 {margin-left: 15px;}
.gap-left20 {margin-left: 20px;}

.gap-bottom0 {margin-bottom:0;}
.gap-bottom5 {margin-bottom:5px;}
.gap-bottom10 {margin-bottom: 10px;}
.gap-bottom15 {margin-bottom: 15px;}
.gap-bottom20 {margin-bottom: 20px;}

/* Pads (padding) */

.pad0 {padding: 0;}
.pad5 {padding: 5px;}
.pad10 {padding: 10px;}
.pad15 {padding: 15px;}
.pad20 {padding: 20px;}
.pad25 {padding: 25px;}

.pad-right0 {padding-right:0;}
.pad-right5 {padding-right:5px;}
.pad-right10 {padding-right: 10px;}
.pad-right15 {padding-right: 15px;}
.pad-right20 {padding-right: 20px;}

.pad-left0 {padding-left:0;}
.pad-left5 {padding-left:5px;}
.pad-left10 {padding-left: 10px;}
.pad-left15 {padding-left: 15px;}
.pad-left20 {padding-left: 20px;}

.pad-bottom0 {padding-bottom:0;}
.pad-bottom5 {padding-bottom:5px;}
.pad-bottom10 {padding-bottom: 10px;}
.pad-bottom15 {padding-bottom: 15px;}
.pad-bottom20 {padding-bottom: 20px;}

.pad-top0 {padding-top:0;}
.pad-top5 {padding-top:5px;}
.pad-top10 {padding-top: 10px;}
.pad-top15 {padding-top: 15px;}
.pad-top20 {padding-top: 20px;}

/* Borders */
.bt {border-bottom: 1px solid #c9c9c9;}
.br {border-right: 1px solid #c9c9c9;}
.bb {border-bottom: 1px solid #c9c9c9;}
.bl {border-left: 1px solid #c9c9c9;}

/*Notification styles*/
.notification {
	-webkit-border-radius:3px;
	border-radius:3px;
	border: 1px solid #5b6779;
	background: #6f7a8a;
	padding: 0px 6px;
	position: relative;
	color: #f2f2f2;
	font-weight: bold;
	font-size: 12px;
}
	.notification:after, .notification:before {
		right: 100%;
		border: solid transparent;
		content: " ";
		height: 0;
		width: 0;
		position: absolute;
		pointer-events: none;
	}
	.notification:after {
		border-color: rgba(111, 122, 138, 0);
		border-right-color: #6f7a8a;
		border-width: 6px;
		top: 50%;
		margin-top: -6px;
	}
	.notification:before{
		border-color: rgba(182, 119, 9, 0);
		border-right-color: #5b6779;
		border-width: 7px;
		top: 50%;
		margin-top: -7px;
	}

	.notification.green {border-color: #58890b;background: #72b110;color: #f2f2f2;}
		.notification.green:after {
			border-color: rgba(114, 177, 16, 0);
			border-right-color: #72b110;
		}
		.notification.gree:before{
			border-color: rgba(88, 137, 11, 0);
			border-right-color: #58890b;
		}
	.notification.red {border-color: #be3d3c;background: #d8605f;color: #f2f2f2;}
		.notification.red:after {
			border-color: rgba(216, 96, 95, 0);
			border-right-color: #d8605f;
		}
		.notification.red:before{
			border-color: rgba(190, 61, 60, 0);
			border-right-color: #be3d3c;
		}
	.notification.blue {border-color: #3693e2;background: #62aeef;color: #f2f2f2;}
		.notification.blue:after {
			border-color: rgba(98, 174, 239, 0);
			border-right-color: #62aeef;
		}
		.notification.blue:before{
			border-color: rgba(54, 147, 226, 0);
			border-right-color: #3693e2;
		}

/* -----------------------------------------
   Typography
----------------------------------------- */
li {margin-bottom:7px;}

a {
	color: #7090c8;
	-webkit-transition: 0.25s all ease-in; -moz-transition: 0.25s all ease-in; -o-transition: 0.25s all ease-in; transition: 0.25s all ease-in;
}
a:hover {color: #2f93d7;}
a:focus {outline: none;}

body {
	font-family: 'Droid Sans',Helvetica, Arial, sans-serif; 
	color:#686866; 
	-webkit-font-smoothing: antialiased; /* Fix for webkit rendering */
	-webkit-text-size-adjust: 100%; 
	font-size-adjust: 100%;
	font-weight: 400;
	width: 100%;
	background: #fff ;/*none;*/ /*witman gonzalez*/
}

p {
  margin: 0 0 9px;
  font-family: 'Droid Sans', Helvetica, Arial, sans-serif;
  font-size: 13px;
  line-height: 22px;
  font-weight: 400;
}

p small {
  font-size: 11px;
  color: #999999;
}

h1,h2,h3,h4,h5,h6 {
  margin: 0;
  font-family: 'Open Sans', sans-serif;
  font-weight: 700;
  color: inherit;
  text-rendering: optimizelegibility;
  margin-bottom:10px;
}

h1 small,h2 small,h3 small,h4 small,h5 small,h6 small {
  font-weight: normal;
  color: #999999;
}

h1 { font-size: 30px;line-height: 45px;}
h1 small {font-size: 18px;}
h2 { font-size: 24px;line-height: 36px;}
h2 small {font-size: 18px;}
h3 {font-size: 18px;line-height: 27px;}
h3 small {font-size: 14px;}
h4,h5,h6 {line-height: 18px;}
h4 {font-size: 14px;}
h4 small {font-size: 12px;}
h5 {font-size: 12px;}
h6 {font-size: 11px;color: #999999;text-transform: uppercase;}

/*Change fonts here*/
h1,h2,h3,h4,h5,h6 {font-family: 'Open Sans', sans-serif;}
body, p {font-family: 'Droid Sans', Helvetica, Arial, sans-serif;}

.list-unstyled {padding-left: 0;list-style: none;}

/* -----------------------------------------
   Bootstrap custom styles
----------------------------------------- */

/* Tooltips */
.tooltip {font-size: 12px;}
.tooltip.in {opacity: 1;filter: alpha(opacity=100);}
.tooltip-inner {
	background: <?php echo $color_secundary; ?>;
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iIzRlNTI1ZCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiMzNjM4NDAiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top,  <?php echo $color_secundary; ?> 0%, <?php echo $color_primary; ?> 100%); 
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,<?php echo $color_secundary; ?>), color-stop(100%,<?php echo $color_primary; ?>)); 
	background: -webkit-linear-gradient(top,  <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%);
	background: -o-linear-gradient(top,  <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%); 
	background: -ms-linear-gradient(top,  <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%); 
	background: linear-gradient(to bottom,  <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%); 
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $color_secundary; ?>', endColorstr='<?php echo $color_primary; ?>',GradientType=0 );
	
	  -webkit-border-radius: 3px;
	     -moz-border-radius: 3px;
	          border-radius: 3px;
}
.tooltip.top .tooltip-arrow {border-top-color: <?php echo $color_secundary; ?>;}
.tooltip.right .tooltip-arrow {border-right-color: <?php echo $color_secundary; ?>;}
.tooltip.left .tooltip-arrow {border-left-color: <?php echo $color_secundary; ?>;}
.tooltip.bottom .tooltip-arrow {border-bottom-color: <?php echo $color_secundary; ?>;}


.navbar-fixed-top, .navbar-fixed-bottom {z-index: 1020;}

.page-header {
	padding-bottom: 0;
	margin-top: 9px;
	border-color: #c9c9c9;
	-moz-box-shadow: 0 1px 0px rgba(255, 255, 255, 1);
	-webkit-box-shadow: 0 1px 0px rgba(255, 255, 255, 1);
	box-shadow: 0 1px 0px rgba(255, 255, 255, 1);
}

/* Static Alerts */
.alert {
	padding: 12px 35px 11px 14px;
	margin-bottom: 20px;
	text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
	border: 1px solid #d3b85a;
	-webkit-border-radius: 0px;
	-moz-border-radius: 0px;
	border-radius: 0px;
	background: #fff1c4; /* Old browsers */
	/* IE9 SVG, needs conditional override of 'filter' to 'none' */
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ZmZjFjNCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNlZGUxYjQiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top,  #fff1c4 0%, #ede1b4 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#fff1c4), color-stop(100%,#ede1b4)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top,  #fff1c4 0%,#ede1b4 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top,  #fff1c4 0%,#ede1b4 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top,  #fff1c4 0%,#ede1b4 100%); /* IE10+ */
	background: linear-gradient(to bottom,  #fff1c4 0%,#ede1b4 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fff1c4', endColorstr='#ede1b4',GradientType=0 ); /* IE6-8 */
	
}
	.alert i {margin-top: -3px;}
	.alert, .alert h4 {color: #745f1d;}
.alert-danger, .alert-error {
	color: #8d313d;
	border-color: #ceabab;
	background: #f3dfdf; /* Old browsers */
	/* IE9 SVG, needs conditional override of 'filter' to 'none' */
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2YzZGZkZiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNlMmNlY2UiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top,  #f3dfdf 0%, #e2cece 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#f3dfdf), color-stop(100%,#e2cece)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top,  #f3dfdf 0%,#e2cece 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top,  #f3dfdf 0%,#e2cece 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top,  #f3dfdf 0%,#e2cece 100%); /* IE10+ */
	background: linear-gradient(to bottom,  #f3dfdf 0%,#e2cece 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f3dfdf', endColorstr='#e2cece',GradientType=0 ); /* IE6-8 */
	
}
.alert-success {
	color: #2d6524;
	border-color: #9dc286;
	background: #e0f1d6; /* Old browsers */
	/* IE9 SVG, needs conditional override of 'filter' to 'none' */
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2UwZjFkNiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNjZmUwYzYiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top,  #e0f1d6 0%, #cfe0c6 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#e0f1d6), color-stop(100%,#cfe0c6)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top,  #e0f1d6 0%,#cfe0c6 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top,  #e0f1d6 0%,#cfe0c6 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top,  #e0f1d6 0%,#cfe0c6 100%); /* IE10+ */
	background: linear-gradient(to bottom,  #e0f1d6 0%,#cfe0c6 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e0f1d6', endColorstr='#cfe0c6',GradientType=0 ); /* IE6-8 */
	
}
.alert-info {
	color: #23485a;
	border-color: #9ec5dc;
	background: #daecf8; /* Old browsers */
	/* IE9 SVG, needs conditional override of 'filter' to 'none' */
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2RhZWNmOCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNjOWRjZTgiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top,  #daecf8 0%, #c9dce8 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#daecf8), color-stop(100%,#c9dce8)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top,  #daecf8 0%,#c9dce8 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top,  #daecf8 0%,#c9dce8 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top,  #daecf8 0%,#c9dce8 100%); /* IE10+ */
	background: linear-gradient(to bottom,  #daecf8 0%,#c9dce8 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#daecf8', endColorstr='#c9dce8',GradientType=0 ); /* IE6-8 */
	
}
	.alert .close {opacity: 0.5;}

/*Labels*/
.label {padding: 4px 6px;border-radius: 1px;-webkit-border-radius: 1px;}
.label, .badge {background-color: #6f7a8a;}
.label-success, .badge-success {background-color: #72b110;}
.label-warning, .badge-warning {background-color: #F88C00;}
.label-important, .badge-important {background-color: #f40a0a;}
.label-info, .badge-info {background-color: #62aeef;}
.label-inverse, .badge-inverse {background-color: #333333;}

/* Btn groups preapend and append elements */
.btn-group > .btn:first-child {
	-webkit-border-bottom-left-radius: 1px;
	border-bottom-left-radius: 1px;
	-webkit-border-top-left-radius: 1px;
	border-top-left-radius: 1px;
	-moz-border-radius-bottomleft: 1px;
	-moz-border-radius-topleft: 1px;
}
.btn-group > .btn:last-child, .btn.last-child, .btn-group > .dropdown-toggle {
	-webkit-border-top-right-radius: 1px;
	border-top-right-radius: 1px;
	-webkit-border-bottom-right-radius: 1px;
	border-bottom-right-radius: 1px;
	-moz-border-radius-topright: 1px;
	-moz-border-radius-bottomright: 1px;
}
.btn-group-vertical > .btn:first-child {
	-webkit-border-radius: 1px 1px 0 0;
	-moz-border-radius: 1px 1px 0 0;
	border-radius: 1px 1px 0 0;
}
.btn-group-vertical > .btn:last-child, .btn-group-vertical > .btn.last-child {
	-webkit-border-radius: 0 0 1px 1px;
	-moz-border-radius: 0 0 1px 1px;
	border-radius: 0 0 1px 1px;
}
.btn-group .btn i {margin-left: 0;margin-right: 0;}
.btn-group {vertical-align: top;}
.input-group .input-group-addon, .input-group .btn {height: 36px;padding: 4px 7px;line-height: 26px;}
.input-group .input-group-addon, .input-group .btn, .input-group .btn-group {height: 36px;padding: 4px 7px;line-height: 26px;}
.controls.controls-row .input-group .input-group-addon {float: left;}
.controls.controls-row .input-group .btn-group {padding:0;}
.controls.controls-row .input-group .btn-group {float: left;}
.controls.controls-row .input-group .btn-group > .btn:first-child {
	margin-left: -1px;
	border-top-left-radius: 0px;
	-moz-border-radius-bottomleft: 0px;
	-moz-border-radius-topleft: 0px;
	-webkit-border-bottom-left-radius: 0px;
	border-bottom-left-radius: 0px;
	-webkit-border-top-left-radius: 0px;
}

.help-block {
margin-top: 0;
margin-bottom: 0;
}
.form-horizontal .radio {padding-top: 0;}

.input-group .input-group-addon:last-child, .input-group .btn:last-child, .input-group .btn-group:last-child > .dropdown-toggle {
	-webkit-border-radius: 0 1px 1px 0;
	-moz-border-radius: 0 1px 1px 0;
	border-radius: 0 1px 1px 0;
}
.input-group .input-group-addon:first-child, .input-group .btn:first-child {
	-webkit-border-radius: 1px 0 0 1px;
	-moz-border-radius: 1px 0 0 1px;
	border-radius: 1px 0 0 1px;
}

/* Form styles */
.form-group {clear: both;}
.form-group.relative {position: relative;}
.form-group.relative input {padding-left: 46px;}
textarea.form-control {border-radius: 1px;}

textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"], .uneditable-input {
	background: #ffffff;
	background: -moz-linear-gradient(top, #ffffff 0%, #f5f5f5 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#f5f5f5));
	background: -webkit-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: -o-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: -ms-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: linear-gradient(to bottom, #ffffff 0%,#f5f5f5 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f5f5f5',GradientType=0 );
	border: 1px solid #c9c9c9;
	-webkit-box-shadow: inset 0 1px 1px rgba(255, 255, 255, 1), 0 1px 1px rgba(0, 0, 0, 0.050);
	-moz-box-shadow: inset 0 1px 1px rgba(255, 255, 255, 1), 0 1px 1px rgba(0, 0, 0, 0.050);
	box-shadow: inset 0 1px 1px rgba(255, 255, 255, 1), 0 1px 1px rgba(0, 0, 0, 0.050);
	-webkit-transition: border linear 0.2s, box-shadow linear 0.2s;
	-moz-transition: border linear 0.2s, box-shadow linear 0.2s;
	-o-transition: border linear 0.2s, box-shadow linear 0.2s;
	transition: border linear 0.2s, box-shadow linear 0.2s;
}
select, textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"], .uneditable-input {
	height: auto;
	padding: 2px 3px;
	font-size: 14px;
	line-height: 30px;
	color: #777777;
	-webkit-border-radius: 1px;
	-moz-border-radius: 1px;
	border-radius: 1px;
	min-height: 36px !important;
}


/*label, input, button, select, textarea {line-height: 25px;}*/

.radio, .checkbox {
	padding-left: 0;min-height: auto;
	-webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: -moz-none;
    -o-user-select: none;
    user-select: none;
}

.radio input[type="radio"], .checkbox input[type="checkbox"] {margin-left: 0px;}
label.checkbox.inline {display: inline-block;}

.control-label {font-weight: bold;}
.from-group .switch {margin-right: 10px; margin-bottom: 10px;}

.panel form {margin-bottom: 0;}
.panel .form-actions {
	margin-bottom: 0;
	background: #ffffff;
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJod…EiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top, #ffffff 0%, #f5f5f5 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#f5f5f5));
	background: -webkit-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: -o-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: -ms-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: linear-gradient(to bottom, #ffffff 0%,#f5f5f5 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f5f5f5',GradientType=0 );
	
	-webkit-box-shadow: inset 0 1px 1px rgba(255, 255, 255, 1), 0 1px 1px rgba(0, 0, 0, 0.050);
	-moz-box-shadow: inset 0 1px 1px rgba(255, 255, 255, 1), 0 1px 1px rgba(0, 0, 0, 0.050);
	box-shadow: inset 0 1px 1px rgba(255, 255, 255, 1), 0 1px 1px rgba(0, 0, 0, 0.050);
	-webkit-transition: border linear 0.2s, box-shadow linear 0.2s;
	-moz-transition: border linear 0.2s, box-shadow linear 0.2s;
	-o-transition: border linear 0.2s, box-shadow linear 0.2s;
	transition: border linear 0.2s, box-shadow linear 0.2s;
}

form .from-group {border-bottom: 1px dashed #c9c9c9;}
	form .from-group.borderless {border-bottom: 0; margin-bottom: 0;}
form .from-group:last-child {margin-bottom: 0;}
form .from-group:last-child {border-bottom: none;}

textarea:focus,
input[type="text"]:focus,
input[type="password"]:focus,
input[type="datetime"]:focus,
input[type="datetime-local"]:focus,
input[type="date"]:focus,
input[type="month"]:focus,
input[type="time"]:focus,
input[type="week"]:focus,
input[type="number"]:focus,
input[type="email"]:focus,
input[type="url"]:focus,
input[type="search"]:focus,
input[type="tel"]:focus,
input[type="color"]:focus,
.uneditable-input:focus {
  border-color: rgba(98, 174, 239, 0.8);
  outline: 0;
  outline: thin dotted \9;
  /* IE6-9 */

  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(98, 174, 239, 0.6);
     -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(98, 174, 239, 0.6);
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(98, 174, 239, 0.6);
}

.input-group-btn .btn {margin-left: -1px;}

select {background-color: #ffffff;border: 1px solid #c9c9c9;}

select[multiple], select[size] {height: 100px;min-width: 250px;margin-bottom: 15px;}

hr {
	margin: 20px 0;
	border: 0;
	border-top: 1px solid #c9c9c9;
	border-bottom: 1px solid #ffffff;
}

/* validation style */
input.error, textarea.error {
	background: url(../images/error.png) no-repeat 99% 6px;
	background: #ffffff;
	background: url(../images/error.png) no-repeat 99% 6px, url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJod…EiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: url(../images/error.png) no-repeat 99% 6px, -moz-linear-gradient(top, #ffffff 0%, #f5f5f5 100%);
	background: url(../images/error.png) no-repeat 99% 6px, -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#f5f5f5));
	background: url(../images/error.png) no-repeat 99% 6px, -webkit-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: url(../images/error.png) no-repeat 99% 6px, -o-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: url(../images/error.png) no-repeat 99% 6px, -ms-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: url(../images/error.png) no-repeat 99% 6px, linear-gradient(to bottom, #ffffff 0%,#f5f5f5 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f5f5f5',GradientType=0 );
	
	border-color: rgba(216, 95, 96, 0.8);
	outline: 0;
	outline: thin dotted \9;
	/* IE6-9 */
	-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(216, 95, 96, 0.6);
	 -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(216, 95, 96, 0.6);
	      box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(216, 95, 96, 0.6);
}

input.valid {
	background: url(../images/success.png) no-repeat 99% 6px;
	background: #ffffff;
	background: url(../images/success.png) no-repeat 99% 6px, url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJod…EiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: url(../images/success.png) no-repeat 99% 6px, -moz-linear-gradient(top, #ffffff 0%, #f5f5f5 100%);
	background: url(../images/success.png) no-repeat 99% 6px, -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#f5f5f5));
	background: url(../images/success.png) no-repeat 99% 6px, -webkit-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: url(../images/success.png) no-repeat 99% 6px, -o-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: url(../images/success.png) no-repeat 99% 6px, -ms-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: url(../images/success.png) no-repeat 99% 6px, linear-gradient(to bottom, #ffffff 0%,#f5f5f5 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f5f5f5',GradientType=0 );
	
	border-color: rgba(114, 195, 128, 0.8);
	outline: 0;
	outline: thin dotted \9;
	/* IE6-9 */
	-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(114, 195, 128, 0.6);
	 -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(114, 195, 128, 0.6);
	      box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(114, 195, 128, 0.6);
}

label.error {color: #f40a0a;font-weight: bold;margin-bottom: 0;font-size: 13px;}

.checker label.error {
	float: left;
	width: 260px;
	padding-left: 30px;
	margin-top: -20px;
	text-align: left;
}

.select2-container {margin-bottom: 0px;}
/*
.select2-container {margin-bottom: 15px;}
*/

/* Tables */
.table {border-color: #c9c9c9;}
.table th, .table td {border-top: 1px solid #c9c9c9;}
.table thead th {vertical-align: middle;}
.table-bordered {
	border: 1px solid #c9c9c9;
	border-left: 1px solid #c9c9c9;
	-webkit-border-radius: 1px;
	-moz-border-radius: 1px;
	border-radius: 1px;
	padding-bottom: 1px;
}
.table-bordered thead:first-child tr:first-child > th:first-child, .table-bordered tbody:first-child tr:first-child > td:first-child, .table-bordered tbody:first-child tr:first-child > th:first-child {
	border-left: 0;
}
.table-bordered thead:last-child tr:last-child > th:first-child, .table-bordered tbody:last-child tr:last-child > td:first-child, .table-bordered tbody:last-child tr:last-child > th:first-child, .table-bordered tfoot:last-child tr:last-child > td:first-child, .table-bordered tfoot:last-child tr:last-child > th:first-child {
	border-left: 0;
}
.table-bordered thead:first-child tr:first-child > th:first-child, .table-bordered tbody:first-child tr:first-child > td:first-child, .table-bordered tbody:first-child tr:first-child > th:first-child {
	-webkit-border-top-left-radius: 1px;
	border-top-left-radius: 1px;
	-moz-border-radius-topleft: 1px;
}
.table-bordered thead:first-child tr:first-child > th:last-child, .table-bordered tbody:first-child tr:first-child > td:last-child, .table-bordered tbody:first-child tr:first-child > th:last-child {
	-webkit-border-top-right-radius: 1px;
	border-top-right-radius: 1px;
	-moz-border-radius-topright: 1px;
}
.table-bordered thead:last-child tr:last-child > th:first-child, .table-bordered tbody:last-child tr:last-child > td:first-child, .table-bordered tbody:last-child tr:last-child > th:first-child, .table-bordered tfoot:last-child tr:last-child > td:first-child, .table-bordered tfoot:last-child tr:last-child > th:first-child {
	-webkit-border-bottom-left-radius: 1px;
	border-bottom-left-radius: 1px;
	-moz-border-radius-bottomleft: 1px;
}
.table-bordered thead:last-child tr:last-child > th:last-child, .table-bordered tbody:last-child tr:last-child > td:last-child, .table-bordered tbody:last-child tr:last-child > th:last-child, .table-bordered tfoot:last-child tr:last-child > td:last-child, .table-bordered tfoot:last-child tr:last-child > th:last-child {
	-webkit-border-bottom-right-radius: 1px;
	border-bottom-right-radius: 1px;
	-moz-border-radius-bottomright: 1px;
}

.table-bordered tbody tr th:first-child {border-left: 0;border-right: 0;}
.table-bordered tbody tr td:first-child {border-left: 0;}
.table tbody + tbody { border-top: 2px solid #c9c9c9;}
.table-bordered th,.table-bordered td {border-left: 1px solid #c9c9c9;}
.table-bordered th:first-child{border-left: 0;}
.table-hover tbody tr:hover > td,
.table-hover tbody tr:hover > th {background: #e6e6e6;}
.table-striped tbody > tr:nth-child(odd) > td, .table-striped tbody > tr:nth-child(odd) > th {background: #f7f7f7;}
.table-bordered thead th, .table-bordered tbody th {text-align: center;}
.table {background: #fcfcfc;}
.table thead, .table tfoot, .table tbody tr th {
	background: url(../images/patterns/furley_bg2.png);
	border-top: 1px solid #c9c9c9;
	border-left: 1px solid #c9c9c9;
	border-right: 1px solid #c9c9c9;
}
.table tfoot{font-weight: bold;}
.table th, .table td{
	-webkit-box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, 1), 0px 1px 0px 0px rgba(255, 255, 255, 1);
	box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, 1), 0px 1px 0px 0px rgba(255, 255, 255, 1);
}
.table tbody {border-left: 1px solid #c9c9c9;border-right: 1px solid #c9c9c9;border-bottom: 1px solid #c9c9c9;}
table .center {text-align: center}
table .vcenter {vertical-align: middle;}
.table tbody tr.success > td { 
	background: #e0f1d6;
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJod…EiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top, #e0f1d6 0%, #cfe0c6 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#e0f1d6), color-stop(100%,#cfe0c6));
	background: -webkit-linear-gradient(top, #e0f1d6 0%,#cfe0c6 100%);
	background: -o-linear-gradient(top, #e0f1d6 0%,#cfe0c6 100%);
	background: -ms-linear-gradient(top, #e0f1d6 0%,#cfe0c6 100%);
	background: linear-gradient(to bottom, #e0f1d6 0%,#cfe0c6 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e0f1d6', endColorstr='#cfe0c6',GradientType=0 );
	
}
.table tbody tr.error > td {
	background: #f3dfdf;
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJod…EiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top, #f3dfdf 0%, #e2cece 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#f3dfdf), color-stop(100%,#e2cece));
	background: -webkit-linear-gradient(top, #f3dfdf 0%,#e2cece 100%);
	background: -o-linear-gradient(top, #f3dfdf 0%,#e2cece 100%);
	background: -ms-linear-gradient(top, #f3dfdf 0%,#e2cece 100%);
	background: linear-gradient(to bottom, #f3dfdf 0%,#e2cece 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f3dfdf', endColorstr='#e2cece',GradientType=0 );
	
}
.table tbody tr.warning > td {
	background: #fff1c4;
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJod…EiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top, #fff1c4 0%, #ede1b4 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#fff1c4), color-stop(100%,#ede1b4));
	background: -webkit-linear-gradient(top, #fff1c4 0%,#ede1b4 100%);
	background: -o-linear-gradient(top, #fff1c4 0%,#ede1b4 100%);
	background: -ms-linear-gradient(top, #fff1c4 0%,#ede1b4 100%);
	background: linear-gradient(to bottom, #fff1c4 0%,#ede1b4 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fff1c4', endColorstr='#ede1b4',GradientType=0 );
	
}
.table tbody tr.info > td {
	background: #daecf8;
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJod…EiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top, #daecf8 0%, #c9dce8 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#daecf8), color-stop(100%,#c9dce8));
	background: -webkit-linear-gradient(top, #daecf8 0%,#c9dce8 100%);
	background: -o-linear-gradient(top, #daecf8 0%,#c9dce8 100%);
	background: -ms-linear-gradient(top, #daecf8 0%,#c9dce8 100%);
	background: linear-gradient(to bottom, #daecf8 0%,#c9dce8 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#daecf8', endColorstr='#c9dce8',GradientType=0 );
	
}
tr.rowlink:hover {cursor: pointer;}
tr.rowlink:hover td.nolink {cursor: default;}
.table-toolbar {
	background: #fcfcfc;
	margin-top: 0;
	margin-bottom: 0;
	padding-top: 10px;
	padding-bottom: 10px;
	border-bottom: 1px solid #c9c9c9;
}
	.table-toolbar .btn-group {margin-left: 20px;margin-right: 20px;}


/* ------------------Pagination--------------------*/
.pagination {
	-webkit-border-radius: 1px;
	-moz-border-radius: 1px;
	border-radius: 1px;
	box-shadow: none;
	margin-bottom: 0;
	margin-top: 0;
}
.pagination ul > li > a, .pagination ul > li > span {
	float: left;
	padding: 4px 12px;
	line-height: 20px;
	margin-left: 8px;
	border: 1px solid #c9c9c9;
	background-color: #f7f7f7;
	-webkit-border-radius: 1px;
	-moz-border-radius: 1px;
	border-radius: 1px;
	-webkit-box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, 1), 0px 1px 0px 0px rgba(255, 255, 255, 1);
	box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, 1), 0px 1px 0px 0px rgba(255, 255, 255, 1);
	font-weight: bold;
}
.pagination ul > li:first-child > a, .pagination ul > li:first-child > span {
	-webkit-border-bottom-left-radius: 1px;
	border-bottom-left-radius: 1px;
	-webkit-border-top-left-radius: 1px;
	border-top-left-radius: 1px;
	-moz-border-radius-bottomleft: 1px;
	-moz-border-radius-topleft: 1px;
}
.pagination ul > li:last-child > a, .pagination ul > li:last-child > span {
	-webkit-border-top-right-radius: 1px;
	border-top-right-radius: 1px;
	-webkit-border-bottom-right-radius: 1px;
	border-bottom-right-radius: 1px;
	-moz-border-radius-topright: 1px;
	-moz-border-radius-bottomright: 1px;
}
.pagination ul > .disabled > span, .pagination ul > .disabled > a, .pagination ul > .disabled > a:hover, .pagination ul > .disabled > a:focus {
	color: #999999;
	cursor: default;
	background-color: #f7f7f7;
	-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=75)";
	filter: alpha(opacity=75);
	opacity: 0.75;
}
.pagination ul > .active > a:hover, .pagination ul > .active > a, .pagination ul > .active > span {
	background: none;
	-ms-filter: none;
	filter: none;
	box-shadow: none;
	border-color: transparent;
	color: inherit;
}
.pagination ul > li > a:hover, .pagination ul > li > a:focus {border-color: #bababa;background: #e6e6e6;}

/* ------------------Buttons--------------------*/
.btn {
	border: 1px solid #c9c9c9;
	-webkit-border-radius: 1px;
	-moz-border-radius: 1px;
	border-radius: 1px;
}
.btn:hover, .btn:focus {
	-webkit-transition: background-position 0.2s linear;
	-moz-transition: background-position 0.2s linear;
	-o-transition: background-position 0.2s linear;
	transition: background-position 0.2s linear;
}
.btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .btn-primary.disabled, .btn-primary[disabled] {
	background-color: #519fe2;
}
.btn-primary {
	color: #ffffff;
	text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
	background-color: #62aeef;
	background-image: -moz-linear-gradient(top, #0088cc, #519fe2);
	background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#0088cc), to(#519fe2));
	background-image: -webkit-linear-gradient(top, #0088cc, #519fe2);
	background-image: -o-linear-gradient(top, #0088cc, #519fe2);
	background-image: linear-gradient(to bottom, #0088cc, #519fe2);
	background-repeat: repeat-x;
	border-color: #519fe2 #519fe2 #002a80;
	border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff0088cc', endColorstr='#ff0044cc', GradientType=0);
	
}
.btn-info:hover, .btn-info:focus, .btn-info:active, .btn-info.active, .btn-info.disabled, .btn-info[disabled] {
	background-color: #4eacc8;
}

.btn-info {
	color: #ffffff;
	text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
	background-color: #49afcd;
	background-image: -moz-linear-gradient(top, #5bc0de, #4eacc8);
	background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#5bc0de), to(#4eacc8));
	background-image: -webkit-linear-gradient(top, #5bc0de, #4eacc8);
	background-image: -o-linear-gradient(top, #5bc0de, #4eacc8);
	background-image: linear-gradient(to bottom, #5bc0de, #4eacc8);
	background-repeat: repeat-x;
	border-color: #4eacc8 #4eacc8 #1f6377;
	border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff5bc0de', endColorstr='#ff2f96b4', GradientType=0);
	
}

.btn-success:hover, .btn-success:focus, .btn-success:active, .btn-success.active, .btn-success.disabled, .btn-success[disabled] {
	background-color: #72b110;
}

.btn-success {
	color: #ffffff;
	text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
	background-color: #5bb75b;
	background-image: -moz-linear-gradient(top, #62c462, #72b110);
	background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#62c462), to(#72b110));
	background-image: -webkit-linear-gradient(top, #62c462, #72b110);
	background-image: -o-linear-gradient(top, #62c462, #72b110);
	background-image: linear-gradient(to bottom, #62c462, #72b110);
	background-repeat: repeat-x;
	border-color: #72b110 #72b110 #387038;
	border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff62c462', endColorstr='#ff51a351', GradientType=0);
	
}

.btn-warning:hover, .btn-warning:focus, .btn-warning:active, .btn-warning.active, .btn-warning.disabled, .btn-warning[disabled] {
	background-color: #f88c00;
}

.btn-warning {
	color: #ffffff;
	text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
	background-color: #faa732;
	background-image: -moz-linear-gradient(top, #fbb450, #f88c00);
	background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#fbb450), to(#f88c00));
	background-image: -webkit-linear-gradient(top, #fbb450, #f88c00);
	background-image: -o-linear-gradient(top, #fbb450, #f88c00);
	background-image: linear-gradient(to bottom, #fbb450, #f88c00);
	background-repeat: repeat-x;
	border-color: #f88c00 #f88c00 #ad6704;
	border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fffbb450', endColorstr='#fff89406', GradientType=0);
	
}

.btn-danger:hover, .btn-danger:focus, .btn-danger:active, .btn-danger.active, .btn-danger.disabled, .btn-danger[disabled] {
	background-color: #d8605f;
}

.btn-danger {
	color: #ffffff;
	text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
	background-color: #da4f49;
	background-image: -moz-linear-gradient(top, #ee5f5b, #d8605f);
	background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ee5f5b), to(#d8605f));
	background-image: -webkit-linear-gradient(top, #ee5f5b, #d8605f);
	background-image: -o-linear-gradient(top, #ee5f5b, #d8605f);
	background-image: linear-gradient(to bottom, #ee5f5b, #d8605f);
	background-repeat: repeat-x;
	border-color: #d8605f #d8605f #802420;
	border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffee5f5b', endColorstr='#ffbd362f', GradientType=0);
	
}

.btn-inverse:hover, .btn-inverse:focus, .btn-inverse:active, .btn-inverse.active, .btn-inverse.disabled, .btn-inverse[disabled] {
	background-color: #6f7a8a;
}

.btn-inverse {
	color: #ffffff;
	text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
	background-color: #363636;
	background-image: -moz-linear-gradient(top, #444444, #6f7a8a);
	background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#444444), to(#6f7a8a));
	background-image: -webkit-linear-gradient(top, #444444, #6f7a8a);
	background-image: -o-linear-gradient(top, #444444, #6f7a8a);
	background-image: linear-gradient(to bottom, #444444, #6f7a8a);
	background-repeat: repeat-x;
	border-color: #6f7a8a #6f7a8a #000000;
	border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff444444', endColorstr='#ff222222', GradientType=0);
	
}

.btn-link {
	color: #7090c8;
	cursor: pointer;
	border-color: transparent;
	-webkit-border-radius: 0;
	-moz-border-radius: 0;
	border-radius: 0;
}

.btn-link, .btn-link:active, .btn-link[disabled] {
	background-color: transparent;
	background-image: none;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
	box-shadow: none;
}
.btn:focus {outline: none;}

/* ------------------dropdowns--------------------*/
.btn-group.open .btn-primary.dropdown-toggle {background-color: #62aeef;}
.dropdown-menu {
	padding: 0;
	margin: 2px 0 0;
	border: 1px solid #c9c9c9;
	-webkit-border-radius: 1px;
	-moz-border-radius: 1px;
	border-radius: 1px;
	-webkit-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
	-moz-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
	box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}
.nav-tabs .dropdown-menu {
	-webkit-border-radius: 0 0 1px 1px;
	-moz-border-radius: 0 0 1px 1px;
	border-radius: 0 0 1px 1px;
}
	.dropdown-menu li {margin-bottom: 0;}
		.dropdown-menu > li > a {padding: 6px 20px;color: #666666; border: 1px solid transparent;}
	.dropdown-menu .divider {height: 1px;margin: 2px 1px;background-color: #c9c9c9;}
	.dropdown-menu > li > a:hover, .dropdown-menu > li > a:focus, .dropdown-submenu:hover > a, .dropdown-submenu:focus > a {
		background-color: #62aeef;
		background-image: -moz-linear-gradient(top, #0088cc, #62aeef);
		background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#0088cc), to(#62aeef));
		background-image: -webkit-linear-gradient(top, #0088cc, #62aeef);
		background-image: -o-linear-gradient(top, #0088cc, #62aeef);
		background-image: linear-gradient(to bottom, #0088cc, #62aeef);
		background-repeat: repeat-x;
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff0088cc', endColorstr='#ff0077b3', GradientType=0);
		
		border-color: #0277b1 #0088cc #0088cc #0088cc 
	}

/* ------------------ Tabs --------------------*/
.nav-tabs {border-bottom: 1px solid #c9c9c9; margin-bottom: 0;}
.nav-tabs > li > a {
	padding-top: 12px;
	padding-bottom: 12px;
	line-height: 20px;
	border: 1px solid #c9c9c9;
	border-right: 1px solid transparent;
	-webkit-border-radius: 1px 1px 0 0;
	-moz-border-radius: 1px 1px 0 0;
	border-radius: 1px 1px 0 0;
	font-weight: bold;
	background: -moz-linear-gradient(top, #ffffff 0%, #f5f5f5 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#f5f5f5));
	background: -webkit-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: -o-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: -ms-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: linear-gradient(to bottom, #ffffff 0%,#f5f5f5 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f5f5f5',GradientType=0 );
	-webkit-box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, 1);
	box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, 1);
	color: #999999;
}
.nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
	color: #666666;
	background: #ffffff;
	border: 1px solid #c9c9c9;
	border-right: 1px solid transparent;
	border-bottom-color: transparent;
	filter:none;
}
.nav-tabs > li > a:hover {color: #62aeef;}
.nav-tabs > li > a, .nav-pills > li > a {
	padding-right: 12px;
	padding-left: 12px;
	margin-right: 0px;
	line-height: 14px;
}
	.nav-tabs > li:last-child > a, .nav-tabs > li.last-child > a {border-right: 1px solid #c9c9c9;}

.tab-content {
	background: #fff;
	border: 1px solid #c9c9c9;
	border-top: 0;
	padding: 15px 15px 15px 15px;
	-webkit-border-radius: 1px;
	-moz-border-radius: 1px;
	border-radius: 1px;
}
.nav-tabs > li > a:hover, .nav-tabs > li > a:focus {border-color: #c9c9c9 transparent #c9c9c9 #c9c9c9;}
.nav-tabs > li:last-child > a:hover, .nav-tabs > li:last-child > a:focus {border-right-color: #c9c9c9;}
.nav > li > a:hover, .nav > li > a:focus {text-decoration: none;background-color: #eeeeee;}
.nav-tabs .open .dropdown-toggle, .nav-pills .open .dropdown-toggle, .nav > li.dropdown.open.active > a:hover, .nav > li.dropdown.open.active > a:focus {
color: #666666;
background-color: inherit;
border-color: #c9c9c9;
}
.nav li.dropdown.open .caret, .nav li.dropdown.open.active .caret, .nav li.dropdown.open a:hover .caret, .nav li.dropdown.open a:focus .caret {
	border-top-color: #999999;
	border-bottom-color: #999999;
	opacity: 1;
	filter: alpha(opacity=100);
}

/* ------------------ Accordion --------------------*/
.accordion-toggle {margin-left: -30px;}
.panel-heading .accordion-toggle:after {
    font-family: 'icomoonregular'; 
    content: "\e3b4"; 
    position: absolute;
	right: 10px;
	top: 12px;
}
.panel-heading .accordion-toggle.collapsed:after {
    content: "\e3b5"; 
}

/* ------------------ Action links --------------------*/
.act {color: #6f7a8a;}
.act:hover {color: #4e535c;text-shadow: 0px 1px 1px rgba(85, 85, 85, 0.2);}
.act-primary {color: #62aeef;}
.act-primary:hover {color: #378cd5;text-shadow: 0px 1px 1px rgba(0, 109, 204, 0.2);}
.act-info {color: #4eacc8;}
.act-info:hover {color: #2b8eab;text-shadow: 0px 1px 1px rgba(75, 175, 206, 0.2);}
.act-success {color: #72b110;}
.act-success:hover {color: #5d9408;	text-shadow: 0px 1px 1px rgba(81, 164, 81, 0.2);}
.act-warning {color: #f88c00;}
.act-warning:hover {color: #d87b02;	text-shadow: 0px 1px 1px rgba(192, 152, 84, 0.2);}
.act-danger {color: #d8605f;}
.act-danger:hover {color: #bc3b3a;text-shadow: 0px 1px 1px rgba(185, 72, 70, 0.2);}

/* ------------------ Progress bars--------------------*/
.progress {
	height: 14px;
	margin-top: 10px;
	margin-bottom: 10px;
	overflow: hidden;
	-webkit-border-radius: 1px;
	-moz-border-radius: 1px;
	border-radius: 1px;
	background: url(../images/patterns/furley_bg1.png) repeat;
	border: 1px solid #c9c9c9;
	-webkit-box-shadow: inset 0 1px 1px rgba(255, 255, 255, 1), 0 1px 1px rgba(0, 0, 0, 0.050);
	-moz-box-shadow: inset 0 1px 1px rgba(255, 255, 255, 1), 0 1px 1px rgba(0, 0, 0, 0.050);
	box-shadow: inset 0 1px 1px rgba(255, 255, 255, 1), 0 1px 1px rgba(0, 0, 0, 0.050);
}

.progress.progress-mini {
	height: 8px;
	margin-top: 13px;
}

.progress .bar {
	background-color: #62aeef;
	background-image: -moz-linear-gradient(top, #0088cc, #519fe2);
	background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#0088cc), to(#519fe2));
	background-image: -webkit-linear-gradient(top, #0088cc, #519fe2);
	background-image: -o-linear-gradient(top, #0088cc, #519fe2);
	background-image: linear-gradient(to bottom, #0088cc, #519fe2);
	background-repeat: repeat-x;
	border-color: #519fe2 #519fe2 #002a80;
	border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff0088cc', endColorstr='#ff0044cc', GradientType=0);
	
}

.progress-striped .bar {
	background-color: #149bdf;
	background-image: -webkit-gradient(linear, 0 100%, 100% 0, color-stop(0.25, rgba(255, 255, 255, 0.15)), color-stop(0.25, transparent), color-stop(0.5, transparent), color-stop(0.5, rgba(255, 255, 255, 0.15)), color-stop(0.75, rgba(255, 255, 255, 0.15)), color-stop(0.75, transparent), to(transparent));
	background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
	background-image: -moz-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
	background-image: -o-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
	background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
	-webkit-background-size: 40px 40px;
	-moz-background-size: 40px 40px;
	-o-background-size: 40px 40px;
	background-size: 40px 40px;
}
.progress-info .bar, .progress .bar-info {
	background-color: #49afcd;
	background-image: -moz-linear-gradient(top, #5bc0de, #4eacc8);
	background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#5bc0de), to(#4eacc8));
	background-image: -webkit-linear-gradient(top, #5bc0de, #4eacc8);
	background-image: -o-linear-gradient(top, #5bc0de, #4eacc8);
	background-image: linear-gradient(to bottom, #5bc0de, #4eacc8);
	background-repeat: repeat-x;
	border-color: #4eacc8 #4eacc8 #1f6377;
	border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff5bc0de', endColorstr='#ff2f96b4', GradientType=0);
	
}

.progress-success .bar, .progress .bar-success {
	background-color: #5bb75b;
	background-image: -moz-linear-gradient(top, #62c462, #72b110);
	background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#62c462), to(#72b110));
	background-image: -webkit-linear-gradient(top, #62c462, #72b110);
	background-image: -o-linear-gradient(top, #62c462, #72b110);
	background-image: linear-gradient(to bottom, #62c462, #72b110);
	background-repeat: repeat-x;
	border-color: #72b110 #72b110 #387038;
	border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff62c462', endColorstr='#ff51a351', GradientType=0);
	
}

.progress-danger .bar, .progress .bar-danger {
	background-color: #da4f49;
	background-image: -moz-linear-gradient(top, #ee5f5b, #d8605f);
	background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ee5f5b), to(#d8605f));
	background-image: -webkit-linear-gradient(top, #ee5f5b, #d8605f);
	background-image: -o-linear-gradient(top, #ee5f5b, #d8605f);
	background-image: linear-gradient(to bottom, #ee5f5b, #d8605f);
	background-repeat: repeat-x;
	border-color: #d8605f #d8605f #802420;
	border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffee5f5b', endColorstr='#ffbd362f', GradientType=0);
	
}

.progress-warning .bar, .progress .bar-warning {
	background-color: #faa732;
	background-image: -moz-linear-gradient(top, #fbb450, #f88c00);
	background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#fbb450), to(#f88c00));
	background-image: -webkit-linear-gradient(top, #fbb450, #f88c00);
	background-image: -o-linear-gradient(top, #fbb450, #f88c00);
	background-image: linear-gradient(to bottom, #fbb450, #f88c00);
	background-repeat: repeat-x;
	border-color: #f88c00 #f88c00 #ad6704;
	border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fffbb450', endColorstr='#fff89406', GradientType=0);
	
}

/* ------------------ Modal --------------------*/
.modal {
	border: 1px solid #c9c9c9;
	-webkit-border-radius: 1px;
	-moz-border-radius: 1px;
	border-radius: 1px;
	-webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);
	-moz-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);
	box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);
	z-index: 99999;
}
.modal-header {
	border-bottom: 1px solid #c9c9c9;
	box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0.05);
	background: #ffffff;
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJod…EiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top, #ffffff 0%, #f5f5f5 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#f5f5f5));
	background: -webkit-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: -o-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: -ms-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: linear-gradient(to bottom, #ffffff 0%,#f5f5f5 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f5f5f5',GradientType=0 );
}
.modal-footer {
	padding: 8px 15px 9px;
	border-top: 1px solid #c9c9c9;
	-webkit-border-radius: 0 0 1px 1px;
	-moz-border-radius: 0 0 1px 1px;
	border-radius: 0 0 1px 1px;
	box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0.05);
	background: #ffffff;
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJod…EiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top, #ffffff 0%, #f5f5f5 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#f5f5f5));
	background: -webkit-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: -o-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: -ms-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: linear-gradient(to bottom, #ffffff 0%,#f5f5f5 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f5f5f5',GradientType=0 );
}

/* ------------------ Popovers --------------------*/
.popover {
	border: 1px solid #c9c9c9;
	-webkit-border-radius: 1px;
	-moz-border-radius: 1px;
	border-radius: 1px;
	-webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
	-moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
	box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
}
.popover-title {
	border-bottom: 1px solid #c9c9c9;
	-webkit-border-radius: 1px 1px 0 0;
	-moz-border-radius: 1px 1px 0 0;
	border-radius: 1px 1px 0 0;
	box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0.05);
	background: #ffffff;
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJod…EiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top, #ffffff 0%, #f5f5f5 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#f5f5f5));
	background: -webkit-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: -o-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: -ms-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: linear-gradient(to bottom, #ffffff 0%,#f5f5f5 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f5f5f5',GradientType=0 );
	font-weight: bold;
}
.popover.bottom .arrow:after {border-bottom-color: #f9f9f9;}

/* ------------------ Well --------------------*/
.well {
	border: 1px solid #c9c9c9;
	-webkit-border-radius: 1px;
	-moz-border-radius: 1px;
	border-radius: 1px;
	box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0.05);
	background: #ffffff;
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJod…EiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top, #ffffff 0%, #f5f5f5 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#f5f5f5));
	background: -webkit-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: -o-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: -ms-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: linear-gradient(to bottom, #ffffff 0%,#f5f5f5 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f5f5f5',GradientType=0 );
}

/* ------------------ Pills --------------------*/
.nav-pills > .active > a, .nav-pills > .active > a:hover, .nav-pills > .active > a:focus {background-color: #62aeef;}
.nav-pills > li > a {
	-webkit-border-radius: 1px;
	-moz-border-radius: 1px;
	border-radius: 1px;
	margin-right: 4px;
}
/* ------------------ Pager --------------------*/
.pager li > a, .pager li > span {
	background-color: #f7f7f7;
	border: 1px solid #c9c9c9;
	webkit-box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, 1), 0px 1px 0px 0px rgba(255, 255, 255, 1);
	box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, 1), 0px 1px 0px 0px rgba(255, 255, 255, 1);
}
.pager li > a:hover, .pager li > a:focus {border-color: #bababa;background: #e6e6e6;}

/* -----------------------------------------
   Page structure
----------------------------------------- */

#header {height: 50px;}
/*.main {overflow: hidden;}*/
#sidebar {width:213px; float: left;}
#content {
	margin-left: 213px;
	-webkit-transition: margin ease-in-out 0.1s;
 	-moz-transition: margin ease-in-out 0.1s;
   	-o-transition: margin ease-in-out 0.1s;
    transition: margin ease-in-out 0.1s;
}
#content.hided, #content.isCollapse.hided, #content.hided.isCollapse {margin-left: 0px;}
#content.isCollapse {margin-left: 39px;}
#content.offCanvas {margin-left: 213px; width: 100%; }
#content .row {margin-bottom: 20px;}

	#content .wrapper {float: left; width: 100%;}
	#content .wrapper>.container-fluid {padding-left: 25px;padding-right: 25px;}
	/* ------------------Header--------------------*/
	#header .navbar {
		-webkit-box-shadow	: inset 0px 1px 0px 0px rgba(0, 0, 0, 1), 0px 1px 0px 0px rgba(0, 0, 0, 0.8);
		box-shadow			: inset 0px 1px 0px 0px rgba(0, 0, 0, 1), 0px 1px 0px 0px rgba(0, 0, 0, 0.8);
		height				: 50px;
		background			: <?php echo $color_secundary; ?>;
		background			: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iIzRlNTI1ZCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiMzNjM4NDAiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
		background			: -moz-linear-gradient(top,  <?php echo $color_secundary; ?> 0%, <?php echo $color_primary; ?> 100%); 
		background			: -webkit-gradient(linear, left top, left bottom, color-stop(0%,<?php echo $color_secundary; ?>), color-stop(100%,<?php echo $color_primary; ?>)); 
		background			: -webkit-linear-gradient(top,  <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%);
		background			: -o-linear-gradient(top,  <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%); 
		background			: -ms-linear-gradient(top,  <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%); 
		background			: linear-gradient(to bottom,  <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%); 
		filter				: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $color_secundary; ?>', endColorstr='<?php echo $color_primary; ?>',GradientType=0 ); /* IE6-8 */
		filter				: none;
		border-width		: 0;
		margin-bottom		: 0;
			
	}

	#header .navbar .navbar-brand {padding: 4px 8px 4px; position: absolute;left: 0;}
		#header .navbar-form {margin-left: 210px;width:300px;margin-top: 11px;}
		#header #top-search.shown {
			position: absolute;
			z-index: 999;
			padding-top: 1px;
			padding-right: 10px;
			padding-left: 10px;
			padding-bottom: 3px;
			margin-left: 0px;
			margin-top: 51px;
			width: 351px;
			background: <?php echo $color_secundary; ?>;
			background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJod…EiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
			background: -moz-linear-gradient(top, <?php echo $color_secundary; ?> 0%, <?php echo $color_primary; ?> 100%);
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,<?php echo $color_secundary; ?>), color-stop(100%,<?php echo $color_primary; ?>));
			background: -webkit-linear-gradient(top, <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%);
			background: -o-linear-gradient(top, <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%);
			background: -ms-linear-gradient(top, <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%);
			background: linear-gradient(to bottom, <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $color_secundary; ?>', endColorstr='<?php echo $color_primary; ?>',GradientType=0 );
			filter: none;
		}
			#header #top-search.shown form {display: inline-block;}
				#header #top-search.shown form .input-btn-group {text-align: center;}
		#header #top-search form {margin-bottom: 0;}
		#header #top-search input#tsearch {
			height: 25px;
			min-height: 30px !important;
			width: 300px;
			-webkit-border-radius:1px;
			border-radius: 1px;
		}
		#header #top-search .btn {
			-webkit-border-radius:1px;
			border-radius: 1px;
			margin-top: 0;
			margin-left: -1px;
			line-height: 20px;
			height: 30px;
		}
	#header .nav > li {margin-bottom: 0;line-height: 19px;}
	#header .nav > li > a {padding: 13px 20px 12px 20px; color: #f4f4f4;margin-left: 1px;margin-right: 1px; float: left;}
	#header .nav a:hover {
		background: <?php echo $color_4; ?>; /* Old browsers */
		/* IE9 SVG, needs conditional override of 'filter' to 'none' */
		background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iIzVjNjc3OCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiMzYTNmNGIiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
		background: -moz-linear-gradient(top,  <?php echo $color_4; ?> 0%, <?php echo $color_3; ?> 100%); /* FF3.6+ */
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,<?php echo $color_4; ?>), color-stop(100%,<?php echo $color_3; ?>)); /* Chrome,Safari4+ */
		background: -webkit-linear-gradient(top,  <?php echo $color_4; ?> 0%,<?php echo $color_3; ?> 100%); /* Chrome10+,Safari5.1+ */
		background: -o-linear-gradient(top,  <?php echo $color_4; ?> 0%,<?php echo $color_3; ?> 100%); /* Opera 11.10+ */
		background: -ms-linear-gradient(top,  <?php echo $color_4; ?> 0%,<?php echo $color_3; ?> 100%); /* IE10+ */
		background: linear-gradient(to bottom,  <?php echo $color_4; ?> 0%,<?php echo $color_3; ?> 100%); /* W3C */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $color_4; ?>', endColorstr='<?php echo $color_3; ?>',GradientType=0 ); /* IE6-8 */
		filter: none;
		
	}
	#header .navbar .nav .active > a, #header .navbar .nav .active > a:hover, #header .navbar .nav .active > a:focus {
		background: <?php echo $color_4; ?>; /* Old browsers */
		/* IE9 SVG, needs conditional override of 'filter' to 'none' */
		background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iIzVjNjc3OCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiMzYTNmNGIiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
		background: -moz-linear-gradient(top,  <?php echo $color_4; ?> 0%, <?php echo $color_3; ?> 100%); /* FF3.6+ */
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,<?php echo $color_4; ?>), color-stop(100%,<?php echo $color_3; ?>)); /* Chrome,Safari4+ */
		background: -webkit-linear-gradient(top,  <?php echo $color_4; ?> 0%,<?php echo $color_3; ?> 100%); /* Chrome10+,Safari5.1+ */
		background: -o-linear-gradient(top,  <?php echo $color_4; ?> 0%,<?php echo $color_3; ?> 100%); /* Opera 11.10+ */
		background: -ms-linear-gradient(top,  <?php echo $color_4; ?> 0%,<?php echo $color_3; ?> 100%); /* IE10+ */
		background: linear-gradient(to bottom,  <?php echo $color_4; ?> 0%,<?php echo $color_3; ?> 100%); /* W3C */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $color_4; ?>', endColorstr='<?php echo $color_3; ?>',GradientType=0 ); /* IE6-8 */
		filter: none;
		text-shadow: 0 1px 2px rgba(0,0,0,.7);
	}

	#header .navbar .divider-vertical {
		background: <?php echo $color_5; ?>;
		-webkit-box-shadow: 1px 0 0 rgba(255, 255, 255, 0.1);
		-moz-box-shadow: 1px 0 0 rgba(255,255,255,0.1);
		box-shadow: 1px 0 0 rgba(255, 255, 255, 0.1);
		height: 50px;
		margin-right: 1px;
		width: 1px;
		border-right: none;
		border-left: none;
		margin:0;
	}

	#header .dropdown.open .dropdown-toggle {
		/*padding-bottom: 27px;*/
		background: <?php echo $color_4; ?>; /* Old browsers */
		/* IE9 SVG, needs conditional override of 'filter' to 'none' */
		background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iIzVjNjc3OCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiMzYTNmNGIiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
		background: -moz-linear-gradient(top,  <?php echo $color_4; ?> 0%, <?php echo $color_3; ?> 100%); /* FF3.6+ */
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,<?php echo $color_4; ?>), color-stop(100%,<?php echo $color_3; ?>)); /* Chrome,Safari4+ */
		background: -webkit-linear-gradient(top,  <?php echo $color_4; ?> 0%,<?php echo $color_3; ?> 100%); /* Chrome10+,Safari5.1+ */
		background: -o-linear-gradient(top,  <?php echo $color_4; ?> 0%,<?php echo $color_3; ?> 100%); /* Opera 11.10+ */
		background: -ms-linear-gradient(top,  <?php echo $color_4; ?> 0%,<?php echo $color_3; ?> 100%); /* IE10+ */
		background: linear-gradient(to bottom,  <?php echo $color_4; ?> 0%,<?php echo $color_3; ?> 100%); /* W3C */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $color_4; ?>', endColorstr='<?php echo $color_3; ?>',GradientType=0 ); /* IE6-8 */
		
		-moz-transition: none;
	    -webkit-transition: none;
	    -o-transition: color 0 ease-in;
	    transition: none;
	}
	#header .dropdown-menu {
		padding: 5px;
		margin-top: 8px; 
		border-radius: 0;
		top: 85%;
		border-top: none; 
		right: 0;
		background: <?php echo $color_3; ?>; 
	}
		#header .dropdown-menu:after, #header .dropdown-menu:before {border:none;}
		#header .dropdown-menu li {background: #f2f2f0;border-bottom: 1px dotted #c9c9c9; margin-bottom:0;}
			#header .dropdown-menu li a {padding: 8px 10px; font-size: 13px;}
			#header .dropdown-menu li a:hover {
				background: url(../images/patterns/cream_dust.png) repeat !important; color: #555;
				-webkit-transition: 0.15s all ease-in; -moz-transition: 0.15s all ease-in; -o-transition: 0.15s all ease-in; transition: 0.15s all ease-in;
				border-color: transparent;
			}
			#header .dropdown-menu li a i {color: #777779; margin-left: 5px;}
			#header .dropdown-menu li a:hover i {color: #63a9dd; -webkit-transition: 0.15s all ease-in; -moz-transition: 0.15s all ease-in; -o-transition: 0.15s all ease-in; transition: 0.15s all ease-in;}
			#header .dropdown-menu li a .notification {margin-left: 10px;}

	#header .dropdown-menu.messages {min-width: 280px;}
	#header .dropdown-menu.messages li.head {
		border-bottom: 1px solid #c9c9c9 !important;
		background: url(../images/patterns/furley_bg.png) repeat !important;
		text-align: right;
	}
	#header .dropdown .notification {margin-left: 5px;float: right;}
	#header .dropdown a i[class*="icon24"] {float: left;}
	#header .dropdown-menu.messages li {float: left;min-width: 100%;}
	#header .dropdown-menu.messages li:last-child {border-bottom: none;}
	#header .dropdown-menu.messages li.head h4 {padding-left: 10px;margin-top: 9px;margin-bottom: 5px;float: left;}
	#header .dropdown-menu.messages li.head .new-msg {border-left: 1px solid #c9c9c9; display: inline-block; position: relative; padding-top: 8px; padding-bottom: 5px;}
	#header .dropdown-menu.messages li.head .new-msg a {}
	#header .dropdown-menu.messages li.head .count {font-style: italic;font-size: 11px;padding: 0 10px;}
	#header .dropdown-menu.messages li a.clearfix {float: left; width: 100%; height: 100%; line-height: 32px; padding: 5px 0;}
	#header .dropdown-menu.messages .avatar {float: left; padding-left: 10px;}
	#header .dropdown-menu.messages .avatar img {width: 32px;height: 32px;}
	#header .dropdown-menu.messages .msg {float: left;padding: 0 10px;}
	#header .dropdown-menu.messages li .btn.close {margin-right: 6px;margin-top: 6px;}
	#header .dropdown-menu.messages .foot {background: url(../images/patterns/furley_bg.png) repeat !important;line-height: 36px;text-align: center;}
	#header .dropdown-menu.messages .foot a {font-size: 14px;font-weight: bold;}

	#header .dropdown-menu.messages li:not(.head):not(.foot) a:hover {
		background: url(../images/patterns/cream_dust.png) repeat !important;
		-webkit-transition: 0.15s all ease-in;
		-moz-transition: 0.15s all ease-in;
		-o-transition: 0.15s all ease-in;
		transition: 0.15s all ease-in;
	}

	#header .user .avatar{padding-top: 9px; padding-bottom: 10px;}
		#header .user .avatar img{width: 32px;height: 32px;}
		#header .user .avatar .more i {margin-right: -5px;}
	/* ------------------Sidebar --------------------*/
	#sidebar {
		-webkit-transition: margin ease-in-out 0.1s;
     	-moz-transition: margin ease-in-out 0.1s;
       	-o-transition: margin ease-in-out 0.1s;
        transition: margin ease-in-out 0.1s;
        margin-left: 0;
	}
	#sidebar.fixed {
		top: 0;
		position: fixed;
		left: 0;
		bottom: 0;
	}
	#sidebar:after {
		content: "";
		width: 213px;
		position: fixed;
		top: 0;
		left: 0;
		bottom: 0;
		border-right: 1px solid #c9c9c9;
		background: url(../images/patterns/furley_bg.png) repeat;
		z-index: -1;
	}
	#sidebar.hided:after {left:-300px;}
	#sidebar.isCollapse:after {width: 38px;}
		.sidebar-wrapper {float: left;width: 100%;}
    #sidebar.isCollapse {width: 39px;}
    #sidebar.hided {
    	margin-left: -300px;
    }
	/* ------------------Side opitons--------------------*/
	.side-options {background: url(../images/patterns/furley_bg.png) repeat; float: left;width: 212px;}
        #sidebar.isCollapse .side-options {width: 37px;}
	.side-options ul {list-style: none; margin:0;float: left;border-bottom: 1px solid #c9c9c9; width: 100%; padding-left: 0;}
		.side-options ul li {margin-bottom: 0;float: left;border-left: 1px solid #c9c9c9;text-align: center;min-width: 100%;}
			.side-options ul li a {
				float: left;
				color: #777777;
				height: 35px;
				line-height: 35px;
				width: 100%;
				position: relative;
			}
			.side-options ul li a#collapse-nav.hided {text-indent: -9999px;}
	/* ------------------ Sidebar.panel --------------------*/
	.sidebar-widget {float: left; width: 100%; margin-top: 0px; margin-bottom: 10px;}
	.sidebar-widget.hided {display: none;}
		.sidebar-widget-header {
			-webkit-box-shadow: inset 0px 1px 0px 0px rgba(0, 0, 0, 1), 0px 1px 0px 0px rgba(0, 0, 0, 0.8);
			box-shadow: inset 0px 1px 0px 0px rgba(0, 0, 0, 1), 0px 1px 0px 0px rgba(0, 0, 0, 0.8);
			color: white;
			padding: 10px 0;
			border-bottom: 1px solid #25272c;
			background: <?php echo $color_secundary; ?>;
			background: -moz-linear-gradient(top, <?php echo $color_secundary; ?> 0%, <?php echo $color_primary; ?> 100%);
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,<?php echo $color_secundary; ?>), color-stop(100%,<?php echo $color_primary; ?>));
			background: -webkit-linear-gradient(top, <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%);
			background: -o-linear-gradient(top, <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%);
			background: -ms-linear-gradient(top, <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%);
			background: linear-gradient(to bottom, <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $color_secundary; ?>', endColorstr='<?php echo $color_primary; ?>',GradientType=0 );
			
		}
	/* ------------------Main navigation--------------------*/
	#mainnav {}
		#mainnav .nav-list {padding-left: 0;padding-right: 0;}
		#mainnav .nav-list>li {
			margin-bottom: 0; 
			border-bottom: 1px solid #c9c9c9;
    		position: relative;
    		margin-right: 1px;
    		display: block;
    	}
    	#mainnav .nav-list>li>a {
    		background: #ffffff; /* Old browsers */
			/* IE9 SVG, needs conditional override of 'filter' to 'none' */
			background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ZmZmZmZiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmNWY1ZjUiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
			background: -moz-linear-gradient(top,  #ffffff 0%, #f5f5f5 100%); /* FF3.6+ */
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#f5f5f5)); /* Chrome,Safari4+ */
			background: -webkit-linear-gradient(top,  #ffffff 0%,#f5f5f5 100%); /* Chrome10+,Safari5.1+ */
			background: -o-linear-gradient(top,  #ffffff 0%,#f5f5f5 100%); /* Opera 11.10+ */
			background: -ms-linear-gradient(top,  #ffffff 0%,#f5f5f5 100%); /* IE10+ */
			background: linear-gradient(to bottom,  #ffffff 0%,#f5f5f5 100%); /* W3C */
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f5f5f5',GradientType=0 ); /* IE6-8 */
			
			color: #777777;
			padding: 0;
			text-shadow: none;
			margin-left: 0;
			margin-right: 0;
			line-height: 39px;
			font-size: 13px;
			position: relative;
    	}
    		#mainnav .nav-list>li>a .icon {
    			color: #777777;float: left;border-left: 1px solid #c9c9c9; border-right: 1px solid #c9c9c9; width: 38px; height: 39px; box-shadow: none;text-align: center;
    		}
    		#mainnav .nav-list li a .notification {float: right;line-height: 20px;margin-top: 9px;margin-right: 9px;}
    		#sidebar.isCollapse #mainnav .nav-list li a .notification {display: none;}
    		#mainnav .nav-list>li>a .icon i {margin-left: 0;margin-right: 0;}
    		#mainnav .nav-list>li>a .icon:before{
				position: absolute;
				top: 0;
				left:1px;
				width: 36px;
				height: 38px;
				content: "";
				-webkit-box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, 1), 0px 1px 0px 0px rgba(255, 255, 255, 1);
				box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, 1), 0px 1px 0px 0px rgba(255, 255, 255, 1);
			}
			#mainnav .nav-list>li>a .icon:after{
				position: absolute;
				top: 0;
				left:1px;
				width: 35px;
				height: 39px;
				content: "";
				-webkit-box-shadow: inset 1px 0px 0px 0px rgba(255, 255, 255, 1), 1px 0px 0px 0px rgba(255, 255, 255, 1);
				box-shadow: inset 1px 0px 0px 0px rgba(255, 255, 255, 1), 1px 0px 0px 0px rgba(255, 255, 255, 1);
			}
			#mainnav .nav-list>li> a .txt {padding-left: 10px;}
			#mainnav .nav-list li  a .more {float: right;line-height: 20px; margin-top: 10px;}
				#mainnav .nav-list>li> a .more i {}
				#mainnav ul li a.rotateIn .icon i {
					transition: all .5s;
					-moz-transition: all .5s; 
					-webkit-transition: all .5s;
					-o-transition: all .5s;
					-moz-transform: rotate(360deg);
					-webkit-transform: rotate(360deg);
					-o-transform: rotate(360deg);
					transform: rotate(360deg);
				}
				#mainnav ul li a.rotateOut .icon i {
					transition: all .5s;
					-moz-transition: all .5s; 
					-webkit-transition: all .5s;
					-o-transition: all .5s;
					-moz-transform: rotate(-360deg);
					-webkit-transform: rotate(-360deg);
					-o-transform: rotate(-360deg);
					transform: rotate(-360deg);
				}

			/* Current states */
			#mainnav .nav-list>li.current>a .icon {background: #62aeef;}
			#mainnav .nav-list>li.current>a {background: url(../images/patterns/furley_bg2.png);}
				#mainnav .nav-list>li.current>a .icon i {color: #f2f2f2;}
				#mainnav .nav-list>li.current> .sub li.current {border-top: 1px solid #c9c9c9;}
				#mainnav .nav-list>li.current> .sub li.current {border-bottom: 1px solid #c9c9c9;}
				#mainnav .nav-list>li.current> .sub li.current:first-child {border-top: 1px solid transparent;}
				#mainnav .nav-list>li.current> .sub li.current:last-child {border-bottom: 1px solid transparent;}
				#mainnav .nav-list>li.current> .sub li.current a {background: url(../images/patterns/furley_bg2.png); }
				#mainnav .nav-list>li.current> .sub li.current a:after {
					display: inline-block;
					content: "";
					position: absolute;
					left: 0;
					top: -1px;
					bottom: 0;
					border-left: 3px solid #62aeef;
					width: 100%;
					height: 40px;
				}
			/* Hover states */
			#mainnav .nav-list>li:hover> a {background: url(../images/patterns/furley_bg2.png);}
				#mainnav .nav-list>li> .sub li:hover {border-top: 1px solid #c9c9c9;border-bottom: 1px solid #c9c9c9;background: url(../images/patterns/furley_bg1.png);}
				#mainnav .nav-list>li> .sub li:first-child:hover {border-top: 1px solid transparent;}
				#mainnav .nav-list>li> .sub li:last-child:hover {border-bottom: 1px solid transparent;}

			/* Sub states */
			#mainnav .nav-list>li> .sub, #mainnav .nav-list>li> .sub>li .sub {
				display: none;
				list-style: none;
				margin: 0;
				padding: 0;
				position: relative;
				border-top: 1px solid #c9c9c9;
				background: #ffffff; /* Old browsers */
				/* IE9 SVG, needs conditional override of 'filter' to 'none' */
				background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ZmZmZmZiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmNWY1ZjUiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
				background: -moz-linear-gradient(top,  #ffffff 0%, #f5f5f5 100%); /* FF3.6+ */
				background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#f5f5f5)); /* Chrome,Safari4+ */
				background: -webkit-linear-gradient(top,  #ffffff 0%,#f5f5f5 100%); /* Chrome10+,Safari5.1+ */
				background: -o-linear-gradient(top,  #ffffff 0%,#f5f5f5 100%); /* Opera 11.10+ */
				background: -ms-linear-gradient(top,  #ffffff 0%,#f5f5f5 100%); /* IE10+ */
				background: linear-gradient(to bottom,  #ffffff 0%,#f5f5f5 100%); /* W3C */
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f5f5f5',GradientType=0 ); /* IE6-8 */
				
			}
			#mainnav .nav-list>li> .sub li {margin-bottom: 0;border-top: 1px solid transparent;border-bottom: 1px solid transparent;}
			#mainnav .nav-list>li> .sub li a{
				font-size: 13px; 
				text-decoration: none; 
				color: #777;
				line-height: 37px;
				display: block;
				padding-left: 20px;
				position: relative;
			}
				#mainnav .nav-list>li> .sub>li .sub>li>a {padding-left: 40px; position: relative;}
			#mainnav .nav-list>li> .sub li a .txt {padding-left: 10px;}
			#mainnav .nav-list>li> .sub li a .icon i{font-size: 16px;}
			#mainnav .nav-list>li> .sub.show {display: block;}

			#mainnav .nav-list li.hasSub ul.sub li.hasSub ul.sub.show {border-bottom: 1px solid #c9c9c9;}

			/* Collapsed nav  */

			#sidebar.isCollapse .nav-list>li {width: 38px;height: 40px;position: relative;}
			#sidebar.isCollapse .nav-list>li>a {float: left;}
			#sidebar.isCollapse .nav-list>li>a .txt {
				display: none;
				position: absolute;
				left: 37px;
				top: -1px;
				width: 175px;
				height: 41px;
				line-height: 40px;
				z-index: 9998;
				border: 1px solid #c9c9c9;
			}
			#sidebar.isCollapse .nav-list>li>ul.sub {
				position: absolute;
				z-index: 9999;
				left: 37px;
				top: 39px;
				width: 175px;
				border: 1px solid #c9c9c9;
				display: none!important;
			}
			#sidebar.isCollapse .nav-list>li>ul.sub li>ul.sub {
				position: absolute;
				z-index: 9999;
				left: 173px;
				top: -1px;
				width: 175px;
				border: 1px solid #c9c9c9;
			}

			#sidebar.isCollapse .nav-list>li ul.sub li a { padding-left: 10px;}

			#sidebar.isCollapse .nav-list>li a .more {display: none;}

			#sidebar.isCollapse .nav-list>li:hover a .txt {display: inline-block;}
			#sidebar.isCollapse .nav-list>li:hover> a .txt {background: url(../images/patterns/furley_bg2.png); }
			#sidebar.isCollapse .nav-list>li:hover>ul.sub, #sidebar.isCollapse .nav-list>li>ul.sub>li:hover>ul.sub{display: block !important;}
	
	/* ------------------Breadcrumb--------------------*/		
	.crumb {
		width: 100%;
		height: 36px;
		background: url(../images/patterns/furley_bg.png) repeat;
		border-bottom: 1px solid #c9c9c9;
	}
	#content.hided .crumb, #content.isCollapse.hided .crumb, #content.hided.isCollapse .crumb {
		padding-left: 0px;
	}
	#content.isCollapse .crumb {padding-left: 0px;}
		.crumb .breadcrumb {
			background: none;
			border-radius: 0;
			margin: 0;
			padding: 0 15px 0 30px;
			line-height: 35px;
		}
	
	/* ------------------Heading area--------------------*/
	#heading {
		text-transform: uppercase;
		padding-bottom: 0;
		border-bottom: 1px solid #c9c9c9;
		-webkit-box-shadow:  0px 1px 0px 0px rgba(255, 255, 255, 1);box-shadow:  0px 1px 0px 0px rgba(255, 255, 255, 1);
		position: relative;
		margin: 0 -25px 25px -25px;
		padding-left: 20px;
		background: white;
	}
	#heading:after, #heading:before {
		top: 100%;
		border: solid transparent;
		content: " ";
		height: 0;
		width: 0;
		position: absolute;
		pointer-events: none;
	}
	#heading:before {
		border-color: rgba(201, 201, 201, 0);
		border-top-color: #c9c9c9;
		border-width: 11px;
		left: 47px;
		margin-left: -11px;
	}
	#heading:after {
		border-color: rgba(255, 255, 255, 0);
		border-top-color: #fff;
		border-width: 10px;
		left: 47px;
		margin-left: -10px;
	}
		#heading h1 {
			font-size: 15px;
			margin-top: 1px;
			margin-bottom: 0;
			margin-right: 20px;
			margin-left: 12px;
			display: inline-block;
			line-height: 36px;
		}

/* ------------------ Back to top--------------------*/
#back-to-top {position: fixed;z-index: 1000;bottom: 20px;right: 20px;display: none;}
#back-to-top a {display: block;width: 40px;height: 40px;background: #666666 url(../images/backtotop.png) no-repeat center center;
	text-indent: -9999px;-webkit-border-radius: 1px;-moz-border-radius: 1px;border-radius: 1px;-webkit-transition: 0.4s all ease;
	-moz-transition: 0.4s all ease;-o-transition: 0.4s all ease;transition: 0.4s all ease;
}
#back-to-top a:hover {background-color: #62aaef;}

/* -----------------------------------------
  Custom.panels
----------------------------------------- */

/* ------------------Widget box--------------------*/

.panel {
	padding: 0;
	border: 1px solid #c9c9c9;
	-webkit-box-shadow: inset 1px 1px 0px 0px rgba(222, 2222, 222, 0.1), 1px 1px 0px 0px rgba(255, 255, 255, 1);
	box-shadow: inset 1px 1px 0px 0px rgba(222, 222, 222, 0.1), 1px 1px 0px 0px rgba(255, 255, 255, 1);
	margin-bottom: 1px;
	background: #fefefe;
	position: relative;
}
.panel.second {margin-top: 20px;} 
.panel.plain {background: #ffffff;}
.panel.closed .panel-heading {border-bottom: 1px solid #c9c9c9;}
	.panel .panel-heading {
		height: auto;
		padding: 0;
		position: relative;
		box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0.05);
		background: #ffffff; /* Old browsers */
		/* IE9 SVG, needs conditional override of 'filter' to 'none' */
		background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ZmZmZmZiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmNWY1ZjUiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
		background: -moz-linear-gradient(top,  #ffffff 0%, #f5f5f5 100%); /* FF3.6+ */
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#f5f5f5)); /* Chrome,Safari4+ */
		background: -webkit-linear-gradient(top,  #ffffff 0%,#f5f5f5 100%); /* Chrome10+,Safari5.1+ */
		background: -o-linear-gradient(top,  #ffffff 0%,#f5f5f5 100%); /* Opera 11.10+ */
		background: -ms-linear-gradient(top,  #ffffff 0%,#f5f5f5 100%); /* IE10+ */
		background: linear-gradient(to bottom,  #ffffff 0%,#f5f5f5 100%); /* W3C */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f5f5f5',GradientType=0 ); /* IE6-8 */
		
	}
	.panel.drag .panel-heading {
		cursor: move;
		border-bottom: 1px solid #c9c9c9;
	}
		.panel .panel-heading.pattern1 {background: url(../images/patterns/debut_light.png) repeat;}
		.panel.plain .panel-heading {border-bottom: none; box-shadow: none; background: none;padding: 2px 0 3px;filter: none;}
		.panel .panel-heading h4 {margin-top: 0; margin-bottom: 0; font-weight: 700; color: #777777; display: inline-block;padding:11px 0 11px 50px;}
		.panel.plain .panel-heading h4 {padding-left: 0;}
			.panel.plain .panel-heading i {margin-top: 0px;}
		.panel .panel-heading .icon {
			float: left;
			border-right: 1px solid #c9c9c9;
			width: 41px;
			height: 100%;
			box-shadow: none;
			position: absolute;
			top: 0;
			left: 1px;
			text-align: center
		}
		.panel .panel-heading .icon:after {
			position: absolute;
			top: 0;
			left:0;
			width: 39px;
			height: 98%;
			content: "";
			-webkit-box-shadow: inset 1px 0px 0px 0px rgba(255, 255, 255, 1), 1px 0px 0px 0px rgba(255, 255, 255, 1);
			box-shadow: inset 1px 0px 0px 0px rgba(255, 255, 255, 1), 1px 0px 0px 0px rgba(255, 255, 255, 1);
		}
		.panel .panel-heading .icon:before {
			position: absolute;
			top: 0;
			left:1px;
			width: 38px;
			height: 97%;
			content: "";
			-webkit-box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, 1), 0px 1px 0px 0px rgba(255, 255, 255, 1);
			box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, 1), 0px 1px 0px 0px rgba(255, 255, 255, 1);
		}
			.panel .panel-heading .icon i {color:#777777;margin-top: 9px;}
		.panel.plain .panel-heading i {margin-left: 15px;}

		.panel .panel-heading .minimize, .panel .panel-heading .maximize {
			width: 24px; height: 24px;
			float: right;
			margin-top: 9px;
			margin-right: 10px;
			border:1px solid transparent;
		}
		.panel .panel-heading .minimize:hover, .panel .panel-heading .maximize:hover {border:1px dashed #c9c9c9; -webkit-transition: 0.15s all ease-in; -moz-transition: 0.15s all ease-in; -o-transition: 0.15s all ease-in; transition: 0.15s all ease-in;}
		.panel .panel-heading .minimize:after {
			content: "";
			display: block;
			width: 0;
			height: 0;
			border: solid 6px;
			border-color: transparent transparent #777 transparent;
			margin-top: 2px;
			margin-left: 5px;
		}
		.panel .panel-heading .maximize:after {
			content: "";
			display: block;
			width: 0;
			height: 0;
			border: solid 6px;
			border-color: #777 transparent transparent transparent;
			margin-top: 9px;
			margin-left: 4px;
		}
		.panel .panel-heading .w-right {float: right;margin-top: 5px;margin-bottom: 5px;margin-right: 10px;padding-top: 5px;}
			.panel .panel-heading .w-right .btn-full {
				height: 40px;
				margin-top: -10px;
				margin-right: -10px;
			}
			.panel .panel-heading .w-right .progress {min-width: 100px; margin-top: 8px;}
			.panel .panel-heading .w-right .search-query {
				margin-bottom: 0;
				min-height: 24px !important;
				line-height: 24px;
				margin-top: -5px;
				background: #ffffff;
			}
			.panel .panel-heading .w-right div.selector {margin-top: -5px;height: 30px; background: white;}
				.panel .panel-heading .w-right div.selector span {height: 30px;background-position: right -3px;}
				.panel .panel-heading .w-right div.selector select {top: -8px; height: 30px;}

			.panel .panel-heading .w-right .has-switch {margin-top: -9px;}
	.panel .panel-body {padding: 15px;}
		.panel .panel-body table {margin-bottom: 0;}
		.panel .panel-body.noPadding {padding: 0;}
		.panel .panel-body.noPadding table {
			margin-bottom: 0;
			border-left: 0;
			border-right: 0;
			border-bottom: 0;
			border-top: 0;
		}
			.panel .panel-body.noPadding table thead th {box-shadow: none;}
		.panel .panel-body.noPadding .nav-tabs > li > a {border-top-color: transparent;}
		.panel .panel-body.noPadding .nav-tabs > li:first-child > a {border-left-color: transparent;}
		.panel .panel-body.noPadding .tab-content {border-left: 0;border-right: 0; border-bottom: 0;}
		.panel .panel-body.slideDown {
			display: block;
		}
		.panel .panel-body.slideUp {
			display: none;
		}

.sortable-placeholder {
	border:1px dashed #c9c9c9;
	box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0.05);
	background: #ffffff;
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJod…EiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top, #ffffff 0%, #f5f5f5 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#f5f5f5));
	background: -webkit-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: -o-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: -ms-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: linear-gradient(to bottom, #ffffff 0%,#f5f5f5 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f5f5f5',GradientType=0 );
}

/* ------------------Vital stats--------------------*/
.vital-stats {width: 100%; height: auto;padding-top: 15px;}
	.vital-stats ul {list-style: none; margin-left: 0;margin-bottom: 0;}
	.vital-stats li {display: inline-block; margin-bottom: 0;}
		.vital-stats .item {
			text-align: left;
			width: 152px; height:50px; background: #f5f5f5;
			-webkit-box-shadow:  0px 0px 0px 1px rgba(0, 0, 0, 0.1);box-shadow:  0px 0px 0px 1px rgba(0, 0, 0, 0.1);
			-moz-border-radius: 2px; -webkit-border-radius: 2px; border-radius: 2px;
			margin-right: 15px;margin-bottom: 15px;
		}
		.vital-stats .item:hover {
			background: #fcfcfc;
			-webkit-box-shadow:  0px 0px 0px 1px rgba(0, 0, 0, 0.1);box-shadow:  0px 0px 0px 1px rgba(0, 0, 0, 0.1);
			-webkit-transition: 0.15s all ease-in; -moz-transition: 0.15s all ease-in; -o-transition: 0.15s all ease-in; transition: 0.15s all ease-in;
		}
			.vital-stats .item .icon {
				float: left; 
				width: 50px; height: 50px;  
				-moz-border-radius: 2px; -webkit-border-radius: 2px; border-radius: 2px;
				background: #6f7a8a;
			}
			.vital-stats .item:hover .icon i {
				-webkit-animation-name: rotateIn;
				-moz-animation-name: rotateIn;
				-o-animation-name: rotateIn;
				animation-name: rotateIn;
				-webkit-animation-fill-mode: both;
			    -moz-animation-fill-mode: both;
			    -o-animation-fill-mode: both;
			    animation-fill-mode: both;
			    -webkit-animation-duration: 1s;
			    -moz-animation-duration: 1s;
			    -o-animation-duration: 1s;
			    animation-duration: 1s;
			}
			.vital-stats .item .icon.red {background: #f40a0a;}
			.vital-stats .item .icon.blue {background: #62aeef;}
			.vital-stats .item .icon.green {background: #72b110;}
			.vital-stats .item .icon.yellow {background: #f7cb38;}
			.vital-stats .item .icon.orange {background: #F88C00;}
				.vital-stats .item .icon i {font-size: 24px; padding: 12px; color: white; margin-left: 0;margin-right: 0;}

			.vital-stats .item .percent {float: right; width: 90px; font-weight: bold; font-size: 24px; color: #666;height: 25px; line-height: 32px;}
			.vital-stats .item .txt {float: right; width: 90px; color: #b3b0b0; text-transform: uppercase; height: 25px; font-size: 11px; line-height: 25px;}

/* ------------------Stats buttons--------------------*/

.stats-buttons {float: left;}
.stats-buttons ul {margin:0; text-align: center;padding-left: 0;}
.stats-buttons li {display: inline-block; margin-bottom: 0;}
.stats-buttons li.center {display: inline-block; float: none;}
	.stats-buttons li a {
		float: left; 
		border: 1px solid #c9c9c9;
		width: 150px; height: 140px;
		margin-bottom: 10px;
		margin-right: 10px;
		text-align: center;
		background: url(../images/patterns/debut_light.png) repeat;
		-webkit-box-shadow: inset 1px 1px 0px 0px rgba(255, 255, 255, 1), 0px 1px 1px 0px rgba(255, 255, 255, 1);
		box-shadow: inset 1px 1px 0px 0px rgba(255, 255, 255, 1), 0px 1px 1px 0px rgba(255, 255, 255, 1);
		-webkit-transition: 0.15s all ease-in; -moz-transition: 0.15s all ease-in;
		-o-transition: 0.15s all ease-in; transition: 0.15s all ease-in;
	}
	.stats-buttons li a:hover {
		background: url(../images/patterns/debut_light1.png) repeat;
		-webkit-box-shadow: 0px 0px 4px 0px rgba(0, 0, 0, 0.1);
		box-shadow: 0px 0px 4px 0px rgba(0, 0, 0, 0.1);
	}
	.stats-buttons li a:hover .icon {
		-webkit-animation-name: rotateIn;
		-moz-animation-name: rotateIn;
		-ms-animation-name: rotateIn;
		-o-animation-name: rotateIn;
		animation-name: rotateIn;

		-webkit-animation-fill-mode: both;
		-moz-animation-fill-mode: both;
		-ms-animation-fill-mode: both;
		-o-animation-fill-mode: both;
		animation-fill-mode: both;
		-webkit-animation-duration: 1s;
		-moz-animation-duration: 1s;
		-o-animation-duration: 1s;
		animation-duration: 1s;
	}
	.stats-buttons li:last-child a {}

		/* ------------------red color is defaulth--------------------*/
		.stats-buttons li a .icon {
			float: left;
			width: 50px;
			height: 50px;
			border: 1px solid #d90000; 
			background: #f40a0a;
			-webkit-border-radius: 25px;
			border-radius: 25px;
			-webkit-box-shadow:inset 0px 0px 4px 0px #ffffff, 0px 0px 4px 0px #c9c9c9;
          	box-shadow:inset 0px 0px 4px 0px #ffffff, 0px 0px 4px 0px #c9c9c9;
          	margin: 10px 50px 5px;
		}
		.stats-buttons li a .icon.green {background: #72b110;border: 1px solid #72b110;}
		.stats-buttons li a .icon.blue {background: #62aeef;border: 1px solid #3693e2;}
		.stats-buttons li a .icon.yellow {background: #e7d246;border: 1px solid #d0ba24;}
			.stats-buttons li a .icon.gray {background: #777777;border: 1px solid #5b5a5a;}
			.stats-buttons li a .icon i {color: white; padding: 9px 1px 9px 1px;}
		.stats-buttons li a .number {width: 100%; float: left; font-weight: 700; font-size: 24px; margin:5px 0 5px; color: #777777;}
		.stats-buttons li a .txt {width: 100%; float: left; text-transform: uppercase; font-size: 12px; color: #999999;}

/* ------------------spark stats --------------------*/
/*Stats buttons with sparklines */
.spark-stats {display: inline-block;}
.spark-stats ul {margin:0;}
.spark-stats li {display: inline-block; margin-bottom: 0;}
.spark-stats li a {
	position: relative;
	float: left; 
	width: 152px; height: 50px; 
	border:1px solid #d7d7d7; 
	background: url(../images/patterns/debut_light.png) repeat;
	-webkit-box-shadow: inset 1px 1px 0px 0px rgba(255, 255, 255, 1), 0px 1px 1px 0px rgba(255, 255, 255, 1);
	box-shadow: inset 1px 1px 0px 0px rgba(255, 255, 255, 1), 0px 1px 1px 0px rgba(255, 255, 255, 1);
	-webkit-transition: 0.15s all ease-in; -moz-transition: 0.15s all ease-in;
	-o-transition: 0.15s all ease-in; transition: 0.15s all ease-in;
	text-decoration: none;
	margin-right: 15px;
}
.spark-stats li a:hover {
	background: url(../images/patterns/debut_light1.png) repeat;
	-webkit-box-shadow: 0px 0px 4px 0px rgba(0, 0, 0, 0.1);
	box-shadow: 0px 0px 4px 0px rgba(0, 0, 0, 0.1);
}
.spark-stats li a:hover .up {
	-webkit-animation-name: bounceInUp;
	-moz-animation-name: bounceInUp;
	-o-animation-name: bounceInUp;
	animation-name: bounceInUp;
	-webkit-animation-fill-mode: both;
    -moz-animation-fill-mode: both;
    -o-animation-fill-mode: both;
    animation-fill-mode: both;
    -webkit-animation-duration: 0.5s;
    -moz-animation-duration: 0.5s;
    -o-animation-duration: 0.5s;
    animation-duration: 0.5s;
}
.spark-stats li a:hover .down {
	-webkit-animation-name: bounceInDown;
	-moz-animation-name: bounceInDown;
	-o-animation-name: bounceInDown;
	animation-name: bounceInDown;
	-webkit-animation-fill-mode: both;
    -moz-animation-fill-mode: both;
    -o-animation-fill-mode: both;
    animation-fill-mode: both;
    -webkit-animation-duration: 0.5s;
    -moz-animation-duration: 0.5s;
    -o-animation-duration: 0.5s;
    animation-duration: 0.5s;
}
	.spark-stats a .txt {width: 80%; height: 20px;display: inline-block;font-size: 16px;font-weight: 700;margin-left: 10px;margin-top: 5px; color: #474749;}
	.spark-stats a .number{font-size: 16px; font-weight: bold;margin-left: 5px;}
	.spark-stats a .positive .number {color: #42b449;}
	.spark-stats a .negative .number {color: #db4a37;}
		.spark-stats a .up {
			display: block;
			width: 0;
			height: 0;
			border: solid 10px;
			border-color: transparent transparent #42b449 transparent;
			margin-top: -5px;
			margin-left: 10px;
			float: left;
		}
		.spark-stats a .down {
			display: block;
			width: 0;
			height: 0;
			border: solid 10px;
			border-color: #db4a37 transparent transparent transparent;
			margin-top: 5px;
			margin-left: 10px;
			float: left;
			
		}
	.spark-stats a .spark {float: right;margin-right: 10px;}
	.jqstooltip {padding: 5px;display: inline-block;}
	.jqsfield { color: white;text-align: left;padding: 5px;margin-left: -5px; margin-right: 5px;margin-top: -5px;margin-bottom: 5px;}

/* ------------------Campaign stats--------------------*/
.campaign-stats {
	width: 100%;
	height: auto;
	float: left;
	border-top: 1px solid #c9c9c9;
	margin-top: 15px;
	margin-bottom: -15px;
}
.circular-bar {border-top: none; width: 100%; height: auto;float: left;}
.campaign-stats .items, .circular-bar .items {display: inline-block; margin: 15px};
.campaign-stats .percentage, .circular-bar .percentage {
    text-align: center;
    color: #333;
    font-weight: 100;
    font-size: 1.2em;
    margin-bottom: 0.3em;
}
.campaign-stats .items span, .circular-bar .items span {font-weight: bold;}
.campaign-stats .txt, .circular-bar .txt {text-align: center;margin-top: 5px;font-weight: bold;}

/* ------------------Social stats--------------------*/
.social-stats {float: right; width: 50%;margin-top: 15px;}
.social-stats ul {list-style: none; margin-bottom: 0;margin-left: 0;}
	.social-stats li.item {display: inline-block;}
		.social-stats li.item .icon {float: left;width: 100%;margin-bottom: 10px;}
			.social-stats li.item .icon i {font-size: 42px;}
		.social-stats li.item .number {float: left; width: 100%;margin-bottom: 0px;font-weight: bold;}
		.social-stats li.item .txt {float: left;width: 100%;}

/* ------------------ Recent activity --------------------*/
.recent-activity {list-style:none; margin-left: 0; margin-bottom: 0; padding-left: 0;}
.recent-activity li.item {margin-left: 50px;margin-right: 20px;padding: 20px 0; position: relative; margin-bottom: 0; border-bottom: 1px dashed #c9c9c9;}
.recent-activity li:last-child {border-bottom: none;}
.recent-activity li.last-child {border-bottom: 0;}
	.recent-activity li .icon {
		float: left;
		width: 32px;
		height: 32px;
		border: 1px solid #ba3938; 
		background: #d8605f;
		-webkit-border-radius: 16px;
		border-radius: 16px;
		-webkit-box-shadow:inset 0px 0px 4px 0px #ffffff, 0px 0px 4px 0px #c9c9c9;
      	box-shadow:inset 0px 0px 4px 0px #ffffff, 0px 0px 4px 0px #c9c9c9;
      	margin-left: -40px;

	}
	.recent-activity li .icon:after {
		content: "";
		position: absolute;
		top: 61px;
		left: -25px;
		border-top: none;
		border-left: 2px solid #c9c9c9;
		height: 16px;
		width: 2px;
	}
	.recent-activity li:last-child .icon:after {border:none;}
	.recent-activity li.last-child .icon:after {border-left:0;}
		.recent-activity li .icon.green {background: #72b110;border: 1px solid #72b110;}
		.recent-activity li .icon.blue {background: #62aeef;border: 1px solid #3693e2;}
		.recent-activity li .icon.yellow {background: #e7d246;border: 1px solid #d0ba24;}
			.recent-activity li .icon.yellow i {color: #555555;}
		.recent-activity li .icon.dark {background: #777777;border: 1px solid #5b5a5a;}
		.recent-activity li .icon.orange {background: #F88C00;border: 1px solid #ca7302;}
		.recent-activity li .icon.gray {background: #fcfcfc;border: 1px solid #999999;}
			.recent-activity li .icon.gray i {color: #999999;}
		.recent-activity li .icon i {color: white; padding: 6px 2px 5px;}
	.recent-activity li.item .text {margin-left: 10px; margin-top: 5px;display: inline-block;}
		.recent-activity li.item .text a {font-weight: bold; color: #d8605f;}
	.recent-activity li .ago {color: #999999; font-weight: bold; font-size: 12px; margin-right: 10px; margin-left: 10px;}
	.recent-activity.stripes li:nth-child(even) {background: #f8f8f8;}

/* ------------------ Recent users --------------------*/
.recent-users {list-style:none; margin-left: 0; margin-bottom: 0; padding-left: 0;}
.recent-users li {line-height: 40px; margin-bottom: 0; border-bottom: 1px dashed #c9c9c9; padding: 10px 0;}
.recent-users li:last-child {border-bottom: none;}
	.recent-users li .image {float: left; margin-right: 15px;}
		.recent-users li .image img {
			width: 40px; 
			height: 40px; 
			border-radius: 1px;
			-webkit-border-radius:1px;
		}
	.recent-users li .name {font-weight: bold; margin-right: 10px;}
		.recent-users li .name a {color: #6f7a8a;}
	.recent-users li .status { float: left; margin-right: 10px;}
		.recent-users li .status.online i {color: #72b110;}
		.recent-users li .status.offline i {color: #d8605f;}
	.recent-users .actions {float: right; margin-right: 15px;}
	.recent-users.stripes li:nth-child(even) {background: #f8f8f8;}

/* ------------------ Contact.panel --------------------*/
.contact-nav { margin:10px 0 0 0; position: relative;}
.ln-letters { overflow:hidden; padding: 0 15px;}
.ln-letters a { font-size:14px; display:block; float:left; padding:5px 10px; text-decoration:none; font-weight: bold; }
.ln-letters a:hover,
.ln-letters a.ln-selected { background-color:#f2f2f2; }
.ln-letters a.ln-disabled { color:#999999; }
.ln-letter-count { text-align:center; font-size:13px; line-height:1; margin-bottom:10px; color:#555555; font-weight: bold; }
.ln-no-match {
	font-weight: bold;
	margin:15px !important;
	padding: 15px !important;
	border-bottom: none !important;
	color: #8d313d;
	border-color: #ceabab;
	background: #f3dfdf;
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJod…EiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top, #f3dfdf 0%, #e2cece 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#f3dfdf), color-stop(100%,#e2cece));
	background: -webkit-linear-gradient(top, #f3dfdf 0%,#e2cece 100%);
	background: -o-linear-gradient(top, #f3dfdf 0%,#e2cece 100%);
	background: -ms-linear-gradient(top, #f3dfdf 0%,#e2cece 100%);
	background: linear-gradient(to bottom, #f3dfdf 0%,#e2cece 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f3dfdf', endColorstr='#e2cece',GradientType=0 );
	
}

.contact-list {list-style:none; margin-left: 0; margin-bottom: 0; margin-top: 10px; padding-left: 0;}
.contact-list li {margin-bottom: 0; border-top: 1px dashed #c9c9c9; padding: 10px 0;margin-left: 0;margin-right: 0;}
.contact-list li.row-fluid {margin-bottom: 0 !important;}
.contact-list li .image {float: left; margin-left: 15px;margin-right: 15px;}
	.contact-list li .image img {
		width: 40px; 
		height: 40px; 
		border-radius: 1px;
		-webkit-border-radius:1px;
	}
	.contact-list li .name {font-weight: bold; float: left;margin-left: 15px;}
	.contact-list li .location {float: left;margin-left: 10px;}

	.contact-list li .status { float: left;padding: 10px 12px 10px 0;}
		.contact-list li .status.online i {color: #72b110;}
		.contact-list li .status.offline i {color: #d8605f;}
	
		.contact-list li .name a {color: #6f7a8a;}
	.contact-list li .email {font-weight: bold;color: #d8605f;}
	.contact-list li .phone, .contact-list li .mobile {float: left;}
	.contact-list li .actions a {width: 100%; float: left;}

/* ------------------ Chat layout --------------------*/
.chat-layout {}
.chat-layout ul {list-style:none; margin-left: 0; margin-bottom: 0; padding-left: 0;}
.chat-layout li {margin-bottom: 10px;}
	.chat-layout li .user {float: left; margin-right: 15px;}
	.chat-layout li.rightside .user {float: right; margin-left: 15px; margin-right: 0;}
	.chat-layout li.rightside .message .name {text-align: right;}
		.chat-layout li .user .avatar {margin-top: 10px;}
			.chat-layout li .user .avatar img {width: 40px;height: 40px;}
		.chat-layout li .user .ago {color: #999; font-weight: bold; font-size: 12px;}
	.chat-layout li .message {
		position: relative;
		border: 1px solid #c9c9c9;
		-webkit-border-radius: 1px;
		border-radius: 1px;
		padding: 10px;
		margin-left: 55px;
		background: #fbfbfb;
		-webkit-box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, 1), 0px 1px 0px 0px rgba(255, 255, 255, 1);
		box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, 1), 0px 1px 0px 0px rgba(255, 255, 255, 1);
	}
	.chat-layout li.rightside .message {
		margin-right: 55px;
		margin-left: 0;
	}
	.chat-layout li.leftside .message:after, .chat-layout li.leftside .message:before {
		right: 100%;
		border: solid transparent;
		content: " ";
		height: 0;
		width: 0;
		position: absolute;
		pointer-events: none;
	}
	.chat-layout li.rightside .message:after, .chat-layout li.rightside .message:before {
		left: 100%;
		border: solid transparent;
		content: " ";
		height: 0;
		width: 0;
		position: absolute;
		pointer-events: none;
	}
	.chat-layout li.leftside .message:after {
		border-color: rgba(255, 255, 255, 0);
		border-right-color: #fbfbfb;
		border-width: 8px;
		top: 70%;
		margin-top: -8px;
	}
	.chat-layout li.rightside .message:after {
		border-color: rgba(255, 255, 255, 0);
		border-left-color: #fbfbfb;
		border-width: 8px;
		top: 70%;
		margin-top: -8px;
	}
	.chat-layout li.leftside .message:before {
		border-color: rgba(201, 201, 201, 0);
		border-right-color: #c9c9c9;
		border-width: 9px;
		top: 70%;
		margin-top: -9px;
	}
	.chat-layout li.rightside .message:before {
		border-color: rgba(201, 201, 201, 0);
		border-left-color: #c9c9c9;
		border-width: 9px;
		top: 70%;
		margin-top: -9px;
	}
		.chat-layout li .message .name {color: #6f7a8a; font-weight: bold; width: 100%; display: inline-block;}
		.chat-layout li .message .txt {font-size: 13px;}
.chat-layout form {margin-top: 10px;}
.chat-layout .btn {font-weight: bold; line-height: 26px;}

/* ------------------ Weather.panel--------------------*/
.weather ul {list-style:none; margin-left: 0; margin-bottom: 0; margin-top: 10px; border-top: 1px dashed #c9c9c9; padding-left: 0;}
.weather li {
	margin-bottom: 0;
	display: inline;
	padding: 10px 0 0 0;
	width: 16.6%;
	float: left;
	text-align: center;
	border-right: 1px dashed #c9c9c9;
}
.weather li:last-child {border-right: none;}
	.weather .pull-left {width: 50%;}
	.weather .pull-right {width: 50%;}
	.weather .location {margin-bottom: 15px; font-weight: bold;}
	.weather .icon {
		display: inline-block;
		width: 100%;
		text-align: right;
		padding-right: 40px;
		margin-bottom: 5;
	}
	.weather .degree {
		font-size: 100px;
		margin-top: -12px;
		display: inline-block;
		width: 100%;
		text-align: left;
		margin-bottom: 5px;
		text-shadow: 0px 1px 0px rgba(0,0,0,0.5);
	}
	.weather .today {
		display: inline-block;
		width: 100%;
		margin-top: 5px;
		margin-left: 5px;
		font-weight: bold;
		text-align: right;
		padding-right: 50px;
		margin-bottom: 5px;
	}
	.weather li .day {text-transform: uppercase; color: #666; margin-bottom: 5px;display: inline-block;}
	.weather li .dayicon {width: 100%; display: inline-block; margin-bottom: 5px;}
	.weather li .max {width: 100%; display: inline-block; font-weight: bold; color: #62aaef;}
	.weather li .min {width: 100%; display: inline-block; color: #999;}

/* ------------------ ToDo.panel--------------------*/
.toDo {}
.toDo h4 {}
.toDo ul {list-style:none; margin-left: 0; margin-bottom: 10px; min-height: 40px; padding-left: 0;}
.toDo ul.ui-sortable {cursor: move;}
.toDo li {margin-bottom: 0;border-top: 1px dashed #c9c9c9; padding: 10px 15px; position: relative;}
.toDo li:last-child {border-bottom: 1px dashed #c9c9c9;}
.toDo li:nth-child(even) {background: #f8f8f8;}
.toDo li.done {
	-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=50)";
	filter: alpha(opacity=50);
	opacity: 0.5;
}
	.toDo li .chBox {}
	.toDo li .priority {margin-left: 10px;margin-right: 10px;} 
		.toDo li .priority.high i {color: #d8605f;}
		.toDo li .priority.medium i {color: #F88C00;}
		.toDo li .priority.normal i { color: #72b110;}
	.toDo li .category {margin-right: 15px; float: right;}
	.toDo li .task {font-weight: bold;}
		.toDo li.done .task {text-decoration: line-through;}
		.toDo li.show .task {color: #d8605f;}
	.toDo li .act {display: none; position: absolute; right: 3px; top: 12px;}
		.toDo li.show .act {display: block;}

.addToDo {display: none;}

/* -----------------------------------------
   Plugins custom styles 
----------------------------------------- */

/* ------------------ Mention.js --------------------*/
.mention_name{font-size:12px;}
.mention_username{font-size:12px;color:#999;}
.mention_image{
	float: left;
	margin-right: 5px;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	width: 20px;
	height: 20px;
}
.active .mention_username{color:#fff;}

/* ------------------jpreloader Styles--------------------*/
#jpreOverlay {
	background: url(../images/patterns/debut_light.png) repeat;
}
#jpreLoader{width:100%;height:5px;}
#jpreBar{background-color:#62aeef;}

/* ------------------ Flot tooltip --------------------*/
#flotTip {
	padding: 5px;
	color: white;
	border-radius: 3px;
	-webkit-box-shadow: inset 0px 1px 0px 0px rgba(0, 0, 0, 1), 0px 1px 0px 0px rgba(0, 0, 0, 0.8);
	box-shadow: inset 0px 1px 0px 0px rgba(0, 0, 0, 1), 0px 1px 0px 0px rgba(0, 0, 0, 0.8);
	background: <?php echo $color_secundary; ?>;
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iIzRlNTI1ZCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiMzNjM4NDAiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top,  <?php echo $color_secundary; ?> 0%, <?php echo $color_primary; ?> 100%); 
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,<?php echo $color_secundary; ?>), color-stop(100%,<?php echo $color_primary; ?>)); 
	background: -webkit-linear-gradient(top,  <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%);
	background: -o-linear-gradient(top,  <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%); 
	background: -ms-linear-gradient(top,  <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%); 
	background: linear-gradient(to bottom,  <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%); 
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $color_secundary; ?>', endColorstr='<?php echo $color_primary; ?>',GradientType=0 );
	
}


/* ------------------Pie chart label--------------------*/
.pie-chart-label {
	font-size: 12px;
	text-align: center;
	background: #fff;
	color: #000;
	border:1px solid #c9c9c9;
	border-radius: 1px;
	-webkit-border-radius:1px;
	-moz-border-radius:1px;
	padding:2px 10px;
	-moz-box-shadow:0 1px 0px rgba(0, 0, 0, 0.1);
	-webkit-box-shadow: 0 1px 0px rgba(0, 0, 0, 0.1);
	box-shadow: 0 1px 0px rgba(0, 0, 0, 0.1);
	opacity: 0.9;
}

/* ------------------Easy pie chart--------------------*/
.easyPieChart {position: relative; text-align: center;}
.easyPieChart canvas {position: absolute;top: 0; left: 0;}

/* ------------------Full calendar external event style--------------------*/
.external-event {
	margin: 10px 0;
	padding: 4px 6px;
	background: #72c380;
	color: #fff;
	font-size: .85em;
	cursor: pointer;
	border: 1px solid #3ea04f;
	border-radius: 2px;
	-webkit-border-radius: 1px;
	-moz-border-radius: 1px;
	-moz-box-shadow: 0 1px 0px rgba(255, 255, 255, 1);
	-webkit-box-shadow: 0 1px 0px rgba(255, 255, 255, 1);
	box-shadow: 0 1px 0px rgba(255, 255, 255, 1);
	cursor: move;
}

/* ------------------Input limiter --------------------*/

.limiterBox {
	margin-top:15px;
	margin-left: 5px;
	padding: 3px 6px;
	font-size: 11px;
	width: 100%;
	border-radius: 1px;
	-moz-border-radius: 1px;
	-webkit-border-radius: 1px;
	float: left;
	border: 1px solid #000;
	background: <?php echo $color_secundary; ?>;
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJod…EiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top, <?php echo $color_secundary; ?> 0%, <?php echo $color_primary; ?> 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,<?php echo $color_secundary; ?>), color-stop(100%,<?php echo $color_primary; ?>));
	background: -webkit-linear-gradient(top, <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%);
	background: -o-linear-gradient(top, <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%);
	background: -ms-linear-gradient(top, <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%);
	background: linear-gradient(to bottom, <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $color_secundary; ?>', endColorstr='<?php echo $color_primary; ?>',GradientType=0 );
	
	color: white;
	position: relative;
}
.limiterBox:after, .limiterBox:before {
	bottom: 100%;
	border: solid transparent;
	content: " ";
	height: 0;
	width: 0;
	position: absolute;
	pointer-events: none;
}
.limiterBox:after {
	border-color: rgba(78, 82, 93, 0);
	border-bottom-color: <?php echo $color_secundary; ?>;
	border-width: 10px;
	left: 30px;
	margin-left: -10px;
}
.limiterBox:before{
	border-color: rgba(0, 0, 0, 0);
	border-bottom-color: #000;
	border-width: 11px;
	left: 30px;
	margin-left: -11px;
}

/* ------------------ Range slider --------------------*/
.ui-rangeSlider-label-value input {
	width: 1.5em;
	height: 1.5em;
	min-height: auto !important;
	margin-bottom: 0;
	line-height: 16px;
	font-size: 11px;
	font-weight: bold;
	padding: 0;
	margin-top: -2px;
}

/* ------------------Prettify--------------------*/
.com { color: #93a1a1; }
.lit { color: #195f91; }
.pun, .opn, .clo { color: #93a1a1; }
.fun { color: #dc322f; }
.str, .atv { color: #ca3131; }
.kwd, .prettyprint .tag { color: #1e347b; }
.typ, .atn, .dec, .var { color: teal; }
.pln { color: #48484c; }
.prettyprint { padding: 8px; background-color: #f7f7f9; border: 1px solid #e1e1e8; }
.prettyprint.linenums { -webkit-box-shadow: inset 40px 0 0 #fbfbfc, inset 41px 0 0 #ececf0; -moz-box-shadow: inset 40px 0 0 #fbfbfc, inset 41px 0 0 #ececf0; box-shadow: inset 40px 0 0 #fbfbfc, inset 41px 0 0 #ececf0; }
ol.linenums { margin: 0 0 0 33px; list-style-type: decimal; }
ol.linenums li { padding-left: 12px; color: #bebec5; line-height: 20px; text-shadow: 0 1px 0 #fff; }

/* ------------------ Wizard styles --------------------*/
.ui-formwizard {margin-top: 15px;position: relative;}
.wizard-steps.show {
	width: 100%;
	height: auto;
	padding: 0  0 15px 0;
	border-bottom: 1px dashed #c9c9c9;
	margin-bottom: 20px;
	text-align: center;
}
.wizard-steps.hide{display: none;}
	.wizard-steps .wstep {
		display: inline-block; 
		width: 150px;
		position: relative;
		color: #c9c9c9;
		font-weight: bold;
		font-size: 13px;
		margin-right: 20px;
	}
	.wizard-steps .wstep.current {color: #6f7a8a;}
	.wizard-steps .wstep.done {color: #72b110;}
		.wizard-steps .wstep:after {
			content: "";
			position: absolute;
			top: 24px;
			left: 110px;
			border-top: 2px solid #c9c9c9;
			height: 2px;
			width: 100px;
		}
		.wizard-steps .wstep:last-child:after{border: none;}
		.wizard-steps .wstep.last-child:after{border: none;}
	.wizard-steps .wstep .donut { 
	    border: 5px solid #c9c9c9;
	    border-radius: 50px;
	    height:50px;
	    width:50px;
	    margin-left: 50px;
	    position: relative;
	    margin-bottom: 5px;
	}
	.wizard-steps .wstep.current .donut {border-color: #6f7a8a;}
	.wizard-steps .wstep.done .donut {border-color: #72b110;}
		.wizard-steps .wstep .donut i {color: #c9c9c9;margin-left: 6px;margin-top: 7px;}
		.wizard-steps .wstep.current .donut i {color: #6f7a8a;} 
		.wizard-steps .wstep.done .donut i {color: #72b110;} 
.ui-formwizard-button.pull-left i {margin-left: 0;}
.ui-formwizard-button.pull-right i {margin-right: 0;}
/* vertical styles */
.wizard-vertical .wizard-steps.show {
	width: 200px;
	border-bottom: none;
	border-right: 1px dashed #c9c9c9;
	float: left;
	margin-bottom: 10px;
	
}
	.wizard-vertical .wizard-steps .wstep {width: 150px;margin-bottom: 30px;}
	.wizard-vertical .wizard-steps .wstep .donut {
		margin-left: 0;
		position: relative;
		margin-bottom: 5px;
		float: left;
	}
	.wizard-vertical .wizard-steps .wstep .txt {display: inline-block; width: 100px;}
		.wizard-vertical .wizard-steps .wstep:after {
			top: 55px;
			left: 24px;
			border-top: none;
			border-left: 2px solid #c9c9c9;
			height: 25px;
			width: 2px;
		}
		.wizard-vertical  .wizard-steps .wstep:last-child:after {border: none;}
		.wizard-vertical  .wizard-steps .wstep.last-child:after {border-left: 0;}
.wizard-vertical .step {display: inline-block;float: left;width: 100%;}
.wizard-vertical .wrap {padding-left: 210px;}
.wizard-vertical .form-actions {clear: both;}

/* ------------------ Datatables--------------------*/
.dataTables_wrapper .row {margin-bottom: 0 !important;}
.dataTables_length {margin-bottom: 15px;float: left;}

/* ------------------ Gmap plugin--------------------*/
.gmap3{
	width: 100%;
	height: 250px;
	border: 1px solid #c9c9c9;
	position: relative;
}
	.gmap3 img {max-width: none !important;}
	.gmap3 .localized {font-weight: bold; color: #f40a0a; text-align: center; margin-top: 20px;}
	.gmap3 .infow{width:250px;height:150px;}

/* ------------------ Animated progress bar--------------------*/
.pbar .ui-progressbar-value {display:block !important}
.pbar {overflow: hidden}
.percent1 {position:relative;float: left; margin-top: 5px;}
.elapsed {position:relative;float: right; margin-top: 5px;}

/* -----------------------------------------
   Animation effects thanks to animate.css
----------------------------------------- */

@-webkit-keyframes rotateIn {
	0% {-webkit-transform-origin: center center;-webkit-transform: rotate(-180deg);}
	100% {-webkit-transform-origin: center center;-webkit-transform: rotate(0);}
}
@-moz-keyframes rotateIn {
	0% {-moz-transform-origin: center center;-moz-transform: rotate(-180deg);}
	100% {-moz-transform-origin: center center;-moz-transform: rotate(0);}
}
@-o-keyframes rotateIn {
	0% {-o-transform-origin: center center;-o-transform: rotate(-180deg);}
	100% {-o-transform-origin: center center;-o-transform: rotate(0);}
}
@keyframes rotateIn {
	0% {transform-origin: center center;transform: rotate(-180deg);}
	100% {transform-origin: center center;transform: rotate(0);}
}
.rotateIn {-webkit-animation-name: rotateIn;-moz-animation-name: rotateIn;-o-animation-name: rotateIn;animation-name: rotateIn;}

@-webkit-keyframes bounceInUp {
	0% {-webkit-transform: translateY(15px);}
	30% {-webkit-transform: translateY(-15px);}
	60% {-webkit-transform: translateY(-10px);}
	80% {-webkit-transform: translateY(10px);}
	100% {-webkit-transform: translateY(0);}
}
@-moz-keyframes bounceInUp {
	0% {-moz-transform: translateY(15px);}
	30% {-moz-transform: translateY(-15px);}
	60% {-moz-transform: translateY(-10px);}
	80% {-moz-transform: translateY(10px);}
	100% {-moz-transform: translateY(0);}
}
@-o-keyframes bounceInUp {
	0% {-o-transform: translateY(15px);}
	30% {-o-transform: translateY(-15px);}
	60% {-o-transform: translateY(-10px);}
	80% {-o-transform: translateY(10px);}
	100% {-o-transform: translateY(0);}
}
@keyframes bounceInUp {
	0% {transform: translateY(15px);}
	30% {transform: translateY(-15px);}
	60% {transform: translateY(-10px);}
	80% {transform: translateY(10px);}
	100% {transform: translateY(0);}
}
.bounceInUp {-webkit-animation-name: bounceInUp;-moz-animation-name: bounceInUp;-o-animation-name: bounceInUp;animation-name: bounceInUp;}

@-webkit-keyframes bounceInDown {
	0% {-webkit-transform: translateY(-20px);}
	30% {-webkit-transform: translateY(20px);}
	60% {-webkit-transform: translateY(10px);}
	80% {-webkit-transform: translateY(-10px);}
	100% {-webkit-transform: translateY(0);}
}
@-moz-keyframes bounceInDown {
	0% {-moz-transform: translateY(-20px);}
	30% {-moz-transform: translateY(20px);}
	60% {-moz-transform: translateY(10px);}
	80% {-moz-transform: translateY(-10px);}
	100% {-moz-transform: translateY(0);}
}
@-o-keyframes bounceInDown {
	0% {-o-transform: translateY(-20px);}
	30% {-o-transform: translateY(20px);}
	60% {-o-transform: translateY(10px);}
	80% {-o-transform: translateY(-10px);}
	100% {-o-transform: translateY(0);}
}

@keyframes bounceInDown {
	0% {transform: translateY(-20px);}
	30% {transform: translateY(20px);}
	60% {transform: translateY(10px);}
	80% {transform: translateY(-10px);}
	100% {transform: translateY(0);}
}

.bounceInDown {-webkit-animation-name: bounceInDown;-moz-animation-name: bounceInDown;-o-animation-name: bounceInDown;animation-name:bounceInDown;}

/* -----------------------------------------
   Login page
----------------------------------------- */
html.loginPage {
	background: url(../images/patterns/debut_light.png) repeat;
	height: auto;
}

#login {
	width: 480px;
	margin-left: -240px;
	padding-bottom: 20px;
	margin-top: -200px;
	position: absolute;
    left: 50%;
    top: 50%;
    border: 1px solid #c9c9c9;
    background: white;
    -webkit-box-shadow: 1px 1px 0px 0px rgba(255, 255, 255, 1);
	box-shadow: 1px 1px 0px 0px rgba(255, 255, 255, 1);
}
#login .navbar-brand {
	padding: 5px 8px 5px;
	width: 100%;
	float: left;
	margin-top: -90px;
	text-align: center;
	font-size: 24px;
	font-weight: 200;
	margin-bottom: 15px;
	text-decoration: none;
	color: #666;
}

#login .login-wrapper {
	width: 320px;
	margin-left: auto;
	margin-right: auto;
	position: relative;
}
	#login .login-wrapper #avatar {
		width: 90px;
		height: 90px;
		border: 1px solid #c9c9c9;
		background: white;
		position: absolute;
		left: -125px;
		top: -50px;
		float: left;
		padding:5px;
	}
	#login .login-wrapper #avatar img {float: left;}

	#login .login-wrapper form .from-group {border-bottom: none; margin-bottom: 10px; position: relative;}
	#login .login-wrapper form label {margin-bottom: 0;}
	#login .login-wrapper .icon {
		position: absolute; 
		top: 1px;
		left:1px;
		height: 34px;
		width: 36px;
		border-right: 1px solid #C9c9c9;
	}
	#login .login-wrapper .icon i {margin-left: 8px;margin-top: 6px;}
	#login .login-wrapper .from-group input.form-control {padding-left: 46px !important;}
	#login .login-wrapper .or strong{
		background: white;
		padding-left: 10px;
		padding-right: 10px;
	}
	#login .login-wrapper .seperator {
		margin-top: -10px;
	}

	#login #bar {
		position: absolute;
		right: -43px;
		top: -1px;
	}

	#login #bar[data-active="log"] a#log, #login #bar[data-active="reg"] a#reg, #login #bar[data-active="forgot"] a#forgot {
		background: white;
		border-left: 1px solid transparent;
		box-shadow: none;
		margin-left: 0;
		border-bottom-left-radius: 0;
	}

	#login .login-wrapper #log,#login .login-wrapper #reg,#login .login-wrapper #forgot {display: none;}

	#login .login-wrapper[data-active="log"] #log, #login .login-wrapper[data-active="reg"] #reg, #login .login-wrapper[data-active="forgot"] #forgot {
		display: block;
	}

/* -----------------------------------------
	Error pages and offline page
----------------------------------------- */
html.errorPage {
	background: url(../images/patterns/debut_light.png) repeat;
}

.errorContainer {
	width: 480px;
	margin-left: -240px;
	padding-bottom: 20px;
	margin-top: -200px;
	position: absolute;
    left: 50%;
    top: 50%;
    border: 1px solid #c9c9c9;
    background: white;
    -webkit-box-shadow: 1px 1px 0px 0px rgba(255, 255, 255, 1);
	box-shadow: 1px 1px 0px 0px rgba(255, 255, 255, 1);
	display: none;
}
.errorContainer form {margin-bottom: 10px;}
.errorContainer .page-header h1 {
	font-size: 128px; line-height: 150px;
    text-shadow: 4px 3px 0px #fff, 9px 8px 0px rgba(0,0,0,0.15);
}
	.errorContainer .page-header h1.offline {font-size: 100px; line-height: 124px;}
.errorContainer .or strong{
	background: white;
	padding-left: 10px;
	padding-right: 10px;
}
.errorContainer .seperator {
	margin-top: -10px;
}

/* -----------------------------------------
	Other pages
----------------------------------------- */

/* ------------------ Profile page --------------------*/
.profile-avatar {border: 1px solid #c9c9c9; float: left; padding: 2px; margin-right: 20px;}
.profile-avatar img {width: 75px;height: 75px;}

/* ------------------ FAQ page--------------------*/
.faq-search {
	position: relative;
}
	.faq-search form {margin-bottom: 0; position: relative;}
	.faq-search .search-btn {
		background: url(../images/search.png) no-repeat;
		border: none;
		width:20px;
		height:20px;
		position: absolute;
		top:15px;
		left: 7px;
	}
.categories ul {list-style: none;}
	.categories ul li {line-height: 32px;}
	.categories a {color: #555; text-decoration: underline; font-weight: 700;}
	.categories a:hover {color: #666;text-decoration: none;}

.popular-question ul {list-style: none;}
	.popular-question a {color: #555; text-decoration: underline; font-weight: 700;}
	.popular-question a:hover {color: #666;text-decoration: none;}
.popular-question .txt {margin-bottom: 0;}
.popular-question .info {
	font-size: 11px;
	margin-top: -5px;
	display: inline-block;
	color: #666;
}

/* ------------------ Email page--------------------*/
#email {position: relative;}
#email .email-bar {
	border: 1px solid #c9c9c9; 
	border-left: none;
	border-right: none;
	height: 36px;
	background: #ffffff;
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJod…EiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top, #ffffff 0%, #f5f5f5 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#f5f5f5));
	background: -webkit-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: -o-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: -ms-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
	background: linear-gradient(to bottom, #ffffff 0%,#f5f5f5 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f5f5f5',GradientType=0 );
	
	margin-bottom: 20px;
}	
	#email .email-bar .navbar-form {padding: 0;margin: 0; margin-top: -1px; width: 220px;}
	#email .email-bar .navbar-form .srch {margin-left: -15px; margin-right: 15px;}
	#email .email-bar .btn {padding: 6px 12px 5px; height: 36px; margin-top: -1px;line-height: 23px;}
	#email .email-bar .navbar-form .btn {margin-top: 0;}
	#email .email-bar .navbar-form .input-group {margin-top:-1px; width: 218px;}

	#email .email-nav {width: 210px;padding: 5px;float: left;margin-top: 15px;margin-left: 15px;}
		#email .email-nav li {margin-bottom: 0;}
		#email .email-nav li a {text-shadow: none; padding: 10px 15px; font-weight: bold; font-size: 14px;}
		#email .email-nav li a .notification {margin-left: 10px; float: right;}
		#email .email-nav li.active a {background: url(../images/patterns/furley_bg1.png) repeat; color: #666;}

	#email .email-wrapper {padding-left: 240px; background: white; border: 1px solid #c9c9c9; padding: 15px 15px 15px 240px;}
	#email .email-list {position: relative;}
	#email .email-list ul {}
	#email .email-list li {margin-bottom: 0;border-bottom: 1px dashed #c9c9c9; float: left; width: 100%;}
	#email .email-list li:nth-child(odd) {background: #f7f7f7;}
	#email .email-list a {padding: 10px 0px;float: left;; text-decoration: none; color: #999999; width: 100%; border-left: 5px solid transparent;}
		#email .email-list a .name{font-weight: bold; color: #d8605f; width: 50%;float: left; padding-left: 10px;}
		#email .email-list a .date{font-size: 75%; float: right; width: 50%; text-align: right; padding-right: 10px;}
		#email .email-list a .txt{ width: 100%; clear: both; margin-top: 5px; float: left; padding: 0 10px;}
	#email .email-list a:hover {border-left-color: #62aaef; background: #fcfcfc;}
	#email .email-list li.active a {background: white;}
	#email .email-list li.header{
		background: #ffffff;
		background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJod…EiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
		background: -moz-linear-gradient(top, #ffffff 0%, #f5f5f5 100%);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#f5f5f5));
		background: -webkit-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
		background: -o-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
		background: -ms-linear-gradient(top, #ffffff 0%,#f5f5f5 100%);
		background: linear-gradient(to bottom, #ffffff 0%,#f5f5f5 100%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#f5f5f5',GradientType=0 );
		
		border: 1px solid #c9c9c9;
		padding: 5px 15px;
	}
	#email .email-content .email-subject {width: 100%;}
	#email .email-content .from {float: left; margin-right: 10px;}
	#email .email-content .to {float: left; font-weight: bold; color: #999;}
	#email .email-content .date {float: right; margin-right: 10px;color: #999;font-size: 85%;}
	#email .email-content .message {width: 100%; float: left; clear: both; margin-top: 10px;}

/* ------------------ Search page --------------------*/
.search-results {list-style:none; margin-bottom: 20px; float: left; padding-left: 0;}
.search-results li {margin-bottom: 0; float: left; width: 100%; border-bottom: 1px dashed #c9c9c9; padding: 10px 15px;}
.search-results a.search-string {float: left;width: 100%; font-weight: bold;}
.search-results li .search-url {width: 100%; margin-bottom: 5px; float: left;}
.search-results li .search-type {float: left; margin-right: 10px;}
.search-results li .search-txt{width: 100%; float: left; clear: both;}
.search-results li:nth-child(even) {background: #f7f7f7;}

/* ------------------ Gallery --------------------*/
#gallery a[data-gallery="gallery"]{
	border: 1px solid #c9c9c9;
	margin: 5px;
	float: left;
	padding: 5px;
}

/* ------------------Responsive buttons --------------------*/
#resBtn {margin-top: 7px; margin-left: 20px; margin-right: 5px; float: left;position: absolute;}
/*#resBtnSearch {margin-top: 7px; margin-left: 10px;margin-right: 5px; float: left;position: absolute;left: 75px;}*/
.navbar-toggle {
	padding: 7px 10px;
	margin-top: 7px;
	margin-right: 15px;
	margin-bottom: 6px;
	background-color: transparent;
	border: 1px solid transparent;
	border-radius: 1px;
}
.navbar-toggle i {margin-left: 5px; vertical-align: bottom;}
.navbar-default .navbar-toggle {border-color: #ac2925;}

/* ------------------ Navbar collapse --------------------*/
.navbar-collapse {
	width: 100%;
	border-top: 0;
	box-shadow: none;
	background: <?php echo $color_secundary; ?>;
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJod…EiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top, <?php echo $color_secundary; ?> 0%, <?php echo $color_primary; ?> 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,<?php echo $color_secundary; ?>), color-stop(100%,<?php echo $color_primary; ?>));
	background: -webkit-linear-gradient(top, <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%);
	background: -o-linear-gradient(top, <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%);
	background: -ms-linear-gradient(top, <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%);
	background: linear-gradient(to bottom, <?php echo $color_secundary; ?> 0%,<?php echo $color_primary; ?> 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $color_secundary; ?>', endColorstr='<?php echo $color_primary; ?>',GradientType=0 );
}

/* ------------------ Fixed with styles --------------------*/
html.fixedWidth {background: url(../images/patterns/cream_dust.png) repeat;}
html.fixedWidth body {display: block; background: url(../images/patterns/debut_light.png) repeat;border-left: 1px solid #c9c9c9;border-right: 1px solid #c4c4c4;}
html.fixedWidth.loginPage{background: url(../images/patterns/debut_light.png) repeat;}
html.fixedWidth.loginPage body {border-left: none;border-right: none;background: none;}
html.fixedWidth #sidebar:after {
	content: "";
	width: auto;
	position: inherit;
	top: auto;
	left: auto;
	bottom: auto;
	border-right: none;
	background: none;
	z-index: inherit;
}
html.fixedWidth #sidebar {position: relative;}
html.fixedWidth #sidebarbg {
	width: 213px;
	position:fixed;
	top: 50px;
	bottom: 0;
	border-right: 1px solid #c9c9c9;
	background: url(../images/patterns/furley_bg.png) repeat;
}
html.fixedWidth #sidebarbg.hided {
	display: none;
}
html.fixedWidth #mainnav .nav-list>li>a .icon {border-left: 0;}
html.fixedWidth .sub.show {border-right: 1px solid #c9c9c9;margin-right: -1px !important;}
html.fixedWidth .main {
	position: relative;
}
html.fixedWidth .side-options ul li {border-left: none;}
html.fixedWidth #header .navbar-brand {margin-left: 10px;}
html.fixedWidth .main.isCollapse #sidebarbg {width: 38px;}

/* Media queries only for fixed width */
@media only screen and (max-width: 980px) {
	#content .row-fluid [class*="span"] {
		display: block;
		float: none;
		width: 100%;
		margin-left: 0;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
	}
	#content .row-fluid [class*="span"] {margin-bottom: 20px;}
	#content .row-fluid [class*="span"]:last-child {margin-bottom: 0;}
}

/* -----------------------------------------
   Media Queries
----------------------------------------- */

@media only screen and (max-width: 1360px) {
	/* ------------------ Stat buttons--------------------*/
	.stats-buttons li a {width: 130px;}
	.stats-buttons li a .icon {margin: 10px 39px 5px;}

	/* ------------------ Vital stats--------------------*/
	.vital-stats .item {width: 130px;}
	.vital-stats .item .percent {width: 70px;}
	.vital-stats .item .txt {width: 70px;}

	/* ------------------ Social stats --------------------*/
	.social-stats {width: 100%;}

	/* ------------------ Form styles --------------------*/
	[class*="col-lg-6"] form .from-group .control-label {width: 120px;}
	[class*="col-lg-6"] form .from-group .controls {margin-left: 150px;}
}


@media only screen and (max-width: 1280px) {
	/* ------------------ Grid margin --------------------*/
	#content .row [class*="col-lg-"] {margin-bottom: 20px;}
	#content .row [class*="col-lg-"]:last-child {margin-bottom: 0;}

	/* ------------------ Stat buttons--------------------*/
	.stats-buttons li a {width: 120px;}
	.stats-buttons li a .icon {margin: 10px 34px 5px;}

	/* ------------------ Vital stats--------------------*/

	.vital-stats .item {margin-right: 5px;margin-bottom: 5px;}
	/* ------------------ Weater.panel --------------------*/
	.weather li {}
	.weather .degree {font-size: 85px;}

	/* ------------------ Form styles --------------------*/
	.ui-spinner, div.selector, div.uploader, .sp-replacer {margin-bottom: 15px;}
}

@media only screen and (max-width: 1024px) {
	/* ------------------ Structure --------------------*/
	#header .navbar {padding: 0;}
	#header .navbar .navbar-brand {left: 80px;}

	/* ------------------ Weater.panel --------------------*/
	.weather li {width: 100%; border-right: none;border-bottom: 1px dashed #c9c9c9;}
	.weather li:last-child {border-bottom: none;}
		.weather li .day, .weather li .dayicon, .weather li .max, .weather li .min {width: 25%; float: left;}

	/* ------------------ Email page --------------------*/
	#email .email-bar {height: auto; float: left; border-left: 1px solid #c9c9c9; border-right: 1px solid #c9c9c9;padding: 10px;}
	#email .email-bar  .btn-group.pull-right {float: left; margin-top: 10px;margin-left: 0; white-space: normal;}
	#email .email-bar .navbar-form .input-group, #email .email-bar .navbar-form .input-group input {margin-bottom: 0;}
	#email .email-nav {width: 100%;}
	#email .email-wrapper {padding-left: 0;}
	
}

@media only screen and (max-width: 767px) {

	body {padding-left: 0; padding-right: 0;}
	/* ------------------ Structure --------------------*/
	#header {margin-left: 0px;margin-right: 0;}
	#top-search {border: none;margin-left: auto !important;margin-right: auto;width: 331px !important;padding: 0;}
	/* ------------------Navbar collapse--------------------*/
	.navbar-collapse .navbar-nav.pull-right {float: none !important;margin: 0;}
	.navbar-collapse .navbar-nav li {display: inline-block;width: 100%;text-align: left;}
	.navbar-collapse .navbar-nav li.divider-vertical {display: none;}
	.navbar-collapse .navbar-nav li a {width: 100%;}

	/* ------------------ Top search --------------------*/
	#top-search.shown {margin-left: 0px !important;}

	/* ------------------Jgrowl--------------------*/
	div.jGrowl.top-left, div.jGrowl.top-right, div.jGrowl.center {top:110px;}
	/* ------------------Chat layout--------------------*/
	.chat-layout .btn {margin-left: 0;}
	/* ------------------ Calnedar --------------------*/
	.fc-content {margin-right: -1px;}
	/* ------------------ Form styles--------------------*/
	form .from-group [class*="span"] {margin-bottom: 10px !important;}
	form .from-group .control-label {width: 100%; text-align: left;margin-bottom: 5px;}
	form .from-group .controls {margin-left: 0;}
	form .from-group {margin-bottom: 10px;}
	form .form-actions {padding-left: 20px;}
	form .from-group .controls-row [class*="span"] {margin-left: 0 !important;}
	/* ------------------ Wizard styles --------------------*/
	.wizard-steps .wstep {width: auto; margin-right: 5px;}
	.wizard-steps .wstep .donut {margin-left: 43px;}
	.wizard-steps .wstep .txt {width: 50%; display: inline-block;}
	.wizard-steps .wstep:after {width: 45%}

	.wizard-vertical .wizard-steps.show {width:160px;}
	.wizard-vertical .wrap {padding-left: 170px;}

	/* ------------------Responsive tables --------------------*/
	.responsive {overflow-x: auto; margin-bottom: 20px;}
	/*witman:::::  .responsive table {min-width: 680px;margin-bottom: 0;}*/
	.responsive table {min-width: 300px;margin-bottom: 0;}
	

	/* ------------------ Data tables --------------------*/
	.dataTables_wrapper>.row-fluid>.col-lg-6 {text-align: center;}
	.dataTables_wrapper>.row-fluid>.col-lg-6:first-child {margin-bottom: 0px !important;}
	.dataTables_wrapper>.row-fluid>.col-lg-6>.dataTables_length, .dataTables_wrapper>.row-fluid>.col-lg-6>.dataTables_filter {display: inline-block;}
	.dataTables_wrapper>.row-fluid>.col-lg-6>.dataTables_paginate {display: inline-block; width: 100%;}

	/* ------------------Login page--------------------*/
	#login #loginBtn {width: 120px;}

}

@media only screen and (max-width: 680px) { 
	/* ------------------ Calendar --------------------*/
	.fc-header-title {width: 100%;margin-right: 0;margin-left: 0; text-align: center;display: inline;}
	.fc-header-center .fc-button-today, .fc-header-center .fc-button-prev, .fc-header-center .fc-button-next {float: left;}
	.fc-header-center .fc-button-agendaDay, .fc-header-center .fc-button-agendaWeek,  .fc-header-center .fc-button-month {float: left;}
	.fc-day-header {font-size: 12px;}

	/* ------------------ Email page--------------------*/
	#email .email-bar {padding-bottom: 0;}
	#email .email-bar .btn-group.pull-right .btn {margin-bottom: 10px;}
	#login #bar .btn-group-vertical > .btn {float: left;width: inherit;max-width: inherit;}
	#login .row {margin-left: 15px; margin-right: 15px;}
	#login .btn-group-vertical {margin-top: 0;}


/* ------------------ Samsung galaxy landscape --------------------*/
@media only screen and (min-width: 380px) and (max-width: 685px) and (orientation : landscape) {
	/* ------------------ Login page--------------------*/
	#login {margin-top: -145px;}
	#login .navbar-brand {display: none;}
	#login .page-header {margin-bottom: 15px;}
	#login form {margin-bottom: 10px;}
	#login .login-wrapper form .from-group {margin-bottom: 0;}
	#login .login-wrapper form label.checkbox {margin-top: -10px;margin-bottom: 10px;}
	#login #loginBtn {margin-top: -10px;}
	#header {position: relative;}
	#header .dropdown.open .dropdown-toggle{padding-bottom: 13px;}
	#header .dropdown-menu {padding-top: 8px;}
	#content .wrapper {margin-top: 0;}
	#sidebar {margin-top: 1px;}
	/* ------------------Error pages --------------------*/
	.errorContainer {padding-bottom: 10px; margin-top: -180px;}
	.errorContainer .page-header {margin-bottom: 10px; margin-top: 0;}
	.errorContainer .page-header h1 {margin-bottom: 0;}
	.errorContainer form {margin-bottom: 0;}
}
/* ------------------ Samsung galaxy portrait --------------------*/
@media only screen and (min-width: 380px) and (max-width: 685px) and (orientation : portrait) {
	#login {width: 280px;margin-left: -140px;margin-top: -150px;}
	#login .login-wrapper {width: 260px;}
	#login .navbar-brand {margin-top: -120px;}
	#login .login-wrapper #avatar {display: none;}
	#login .page-header {margin-bottom: 15px;}
	#login form {margin-bottom: 10px;}
	#login .login-wrapper form .from-group {margin-bottom: 0;}
	#login .login-wrapper form label.checkbox {margin-top: -10px;margin-bottom: 10px;}
	#login #loginBtn {width: 100px;}
	#login #bar {top:-35px; right:70px;}
	#login #bar[data-active="log"] a#log, #login #bar[data-active="reg"] a#reg, #login #bar[data-active="forgot"] a#forgot {
		border-left: 1px solid #c9c9c9;border-bottom: 1px solid transparent;
	}
	#login #bar .btn-group-vertical > .btn {float: left;width: inherit;max-width: inherit;}
	#login .row {margin-left: 15px; margin-right: 15px;}
	#login .btn-group-vertical {margin-top: 0;}
	/* ------------------ Error page--------------------*/
	.errorContainer {padding-bottom: 10px; margin-top: -180px;width: 360px;margin-left: -180px;}
	.errorContainer .page-header {margin-bottom: 10px; margin-top: 0;}
	.errorContainer .page-header h1 {margin-bottom: 0;}
	.errorContainer form {margin-bottom: 0;}
	.errorContainer a.btn.pull-right.gap-right20 {margin-right: 5px;}
	.errorContainer a.btn.pull-left.gap-left20 {margin-left: 5px; margin-right: 5px;}
	.errorContainer .page-header h1.offline {font-size: 80px;}
	
	/* ------------------ Contact list --------------------*/
	.contact-list li .phone, .contact-list li .mobile {margin-left: 10px;}
	.contact-list li .email {margin-left: 10px;}
	.contact-list li .actions a {width: auto;margin-left: 10px;}
}

@media only screen and (max-width: 480px) {
	
	#header .navbar-brand {position: relative !important;left: auto !important;	}
	#header {height: 103px;text-align: center;}
	#header .navbar {height: 103px;}
	#header .navbar-brand {display: inline-block;float: none;}
	/*v001*/
	/*#header .navbar-collapse {margin-top: 49px; }*/
	
	/*v002 witman nuevo*/
	#header .navbar-collapse {margin-top: 75px; }
	#email .email-bar .navbar-form {padding: 0;margin: 0; margin-top: -1px; width: 220px; display:none; }
	#email .email-bar .btn-group.pull-right .btn.btn-inverse{display:none;}
	.dataTables_wrapper.form-inline .dataTables_filter input {width : 80px;}

	#content .wrapper>.container-fluid {padding-left: 15px; padding-right: 15px;}
	#heading {margin-left: -15px;}
	#heading h1 {margin-left: 0;}
	#heading:before { margin-left: -22px;}
	#heading:after {margin-left: -21px;}
	.crumb .breadcrumb {padding-left: 15px;}

	/* ------------------ Login--------------------*/
	#login {width: 280px;margin-left: -140px;margin-top: -150px;}
	#login .login-wrapper {width: 260px;}
	#login .navbar-brand {margin-top: -120px;}
	#login .login-wrapper #avatar {display: none;}
	#login .page-header {margin-bottom: 15px;}
	#login form {margin-bottom: 10px;}
	#login .login-wrapper form .from-group {margin-bottom: 0;}
	#login .login-wrapper form label.checkbox {margin-top: -10px;margin-bottom: 10px;}
	#login #loginBtn {width: 100px;}
	#login #bar {top:-35px; right:70px;}
	#login #bar .btn-group-vertical > .btn {float: left;}
	#login #bar[data-active="log"] a#log, #login #bar[data-active="reg"] a#reg, #login #bar[data-active="forgot"] a#forgot {
		border-left: 1px solid #c9c9c9;border-bottom: 1px solid transparent;
	}

	/* ------------------ Responisve buttons--------------------*/
	#resBtn {position: absolute;top: 50px;left: 0;margin-left: 15px;}
	/*#resBtnSearch {position: absolute;left: 70px;top: 50px;}*/
	.navbar-toggle {position: absolute;right: 0;top: 50px;}
	/* ------------------ Calendar --------------------*/
	.fc-header-title {text-align: center;}
	.fc-header-title h2 {padding-left: 0;}
	.fc-day-header {font-size: 10px; text-transform: capitalize;}
	/* ------------------ Wizard styles --------------------*/
	.wizard-steps .wstep, .wizard-vertical .wizard-steps .wstep {width: 100%; margin-right: 0;}
	.wizard-steps .wstep .donut {margin-left: auto;margin-right: auto;}
	.wizard-steps .wstep .txt {width: 100%; display: inline-block; margin-bottom: 5px;}
	.wizard-steps .wstep:after {display: none;}
	.wizard-steps .wstep .donut i {margin-left: 5px;}

	.wizard-vertical .wizard-steps.show {width: 100%;border-right: none;}
	.wizard-vertical .wrap {padding-left: 0px;}
	.wizard-vertical .wizard-steps .wstep .donut {float: none;margin-left: auto;margin-right: auto;}
	.wizard-vertical .wizard-steps .wstep .txt {width:100%;}
	.wizard-vertical .wizard-steps .wstep {margin-bottom: 10px;}
	.wizard-vertical .wizard-steps.show {border-bottom: 1px dashed #c9c9c9;}

	/* ------------------ Pagination--------------------*/
	.pagination ul > li > a {margin-bottom: 15px;}

	/* ------------------ Elfinder --------------------*/
	.elfinder .elfinder-navbar {width: 170px;}

	/* ------------------ Email page --------------------*/
	#email .email-bar .btn-group.pull-left.gap-left20 {margin-left: 0px}

}
@media only screen and (max-width: 380px) {
	/* ------------------ Email page --------------------*/
	#email .email-bar .btn-group.pull-left.gap-left20 {margin-top: 10px;}
	/* ------------------ Error page--------------------*/
	.errorContainer {margin-top: -180px;width: 320px;margin-left: -160px;}
	.errorContainer .page-header {margin-bottom: 10px; margin-top: 0;}
	.errorContainer .page-header h1 {margin-bottom: 0;}
	.errorContainer form {margin-bottom: 0;}
	.errorContainer a.btn.pull-right.gap-right20 {margin-top: 10px;}
	.errorContainer a.btn.pull-left.gap-left20 {margin-left: 15px; margin-right: 15px;}
	.errorContainer a.btn {margin-left: 15px; float: left;}
	.errorContainer .page-header h1.offline {font-size: 80px;}
	#login #bar .btn-group-vertical > .btn {float: left;width: inherit;max-width: inherit;}
	#login .row {margin-left: 15px; margin-right: 15px;}
	#login .btn-group-vertical {margin-top: 0;}
}

@media only screen and (max-width: 320px) {
	#header .navbar-brand img {max-width: 50%;}
	/* ------------------Top search--------------------*/
	#header #top-search input#tsearch {width: 255px;}

	#header .nav > li > a {padding: 13px 15px 12px 15px;}
	#login {width: 280px;margin-left: -140px;margin-top: -150px;}
	#login .login-wrapper {width: 260px;}
	#login .navbar-brand {display: none;}
	#login .login-wrapper #avatar {display: none;}
	#login .page-header {margin-bottom: 15px;}
	#login form {margin-bottom: 10px;}
	#login .login-wrapper form .from-group {margin-bottom: 0;}
	#login .login-wrapper form label.checkbox {margin-top: -10px;margin-bottom: 10px;}
	#login #loginBtn {width: 100px;margin-top: -10px;}
	#login #bar {top:-30px; right:70px;}
	#login #bar .btn-group-vertical > .btn {float: left;}
	#login #bar[data-active="log"] a#log, #login #bar[data-active="reg"] a#reg, #login #bar[data-active="forgot"] a#forgot {
		border-left: 1px solid #c9c9c9;border-bottom: 1px solid transparent;
	}
	#header {position: relative;}
	#sidebar, #content .wrapper {margin-top: 0;}

	/* ------------------ Support page--------------------*/
	#sendMsg .form-actions .btn-group {margin-bottom: 10px; margin-left: 0;}
	#sendMsg .form-actions .btn.btn-danger[type="submit"] {margin-top: 0;}
	/* ------------------ Error page--------------------*/
	.errorContainer {margin-top: -180px;width: 280px;margin-left: -140px;}

}


<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-navbar-fixed layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/"
  data-template="vertical-menu-template"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title><?= $title ?></title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>/resource/img/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons -->
    <link rel="stylesheet" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/libs/typeahead-js/typeahead.css" />
	<link rel="stylesheet" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/libs/fullcalendar/fullcalendar.css"" />
	<link rel="stylesheet" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/libs/bootstrap-select/bootstrap-select.css" />

    <!-- Page CSS -->
	<link rel="stylesheet" href="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/css/pages/app-calendar.css" />
    <!-- Helpers -->
    <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/js/config.js"></script>
  </head>

  <body>
  
	
	 <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="index.html" class="app-brand-link">
              <span class="app-brand-logo demo">
                <svg
                  width="25"
                  viewBox="0 0 25 42"
                  version="1.1"
                  xmlns="http://www.w3.org/2000/svg"
                  xmlns:xlink="http://www.w3.org/1999/xlink"
                >
                  <defs>
                    <path
                      d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                      id="path-1"
                    ></path>
                    <path
                      d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z"
                      id="path-3"
                    ></path>
                    <path
                      d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z"
                      id="path-4"
                    ></path>
                    <path
                      d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z"
                      id="path-5"
                    ></path>
                  </defs>
                  <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                      <g id="Icon" transform="translate(27.000000, 15.000000)">
                        <g id="Mask" transform="translate(0.000000, 8.000000)">
                          <mask id="mask-2" fill="white">
                            <use xlink:href="#path-1"></use>
                          </mask>
                          <use fill="#696cff" xlink:href="#path-1"></use>
                          <g id="Path-3" mask="url(#mask-2)">
                            <use fill="#696cff" xlink:href="#path-3"></use>
                            <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                          </g>
                          <g id="Path-4" mask="url(#mask-2)">
                            <use fill="#696cff" xlink:href="#path-4"></use>
                            <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                          </g>
                        </g>
                        <g
                          id="Triangle"
                          transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) "
                        >
                          <use fill="#696cff" xlink:href="#path-5"></use>
                          <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                        </g>
                      </g>
                    </g>
                  </g>
                </svg>
              </span>
              <span class="app-brand-text demo menu-text fw-bolder ms-2">Sneat</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
			<?php echo $menuRenderLeft; ?>
			
			<?php			
            //wg-<!-- Apps & Pages -->
            //wg-<li class="menu-header small text-uppercase">
            //wg-  <span class="menu-header-text">Apps &amp; Pages</span>
            //wg-</li>
            //wg-<li class="menu-item">
            //wg-  <a href="app-calendar.html" class="menu-link">
            //wg-    <i class="menu-icon tf-icons bx bx-calendar"></i>
            //wg-    <div data-i18n="Calendar">Calendar</div>
            //wg-  </a>
            //wg-</li>            
            //wg-<li class="menu-item">
            //wg-  <a href="javascript:void(0);" class="menu-link menu-toggle">
            //wg-    <i class="menu-icon tf-icons bx bx-user"></i>
            //wg-    <div data-i18n="Users">Users</div>
            //wg-  </a>
            //wg-  <ul class="menu-sub">
            //wg-    <li class="menu-item">
            //wg-      <a href="app-user-list.html" class="menu-link">
            //wg-        <div data-i18n="List">List</div>
            //wg-      </a>
            //wg-    </li>
            //wg-    <li class="menu-item">
            //wg-      <a href="javascript:void(0);" class="menu-link menu-toggle">
            //wg-        <div data-i18n="View">View</div>
            //wg-      </a>
            //wg-      <ul class="menu-sub">
            //wg-        <li class="menu-item">
            //wg-          <a href="app-user-view-account.html" class="menu-link">
            //wg-            <div data-i18n="Account">Account</div>
            //wg-          </a>
            //wg-        </li>
            //wg-        <li class="menu-item">
            //wg-          <a href="app-user-view-security.html" class="menu-link">
            //wg-            <div data-i18n="Security">Security</div>
            //wg-          </a>
            //wg-        </li>
            //wg-        <li class="menu-item">
            //wg-          <a href="app-user-view-billing.html" class="menu-link">
            //wg-            <div data-i18n="Billing & Plans">Billing & Plans</div>
            //wg-          </a>
            //wg-        </li>
            //wg-        <li class="menu-item">
            //wg-          <a href="app-user-view-notifications.html" class="menu-link">
            //wg-            <div data-i18n="Notifications">Notifications</div>
            //wg-          </a>
            //wg-        </li>
            //wg-        <li class="menu-item">
            //wg-          <a href="app-user-view-connections.html" class="menu-link">
            //wg-            <div data-i18n="Connections">Connections</div>
            //wg-          </a>
            //wg-        </li>
            //wg-      </ul>
            //wg-    </li>
            //wg-  </ul>
            //wg-</li>
			?>
			
			
		  </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
		
		  <?php echo $head; ?>  
		  
		  <?php
          //wg-<!-- Navbar -->
          //wg-<nav
          //wg-  class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
          //wg-  id="layout-navbar"
          //wg->
          //wg-  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
          //wg-    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
          //wg-      <i class="bx bx-menu bx-sm"></i>
          //wg-    </a>
          //wg-  </div>
		  //wg-
          //wg-  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
          //wg-    <!-- Search -->
          //wg-    <div class="navbar-nav align-items-center">
          //wg-      <div class="nav-item navbar-search-wrapper mb-0">
          //wg-        <a class="nav-item nav-link search-toggler px-0" href="javascript:void(0);">
          //wg-          <i class="bx bx-search bx-sm"></i>
          //wg-          <span class="d-none d-md-inline-block text-muted">Search (Ctrl+/)</span>
          //wg-        </a>
          //wg-      </div>
          //wg-    </div>
          //wg-    <!-- /Search -->
		  //wg-
          //wg-    <ul class="navbar-nav flex-row align-items-center ms-auto">
          //wg-      <!-- Language -->
          //wg-      <li class="nav-item dropdown-language dropdown me-2 me-xl-0">
          //wg-        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          //wg-          <i class="fi fi-us fis rounded-circle fs-3 me-1"></i>
          //wg-        </a>
          //wg-        <ul class="dropdown-menu dropdown-menu-end">
          //wg-          <li>
          //wg-            <a class="dropdown-item" href="javascript:void(0);" data-language="en">
          //wg-              <i class="fi fi-us fis rounded-circle fs-4 me-1"></i>
          //wg-              <span class="align-middle">English</span>
          //wg-            </a>
          //wg-          </li>
          //wg-          <li>
          //wg-            <a class="dropdown-item" href="javascript:void(0);" data-language="fr">
          //wg-              <i class="fi fi-fr fis rounded-circle fs-4 me-1"></i>
          //wg-              <span class="align-middle">France</span>
          //wg-            </a>
          //wg-          </li>
          //wg-          <li>
          //wg-            <a class="dropdown-item" href="javascript:void(0);" data-language="de">
          //wg-              <i class="fi fi-de fis rounded-circle fs-4 me-1"></i>
          //wg-              <span class="align-middle">German</span>
          //wg-            </a>
          //wg-          </li>
          //wg-          <li>
          //wg-            <a class="dropdown-item" href="javascript:void(0);" data-language="pt">
          //wg-              <i class="fi fi-pt fis rounded-circle fs-4 me-1"></i>
          //wg-              <span class="align-middle">Portuguese</span>
          //wg-            </a>
          //wg-          </li>
          //wg-        </ul>
          //wg-      </li>
          //wg-      <!--/ Language -->
		  //wg-
          //wg-      <!-- Style Switcher -->
          //wg-      <li class="nav-item me-2 me-xl-0">
          //wg-        <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
          //wg-          <i class="bx bx-sm"></i>
          //wg-        </a>
          //wg-      </li>
          //wg-      <!--/ Style Switcher -->
		  //wg-
          //wg-      <!-- Quick links  -->
          //wg-      <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
          //wg-        <a
          //wg-          class="nav-link dropdown-toggle hide-arrow"
          //wg-          href="javascript:void(0);"
          //wg-          data-bs-toggle="dropdown"
          //wg-          data-bs-auto-close="outside"
          //wg-          aria-expanded="false"
          //wg-        >
          //wg-          <i class="bx bx-grid-alt bx-sm"></i>
          //wg-        </a>
          //wg-        <div class="dropdown-menu dropdown-menu-end py-0">
          //wg-          <div class="dropdown-menu-header border-bottom">
          //wg-            <div class="dropdown-header d-flex align-items-center py-3">
          //wg-              <h5 class="text-body mb-0 me-auto">Shortcuts</h5>
          //wg-              <a
          //wg-                href="javascript:void(0)"
          //wg-                class="dropdown-shortcuts-add text-body"
          //wg-                data-bs-toggle="tooltip"
          //wg-                data-bs-placement="top"
          //wg-                title="Add shortcuts"
          //wg-                ><i class="bx bx-sm bx-plus-circle"></i
          //wg-              ></a>
          //wg-            </div>
          //wg-          </div>
          //wg-          <div class="dropdown-shortcuts-list scrollable-container">
          //wg-            <div class="row row-bordered overflow-visible g-0">
          //wg-              <div class="dropdown-shortcuts-item col">
          //wg-                <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
          //wg-                  <i class="bx bx-calendar fs-4"></i>
          //wg-                </span>
          //wg-                <a href="app-calendar.html" class="stretched-link">Calendar</a>
          //wg-                <small class="text-muted mb-0">Appointments</small>
          //wg-              </div>
          //wg-              <div class="dropdown-shortcuts-item col">
          //wg-                <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
          //wg-                  <i class="bx bx-food-menu fs-4"></i>
          //wg-                </span>
          //wg-                <a href="app-invoice-list.html" class="stretched-link">Invoice App</a>
          //wg-                <small class="text-muted mb-0">Manage Accounts</small>
          //wg-              </div>
          //wg-            </div>
          //wg-            <div class="row row-bordered overflow-visible g-0">
          //wg-              <div class="dropdown-shortcuts-item col">
          //wg-                <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
          //wg-                  <i class="bx bx-user fs-4"></i>
          //wg-                </span>
          //wg-                <a href="app-user-list.html" class="stretched-link">User App</a>
          //wg-                <small class="text-muted mb-0">Manage Users</small>
          //wg-              </div>
          //wg-              <div class="dropdown-shortcuts-item col">
          //wg-                <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
          //wg-                  <i class="bx bx-check-shield fs-4"></i>
          //wg-                </span>
          //wg-                <a href="app-access-roles.html" class="stretched-link">Role Management</a>
          //wg-                <small class="text-muted mb-0">Permission</small>
          //wg-              </div>
          //wg-            </div>
          //wg-            <div class="row row-bordered overflow-visible g-0">
          //wg-              <div class="dropdown-shortcuts-item col">
          //wg-                <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
          //wg-                  <i class="bx bx-pie-chart-alt-2 fs-4"></i>
          //wg-                </span>
          //wg-                <a href="index.html" class="stretched-link">Dashboard</a>
          //wg-                <small class="text-muted mb-0">User Profile</small>
          //wg-              </div>
          //wg-              <div class="dropdown-shortcuts-item col">
          //wg-                <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
          //wg-                  <i class="bx bx-cog fs-4"></i>
          //wg-                </span>
          //wg-                <a href="pages-account-settings-account.html" class="stretched-link">Setting</a>
          //wg-                <small class="text-muted mb-0">Account Settings</small>
          //wg-              </div>
          //wg-            </div>
          //wg-            <div class="row row-bordered overflow-visible g-0">
          //wg-              <div class="dropdown-shortcuts-item col">
          //wg-                <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
          //wg-                  <i class="bx bx-help-circle fs-4"></i>
          //wg-                </span>
          //wg-                <a href="pages-help-center-landing.html" class="stretched-link">Help Center</a>
          //wg-                <small class="text-muted mb-0">FAQs & Articles</small>
          //wg-              </div>
          //wg-              <div class="dropdown-shortcuts-item col">
          //wg-                <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
          //wg-                  <i class="bx bx-window-open fs-4"></i>
          //wg-                </span>
          //wg-                <a href="modal-examples.html" class="stretched-link">Modals</a>
          //wg-                <small class="text-muted mb-0">Useful Popups</small>
          //wg-              </div>
          //wg-            </div>
          //wg-          </div>
          //wg-        </div>
          //wg-      </li>
          //wg-      <!-- Quick links -->
		  //wg-
          //wg-      <!-- Notification -->
          //wg-      <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
          //wg-        <a
          //wg-          class="nav-link dropdown-toggle hide-arrow"
          //wg-          href="javascript:void(0);"
          //wg-          data-bs-toggle="dropdown"
          //wg-          data-bs-auto-close="outside"
          //wg-          aria-expanded="false"
          //wg-        >
          //wg-          <i class="bx bx-bell bx-sm"></i>
          //wg-          <span class="badge bg-danger rounded-pill badge-notifications">5</span>
          //wg-        </a>
          //wg-        <ul class="dropdown-menu dropdown-menu-end py-0">
          //wg-          <li class="dropdown-menu-header border-bottom">
          //wg-            <div class="dropdown-header d-flex align-items-center py-3">
          //wg-              <h5 class="text-body mb-0 me-auto">Notification</h5>
          //wg-              <a
          //wg-                href="javascript:void(0)"
          //wg-                class="dropdown-notifications-all text-body"
          //wg-                data-bs-toggle="tooltip"
          //wg-                data-bs-placement="top"
          //wg-                title="Mark all as read"
          //wg-                ><i class="bx fs-4 bx-envelope-open"></i
          //wg-              ></a>
          //wg-            </div>
          //wg-          </li>
          //wg-          <li class="dropdown-notifications-list scrollable-container">
          //wg-            <ul class="list-group list-group-flush">
          //wg-              <li class="list-group-item list-group-item-action dropdown-notifications-item">
          //wg-                <div class="d-flex">
          //wg-                  <div class="flex-shrink-0 me-3">
          //wg-                    <div class="avatar">
          //wg-                      <img src="../../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
          //wg-                    </div>
          //wg-                  </div>
          //wg-                  <div class="flex-grow-1">
          //wg-                    <h6 class="mb-1">Congratulation Lettie 🎉</h6>
          //wg-                    <p class="mb-0">Won the monthly best seller gold badge</p>
          //wg-                    <small class="text-muted">1h ago</small>
          //wg-                  </div>
          //wg-                  <div class="flex-shrink-0 dropdown-notifications-actions">
          //wg-                    <a href="javascript:void(0)" class="dropdown-notifications-read"
          //wg-                      ><span class="badge badge-dot"></span
          //wg-                    ></a>
          //wg-                    <a href="javascript:void(0)" class="dropdown-notifications-archive"
          //wg-                      ><span class="bx bx-x"></span
          //wg-                    ></a>
          //wg-                  </div>
          //wg-                </div>
          //wg-              </li>
          //wg-              <li class="list-group-item list-group-item-action dropdown-notifications-item">
          //wg-                <div class="d-flex">
          //wg-                  <div class="flex-shrink-0 me-3">
          //wg-                    <div class="avatar">
          //wg-                      <span class="avatar-initial rounded-circle bg-label-danger">CF</span>
          //wg-                    </div>
          //wg-                  </div>
          //wg-                  <div class="flex-grow-1">
          //wg-                    <h6 class="mb-1">Charles Franklin</h6>
          //wg-                    <p class="mb-0">Accepted your connection</p>
          //wg-                    <small class="text-muted">12hr ago</small>
          //wg-                  </div>
          //wg-                  <div class="flex-shrink-0 dropdown-notifications-actions">
          //wg-                    <a href="javascript:void(0)" class="dropdown-notifications-read"
          //wg-                      ><span class="badge badge-dot"></span
          //wg-                    ></a>
          //wg-                    <a href="javascript:void(0)" class="dropdown-notifications-archive"
          //wg-                      ><span class="bx bx-x"></span
          //wg-                    ></a>
          //wg-                  </div>
          //wg-                </div>
          //wg-              </li>
          //wg-              <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
          //wg-                <div class="d-flex">
          //wg-                  <div class="flex-shrink-0 me-3">
          //wg-                    <div class="avatar">
          //wg-                      <img src="../../assets/img/avatars/2.png" alt class="w-px-40 h-auto rounded-circle" />
          //wg-                    </div>
          //wg-                  </div>
          //wg-                  <div class="flex-grow-1">
          //wg-                    <h6 class="mb-1">New Message ✉️</h6>
          //wg-                    <p class="mb-0">You have new message from Natalie</p>
          //wg-                    <small class="text-muted">1h ago</small>
          //wg-                  </div>
          //wg-                  <div class="flex-shrink-0 dropdown-notifications-actions">
          //wg-                    <a href="javascript:void(0)" class="dropdown-notifications-read"
          //wg-                      ><span class="badge badge-dot"></span
          //wg-                    ></a>
          //wg-                    <a href="javascript:void(0)" class="dropdown-notifications-archive"
          //wg-                      ><span class="bx bx-x"></span
          //wg-                    ></a>
          //wg-                  </div>
          //wg-                </div>
          //wg-              </li>
          //wg-              <li class="list-group-item list-group-item-action dropdown-notifications-item">
          //wg-                <div class="d-flex">
          //wg-                  <div class="flex-shrink-0 me-3">
          //wg-                    <div class="avatar">
          //wg-                      <span class="avatar-initial rounded-circle bg-label-success"
          //wg-                        ><i class="bx bx-cart"></i
          //wg-                      ></span>
          //wg-                    </div>
          //wg-                  </div>
          //wg-                  <div class="flex-grow-1">
          //wg-                    <h6 class="mb-1">Whoo! You have new order 🛒</h6>
          //wg-                    <p class="mb-0">ACME Inc. made new order $1,154</p>
          //wg-                    <small class="text-muted">1 day ago</small>
          //wg-                  </div>
          //wg-                  <div class="flex-shrink-0 dropdown-notifications-actions">
          //wg-                    <a href="javascript:void(0)" class="dropdown-notifications-read"
          //wg-                      ><span class="badge badge-dot"></span
          //wg-                    ></a>
          //wg-                    <a href="javascript:void(0)" class="dropdown-notifications-archive"
          //wg-                      ><span class="bx bx-x"></span
          //wg-                    ></a>
          //wg-                  </div>
          //wg-                </div>
          //wg-              </li>
          //wg-              <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
          //wg-                <div class="d-flex">
          //wg-                  <div class="flex-shrink-0 me-3">
          //wg-                    <div class="avatar">
          //wg-                      <img src="../../assets/img/avatars/9.png" alt class="w-px-40 h-auto rounded-circle" />
          //wg-                    </div>
          //wg-                  </div>
          //wg-                  <div class="flex-grow-1">
          //wg-                    <h6 class="mb-1">Application has been approved 🚀</h6>
          //wg-                    <p class="mb-0">Your ABC project application has been approved.</p>
          //wg-                    <small class="text-muted">2 days ago</small>
          //wg-                  </div>
          //wg-                  <div class="flex-shrink-0 dropdown-notifications-actions">
          //wg-                    <a href="javascript:void(0)" class="dropdown-notifications-read"
          //wg-                      ><span class="badge badge-dot"></span
          //wg-                    ></a>
          //wg-                    <a href="javascript:void(0)" class="dropdown-notifications-archive"
          //wg-                      ><span class="bx bx-x"></span
          //wg-                    ></a>
          //wg-                  </div>
          //wg-                </div>
          //wg-              </li>
          //wg-              <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
          //wg-                <div class="d-flex">
          //wg-                  <div class="flex-shrink-0 me-3">
          //wg-                    <div class="avatar">
          //wg-                      <span class="avatar-initial rounded-circle bg-label-success"
          //wg-                        ><i class="bx bx-pie-chart-alt"></i
          //wg-                      ></span>
          //wg-                    </div>
          //wg-                  </div>
          //wg-                  <div class="flex-grow-1">
          //wg-                    <h6 class="mb-1">Monthly report is generated</h6>
          //wg-                    <p class="mb-0">July monthly financial report is generated</p>
          //wg-                    <small class="text-muted">3 days ago</small>
          //wg-                  </div>
          //wg-                  <div class="flex-shrink-0 dropdown-notifications-actions">
          //wg-                    <a href="javascript:void(0)" class="dropdown-notifications-read"
          //wg-                      ><span class="badge badge-dot"></span
          //wg-                    ></a>
          //wg-                    <a href="javascript:void(0)" class="dropdown-notifications-archive"
          //wg-                      ><span class="bx bx-x"></span
          //wg-                    ></a>
          //wg-                  </div>
          //wg-                </div>
          //wg-              </li>
          //wg-              <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
          //wg-                <div class="d-flex">
          //wg-                  <div class="flex-shrink-0 me-3">
          //wg-                    <div class="avatar">
          //wg-                      <img src="../../assets/img/avatars/5.png" alt class="w-px-40 h-auto rounded-circle" />
          //wg-                    </div>
          //wg-                  </div>
          //wg-                  <div class="flex-grow-1">
          //wg-                    <h6 class="mb-1">Send connection request</h6>
          //wg-                    <p class="mb-0">Peter sent you connection request</p>
          //wg-                    <small class="text-muted">4 days ago</small>
          //wg-                  </div>
          //wg-                  <div class="flex-shrink-0 dropdown-notifications-actions">
          //wg-                    <a href="javascript:void(0)" class="dropdown-notifications-read"
          //wg-                      ><span class="badge badge-dot"></span
          //wg-                    ></a>
          //wg-                    <a href="javascript:void(0)" class="dropdown-notifications-archive"
          //wg-                      ><span class="bx bx-x"></span
          //wg-                    ></a>
          //wg-                  </div>
          //wg-                </div>
          //wg-              </li>
          //wg-              <li class="list-group-item list-group-item-action dropdown-notifications-item">
          //wg-                <div class="d-flex">
          //wg-                  <div class="flex-shrink-0 me-3">
          //wg-                    <div class="avatar">
          //wg-                      <img src="../../assets/img/avatars/6.png" alt class="w-px-40 h-auto rounded-circle" />
          //wg-                    </div>
          //wg-                  </div>
          //wg-                  <div class="flex-grow-1">
          //wg-                    <h6 class="mb-1">New message from Jane</h6>
          //wg-                    <p class="mb-0">Your have new message from Jane</p>
          //wg-                    <small class="text-muted">5 days ago</small>
          //wg-                  </div>
          //wg-                  <div class="flex-shrink-0 dropdown-notifications-actions">
          //wg-                    <a href="javascript:void(0)" class="dropdown-notifications-read"
          //wg-                      ><span class="badge badge-dot"></span
          //wg-                    ></a>
          //wg-                    <a href="javascript:void(0)" class="dropdown-notifications-archive"
          //wg-                      ><span class="bx bx-x"></span
          //wg-                    ></a>
          //wg-                  </div>
          //wg-                </div>
          //wg-              </li>
          //wg-              <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
          //wg-                <div class="d-flex">
          //wg-                  <div class="flex-shrink-0 me-3">
          //wg-                    <div class="avatar">
          //wg-                      <span class="avatar-initial rounded-circle bg-label-warning"
          //wg-                        ><i class="bx bx-error"></i
          //wg-                      ></span>
          //wg-                    </div>
          //wg-                  </div>
          //wg-                  <div class="flex-grow-1">
          //wg-                    <h6 class="mb-1">CPU is running high</h6>
          //wg-                    <p class="mb-0">CPU Utilization Percent is currently at 88.63%,</p>
          //wg-                    <small class="text-muted">5 days ago</small>
          //wg-                  </div>
          //wg-                  <div class="flex-shrink-0 dropdown-notifications-actions">
          //wg-                    <a href="javascript:void(0)" class="dropdown-notifications-read"
          //wg-                      ><span class="badge badge-dot"></span
          //wg-                    ></a>
          //wg-                    <a href="javascript:void(0)" class="dropdown-notifications-archive"
          //wg-                      ><span class="bx bx-x"></span
          //wg-                    ></a>
          //wg-                  </div>
          //wg-                </div>
          //wg-              </li>
          //wg-            </ul>
          //wg-          </li>
          //wg-          <li class="dropdown-menu-footer border-top">
          //wg-            <a href="javascript:void(0);" class="dropdown-item d-flex justify-content-center p-3">
          //wg-              View all notifications
          //wg-            </a>
          //wg-          </li>
          //wg-        </ul>
          //wg-      </li>
          //wg-      <!--/ Notification -->
          //wg-      <!-- User -->
          //wg-      <li class="nav-item navbar-dropdown dropdown-user dropdown">
          //wg-        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          //wg-          <div class="avatar avatar-online">
          //wg-            <img src="../../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
          //wg-          </div>
          //wg-        </a>
          //wg-        <ul class="dropdown-menu dropdown-menu-end">
          //wg-          <li>
          //wg-            <a class="dropdown-item" href="pages-account-settings-account.html">
          //wg-              <div class="d-flex">
          //wg-                <div class="flex-shrink-0 me-3">
          //wg-                  <div class="avatar avatar-online">
          //wg-                    <img src="../../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
          //wg-                  </div>
          //wg-                </div>
          //wg-                <div class="flex-grow-1">
          //wg-                  <span class="fw-semibold d-block">John Doe</span>
          //wg-                  <small class="text-muted">Admin</small>
          //wg-                </div>
          //wg-              </div>
          //wg-            </a>
          //wg-          </li>
          //wg-          <li>
          //wg-            <div class="dropdown-divider"></div>
          //wg-          </li>
          //wg-          <li>
          //wg-            <a class="dropdown-item" href="pages-profile-user.html">
          //wg-              <i class="bx bx-user me-2"></i>
          //wg-              <span class="align-middle">My Profile</span>
          //wg-            </a>
          //wg-          </li>
          //wg-          <li>
          //wg-            <a class="dropdown-item" href="pages-account-settings-account.html">
          //wg-              <i class="bx bx-cog me-2"></i>
          //wg-              <span class="align-middle">Settings</span>
          //wg-            </a>
          //wg-          </li>
          //wg-          <li>
          //wg-            <a class="dropdown-item" href="pages-account-settings-billing.html">
          //wg-              <span class="d-flex align-items-center align-middle">
          //wg-                <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
          //wg-                <span class="flex-grow-1 align-middle">Billing</span>
          //wg-                <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
          //wg-              </span>
          //wg-            </a>
          //wg-          </li>
          //wg-          <li>
          //wg-            <div class="dropdown-divider"></div>
          //wg-          </li>
          //wg-          <li>
          //wg-            <a class="dropdown-item" href="pages-help-center-landing.html">
          //wg-              <i class="bx bx-support me-2"></i>
          //wg-              <span class="align-middle">Help</span>
          //wg-            </a>
          //wg-          </li>
          //wg-          <li>
          //wg-            <a class="dropdown-item" href="pages-faq.html">
          //wg-              <i class="bx bx-help-circle me-2"></i>
          //wg-              <span class="align-middle">FAQ</span>
          //wg-            </a>
          //wg-          </li>
          //wg-          <li>
          //wg-            <a class="dropdown-item" href="pages-pricing.html">
          //wg-              <i class="bx bx-dollar me-2"></i>
          //wg-              <span class="align-middle">Pricing</span>
          //wg-            </a>
          //wg-          </li>
          //wg-          <li>
          //wg-            <div class="dropdown-divider"></div>
          //wg-          </li>
          //wg-          <li>
          //wg-            <a class="dropdown-item" href="auth-login-cover.html" target="_blank">
          //wg-              <i class="bx bx-power-off me-2"></i>
          //wg-              <span class="align-middle">Log Out</span>
          //wg-            </a>
          //wg-          </li>
          //wg-        </ul>
          //wg-      </li>
          //wg-      <!--/ User -->
          //wg-    </ul>
          //wg-  </div>
		  //wg-
          //wg-  <!-- Search Small Screens -->
          //wg-  <div class="navbar-search-wrapper search-input-wrapper d-none">
          //wg-    <input
          //wg-      type="text"
          //wg-      class="form-control search-input container-xxl border-0"
          //wg-      placeholder="Search..."
          //wg-      aria-label="Search..."
          //wg-    />
          //wg-    <i class="bx bx-x bx-sm search-toggler cursor-pointer"></i>
          //wg-  </div>
          //wg-</nav>
		  //wg-
          //wg-<!-- / Navbar -->
		  ?>
		  
          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">

				<?php echo $body; ?>
             
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                  ©
                  <script>
                    document.write(new Date().getFullYear());
                  </script>
                  , made with ❤️ by
                  <a href="https://themeselection.com" target="_blank" class="footer-link fw-bolder">posMe</a>
                </div>
                
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>

      <!-- Drag Target Area To SlideIn Menu On Small Screens -->
      <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

 

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/libs/popper/popper.js"></script>
    <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/js/bootstrap.js"></script>
    <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/libs/hammer/hammer.js"></script>
    <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/libs/i18n/i18n.js"></script>
    <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/libs/typeahead-js/typeahead.js"></script>

    <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/libs/fullcalendar/fullcalendar.js"></script>
    <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/libs/masonry/masonry.js"></script>	
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>
	<script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/libs/bootstrap-select/bootstrap-select.js"></script>
	
	
    <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/libs/select2/select2.js"></script>
    <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/vendor/libs/moment/moment.js"></script>
    <!-- Main JS -->
    <script src="<?php echo APP_URL_RESOURCE_CSS_JS; ?>/resource/themplate-sneat-bootstrap-html-admin-template-v-1-1-1/sneat-bootstrap-html-admin-template/html/vertical-menu-template/../../assets/js/main.js"></script>

	<!-- Page JS -->	
  
	<?= $script ?>
  </body>
</html>

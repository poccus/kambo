<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title><?=TITULOSISTEMA?></title>

        <!-- BEGIN META -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="keywords" content="your,keywords">
        <meta name="description" content="Short explanation about this website">
        <!-- END META -->

        <!-- BEGIN STYLESHEETS -->
        <link href='http://fonts.googleapis.com/css?family=Roboto:300italic,400italic,300,400,500,700,900' rel='stylesheet' type='text/css'/>
        <link type="text/css" rel="stylesheet" href="<?=ASSETS?>css/theme-1/bootstrap.css?1422792965" />
        <link type="text/css" rel="stylesheet" href="<?=ASSETS?>css/theme-1/materialadmin.css?1425466319" />
        <link type="text/css" rel="stylesheet" href="<?=ASSETS?>css/theme-1/font-awesome.min.css?1422529194" />
        <link type="text/css" rel="stylesheet" href="<?=ASSETS?>css/theme-1/material-design-iconic-font.min.css?1421434286" />
        <link type="text/css" rel="stylesheet" href="<?=ASSETS?>css/theme-1/libs/rickshaw/rickshaw.css?1422792967" />
        <link type="text/css" rel="stylesheet" href="<?=ASSETS?>css/theme-1/libs/morris/morris.core.css?1420463396" />
        <link href="<?=CSS_FOLDER?>dashboard.css" rel="stylesheet">
        <!-- bootstrap datapicker -->
        <link href="<?=CSS_FOLDER?>datepicker3.css" rel="stylesheet" />
        <!-- BOOTSTRAP MODAL 2.0v -->
        <link href="<?=CSS_FOLDER?>bootstrap-modal-bs3patch.css" rel="stylesheet" />
        <link href="<?=CSS_FOLDER?>bootstrap-modal.css" rel="stylesheet" />
        <!-- bootstrap Select -->
        <link href="<?=CSS_FOLDER?>bootstrap-select.min.css" rel="stylesheet" type="text/css" />
        <!-- END STYLESHEETS -->

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script type="text/javascript" src="<?=ASSETS?>js/libs/utils/html5shiv.js?1403934957"></script>
        <script type="text/javascript" src="<?=ASSETS?>js/libs/utils/respond.min.js?1403934956"></script>
        <![endif]-->

        <!-- BEGIN JAVASCRIPT -->
        <script src="<?=ASSETS?>js/libs/jquery/jquery-1.11.2.min.js"></script>
        <script src="<?=ASSETS?>js/libs/moment/moment.min.js"></script>
        <script src="<?=JS_FOLDER?>moment-timezone.js"></script>
        <script src="<?=JS_FOLDER?>jquery.autocomplete.min.js"></script>
        <!-- END JAVASCRIPt -->


    </head>
    <body class="menubar-hoverable header-fixed ">

    <!-- BEGIN HEADER-->
    <header id="header" >
        <div class="headerbar">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="headerbar-left">
                <ul class="header-nav header-nav-options">
                    <li class="header-nav-brand" >
                        <div class="brand-holder">
                            <a href="html/dashboards/dashboard.html">
                                <span class="text-lg text-bold text-primary">MATERIAL ADMIN</span>
                            </a>
                        </div>
                    </li>
                    <li>
                        <a class="btn btn-icon-toggle menubar-toggle" data-toggle="menubar" href="javascript:void(0);">
                            <i class="fa fa-bars"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="headerbar-right">
                <ul class="header-nav header-nav-options">
                    <li>
                        <!-- Search form -->
                        <form class="navbar-search" role="search">
                            <div class="form-group">
                                <input type="text" class="form-control" name="headerSearch" placeholder="Enter your keyword">
                            </div>
                            <button type="submit" class="btn btn-icon-toggle ink-reaction"><i class="fa fa-search"></i></button>
                        </form>
                    </li>
                    <li class="dropdown hidden-xs">
                        <a href="javascript:void(0);" class="btn btn-icon-toggle btn-default" data-toggle="dropdown">
                            <i class="fa fa-bell"></i><sup class="badge style-danger">4</sup>
                        </a>
                        <ul class="dropdown-menu animation-expand">
                            <li class="dropdown-header">Today's messages</li>
                            <li>
                                <a class="alert alert-callout alert-warning" href="javascript:void(0);">
                                    <img class="pull-right img-circle dropdown-avatar" src="assets/img/avatar2.jpg?1404026449" alt="" />
                                    <strong>Alex Anistor</strong><br/>
                                    <small>Testing functionality...</small>
                                </a>
                            </li>
                            <li>
                                <a class="alert alert-callout alert-info" href="javascript:void(0);">
                                    <img class="pull-right img-circle dropdown-avatar" src="assets/img/avatar3.jpg?1404026799" alt="" />
                                    <strong>Alicia Adell</strong><br/>
                                    <small>Reviewing last changes...</small>
                                </a>
                            </li>
                            <li class="dropdown-header">Options</li>
                            <li><a href="html/pages/login.html">View all messages <span class="pull-right"><i class="fa fa-arrow-right"></i></span></a></li>
                            <li><a href="html/pages/login.html">Mark as read <span class="pull-right"><i class="fa fa-arrow-right"></i></span></a></li>
                        </ul><!--end .dropdown-menu -->
                    </li><!--end .dropdown -->
                    <li class="dropdown hidden-xs">
                        <a href="javascript:void(0);" class="btn btn-icon-toggle btn-default" data-toggle="dropdown">
                            <i class="fa fa-area-chart"></i>
                        </a>
                        <ul class="dropdown-menu animation-expand">
                            <li class="dropdown-header">Server load</li>
                            <li class="dropdown-progress">
                                <a href="javascript:void(0);">
                                    <div class="dropdown-label">
                                        <span class="text-light">Server load <strong>Today</strong></span>
                                        <strong class="pull-right">93%</strong>
                                    </div>
                                    <div class="progress"><div class="progress-bar progress-bar-danger" style="width: 93%"></div></div>
                                </a>
                            </li><!--end .dropdown-progress -->
                            <li class="dropdown-progress">
                                <a href="javascript:void(0);">
                                    <div class="dropdown-label">
                                        <span class="text-light">Server load <strong>Yesterday</strong></span>
                                        <strong class="pull-right">30%</strong>
                                    </div>
                                    <div class="progress"><div class="progress-bar progress-bar-success" style="width: 30%"></div></div>
                                </a>
                            </li><!--end .dropdown-progress -->
                            <li class="dropdown-progress">
                                <a href="javascript:void(0);">
                                    <div class="dropdown-label">
                                        <span class="text-light">Server load <strong>Lastweek</strong></span>
                                        <strong class="pull-right">74%</strong>
                                    </div>
                                    <div class="progress"><div class="progress-bar progress-bar-warning" style="width: 74%"></div></div>
                                </a>
                            </li><!--end .dropdown-progress -->
                        </ul><!--end .dropdown-menu -->
                    </li><!--end .dropdown -->
                </ul><!--end .header-nav-options -->
                <ul class="header-nav header-nav-profile">
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle ink-reaction" data-toggle="dropdown">
                            <img src="assets/img/avatar1.jpg?1403934956" alt="" />
                            <span class="profile-info">
                                Daniel Johnson
                                <small>Administrator</small>
                            </span>
                        </a>
                        <ul class="dropdown-menu animation-dock">
                            <li class="dropdown-header">Config</li>
                            <li><a href="html/pages/profile.html">My profile</a></li>
                            <li><a href="html/pages/blog/post.html">My blog <span class="badge style-danger pull-right">16</span></a></li>
                            <li><a href="html/pages/calendar.html">My appointments</a></li>
                            <li class="divider"></li>
                            <li><a href="html/pages/locked.html"><i class="fa fa-fw fa-lock"></i> Lock</a></li>
                            <li><a href="html/pages/login.html"><i class="fa fa-fw fa-power-off text-danger"></i> Logout</a></li>
                        </ul><!--end .dropdown-menu -->
                    </li><!--end .dropdown -->
                </ul><!--end .header-nav-profile -->
                <ul class="header-nav header-nav-toggle">
                    <li>
                        <a class="btn btn-icon-toggle btn-default" href="#offcanvas-search" data-toggle="offcanvas" data-backdrop="false">
                            <i class="fa fa-ellipsis-v"></i>
                        </a>
                    </li>
                </ul><!--end .header-nav-toggle -->
            </div><!--end #header-navbar-collapse -->
        </div>
    </header>
    <!-- END HEADER-->


    <!-- LOADING FLUTUANTE -->
    <div class="notificacao-loading">
        <div class="ic-loading"></div>
        <div class="pull-left">Processando aguarde</div>
    </div>

    <!-- BOOTSTRAP MODAL -->
    <div id="bootstrap-modal" class="modal fade" tabindex="-1" style="display: none;"></div>

    <!-- MENSAGEM FEEDBACK STATUS -->
    <div class="alert text-center" id="return-status"></div>

    <!-- MODAL PADRÃO -->
    <div id="default-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Modal title</h4>
            </div>
            <div class="modal-body">
                <div id="loading-modal" class="display-n text-center"><img src="<?=PORTAL_URL?>img/load.gif"/><br><span>processando...</span></div>
               <div id="default-modal-container" class="display-n"></div>
            </div>
            <div class="modal-footer">
              <img id="default-modal-loading" class="display-none margin-right-1em" src="<?=PORTAL_URL?>img/ajax-loader.gif" alt="" />
              <button id="default-modal-cancelar" type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
              <button id="default-modal-submit" type="button" class="btn" >Salvar</button>
            </div>
          </div>
      </div>
    </div>

    <!-- MODAL PADRÃO DE PERMISSAO -->
    <div id="default-modal-permissao" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" data-backdrop="static">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Modal title</h4>
            </div>
            <div class="modal-body">
               <div id="default-modal-container"></div>
            </div>
            <div class="modal-footer">
              <img id="default-modal-loading" class="display-none margin-right-1em" src="<?=PORTAL_URL?>img/ajax-loader.gif" alt="" />
              <a id="default-modal-submit" href="<?=PORTAL_URL?>" class="btn btn-default">Ir ao painel</a>
              <a id="default-modal-logout" href="<?=PORTAL_URL?>logout/" class="btn btn-danger">Sair</a>
            </div>
          </div>
      </div>
    </div>
    <!-- AREA DE MENSAGENS PRE-DEFINIDAS -->
    <div id="acesso-negado-area" class="display-n">
        <h2 class="h2aviso text-center">O usuário logado não tem acesso a está área</h2>
        <div class="spAvisopermissao text-center">Por favor consulte o administrador do sistema para rever o seu nível de permissão</div>
    </div>

    <div id="acesso-duplicado-sistema" class="display-n">
        <h2 class="h2aviso text-center">Seu usuário está sendo utilizado em outro acesso</h2>
        <div class="spAvisopermissao text-center">Por favor consulte o administrador do sistema para gerir a duplicidade de login</div>
    </div>

    <!-- BEGIN BASE-->
    <div id="base">

        <!-- BEGIN OFFCANVAS LEFT -->
        <div class="offcanvas">
        </div><!--end .offcanvas-->
        <!-- END OFFCANVAS LEFT -->

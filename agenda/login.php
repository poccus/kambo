<?php 

  session_start();

  // VERIFICAÇÕES DE SESSÕES
  if (isset( $_SESSION['usuario'] ) ) {
    // echo "<script>window.location = '".PORTAL_URL."dashboard/#!';</script>";
    echo "<script>window.location = '".PORTAL_URL."view/agenda/index/#!';</script>";
    exit();
  } 

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta http-equiv="cache-control"   content="no-cache" />
  <meta http-equiv="pragma" content="no-cache" />
  <link rel="shortcut icon" href="images/favicon.png">
  <title><?php echo TITULOSISTEMA; ?></title>
  <link href="<?=CSS_FOLDER?>dashboard.css" rel="stylesheet"> 
  <link rel="shortcut icon" href="<?=FAVICONSISTEMA?>favicon.png" />

  <!-- bootstrap 3.0.2 -->
  <link href="<?=CSS_FOLDER?>bootstrap.min.css" rel="stylesheet" type="text/css" />
  <!-- bootstrap wysihtml5 - text editor -->
  <link href="<?=CSS_FOLDER?>bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />

  <script src="<?=JS_FOLDER;?>jquery.js"></script>
  <script src="<?=UTILS_FOLDER;?>livequery.js"></script>
  <script src="<?=JS_FOLDER;?>jquery.form.js" type="text/javascript" ></script>
  <!-- Bootstrap -->
  <script src="<?=PORTAL_URL?>tema/js/bootstrap.min.js" type="text/javascript"></script>
  <script src="<?=PORTAL_URL?>tema/js/bootstrap-popover.js" type="text/javascript"></script>

  <!-- PROJETO UTIL -->
  <script src="<?=UTILS_FOLDER;?>projeto.utils.js"></script>

  <!-- UTILS JS -->
  <script src="<?=UTILS_FOLDER;?>utils.js"></script>

  <?php 
    $img = rand(1, 10);
  ?>

  <style type="text/css">
    .dir-ltr {direction: ltr;}
    .pglogin{bottom: 0;line-height: normal;min-width: 600px;position: absolute;top: 0;width: 100%;}
    .pglogin .panel {background-position: center top;background-size: cover;display: table;height: 100%;min-height: 100%;table-layout: fixed;width: 100%;}
    .pglogin .logosai{background-image: url('<?=PORTAL_URL?>tema/img/logo-sai-branca.svg'); background-repeat: no-repeat;background-size: 100% auto;height: 140px;width: 95px; margin-left: 35px;}
    .pglogin .logogovac{background-image: url('<?=PORTAL_URL?>img/logo_povo_do_acre.png'); background-repeat: no-repeat;background-size: 100% auto;height: 50px;width: 210px; display: inline-block; margin-top: 10px; margin-left: -13px; margin-bottom: 10px;}
    .pglogin#visual-2 > .panel { background-image: url("<?=PORTAL_URL?>img/background/<?=$img?>.jpg");}
    .pglogin .panel-wrapper {display: table-cell;padding: 20px;vertical-align: middle;}
    .pglogin .container {display: table;margin: 0;position: relative;table-layout: fixed;width: 620px; box-sizing: border-box; padding: 0;}
    .pglogin .column-left {width: 240px; background-color: rgba(35, 35, 42, 0.9);box-sizing: border-box;display: table-cell;vertical-align: bottom;}
    .pglogin .column-right {background-color: rgba(255, 255, 255, 0.9);display: table-cell;padding: 30px;vertical-align: top; width: 360px;}
    .pglogin .column-top {padding: 30px;width: 240px; position: absolute; top: 0;}
    .pglogin .column-top .logosai{ margin-top: 10px; display: inline-block; }
    .pglogin .column-down {width: 240px; position: absolute; bottom: 0;}
    .pglogin .heading-2 {color: #ffffff;font-size: 18px;font-weight: normal;margin: 0;}
    .pglogin .heading-1 {font-size: 32px; color: #23232a; margin-bottom: 40px; font-weight: normal; line-height: 30px; letter-spacing: -0.059em;}
    .pglogin .divider {background-color: #d8d8d8;height: 1px;margin: 10px 0;}
    .pglogin .esqueceu-senha {color: #999999;font-size: 11px;margin-top: 10px;}
    .pglogin .links {color: #23232a;margin-top: 10px;padding-top: 10px;text-align: center; display: table-cell;}
    .footer {background-color: rgba(35, 35, 42, 0.5);bottom: 0;box-sizing: border-box;color: #ffffff;height: 30px;left: 0;line-height: 30px;padding: 0 20px;position: fixed;right: 0;}
    .footer .navigation {font-size: 11px;min-width: 990px;text-align: center;}
    .footer .navigation a {color: #ffffff;}
    .footer .navigation .navigation-separator {margin: 0 5px;}

    @media(max-width: 515px){
      .pglogin .container{width: 300px;}
      .pglogin .column-left{display: none;}
      .pglogin .column-right{width: 300px;}
      .footer .navigation{min-width: 300px;}
    }

  </style>
</head>
<body class="dir-ltr">
  
  <div class="pglogin" id="visual-2">
    <div class="panel">
      <div class="panel-wrapper">

        <div class="container">
          <div class="column-left">
            <div class="column-top">
              <div class="logogovac"></div>
              <div class="logosai"></div>
              <h1 class="heading-2 text-center">Faça o login para explorar o aplicativo e veja relatórios, mapas e pesquisas.</h1>
            </div>
            <div class="column-down">
            </div>
          </div>
          <div class="column-right">
            
            <div class="header">
              <h1 class="heading-1">Informe seus dados de acesso</h1>
              <div class="divider"></div>
            </div><!-- HEADER -->

            <div class="formlogin">
              <form id="autenticar" name="autenticar" method="post">
                <div class="form-group">
                  <input type="text" id="email-ou-usuario" class="form-control" name="login" placeholder="E-mail ou Usuário" data-container="body" data-toggle="popover" data-placement="right" data-content="">
                </div>
                <div class="form-group">
                  <input type="password" id="senha-do-usuario" class="form-control" name="senha" placeholder="Senha" data-container="body" data-toggle="popover" data-placement="right" data-content="">
                </div>
                <div class="form-group">
                  <div class="col-md-12 padding-0">
                    <input id="autenticar" type="submit" type="submit" class="btn btn-primary btn-block" value="Entrar"/>
                    <span id="submit-loading" class="display-n" style="position: absolute; right: 30%; bottom: 25%;"><img src="<?=PORTAL_URL; ?>imagens/ajax-loader-login.gif"></span>
                  </div>
                </div>
                <input type="hidden" value="<?=isset( $_REQUEST['urlanterior'] ) && $_REQUEST['urlanterior'] != '' ? $_REQUEST['urlanterior'] : '' ?>" name="urlanterior" id="urlanterior">
              </form>
            </div><!-- END FORM -->

            <div class="links">
              <div class="divider"></div>
              <span>Você já tem uma conta?</span>
              <a href="<?=PORTAL_URL?>esqueci-minha-senha/">Mais não sei minha senha</a>
            </div>

          </div>
        </div><!-- END CONTAINER -->

      </div>
    </div>
  </div>

  <div class="footer">
    <div class="navigation">
      <a href="#" title="Sobre nós">Sobre nós</a>
      <span class="navigation-separator"></span>
      <a href="<?=PORTAL_URL?>termos-e-politica/" title="Termos e política">Termos e política</a>
      <span class="navigation-separator"></span>
      <a href="<?=PORTAL_URL?>ajuda/" title="Ajuda">Ajuda</a>
    </div>
  </div>



<script type="text/javascript">
$(document).ready(function(){
  $('#email-ou-usuario').focus();
  // $('[data-toggle="popover"]').popover();
  // $('#login').popover('hide');
  $('#email-ou-usuario').popover('destroy');
  $('#senha-do-usuario').popover('destroy');
  $('#email-ou-usuario').popover('hide');
  $('#senha-do-usuario').popover('hide');
  $('#email-ou-usuario').attr('data-content', '');
  $('#senha-do-usuario').attr('data-content', '');

  $('[data-toggle="popover"]').popover();
  $('#autenticar').submit(function( event ) {
    countError = 0;
    if( $('#email-ou-usuario').val() == '' || $('#email-ou-usuario').val() == null ){
      $('#email-ou-usuario').attr('data-content', 'Complete este dado.');
      $('#email-ou-usuario').popover('show');
      countError++;
    }
    if( $('#senha-do-usuario').val() == '' || $('#senha-do-usuario').val() == null ){
      $('#senha-do-usuario').attr('data-content', 'Complete este dado.');
      $('#senha-do-usuario').popover('show');
      countError++;
    }
    if( countError == 0 ){
      var data = $('#autenticar').formSerialize();
      $('#alerta-retorno').hide();
      $('#submit-loading').show();
      $('input#autenticar').attr("value", "aguarde...");
      projetouniversal.util.getjson({
        url : PORTAL_URL+"autenticar",
        data : $('#autenticar').serialize(),
        success : onSuccessAutenticar,
        error : onError
      });
    }
    return false;
  });//end FORM

  function onSuccessAutenticar(obj){
    // $('[data-toggle="popover"]').popover();
    // $('#login').popover('hide');
    $('#submit-loading').hide();
    $('input#autenticar').attr("value", "Entrar");
    if( obj.msg_error_number == 1 ){
      $('#alerta-retorno').show();
      $('#mensagem-retorno').addClass('alert-warning');
      $('#mensagem-retorno').html(obj.msg_error);
    }else if( obj.msg_error_number == 2 ){
      $('#senha-do-usuario').attr('data-content', ''+obj.msg_error);
      $('#senha-do-usuario').popover('show');
    }else if( obj.msg_error_number == 3 ){
      $('#email-ou-usuario').attr('data-content', ''+obj.msg_error);
      $('#email-ou-usuario').popover('show');
    }else if( obj.msg == 'success' ){
      window.location.href = obj.url_dashboard;
    }
  }
  //FUNCAO DE RETORNO DE ERRO DO AJAX
  function onError(args) {
    console.log( 'onError: ' + args );
  }
});
</script>

</body>
</html>
<?php 

  session_start();
  include "conf/config.php";
  $oConexao = Conexao::getInstance();

  // VERIFICAÇÕES DE SESSÕES
  if (isset( $_SESSION['usuario'] ) ) {
    echo "<script>window.location = '".PORTAL_URL."dashboard/#!';</script>";
    exit();
  }

  //VARIAVEL UNIVERSAL
  $msg = '';

  if( isset($_POST['codigo'] ) ) {

    $codigo         = $_POST['codigo'];
    $email          = $_POST['email'];
    $idusuario      = $_POST['idusuario'];
    $senha          = $_POST['senha'];

    //PEGAR OS DADOS DE REDEFINICÃO DA SENHA DE ACORDO O CODIGO INFORMADO
    $sql = "SELECT ( expira < NOW() ) AS menor FROM recuperarsenha WHERE email = '$email' AND codigo = '$codigo' AND alterada IS NULL";
    $result = $oConexao->query($sql);
    $dadosDefinicao = $result->fetch(PDO::FETCH_ASSOC);

    //PEGAR O TOTAL DE DADOS RETORNADOS
    $totalresultado = $oConexao->query($sql)->rowCount();

    echo "totalresultado ".$totalresultado;
    echo "MENOR: ".$dadosDefinicao['menor'];
    
    if( $dadosDefinicao['menor'] == 1 ) {
        $msg = "Seu código expirou. Faça uma nova solicitação, <a href='esqueci-minha-senha.php'>aqui</a>";
    }else{
          
          $senha = sha1($_POST['senha']);
          
          $stmt = $oConexao->prepare("UPDATE usuario SET senha = ? WHERE idusuario = ?");
          $stmt->bindValue(1, $senha);
          $stmt->bindValue(2, $idusuario);
          $stmt->execute();
              
          $atualiza = $oConexao->prepare("UPDATE recuperarsenha SET alterada = NOW() WHERE email = ? AND codigo = ?");
          $atualiza->bindValue(1, $email);
          $atualiza->bindValue(2, $codigo);
          $atualiza->execute();

          $success = 1;

          // $oConexao->commit();

    }//END IF


  }//END IF 

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
    
  <title><?php echo TITULOSISTEMA; ?></title>
  <link href="<?=CSS_FOLDER?>dashboard.css" rel="stylesheet"> 
  <link rel="shortcut icon" href="<?=FAVICONSISTEMA?>favicon.png" />

  <!-- bootstrap 3.0.2 -->
  <link href="<?=CSS_FOLDER?>bootstrap.min.css" rel="stylesheet" type="text/css" />
  <!-- bootstrap wysihtml5 - text editor -->
  <link href="<?=CSS_FOLDER?>bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />

  <script src="<?=JS_FOLDER;?>jquery.js"></script>
  <script src="<?=JS_FOLDER;?>jquery.form.js" type="text/javascript" ></script>

  <script src="<?=UTILS_FOLDER?>jquery.validate.js" type="text/javascript" ></script>

  <style type="text/css">
    body{padding: 0; margin: 0; background-image: -moz-linear-gradient(center top , #F7F7F7, #F5F5F5);}
    footer{background: #FFF !important; padding: 15px 0 0;}
    .alert {padding: 8px 35px 8px 14px;margin-bottom: 18px;text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);background-color: #fcf8e3;border: 1px solid #fbeed5;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;color: #c09853;}
    .alert-heading {color: inherit;}
    .alert-info {background-color: #d9edf7;border-color: #bce8f1;color: #3a87ad;}
  </style>

</head>
<body>
  <section id="login">

    <div class="common-wrapper">
    <div class="layout">
        <div class="common-topbar">
            <div class="common-main-header">
                <a href="<?=PORTAL_URL?>"><h1 class="common-logo common-logo-sai"></h1></a>
                <nav>
                    <ul class="common-main-nav">
                        <li class="common-main-nav-item"><a href="<?=PORTAL_URL?>" id="common-help">Início</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    </div>

    <form id="cadastro_form" action="" method="post" class="cards-card margin-bottom-3em" style="margin-top:10%; position:relative;">
      <?php if( $msg != '' ) { ?>
      <div id="alerta-retorno" class="alert" style="margin: 0.6em 0.6em 0.5em 0.6em; padding: 0 !important; width:95%; font-family:Roboto, arial;">
          <div id="mensagem-retorno"><?php echo $msg; ?></div>
      </div>
      <?php } ?>
      <?php if($success == 1){ ?>
        <nav class="out-of-box-title text-center"><img src="<?=PORTAL_URL?>img/sucess-ok.svg"/></nav>
        <nav class="row text-center" style="font-family: Roboto, arial;">
            <h2 class="fonte-cor-verde">Senha alterada com sucesso!</h2>
            <a href="<?=PORTAL_URL?>login">Retornar para página de Login</a>
        </nav>
      <?php } ?>
    </form>
  </section>

  <div class="common-bottombar">
    <footer class="common-main-footer">
        <nav>
            <ul class="common-footer-nav">
                <li><a href="#">Sobre</a></li>
                <li><a href="<?=PORTAL_URL?>termos-e-politica/" title="Termos e políticas">Termos e políticas</a></li>
                <li><a href="<?=PORTAL_URL?>ajuda/">Ajuda</a></li>
            </ul>
        </nav>
        <nav class="common-footer-nav-logo-gov"></nav>
        <p class="common-footer-legal"> Copyright &copy; <?=date('Y');?> Secretaria de Estado de Articulação Institucional - SAI. / Rua Floriano Peixoto, 460, Centro, / Rio Branco-AC / CEP: 69908-030 </p>
    </footer>
  </div>

  <div class="common-footer-bar">
    <footer class="common-main-footer">
        <p class="common-footer-legal"> Copyright &copy; <?=date('Y');?> Secretaria de Estado de Articulação Institucional - SAI</p>
    </footer>
  </div>
</body>
</html>
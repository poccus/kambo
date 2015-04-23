<?php 

  session_start();
  
  require("conf/phpmailer/class.phpmailer.php");
  include "conf/config.php";
  include "utils/funcoes.php";
  $oConexao = Conexao::getInstance();
  

  // VERIFICAÇÕES DE SESSÕES
  if (isset( $_SESSION['usuario'] ) ) {
    echo "<script>window.location = '".PORTAL_URL."dashboard/#!';</script>";
    exit();
  } 

  //VARIAVEL UNIVERSAL
  $msg = '';

  if( isset($_POST['email'] ) ) {
    
    $email = $_POST['email'];

    //PEGAR OS DADOS DO USUARIO DE ACORDO COM O SEU E-MAIL
    $sql = "SELECT * FROM usuario WHERE email = '$email' AND liberado = 1";
    //$result = $oConexao->query($sql);
    //$dadosUsuario = $result->fetch(PDO::FETCH_ASSOC);

    //PEGAR O TOTAL DE DADOS RETORNADOS
    $totalresultado = $oConexao->query($sql)->rowCount();

    if( $totalresultado == 0 ) {
      $msg = "Email não encontrado! por favor mande-nos um e-mail para sistemas.sai@ac.gov.br informando o assunto juntamente com suas informações pessoais: nome completo e telefones de contato.";
    } else {
      $assunto = EMAIL_TITULO."/ Recuperação de senha";
      $codigo = microtime();

      $stmt = $oConexao->prepare("INSERT INTO recuperarsenha(email, codigo, datasolicitacao, alterada, expira) VALUES (?, ?, NOW(), NULL , DATE_ADD(NOW(), INTERVAL 2 DAY))");
      $stmt->bindValue(1, $email);
      $stmt->bindValue(2, $codigo);
      $stmt->execute();

      // $oConexao->commit();
      
      $encode = base64_encode($email . "@@@" . $codigo );
      $msg    = "Secretaria de Estado de Articulação Institucional - SAI. \n\n  Envio de recuperação de senha para acesso ao Sistemas SAI. \n Você deve alterar sua senha no endereço: <a href='" . PORTAL_URL . "nova-senha.php?codigo=$encode'>www.sai.ac.gov.br</a>";
      $envio  = envia_email($email, $assunto, $msg, EMAIL_ENDERECO, TITULOSISTEMA) or die("ERRO GRAVE AO TENTAR RECUPERAR A SENHA");
      $msg    = "Você receberá um e-mail com instruções para alterar sua senha!";
    
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
  <link rel="shortcut icon" href="images/favicon.png">
  <title><?php echo TITULOSISTEMA; ?></title>
  <link href="<?=CSS_FOLDER?>dashboard.css" rel="stylesheet"> 
  <link rel="shortcut icon" href="<?=FAVICONSISTEMA?>favicon.png" />

  <!-- bootstrap 3.0.2 -->
  <link href="<?=CSS_FOLDER?>bootstrap.min.css" rel="stylesheet" type="text/css" />
  <!-- bootstrap wysihtml5 - text editor -->
  <link href="<?=CSS_FOLDER?>bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />

  <script src="<?=JS_FOLDER;?>jquery.js"></script>
  <script src="<?=JS_FOLDER;?>jquery.form.js" type="text/javascript" ></script>
  
  <style type="text/css">
  .common-bottombar{position: fixed;}
  </style>

  <style type="text/css">
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

    <form action="" method="post" class="cards-card margin-bottom-3em" style="margin-top:10%; position:relative;height: 40em;">
      <?php if( isset($_POST['email'] ) ) { ?>
      <div id="alerta-retorno" class="alert" style="margin: 0.6em 0.6em 0.5em 0.6em; padding: 15 !important; width:95%; font-family:Roboto, arial; float: left;">
          <div id="mensagem-retorno"><?php echo $msg; ?></div>
      </div>
      <?php } ?>
      <nav class="out-of-box-title text-center">Para redefinir sua senha digite o seu e-mail cadastrado, que enviaremos uma mensagem para redefinir sua senha de acesso</nav>
      <nav>
        <input type="text" name="email" placeholder="E-MAIL">
        <input type="submit" name="enviar" value="Obter nova senha" id="botao_entrar" class="bg-blue-logo color-branco border-none">
        <div class="text-muted text-center padding-4">ou</div>
        <a href="<?=PORTAL_URL?>login" class="bg-red btnaux margin-top-0-5em border-none color-branco text-center" style="line-height: 2.5em">Voltar e efetuar login</a>
      </nav>
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
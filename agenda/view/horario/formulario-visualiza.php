<?php 
  session_start();
  // INCLUDE TEMPLATE
  include_once "../../conf/config.php";
  include('../../template/head.php');
  include('../../template/header.php');
?>

<?php 
// $oConexao = Conexao::getInstance();

// $gmtDate = gmdate("D, d M Y H:i:s");

//SETANDO O TITULO DA PAGINA E SUBTITULO
// $_SESSION['titulopagina'] = 'CATEGORIA';
// $_SESSION['subtitulopagina'] = 'FORMULÁRIO';

// $modulos    = $_SESSION['modulodescricao'];
// // VERIFICAR SE É ADMINISTRADOR OU USUARIO COMUM
// // NIVEIS DE ACESSO -> 1 - ADMINISTRADOR , 2 - AUTOR e 3 - USUARIO COMUM
// if( $_SESSION['nivelacesso'] == 1 && !in_array('categoria', $modulos) ){
//   echo "<script>window.location = 'index.php';</script>";
//   exit();
// }else if( $_SESSION['nivelacesso'] == 2 && !in_array('categoria', $modulos) ){
//   echo "<script>window.location = 'index.php';</script>";
//   exit();
// }else if( $_SESSION['nivelacesso'] == 3 && !in_array('categoria', $modulos) ){
//   echo "<script>window.location = 'index.php';</script>";
//   exit();
// }

?>
<?php

$oConexao = new PDO("mysql:host=localhost;dbname=agenda;charset=utf8" , "root", "root");

//PARAMETRO POR URL AMIGAVEL NA POSIÇÃO 03 - PADRÃO
$param = !isset($_POST['id']) && isset($_GET['id']) ?  $_GET['id'] : $_POST['id'];

if( $param != null || $param != '' || $param != NULL ){

  $id = $param;

  // reusltado do usuário
  $result = $oConexao->prepare("SELECT id, nome, datanascimento, telefone, email 
                                FROM paciente  
                                WHERE id = ?");
  $result->bindValue(1, $id);
  $result->execute();
  $dados = $result->fetch(PDO::FETCH_ASSOC);

  $pacienteId                           = $dados['id'];
  $pacienteNome                         = $dados['nome'];
  $pacienteDataNascimento               = date_format(date_create($dados['datanascimento']),'d/m/Y');
  $pacienteTelefone                     = $dados['telefone'];
  $pacienteEmail                        = $dados['email'];
}else{
  $pacienteId                           = '';
  $pacienteNome                         = '';
  $pacienteDataNascimento               = '';
  $pacienteTelefone                     = '';
  $pacienteEmail                        = '';
}

?>

<!-- JAVASCRIPT -->
<script src="../../ajax/paciente/operacao-paciente.js"></script>

<!-- ITEM DE RETORNO -->
<div id="alerta-retorno" class="alert display-none">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <div id="mensagem-retorno"></div>
</div>
<!-- FIM DA MENSAGEM ITEM DE RETORNO -->

<div class="container-fluid" id="container-dashboard">

  <div class="row">
      <br/><br/>
      <div class="col-md-12 margin-bottom-1em">
          <a href="../../view/paciente/index.php" id="voltarMenu" class="pull-right">« voltar à lista</a>
      </div>
  </div>

  <div class="grid-title margin-bottom-2em">
    <h3><span class="semi-bold">Usuário</span></h3>
  </div>

  <form id="form-table" method='post' action='' enctype="multipart/form-data">

    <!-- CAMPOS  -->
    <div class="row">
      <div class="control-group">

        <div class="col-md-12 margin-bottom-0-5em">
          <label class="control-label">Nome: </label>
          <p class="form-control-static"><?php echo $pacienteNome; ?></p>
        </div>

      </div>
    </div>


    <!-- CAMPOS  -->
    <div class="row">
      <div class="control-group">

        <div class="col-md-6 margin-bottom-0-5em">
          <label class="control-label">Data Nascimento: </label>
          <p class="form-control-static"><?php echo $pacienteDataNascimento; ?></p>
        </div>
        <div class="col-md-6 margin-bottom-0-5em">
          <label class="control-label">Telefone: </label><br>
          <p class="form-control-static"><?php echo $pacienteTelefone; ?></p>
        </div>

      </div>
    </div>

    <!-- CAMPOS  -->
    <div class="row">
      <div class="control-group">

        <div class="col-md-12 margin-bottom-0-5em">
          <label class="control-label">Email: </label>
          <p class="form-control-static"><?php echo $pacienteEmail; ?></p>
        </div>

      </div>
    </div>

    <!-- Parâmetro ocultos -->
    <!-- id do projeto/acao -->
    <input type="hidden" id="idpaciente" name="idpaciente" value="<?=$param != '' ? $param : '' ?>"> 
    
  </form>

</div>

<?php 
  // INCLUDE TEMPLATE
  include('../../template/footer.php');
?>
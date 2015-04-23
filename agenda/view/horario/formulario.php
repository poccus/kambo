<?php
  session_start();
  include_once "../../conf/config.php";
  include('../../template/head.php');
  include('../../template/header.php');
?>
<?php
//include("conexao.php");
$oConexao = new PDO("mysql:host=localhost;dbname=agenda;charset=utf8" , "root", "root");

// $param = Url::getURL( 3 );
// echo $param;

// $id    = !isset($_POST['id']) && isset($_GET['id']) ?  $_GET['id'] : $_POST['id'] ;
// $param = Url::getURL( 3 );
// $param = $param == '' && $id != ''  ? $id : $param;
$param = !isset($_POST['id']) && isset($_GET['id']) ?  $_GET['id'] : $_POST['id'];

if( $param != null || $param != '' || $param != NULL ){

  $id = $param;
   // reusltado do usuário
  $result = $oConexao->prepare("SELECT id, descricao, idmedico 
                                FROM horario  
                                WHERE id = ?");
  $result->bindValue(1, $id);
  $result->execute();
  $dados = $result->fetch(PDO::FETCH_ASSOC);

  $horarioId                           = $dados['id'];
  $horarioNome                         = $dados['descricao'];
  $horarioIdMedico                     = $dados['idmedico'];
}else{
  $horarioId                           = '';
  $horarioNome                         = '';
  $horarioIdMedico                     = '';
}

?>

<!-- <link href='<?=CSS_FOLDER?>jquery.datetimepicker.css' rel='stylesheet' /> -->


<script src="<?=JS_FOLDER; ?>jquery.form.js" type="text/javascript" ></script>

<!-- <script src="<?=JS_FOLDER; ?>jquery.datetimepicker.js"></script> -->
<script src="../../ajax/horario/horario.js"></script>

<style>

 body {
  margin-top: 40px;
  /*text-align: center;*/
  font-size: 14px;
  font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
  }

  .event{
    display: block;
    background-color: #c3c3c3;
    width: 12px;
    height: 12px;
    margin-right: 2px;
    margin-bottom: 2px;
    -webkit-box-shadow: inset 0px 0px 5px 0px rgba(0, 0, 0, 0.4);
    box-shadow: inset 0px 0px 5px 0px rgba(0, 0, 0, 0.4);
    border-radius: 8px;
    border: 1px solid #ffffff;
  }
  .event-situacao1{background-color: #AD2121;}
  .event-situacao2{background-color: #1E90FF;}
  .event-situacao3{background-color: #E3BC08;}
  .event-situacao4{background-color: #1B1B1B;}
  .event-situacao5{background-color: #800080;}

  .w100{width:100%}
  .al_left{float:left}.al_right{float:right}
 
</style>
<div class="container-fluid" id="container-dashboard">

  <div class="row">
      <br/><br/>
      <div class="col-md-12 margin-bottom-1em">
          <a href="../../view/horario/index.php" id="voltarMenu" class="pull-right">« voltar à lista</a>
      </div>
  </div>

  <div id="alerta-retorno" class="alert display-n">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <div id="mensagem-retorno"></div>
  </div>

  <form id="formhorario" name="formhorario">

    <input type="hidden" id="idhorario" name="idhorario" value="<?=$id ;?>">

    <!--<div class="grid-title margin-bottom-2em">
      <h3>Agenda <span class="semi-bold">do dia</span></h3>
      <img src="../../imagens/consultas.gif">
    </div>
    -->

    <br>
    <h4 class="semi-bold"><span class="light">Informações do Paciente</span></h4>
    <br>

    <!-- CAMPOS  -->
    <div class="row">
      <div class="control-group">
        <div class="col-md-12 margin-bottom-0-5em">
          <label class="control-label" for="horario_nome">Nome: <span class="require">*</span></label>
          <input type="text" class="form-control" placeholder="Informe o nome do horario" id="horario_nome" name="horario_nome" value="<?php echo $horarioNome; ?>">
        </div>
      </div>
    </div>
          
    <!-- CAMPOS  -->
    <div class="row">
      <div class="control-group">

        <div class="col-md-6 margin-bottom-0-5em">
          <label class="control-label" for="horario_data_nascimento">Data de Nascimento: <span class="require">*</span></label>
          <input type="text" class="form-control" placeholder="Informe a data de nascimento (dd/mm/aaaa)" id="horario_data_nascimento" name="horario_data_nascimento" onkeydown="Mascara(this, Data);" maxlength="10" value="<?php echo $horarioDataNascimento; ?>">
        </div>

        <div class="col-md-6 margin-bottom-0-5em">
          <label class="control-label" for="horario_telefone">Telefone: <span class="require">*</span></label>
          <input type="text" class="form-control" placeholder="Informe o telefone do horario" id="horario_telefone" name="horario_telefone" maxlength="14" onkeypress="Mascara(this, Telefone);" value="<?php echo $horarioTelefone; ?>">
        </div>

      </div>
    </div>

    <!-- CAMPOS  -->
    <div class="row">
      <div class="control-group">
         <div class="col-md-12 margin-bottom-0-5em">
          <label class="control-label" for="horario_email">Email: <span class="require">*</span></label>
          <input type="text" class="form-control" placeholder="Informe o e-mail do horario" id="horario_email" name="horario_email" value="<?php echo $horarioEmail; ?>">
        </div>
      </div>
    </div>

    <br>
      <button type="button" id="salvar_horario" class="btn btn-primary">Salvar</button> 
    <br>

    <br>

  </form>

</div>
<?php include('../../template/footer.php'); ?>
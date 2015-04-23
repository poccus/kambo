<?php 
  //session_start();

  // INCLUDE TEMPLATE
  include_once "conf/config.php";
  include_once "utils/funcoes.php";
  $oConexao = Conexao::getInstance();

  include('template/header.php');
  include('template/menubar.php');
  include('template/asideright.php');

?>

<link href='<?=CSS_FOLDER?>fullcalendar.css' rel='stylesheet' />
<link href='<?=CSS_FOLDER?>fullcalendar.print.css' rel='stylesheet' media='print' />

<link href='<?=JS_FOLDER?>jquery-ui.min.css' rel='stylesheet' />



<style>
.clsDatePicker {
    position: relative;
    z-index: 100000;
}
</style>

<div class="content clearfix">

  <?php if( isset($_POST['type']) ){ 
    $alert_type = $_POST['type'] == 'success' ? 'alert-success' : 'alert-danger';
    $alert_icon = $_POST['type'] == 'success' ? '<span class="glyphicon glyphicon-ok"></span>' : '<span class="glyphicon glyphicon-remove"></span>'; 
  ?>
  <div class="col-sm-12">
    <div id="return-feedback" class="alert <?=$alert_type?>">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <div id="msg-feedback"><?=$alert_icon?>  <?=$_POST['feedback']?> <?=$_POST['error']?></div>
    </div>
  </div><!-- FEEDBACK MESSAGE -->
  <?php } ?>

  <aside class="col-sm-2">
    <h2>Sala de Espera</h2>
    <!--<p class="help-text">Selecione o dia abaixo para adicionar a exceção:</p>-->
    <div id="salaespera">Nenhum registro.</div>
  </aside><!-- ASIDE BAR  -->

  <div class="col-sm-10"><!-- BEGIN PANEL -->

    <form id="createform" name="createform" class="form-horizontal" action=""> 

    <div class="form-group">
      <div class="col-sm-3 controls">
        <select id="profissionalagenda" name="profissionalagenda" class="form-control input-sm">
          <option value="">Escolha o Profissional</option>
          <?php 
          $slct = $oConexao->query('SELECT * FROM profissional order by nome');
          while( $rowSlct  = $slct->fetch(PDO::FETCH_ASSOC) ){
            echo '<option value="'.$rowSlct['idprofissional'].'">'.$rowSlct['nome'].'</option>';
          }
          ?>
        </select>
      </div>
      <div class="col-sm-2 controls">
        <div class='input-group date' id="calendariodatapesquisa">
            <input type="text" class="form-control input-sm" placeholder="<?=date('d/m/Y')?>" id="data_pesquisa" name="data_pesquisa" tabindex="3" />
            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
      </div>
      <div class="col-sm-4 controls">
        <input name="filtronome" id="filtronome" class="form-control input-sm" type="text" placeholder="Digite o nome do paciente">
        <a type="submit" name="filtrobuscapaciente" id="filtrobuscapaciente" class="btn btn-default btn-sm relative-btn pull-right"><i class="glyphicon glyphicon-search"></i></a>
      </div>
      <div class="col-sm-3 controls">
        <a href="javascript:;" class="btn btn-success btn-sm pull-right"><i class="glyphicon glyphicon-plus"></i> Adicionar consulta</a>
      </div>
    </div><!-- END INPUTS -->

    <div id="profissional-slc" class="row options display-n">
        <h1 class="pull-left">Carregando profissional...</h1>
    </div>

    <div class="form-group">
      <div class="col-sm-12 controls">
        <div id='calendar'></div>
      </div>
    </div>
    
  </form>

  </div>

</div>


<script src='<?=JS_FOLDER?>jquery-ui.min.js'></script>
<script src='<?=JS_FOLDER?>fullcalendar.js'></script>
<script src='<?=JS_FOLDER?>lang-all.js'></script>
<script src="<?=PORTAL_URL?>ajax/agenda/agenda.js"></script>

<?php  include('template/footer.php'); ?>
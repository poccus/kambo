<!-- INCLUDE JAVASCRIPT -->
<script src="<?=PORTAL_URL?>ajax/excecao/formulario.js"></script>
<?php 
  session_start();

  // INCLUDE TEMPLATE
  include_once "conf/config.php";
  $oConexao = Conexao::getInstance();
?>
<?php

$id    = !isset($_POST['id']) && isset($_GET['id']) ?  $_GET['id'] : $_POST['id'] ;
$param = Url::getURL( 3 );
$param = $param == '' && $id != ''  ? $id : $param;

if( $param != null || $param != '' || $param != NULL ){

  $id = $param;
   // resultado
  $result = $oConexao->prepare("SELECT id, idprofissional, data, horainicialatendimento, horafinalatendimento, datacadastro
                                FROM excecao  
                                WHERE id = ?");
  $result->bindValue(1, $id);
  $result->execute();
  $dados = $result->fetch(PDO::FETCH_ASSOC);

  $idexcecao                            = $dados['id'];
  $excecao_profissional                 = $dados['idprofissional'];
  $excecao_data                         = data_volta( $dados['data'] );
  $excecao_horainicio                   = $dados['horainicialatendimento'];
  $excecao_horafinal                    = $dados['horafinalatendimento'];

}else{

  $idexcecao                            = '';

}

?>

<form id="createform" name="createform" class="form-horizontal" method="post" action="" enctype="multipart/form-data"> 

<div class="modal-content">
  <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
      <h3 class="modal-title">Adicionar exceção</h3>
  </div>
  <div class="modal-body"> 

      <input type="hidden" id="idexcecao" name="idexcecao" value="<?=$idexcecao?>">

      <div class="form-group">
        <div class="col-sm-12 controls">
          <label class="control-label" for="excecao_data">Data da exceção</label>
          <div class='input-group date' id='excecao_datetimepicker'>
              <input type="text" class="form-control input-sm" id="excecao_data" name="excecao_data" tabindex="1" readonly="readonly" value="<?=$_REQUEST['excecao_data'] != '' ?  $_REQUEST['excecao_data'] : $excecao_data ?>" />
              <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
              </span>
          </div>
        </div>
      </div><!-- END INPUT -->

      <div class="form-group">
        <div class="col-sm-12 controls">
          <label class="control-label" for="excecao_profissional">Profissional *</label>
          <select class="form-control input-sm selectpicker" id="excecao_profissional" name="excecao_profissional" tabindex="2">
            <option value=""></option>
            <?php 
            $result = $oConexao->prepare("SELECT idprofissional, nome FROM profissional"); $result->execute();
            while( $dados = $result->fetch(PDO::FETCH_ASSOC) ){
            ?>
            <option value="<?=$dados['idprofissional']?>" <?=$excecao_profissional == $dados['idprofissional'] ? 'selected="true"' : '' ?> ><?=$dados['nome']?></option>
            <?php 
            }
            ?>
          </select>
        </div>
      </div><!-- END INPUTS -->

      <div class="form-group">
        <div class="col-sm-6 controls">
          <label class="control-label" for="excecao_inicio">Horário de início *</label>
          <input type="text" class="form-control input-sm" placeholder="00:00" id="excecao_inicio" name="excecao_inicio" tabindex="3" value="<?=$excecao_horainicio?>">
        </div>

        <div class="col-sm-6 controls">
          <label class="control-label" for="excecao_fim">Horário de final *</label>
          <input type="text" class="form-control input-sm" placeholder="00:00" id="excecao_fim" name="excecao_fim" tabindex="4" value="<?=$excecao_horafinal?>">
        </div>
      </div><!-- END INPUTS -->

  </div><!-- END MODAL BODY -->
  <div class="modal-footer">
    <?php if( $idexcecao != '' || $idexcecao != NULL ){ ?>
    <button id="deletaritem" type="button" class="btn btn-danger pull-left" value="_delete"><i class="glyphicon glyphicon-trash"></i> Excluir</button>
    <?php } ?>
    <img class="preload-submit display-n" src="<?=PORTAL_URL?>imagens/load.gif">
    <button id="enviarformulario" type="submit" class="btn btn-primary" value="_save">Salvar</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
  </div>
</div>
</form>
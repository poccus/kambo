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
  $result = $oConexao->prepare("SELECT idprofissional, nome, crm, email, telefone, tempoconsulta, horainicialatendimento, horafinalatendimento, horainicialalmoco, horafinalalmoco, valorprocedimento, date_format(datacadastro, '%d/%m/%Y %h:%i') as datacadastro
                                FROM profissional  
                                WHERE idprofissional = ?");
  $result->bindValue(1, $id);
  $result->execute();
  $dados = $result->fetch(PDO::FETCH_ASSOC);

  $profissionalId                           = $dados['idprofissional'];
  $profissionalNome                         = $dados['nome'];
  $profissionalCrm                          = $dados['crm'];
  $profissionalTelefone                     = $dados['telefone'];
  $profissionalEmail                        = $dados['email'];
  $profissionaltempo_consulta               = $dados['tempoconsulta'];
  $profissionalvalor_consulta               = $dados['valorprocedimento'];
  $profissionalinicio_atendimento           = $dados['horainicialatendimento'];
  $profissionalfinal_atendimento            = $dados['horafinalatendimento'];
  $profissionalinicioalmoco_atendimento     = $dados['horainicialalmoco'];
  $profissionalfinalalmoco_atendimento      = $dados['horafinalalmoco'];
  $profissionalDataCadastro                 = $dados['datacadastro'];

  $result = $oConexao->prepare("SELECT idprofissional, dia 
                                FROM profissional_diastrabalho 
                                WHERE idprofissional = ?");
  $result->bindValue(1, $id);
  $result->execute();
  
  $dadosDiasAtendimento = array();
  while($row = $result->fetch(PDO::FETCH_ASSOC)){
    $dadosDiasAtendimento[] = $row['dia'];
  }

}

?>

<div class="modal-content">
  <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
      <h3 class="modal-title">Profissional</h3>
  </div>
  <div class="modal-body">
      
      <fieldset class="clear">
      <div class="section">Informações básicas</div>
          <div class="row">
            <div class="col-sm-12 controls">
              Nome: <span class="help-text"><?=$profissionalNome?></span>
            </div>
          </div><!-- END -->

          <div class="row">
            <div class="col-sm-6 controls">
              Registro(CRM): <span class="help-text"><?=$profissionalCrm?></span>
            </div>

            <div class="col-sm-6 controls">
              Telefone: <span class="help-text"><?=$profissionalTelefone?></span>
            </div>
          </div><!-- END-->

          <div class="row">
            <div class="col-sm-12 controls">
              E-mail: <span class="help-text"><?=$profissionalEmail?></span>
            </div>
          </div><!-- END -->
      </fieldset><!-- END FIELDSET -->

      <fieldset class="clear">
      <div class="section">Consulta</div>
          <div class="row">
            <div class="col-sm-12 controls">
              Qual a duração média da sua consulta? <span class="help-text"><?=$profissionaltempo_consulta?> duração (em minutos)</span>
            </div>
          </div><!-- END -->

          <div class="row">
            <div class="col-sm-12 controls">
              Valor da consulta? R$<span class="help-text"><?=$profissionalvalor_consulta?></span>
            </div>
          </div><!-- END-->
      </fieldset><!-- END FIELDSET -->

      <fieldset class="clear">
      <div class="section">Horário de atendimento</div>
          <div class="row">
            <div class="col-sm-6 controls">
              Início: <span class="help-text"><?=$profissionalinicio_atendimento?></span>
            </div>
          </div><!-- END -->

          <div class="row">
            <div class="col-sm-6 controls">
              Final: <span class="help-text"><?=$profissionalfinal_atendimento?></span>
            </div>
          </div><!-- END-->
      </fieldset><!-- END FIELDSET -->

      <fieldset class="clear">
      <div class="section">Horário de almoço</div>
          <div class="row">
            <div class="col-sm-6 controls">
              Início: <span class="help-text"><?=$profissionalinicioalmoco_atendimento?></span>
            </div>
          </div><!-- END -->

          <div class="row">
            <div class="col-sm-6 controls">
              Final: <span class="help-text"><?=$profissionalfinalalmoco_atendimento?></span>
            </div>
          </div><!-- END-->
      </fieldset><!-- END FIELDSET -->

      <?php if( sizeof($dadosDiasAtendimento) > 1 ){ ?>
      <fieldset class="clear">
      <div class="section">Dias de atendimento</div>
          <div class="row">
            <?php for($i = 0; $i < sizeof($dadosDiasAtendimento); $i++){ ?>
            <div class="col-sm-12 controls"><i class="glyphicon glyphicon-check"></i> <?=getDiaSemana($dadosDiasAtendimento[$i], 1)?></div>
            <?php } ?>
          </div><!-- END -->
      </fieldset><!-- END FIELDSET -->
      <?php } ?>

  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
  </div>
</div>
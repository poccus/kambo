<?php 
  session_start();

  // INCLUDE TEMPLATE
  include_once "conf/config.php";
  $oConexao = Conexao::getInstance();

  include('template/header.php');
  include('template/menubar.php');
  include('template/asideright.php');

?>
<?php

$id    = !isset($_POST['id']) && isset($_GET['id']) ?  $_GET['id'] : $_POST['id'] ;
$param = Url::getURL( 3 );
$param = $param == '' && $id != ''  ? $id : $param;

if( $param != null || $param != '' || $param != NULL ){

  $id = $param;
   // resultado do usuário
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
}else{
  $profissionalId                           = '';
  $profissionalNome                         = '';
  $profissionalCrm                          = '';
  $profissionalTelefone                     = '';
  $profissionalEmail                        = '';
  $profissionaltempo_consulta               = '';
  $profissionalvalor_consulta               = '';
  $profissionalinicio_atendimento           = '';
  $profissionalfinal_atendimento            = '';
  $profissionalinicioalmoco_atendimento     = '';
  $profissionalfinalalmoco_atendimento      = '';
}

  $result = $oConexao->prepare("SELECT idprofissional, dia 
                                FROM profissional_diastrabalho 
                                WHERE idprofissional = ?");
  $result->bindValue(1, $id);
  $result->execute();
  
  $dadosDiasAtendimento = array();
  while($row = $result->fetch(PDO::FETCH_ASSOC)){
    $dadosDiasAtendimento[] = $row['dia'];
  }
  
?>

<div class="form-content clearfix" id="container-dashboard">

  <div class="main-formulario clearfix">
    <h4 class="pull-left">Adicionar profissional</h4>
    <h5 class="go-back pull-right">
      <a href="<?=PORTAL_URL?>view/profissional/index" class="pull-right">« voltar à lista</a>
      <?=$profissionalDataCadastro != '' ? '<br><i class="pull-right text-muted">Profissional cadastrado em '.$profissionalDataCadastro.'</i>' : '' ?>
    </h5>
  </div>

  <div id="return-feedback" class="alert display-n">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <div id="msg-feedback"></div>
  </div>

  <form id="createform" name="createform" class="form-horizontal" method="post" action="" enctype="multipart/form-data">

    <input type="hidden" id="idprofissional" name="idprofissional" value="<?=$profissionalId?>">

    <fieldset class="clear">
      <div class="section">Informações básicas</div>
      
      <div class="form-group">
        <label class="col-sm-1 control-label" for="profissional_nome">Nome *</label>
        <div class="col-sm-11 controls">
          <input type="text" class="form-control input-sm" placeholder="Informe o nome do médico" id="profissional_nome" name="profissional_nome" tabindex="1" value="<?php echo $profissionalNome; ?>">
        </div>
      </div><!-- END INPUT -->

      <div class="form-group">
        <label class="col-sm-1 control-label" for="profissional_crm">Registro *</label>
        <div class="col-sm-3 controls">
          <input type="text" class="form-control input-sm" placeholder="Informe o CRM do médico" id="profissional_crm" name="profissional_crm" tabindex="2" value="<?php echo $profissionalCrm; ?>">
        </div>

        <label class="col-sm-1 control-label" for="profissional_telefone">Telefone</label>
        <div class="col-sm-3 controls">
          <input type="text" class="form-control input-sm" placeholder="Informe o telefone do médico" id="profissional_telefone" name="profissional_telefone" tabindex="3" value="<?php echo $profissionalTelefone; ?>">
        </div>

        <label class="col-sm-1 control-label" for="profissional_email">E-mail</label>
        <div class="col-sm-3 controls">
          <input type="text" class="form-control input-sm" placeholder="Informe o e-mail do médico" id="profissional_email" name="profissional_email" tabindex="4" value="<?php echo $profissionalEmail; ?>">
        </div>
      </div><!-- END INPUTS -->

    </fieldset><!-- END FIELDSET -->


    <fieldset class="clear">
      <div class="section">Consulta</div>
      
      <div class="form-group">
        <label class="col-sm-3 control-label" for="tempo_consulta">Qual a duração média da sua consulta?</label>
        <div class="col-sm-3 controls">
          <input type="text" pattern="[0-9]{10}" maxlength="5" class="form-control input-sm" placeholder="15" id="tempo_consulta" name="tempo_consulta" tabindex="5" value="<?=$profissionaltempo_consulta?>">
          <p class="help-block">duração (em minutos)</p>
        </div>
      </div><!-- END INPUT -->

      <div class="form-group">
        <label class="col-sm-3 control-label" for="valor_consulta">Valor da consulta? R$</label>
        <div class="col-sm-3 controls">
          <input type="text" class="form-control input-sm" placeholder="0,00" id="valor_consulta" name="valor_consulta" onkeyup="Mascara(this,Valor);" onkeypress="Mascara(this,Valor);" onkeydown="Mascara(this,Valor);" tabindex="6" value="<?=valorMonetario($profissionalvalor_consulta)?>">
          <p class="help-block">em reais</p>
        </div>
      </div><!-- END INPUT -->

    </fieldset><!-- END FIELDSET -->


    <fieldset class="clear">
      <div class="section">Horário de atendimento</div>
      
      <div class="form-group">
        <label class="col-sm-1 control-label" for="horainicio_atendimento">Início*</label>
        <div class="col-sm-3 controls">
          <input type="text" class="form-control input-sm" placeholder="08:00" id="horainicio_atendimento" name="horainicio_atendimento" tabindex="7" value="<?=$profissionalinicio_atendimento?>">
          <p class="help-block col-sm-2">horas</p>
        </div>
      </div><!-- END INPUT -->

      <div class="form-group">
        <label class="col-sm-1 control-label" for="horafinal_atendimento">Fim*</label>
        <div class="col-sm-3 controls">
          <input type="text" class="form-control input-sm" placeholder="18:00" id="horafinal_atendimento" name="horafinal_atendimento" tabindex="8" value="<?=$profissionalfinal_atendimento?>">
          <p class="help-block col-sm-2">horas</p>
        </div>
      </div><!-- END INPUT -->

    </fieldset><!-- END FIELDSET -->

    <fieldset class="clear">
      <div class="section">Horário de intervalo</div>
      
      <div class="form-group">
        <label class="col-sm-1 control-label" for="horainicioalmoco_atendimento">Início</label>
        <div class="col-sm-3 controls">
          <input type="text" class="form-control input-sm" placeholder="12:00" id="horainicioalmoco_atendimento" name="horainicioalmoco_atendimento" tabindex="9" value="<?=$profissionalinicioalmoco_atendimento?>">
          <p class="help-block col-sm-2">horas</p>
        </div>
      </div><!-- END INPUT -->

      <div class="form-group">
        <label class="col-sm-1 control-label" for="horafinalalmoco_atendimento">Fim</label>
        <div class="col-sm-3 controls">
          <input type="text" class="form-control input-sm" placeholder="14:00" id="horafinalalmoco_atendimento" name="horafinalalmoco_atendimento" tabindex="10" value="<?=$profissionalfinalalmoco_atendimento?>">
          <p class="help-block col-sm-2">horas</p>
        </div>
      </div><!-- END INPUT -->

    </fieldset><!-- END FIELDSET -->

    <fieldset class="clear">
      <div class="section">Dias de atendimento</div>
      
      <div class="form-group">
        <div class="col-sm-12">
          <label class="checkbox-inline">
            <input type="checkbox" name="dia_atendimento[]" id="dia_atendimento" value="1" <?if( in_array('1', $dadosDiasAtendimento) ){ echo "checked='true'";}?> > Domingo
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name="dia_atendimento[]" id="dia_atendimento" value="2" <?if( in_array('2', $dadosDiasAtendimento) ){ echo "checked='true'";}?> > Segunda
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name="dia_atendimento[]" id="dia_atendimento" value="3" <?if( in_array('3', $dadosDiasAtendimento) ){ echo "checked='true'";}?> > Terça
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name="dia_atendimento[]" id="dia_atendimento" value="4" <?if( in_array('4', $dadosDiasAtendimento) ){ echo "checked='true'";}?> > Quarta
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name="dia_atendimento[]" id="dia_atendimento" value="5" <?if( in_array('5', $dadosDiasAtendimento) ){ echo "checked='true'";}?> > Quinta
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name="dia_atendimento[]" id="dia_atendimento" value="6" <?if( in_array('6', $dadosDiasAtendimento) ){ echo "checked='true'";}?> > Sexta
          </label>
          <label class="checkbox-inline">
            <input type="checkbox" name="dia_atendimento[]" id="dia_atendimento" value="7" <?if( in_array('7', $dadosDiasAtendimento) ){ echo "checked='true'";}?> > Sábado
          </label>
        </div>
      </div><!-- END INPUT -->

    </fieldset><!-- END FIELDSET -->


    <fieldset class="clear form-actions margin-bottom-0">
        <button id="enviarformulario" type="submit" class="btn btn-primary" value="_save">Salvar</button>
        <button id="enviareditar" type="submit" class="btn btn-default" value="_continue">Salvar e continuar editando</button> 
        <button id="enviaradicionar" type="submit" class="btn btn-default" value="_addanother">Salvar e adicionar outro</button>
        <?php if( $profissionalId != '' || $profissionalId != NULL ){ ?>
        <button id="deletaritem" type="button" class="btn btn-danger pull-right" value="_delete"><i class="glyphicon glyphicon-trash"></i> Excluir</button>
        <?php } ?>
        <img class="preload-submit display-n" src="<?=PORTAL_URL?>imagens/load.gif"> 
    </fieldset><!-- END FIELDSET -->

  </form>

</div>

<!-- INCLUDE JAVASCRIPT -->
<script src="<?=PORTAL_URL?>ajax/profissional/formulario.js"></script>
<?php include('template/footer.php'); ?>
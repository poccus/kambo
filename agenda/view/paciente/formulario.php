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
  $result = $oConexao->prepare("SELECT id, nome, datanascimento, rg, cpf, sexo, observacao, email, telefone, celular, sms, status, date_format(datacadastro, '%d/%m/%Y %h:%i') as datacadastro, cep, logradouro, numero, complemento, bairro, cidade, idestado, idpais
                                FROM paciente  
                                WHERE id = ?");
  $result->bindValue(1, $id);
  $result->execute();
  $dados = $result->fetch(PDO::FETCH_ASSOC);

  $idpaciente                           = $dados['id'];
  $paciente_nome                        = $dados['nome'];
  $paciente_datanascimento              = data_volta( $dados['datanascimento'] );
  $paciente_rg                          = $dados['rg'];
  $paciente_cpf                         = $dados['cpf'];
  $paciente_email                       = $dados['email'];
  $paciente_sexo                        = $dados['sexo'];
  $paciente_observacao                  = $dados['observacao'];
  $paciente_status                      = $dados['status'];
  $paciente_datacadastro                = $dados['datacadastro'];
  $paciente_celular                     = $dados['celular'];
  $paciente_sms                         = $dados['sms'];
  $paciente_telefone                    = $dados['telefone'];
  $paciente_cep                         = $dados['cep'];
  $paciente_logradouro                  = $dados['logradouro'];
  $paciente_complemento                 = $dados['complemento'];
  $paciente_numero                      = $dados['numero'];
  $paciente_bairro                      = $dados['bairro'];
  $paciente_cidade                      = $dados['cidade'];
  $paciente_estado                      = $dados['idestado'];
  $paciente_pais                        = $dados['idpais'];
}else{
  $idpaciente                           = '';
  $paciente_nome                        = '';
  $paciente_datanascimento              = '';
  $paciente_rg                          = '';
  $paciente_cpf                         = '';
  $paciente_email                       = '';
  $paciente_sexo                        = '';
  $paciente_observacao                  = '';
  $paciente_status                      = '';
  $paciente_datacadastro                = '';
  $paciente_celular                     = '';
  $paciente_sms                         = '';
  $paciente_telefone                    = '';
  $paciente_cep                         = '';
  $paciente_logradouro                  = '';
  $paciente_complemento                 = '';
  $paciente_numero                      = '';
  $paciente_bairro                      = '';
  $paciente_cidade                      = '';
  $paciente_estado                      = '';
  $paciente_pais                        = '';
}

?>

<div class="form-content clearfix" id="container-dashboard">

  <div class="main-formulario clearfix">
    <h4 class="pull-left">Adicionar paciente</h4>
    <h5 class="go-back pull-right">
      <a href="<?=PORTAL_URL?>view/paciente/index" class="pull-right">« voltar à lista</a>
      <?=$paciente_datacadastro != '' ? '<br><i class="pull-right text-muted">Paciente cadastrado em '.$paciente_datacadastro.'</i>' : '' ?>
    </h5>
  </div>

  <div id="return-feedback" class="alert display-n">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <div id="msg-feedback"></div>
  </div>

  <form id="createform" name="createform" class="form-horizontal" method="post" action="" enctype="multipart/form-data">

    <input type="hidden" id="idpaciente" name="idpaciente" value="<?=$idpaciente?>">

    <fieldset class="clear">
      <div class="section">Informações básicas</div>
      
      <div class="form-group">
        <label class="col-sm-1 control-label" for="paciente_nome">Nome *</label>
        <div class="col-sm-11 controls">
          <input type="text" class="form-control input-sm" placeholder="Informe o nome do paciente" id="paciente_nome" name="paciente_nome" maxlength="80" tabindex="1" value="<?=$paciente_nome?>">
        </div>
      </div><!-- END INPUT -->

      <div class="form-group">
        <label class="col-sm-1 control-label" for="paciente_datanascimento">Data de nascimento</label>
        <div class="col-sm-3 controls">
          <input type="text" class="form-control input-sm" placeholder="Informe a data de nascimento" id="paciente_datanascimento" name="paciente_datanascimento" tabindex="2" value="<?=$paciente_datanascimento?>">
        </div>
      </div><!-- END INPUTS -->

      <div class="form-group">
        <label class="col-sm-1 control-label" for="paciente_rg">RG</label>
        <div class="col-sm-3 controls">
          <input type="text" class="form-control input-sm" placeholder="Informe o RG" id="paciente_rg" name="paciente_rg" tabindex="3" value="<?=$paciente_rg?>">
        </div>

        <label class="col-sm-1 control-label" for="paciente_cpf">CPF</label>
        <div class="col-sm-3 controls">
          <input type="text" class="form-control input-sm" placeholder="Informe o CPF" id="paciente_cpf" name="paciente_cpf" tabindex="4" value="<?=$paciente_cpf?>">
        </div>

        <label class="col-sm-1 control-label" for="paciente_email">E-mail</label>
        <div class="col-sm-3 controls">
          <input type="text" class="form-control input-sm" placeholder="Informe o e-mail do paciente" id="paciente_email" name="paciente_email" maxlength="100" tabindex="5" value="<?=$paciente_email?>">
        </div>
      </div><!-- END INPUTS -->

      <div class="form-group">
        <label class="col-sm-1 control-label" for="paciente_sexo">Sexo</label>
        <div class="col-sm-6 controls">
          <label class="radio-inline">
            <input type="radio" name="paciente_sexo" id="paciente_sexo" tabindex="6" value="M" <?=$paciente_sexo == 'M' ? 'checked="true"' : '' ?> > Masculino
          </label>
          <label class="radio-inline">
            <input type="radio" name="paciente_sexo" id="paciente_sexo" tabindex="6" value="F" <?=$paciente_sexo == 'F' ? 'checked="true"' : '' ?> > Feminino
          </label>
        </div>
      </div><!-- END INPUTS -->

      <div class="form-group">
        <label class="col-sm-1 control-label" for="paciente_observacao">Observação</label>
        <div class="col-sm-6 controls">
          <textarea name="paciente_observacao" class="form-control" maxlength="255" rows="3" tabindex="7"><?=$paciente_observacao?></textarea>
        </div>
      </div><!-- END INPUTS -->

      <div class="form-group">
        <label class="col-sm-1 control-label" for="paciente_status"></label>
        <div class="col-sm-4 controls">
          <label class="checkbox-inline" for="paciente_status"><input type="checkbox" id="paciente_status" name="paciente_status" tabindex="8" value="1" <?=$paciente_status == '1' || $paciente_status == '' ? 'checked="true"' : '' ?> > Ativo</label>
        </div>
      </div><!-- END INPUTS -->

    </fieldset><!-- END FIELDSET -->


    <fieldset class="clear">
      <div class="section">Contato</div>
      
      <div class="form-group">
        <label class="col-sm-1 control-label" for="paciente_celular">Celular *</label>
        <div class="col-sm-3 controls">
          <input type="text" pattern="[0-9]{10}" class="form-control input-sm" placeholder="Informe o celular" id="paciente_celular" name="paciente_celular" tabindex="9" value="<?=$paciente_celular?>">
        </div>

        <label class="col-sm-2 control-label" for="paciente_sms"><input type="checkbox" id="paciente_sms" name="paciente_sms" tabindex="10" value="1" <?=$paciente_sms == '1' || $paciente_sms == '' ? 'checked="true"' : '' ?> > Receber SMS</label>
      </div><!-- END INPUT -->

      <div class="form-group">
        <label class="col-sm-1 control-label" for="paciente_telefone">Telefone</label>
        <div class="col-sm-3 controls">
          <input type="text" pattern="[0-9]{10}" class="form-control input-sm" placeholder="Informe o telefone" id="paciente_telefone" name="paciente_telefone" tabindex="11" value="<?=$paciente_telefone?>">
        </div>
      </div><!-- END INPUT -->

    </fieldset><!-- END FIELDSET -->


    <fieldset class="clear">
      <div class="section">Endereço</div>
      
      <div class="form-group">
        <label class="col-sm-1 control-label" for="paciente_cep">CEP</label>
        <div class="col-sm-3 controls">
          <input type="text" class="form-control input-sm" placeholder="Informe o CEP" id="paciente_cep" name="paciente_cep" tabindex="12" value="<?=$paciente_cep?>">
        </div>
        <img id="preload-cep" class="display-n" src="<?=PORTAL_URL?>imagens/ajax.gif">
      </div><!-- END INPUT -->

      <div class="form-group">
        <label class="col-sm-1 control-label" for="paciente_logradouro">Logradouro</label>
        <div class="col-sm-3 controls">
          <input type="text" class="form-control input-sm" placeholder="Informe o logradouro" id="paciente_logradouro" name="paciente_logradouro" tabindex="13" value="<?=$paciente_logradouro?>">
        </div>

        <label class="col-sm-1 control-label" for="paciente_complemento">Complemento</label>
        <div class="col-sm-3 controls">
          <input type="text" class="form-control input-sm" placeholder="Informe o complemento" id="paciente_complemento" name="paciente_complemento" maxlength="40" tabindex="14" value="<?=$paciente_complemento?>">
        </div>
      </div><!-- END INPUT -->

      <div class="form-group">
        <label class="col-sm-1 control-label" for="paciente_numero">Número</label>
        <div class="col-sm-3 controls">
          <input type="text" class="form-control input-sm" placeholder="Informe o número" id="paciente_numero" name="paciente_numero" maxlength="11" tabindex="15" value="<?=$paciente_numero?>">
        </div>

        <label class="col-sm-1 control-label" for="paciente_bairro">Bairro</label>
        <div class="col-sm-3 controls">
          <input type="text" class="form-control input-sm" placeholder="Informe o bairro" id="paciente_bairro" name="paciente_bairro" tabindex="16" value="<?=$paciente_bairro?>">
        </div>
      </div><!-- END INPUT -->

      <div class="form-group">
        <label class="col-sm-1 control-label" for="paciente_cidade">Cidade</label>
        <div class="col-sm-3 controls">
          <input type="text" class="form-control input-sm" placeholder="Informe a cidade" id="paciente_cidade" name="paciente_cidade" maxlength="60" tabindex="17" value="<?=$paciente_cidade?>">
        </div>

        <label class="col-sm-1 control-label" for="paciente_estado">Estado</label>
        <div class="col-sm-3 controls">
          <select class="form-control input-sm selectpicker" id="paciente_estado" name="paciente_estado" tabindex="18">
            <option value=""></option>
            <?php 
            $result = $oConexao->prepare("SELECT id, nome, uf FROM estado"); $result->execute();
            while( $dados = $result->fetch(PDO::FETCH_ASSOC) ){
            ?>
            <option value="<?=$dados['id']?>" rel="<?=$dados['uf']?>" <?=$paciente_estado == $dados['id'] ? 'selected="true"' : '' ?> ><?=utf8_encode($dados['nome'])?></option>
            <?php 
            }
            ?>
          </select>
        </div>
      </div><!-- END INPUT -->

      <div class="form-group">
        <label class="col-sm-1 control-label" for="paciente_pais">País</label>
        <div class="col-sm-3 controls">
          <select class="form-control input-sm selectpicker" id="paciente_pais" name="paciente_pais" tabindex="19">
            <option value=""></option>
            <?php 
            $result = $oConexao->prepare("SELECT idpais, nome FROM pais"); $result->execute();
            while( $dados = $result->fetch(PDO::FETCH_ASSOC) ){
            ?>
            <option value="<?=$dados['idpais']?>" <?=$paciente_pais == $dados['idpais'] ? 'selected="true"' : '' ?> ><?=utf8_encode($dados['nome'])?></option>
            <?php 
            }
            ?>
          </select>
        </div>
      </div><!-- END INPUT -->

    </fieldset><!-- END FIELDSET -->

    <fieldset class="clear form-actions margin-bottom-0">
        <button id="enviarformulario" type="submit" class="btn btn-primary" value="_save">Salvar</button>
        <button id="enviareditar" type="submit" class="btn btn-default" value="_continue">Salvar e continuar editando</button> 
        <button id="enviaradicionar" type="submit" class="btn btn-default" value="_addanother">Salvar e adicionar outro</button>
        <?php if( $idpaciente != '' || $idpaciente != NULL ){ ?>
        <button id="deletaritem" type="button" class="btn btn-danger pull-right" value="_delete"><i class="glyphicon glyphicon-trash"></i> Excluir</button>
        <?php } ?>
        <img class="preload-submit display-n" src="<?=PORTAL_URL?>imagens/load.gif"> 
    </fieldset><!-- END FIELDSET -->

  </form>

</div>

<!-- INCLUDE JAVASCRIPT -->
<script src="<?=PORTAL_URL?>ajax/paciente/formulario.js"></script>
<?php include('template/footer.php'); ?>
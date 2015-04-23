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
  $result = $oConexao->prepare("SELECT id, nome, datanascimento, rg, cpf, sexo, observacao, email, telefone, celular, sms, status, datacadastro, cep, logradouro, numero, complemento, bairro, cidade, idestado, idpais
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

  $result = $oConexao->prepare("SELECT id, nome, uf 
                                FROM estado 
                                WHERE id = ?");
  $result->bindValue(1, $paciente_estado);
  $result->execute();
  $row = $result->fetch(PDO::FETCH_ASSOC);
  $paciente_estado = $row['nome'].'/'.$row['uf'];

  $result = $oConexao->prepare("SELECT idpais, nome 
                                FROM pais 
                                WHERE idpais = ?");
  $result->bindValue(1, $paciente_pais);
  $result->execute();
  $row = $result->fetch(PDO::FETCH_ASSOC);
  $paciente_pais = $row['nome'];

}

?>

<div class="modal-content">
  <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
      <h3 class="modal-title">Paciente</h3>
  </div>
  <div class="modal-body">
      
      <fieldset class="clear">
      <div class="section">Informações básicas</div>
          <div class="row">
            <div class="col-sm-6 controls">
              Nome: <span class="help-text"><?=$paciente_nome != '' ? $paciente_nome : '-'?></span>
            </div>
            <div class="col-sm-6 controls">
              Data de nascimento: <span class="help-text"><?=$paciente_datanascimento != '' ? $paciente_datanascimento : '-'?></span>
            </div>
          </div><!-- END -->

          <div class="row">
            <div class="col-sm-6 controls">
              RG: <span class="help-text"><?=$paciente_rg != '' ? $paciente_rg : '-'?></span>
            </div>

            <div class="col-sm-6 controls">
              CPF: <span class="help-text"><?=$paciente_cpf != '' ? $paciente_cpf : '-'?></span>
            </div>

            <div class="col-sm-12 controls">
              Email: <span class="help-text"><?=$paciente_email != '' ? $paciente_email : '-'?></span>
            </div>
          </div><!-- END-->

          <div class="row">
            <div class="col-sm-12 controls">
              Sexo: <span class="help-text"><?=$paciente_sexo == 'M' ? 'Masculino' : 'Feminino'?></span>
            </div>
          </div><!-- END -->

          <div class="row">
            <div class="col-sm-12 controls">
              Observação: <span class="help-text"><?=$paciente_observacao != '' ? $paciente_observacao : '-'?></span>
            </div>
          </div><!-- END -->

          <div class="row">
            <div class="col-sm-12 controls">
              Status: <span class="help-text"><?=$paciente_status == '1' ? 'Ativo' : 'Inativo'?></span>
            </div>
          </div><!-- END -->
      </fieldset><!-- END FIELDSET -->

      <fieldset class="clear">
      <div class="section">Contato</div>
          <div class="row">
            <div class="col-sm-6 controls">
              Celular: <span class="help-text"><?=$paciente_celular?></span>
            </div>
            <div class="col-sm-6 controls">
              Gostaria de receber SMS? <span class="help-text"><?=$paciente_sms == '1' ? 'Sim' : 'Não'?></span>
            </div>
          </div><!-- END -->

          <div class="row">
            <div class="col-sm-12 controls">
              Telefone: <span class="help-text"><?=$paciente_telefone != '' ? $paciente_telefone : '-'?></span>
            </div>
          </div><!-- END-->
      </fieldset><!-- END FIELDSET -->

      <?php if( $paciente_cep != '' || $paciente_logradouro != '' ){ ?>
      <fieldset class="clear">
      <div class="section">Endereço</div>
          <div class="row">
            <div class="col-sm-12 controls">
              CEP: <span class="help-text"><?=$paciente_cep != '' ? $paciente_cep : '-'?></span>
            </div>
          </div><!-- END -->

          <div class="row">
            <div class="col-sm-6 controls">
              Logradouro: <span class="help-text"><?=$paciente_logradouro != '' ? $paciente_logradouro : '-'?></span>
            </div>
            <div class="col-sm-6 controls">
              Complemento: <span class="help-text"><?=$paciente_complemento != '' ? $paciente_complemento : '-'?></span>
            </div>
          </div><!-- END-->

          <div class="row">
            <div class="col-sm-6 controls">
              Número: <span class="help-text"><?=$paciente_numero != '' ? $paciente_numero : '-'?></span>
            </div>
            <div class="col-sm-6 controls">
              Bairro: <span class="help-text"><?=$paciente_bairro != '' ? $paciente_bairro : '-'?></span>
            </div>
          </div><!-- END-->

          <div class="row">
            <div class="col-sm-6 controls">
              Cidade: <span class="help-text"><?=$paciente_cidade != '' ? $paciente_cidade : '-'?></span>
            </div>
            <div class="col-sm-6 controls">
              Estado: <span class="help-text"><?=$paciente_estado != '' ? $paciente_estado : '-'?></span>
            </div>
          </div><!-- END-->

          <div class="row">
            <div class="col-sm-12 controls">
              Pais: <span class="help-text"><?=$paciente_pais != '' ? $paciente_pais : '-'?></span>
            </div>
          </div><!-- END -->
      </fieldset><!-- END FIELDSET -->
      <?php } ?>

  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
  </div>
</div>
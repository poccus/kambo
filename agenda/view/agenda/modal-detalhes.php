<?php
$oConexao = Conexao::getInstance();
?>
<?php
$id = $_POST['id'];
$rs = $oConexao->query("SELECT p.*, c.start, c.end, c.id as idconsulta, c.situacao, c.datacadastro as dataagendamento, c.idusuario as usuario FROM consulta c, paciente p WHERE c.idpaciente = p.id AND c.id = ".$id);
while($row = $rs->fetch(PDO::FETCH_OBJ)){
  $rsU = $oConexao->query("SELECT nome FROM usuario WHERE idusuario = ".$row->usuario);
  $rowU = $rsU->fetch(PDO::FETCH_OBJ);
  $usuario = $rowU->nome;
?>
<div class="modal-content">
  <form id="formdetalhes" name="formdetalhes" class="form-horizontal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h3 class="modal-title" id="myModalLabel">Detalhes do Agendamento</h3>
  </div>
  <div class="modal-body">

    <input type="hidden" id="idconsulta" name="idconsulta" value="<?=$id?>">
    
    <fieldset class="clear">
      <div class="section">Dados do Paciente</div>

      <div class="row">
        <div class="col-sm-6 controls">
          <span class="help-text">Nome: <?=$row->nome?></span>
        </div>

        <div class="col-sm-6 controls">
          <span class="help-text">Telefone: <?=$row->telefone?></span>
        </div>
      </div><!-- END INPUT -->

      <div class="row">
        <div class="col-sm-6 controls">
          <span class="help-text">E-mail: <?=$row->email?></span>
        </div>

        <div class="col-sm-6 controls">
          <span class="help-text">Data de nascimento: <?=$row->datanascimento == '' || $row->datanascimento == 'NULL' ? '-' : data_volta($row->datanascimento) ?></span>
        </div>
      </div><!-- END INPUT -->

      <div class="row">
        <div class="col-sm-12 controls">
          <span class="help-text">Paciente desde: <?=date('d/m/Y H:i', strtotime($row->datacadastro));?></span>
        </div>
      </div><!-- END INPUT -->
    </fieldset><!-- END FIELDSET -->


    <?php $tmp = explode(" ", $row->start); ?>
    <fieldset class="clear">
      <div class="section">Consulta</div>
      
      <div class="row">
        <div class="col-sm-12 controls">
          <span class="help-text">Agendado para: <?=dataExtensoTimeline(data_volta($tmp[0]),1)?>, às <?=$tmp[1]?></span>
        </div>
      </div><!-- END INPUT -->

      <div class="row">
        <div class="col-sm-12 controls">
          <span class="help-text">Agendado por: <?=$usuario?>, em: <?=data_volta($row->dataagendamento)?></span>
        </div>
      </div><!-- END INPUT -->
    </fieldset><!-- END FIELDSET -->

  </div>
  <div class="modal-footer">
    <div class="actions">
      <a href='#' class='btn btn-info btn-sm pull-left' rel="<?=$row->idconsulta?>" data-rel="<?=$tmp[0]?>" title='Aguardando atendimento' id='chegou' ><i class='glyphicon glyphicon-user'></i> Aguardando atendimento</a> 
      <?php if( $row->situacao != 3 ){ ?>
      <a href='#' class='btn btn-success btn-sm pull-left' rel="<?=$row->idconsulta?>" data-rel="<?=$tmp[0]?>" title='Confirmar consulta' id='confirmaragendamento'><i class='glyphicon glyphicon-thumbs-up'></i> Confirmar presença</a>  
      <?php } ?>
      <a href='#' class='btn btn-warning btn-sm pull-left' rel="<?=$row->idconsulta?>" data-rel="<?=$tmp[0]?>" title='Remarcar consulta' id='remarcar' ><i class='glyphicon glyphicon-retweet'></i></a>  
      <a href='#' class='btn btn-info btn-sm pull-left' rel="<?=$row->idconsulta?>" data-rel="<?=$tmp[0]?>" title='Enviar e-mail' id='enviaremail' ><i class='glyphicon glyphicon-envelope'></i></a>
      <a href='#' class='btn btn-danger btn-sm' rel="<?=$row->idconsulta?>" data-rel="<?=$tmp[0]?>" title='Cancelar consulta' id='cancelar' ><i class='glyphicon glyphicon-remove'></i> Cancelar</a>
      <a href="#" data-dismiss="modal" class="btn btn-default btn-sm">Fechar</a>
    </div>
  </div>
  </form>
</div>
<?php } ?>
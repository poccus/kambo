<?php
$oConexao = Conexao::getInstance();
?>
<!--<div class="modal-dialog modal-lg">-->
<div class="modal-content">
  <form id="formdetalhes" name="formdetalhes" class="form-horizontal">
  <div class="modal-header">
    <h3 class="modal-title" id="myModalLabel">Agendamentos</h3>
  </div>
  <div class="modal-body">
    <?php
    $consulta = $_POST['nome'];
    $rs = $oConexao->query("SELECT p.*, c.start, c.end, c.id as idconsulta, c.situacao, c.datacadastro as dataagendamento, c.idusuario as usuario, c.idprofissional FROM consulta c, paciente p WHERE c.idpaciente = p.id AND p.nome LIKE '%".$consulta."%'");
    $count = $rs->rowCount();
    if($count > 0){
    ?>
    <div class="table-responsive margin-top-0-5em">          
      <table id="tabela-lista-dados" class="table x-small">
        <thead>
          <tr>
            <th width="1%" scope="col">#</th>
            <th width="25%" scope="col">Profissional</th>
            <th width="19%" scope="col">Paciente</th>
            <th width="15%" scope="col">Telefone</th>
            <th width="15%" scope="col">Horário</th>
            <th width="15%" scope="col">Status</th>
            <th width="10%" scope="col"></th>
          </tr>
        </thead>
        <tbody>
        <?php
        $i = 1;
        while($row = $rs->fetch(PDO::FETCH_OBJ)){
          $rsU = $oConexao->query("SELECT * FROM profissional WHERE idprofissional = ".$row->idprofissional);
          $rowU = $rsU->fetch(PDO::FETCH_OBJ);
          $tmp = explode(" ", $row->start);
        ?>
        <tr>
          <td><?=$i?></td>
          <td><?=$rowU->nome?></td>
          <td><?=$row->nome?></td>
          <td><?=$row->telefone?></td>
          <td><?=data_volta($tmp[0])." às ".$tmp[1]?></td>
          <td>
            <?php
            if($row->situacao == "1" || $row->situacao == "2")
              echo "Não Confirmado";
            if($row->situacao == "3")
              echo "Confirmado";
            if($row->situacao == "4")
              echo "Aguardando Atendimento";
            if($row->situacao == "5")
              echo "Atendido";
            if($row->situacao == "6")
              echo "Cancelado";
            ?>
          </td>
          <td>
            <a href='#' class='btn btn-warning btn-xs' rel="<?=$row->idconsulta?>" data-rel="<?=$tmp[0]?>" title='Remarcar' id='remarcarFiltro' >&nbsp;<i class='glyphicon glyphicon-share-alt'></i>&nbsp;</a>  
            <a href='#' class='btn btn-danger btn-xs' rel="<?=$row->idconsulta?>" data-rel="<?=$tmp[0]?>" title='Cancelar Agendamento' id='cancelar' >&nbsp;<i class='glyphicon glyphicon-remove'></i>&nbsp;</a>
          </td>
        </tr>
        <?php $i++; } ?>
        </table>
        <?php }else{ echo "Nenhuma consulta foi encontrada.";} ?>
      </div>
  </div>
  <div class="modal-footer">
    <!--<a href="#" class="btn btn-primary" id="salvarRemarcacao">Salvar</a>-->
    <a href="#" data-dismiss="modal" class="btn btn-default">Fechar</a>
  </div>
  </form>
</div>
<!--</div>-->
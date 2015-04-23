<?php
$oConexao = Conexao::getInstance();

function m2h($mins) {
        // Se os minutos estiverem negativos
        if ($mins < 0)
            $min = abs($mins);
        else
            $min = $mins;
 
        // Arredonda a hora
        $h = floor($min / 60);
        $m = ($min - ($h * 60)) / 100;
        $horas = $h + $m;
 
        // Matemática da quinta série
        // Detalhe: Aqui também pode se usar o abs()
        if ($mins < 0)
            $horas *= -1;
 
        // Separa a hora dos minutos
        $sep = explode('.', $horas);
        $h = $sep[0];
        if (empty($sep[1]))
            $sep[1] = 00;
 
        $m = $sep[1];
 
        // Aqui um pequeno artifício pra colocar um zero no final
        if (strlen($m) < 2)
            $m = $m . 0;
 
        return sprintf('%02d:%02d', $h, $m);
    }

    function h2m($hora) {
      $tmp = explode(":", $hora);
      return ($tmp[1]+($tmp[0]*60));
    }

?>
<div class="modal-content">
  <form id="formdetalhes" name="formdetalhes" class="form-horizontal">
  <div class="modal-header">
    <h3 class="modal-title" id="myModalLabel">Remarcar Agendamento</h3>
  </div>
  <div class="modal-body">
    <form id="createform" name="createform" class="form-horizontal" method="post" action="" enctype="multipart/form-data">

    <?php
    $consulta = $_POST['consulta'];
    $rs = $oConexao->query("SELECT p.*, c.start, c.end, c.id as idconsulta, c.situacao, c.datacadastro as dataagendamento, c.idusuario as usuario, c.idprofissional FROM consulta c, paciente p WHERE c.idpaciente = p.id AND c.id = ".$consulta);
    while($row = $rs->fetch(PDO::FETCH_OBJ)){
      $rsU = $oConexao->query("SELECT * FROM profissional WHERE idprofissional = ".$row->idprofissional);
      $rowU = $rsU->fetch(PDO::FETCH_OBJ);
      $horainicio = h2m($rowU->horainicialatendimento);
      $horafinal = h2m($rowU->horafinalatendimento);
      $tempo = h2m($rowU->tempoconsulta);
      $contador = $horainicio;
      $idprofissionalr = $row->idprofissional;
    ?>
    <input type="hidden" id="idconsulta" name="idconsulta" value="<?=$consulta?>">
    <input type="hidden" id="idprofissionalr" name="idprofissionalr" value="<?=$idprofissionalr?>">
    <?php $tmp = explode(" ", $row->start); ?>
    <fieldset class="clear">
      <div class="section">Data e Hora Atual</div>
      <div class="form-group">
        <div class="col-sm-12 controls">
          <?=dataExtensoTimeline(data_volta($tmp[0]),1)?>, às <?=$tmp[1]?>
        </div>
      </div><!-- END INPUT -->
    </fieldset>
    <fieldset class="clear">
      <div class="section">Nova Data da Consulta</div>
      <div class="form-group">
        <div class="col-sm-12 controls">
          <div class="col-sm-4 controls">
          <label class="control-label" for="profissional_nome">Data *</label><br>
          <input type="text" class="form-control input-sm clsDatePicker" placeholder="Informe a data" id="dataRemarcacao" name="dataRemarcacao" value="">
        </div>
        <div class="col-sm-4 controls">
          <label class="control-label" for="profissional_nome">Horário *</label><br>
          <select id="horarioRemarcacao" name="horarioRemarcacao" class="form-control input-sm">
            <option value="">Escolha</option>
          </select>
        </div>
        <div class="col-sm-4 controls">
          <label class="control-label" for="profissional_nome">Duração *</label><br>
          <select id="duracaoRemarcacao" name="duracaoRemarcacao" class="form-control input-sm">
            <option value="15" <?php if($tempo == 15) echo "selected"; ?>>00:15</option>
            <option value="30" <?php if($tempo == 30) echo "selected"; ?>>00:30</option>
            <option value="45" <?php if($tempo == 45) echo "selected"; ?>>00:45</option>
            <option value="60" <?php if($tempo == 60) echo "selected"; ?>>01:00</option>
            <option value="75" <?php if($tempo == 75) echo "selected"; ?>>01:15</option>
            <option value="90" <?php if($tempo == 90) echo "selected"; ?>>01:30</option>
            <option value="105" <?php if($tempo == 105) echo "selected"; ?>>01:45</option>
            <option value="120" <?php if($tempo == 120) echo "selected"; ?>>02:00</option>
            <option value="135" <?php if($tempo == 135) echo "selected"; ?>>02:15</option>
            <option value="150" <?php if($tempo == 150) echo "selected"; ?>>02:30</option>
            <option value="165" <?php if($tempo == 165) echo "selected"; ?>>02:45</option>
            <option value="180" <?php if($tempo == 180) echo "selected"; ?>>03:00</option>
            <option value="195" <?php if($tempo == 195) echo "selected"; ?>>03:15</option>
            <option value="210" <?php if($tempo == 210) echo "selected"; ?>>03:30</option>
            <option value="225" <?php if($tempo == 225) echo "selected"; ?>>03:45</option>
            <option value="240" <?php if($tempo == 240) echo "selected"; ?>>04:00</option>
            <option value="255" <?php if($tempo == 255) echo "selected"; ?>>04:15</option>
            <option value="270" <?php if($tempo == 270) echo "selected"; ?>>04:30</option>
            <option value="285" <?php if($tempo == 285) echo "selected"; ?>>04:45</option>
            <option value="300" <?php if($tempo == 300) echo "selected"; ?>>05:00</option>
          </select>
        </div>
        </div>
      </div><!-- END INPUT -->
    </fieldset>
    <?php
    }
    ?>
  </form>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn btn-primary" id="salvarRemarcacao">Salvar</a>
    <a href="#" data-dismiss="modal" class="btn btn-default">Fechar</a>
  </div>
  </form>
</div>
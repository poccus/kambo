<?php
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

$oConexao = Conexao::getInstance();
$data_consulta = $_POST["data_consulta"];
$profissional = $_POST["profissional"];
$horario = $_POST["horario"];

$slct = $oConexao->query('SELECT * FROM profissional WHERE idprofissional = '.$profissional);
$rowSlct  = $slct->fetch(PDO::FETCH_OBJ);
$nomeprofissional = $rowSlct->nome;
$idprofissional = $rowSlct->idprofissional;
$tempo = $rowSlct->tempoconsulta;
?>
<div class="modal-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title">Novo Agendamento</h4>
  </div>
  <div class="modal-body">
    <form id="formnovo" name="formnovo" class="form-horizontal">
    
    <div class="row">
      <h4 class="col-sm-3">Profissional: </h4><h4 class="col-sm-9"><?=$nomeprofissional?></h4>
      <input type="hidden" id="profissional" name="profissional" value="<?=$idprofissional?>">
    </div>


    <fieldset class="clear">
      <div class="section">Informações do Paciente</div>
      <div class="row">
        <div class="col-sm-12 controls">
          <label class="control-label" for="nome">Nome *</label>
          <input type="text" class="form-control typeahead input-sm" size="100" data-provide="typeahead" autocomplete="off" placeholder="Informe o nome do paciente" id="nome" name="nome">
          <input type="hidden" id="paciente" name="paciente" value="0">
          <img id="preload-nome" class="display-n position-absolute position-preload-right-input" src="<?=PORTAL_URL?>imagens/ajax.gif">
        </div>
      </div><!-- END-->
      
      <div class="row">
        <div class="col-sm-5 controls">
          <label class="control-label" for="telefone">Telefone*</label><br>
          <input type="text" class="form-control input-sm" size="100" placeholder="Informe o telefone" id="telefone" name="telefone">
        </div>

        <div class="col-sm-7 controls">
          <label class="control-label" for="email">Email*</label><br>
          <input type="text" class="form-control input-sm" size="100" placeholder="Informe o email" id="email" name="email">
        </div>
      </div><!-- END-->
    </fieldset><!-- END FIELDSET -->

    <fieldset class="clear">
      <div class="section">Informações da Consulta</div>

      <div class="row">
        <div class="col-sm-4 controls">
          <label class="control-label" for="profissional_nome">Data *</label><br>
          <input type="text" class="form-control input-sm" placeholder="Informe a data" id="data_consulta" name="data_consulta" value="<?=$data_consulta?>">
        </div>
        <div class="col-sm-4 controls">
          <label class="control-label" for="profissional_nome">Horário *</label><br>
          <input type="text" class="form-control input-sm" placeholder="Informe o horário" id="horario" name="horario" value="<?=$horario?>">
        </div>
        <div class="col-sm-4 controls">
          <label class="control-label" for="profissional_nome">Duração *</label><br>
          <select id="duracao" name="duracao" class="form-control input-sm">
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
      </div><!-- END-->  

      <div class="row">
        <div class="col-sm-12 controls">
          <label class="checkbox-inline" for="excecao"><input type="checkbox" id="excecao" name="excecao"> Consulta de exceção</label>
        </div>
      </div><!-- END-->          
    </fieldset><!-- END FIELDSET -->

    </form>
  </div>
  <div class="modal-footer">
    <img class="preload-submit display-n" src="<?=PORTAL_URL?>imagens/load.gif">
    <a id="salvarAgendamento" class="btn btn-primary">Salvar</a>
    <a href="#" data-dismiss="modal" class="btn btn-default">Fechar</a>
  </div>
</div>
<?php
session_start();

include_once "../../conf/config.php";
$oConexao = Conexao::getInstance();

header('Content-type: application/json');
date_default_timezone_set("Brazil/East"); 

$hoje = $_GET['data'];
$id = $_GET['id'];

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

//pega as informacoes para montar a lista de horarios do medico
$rs = $oConexao->query("SELECT * FROM profissional WHERE idprofissional = ".$id);
$rw = $rs->fetch(PDO::FETCH_OBJ);
$horainicio = h2m($rw->horainicialatendimento);
$horafinal = h2m($rw->horafinalatendimento);
$tempo = h2m($rw->tempoconsulta);
$contador = $horainicio;

$horarios = "";
$i = 0;
while($contador <= $horafinal){
  $horarios[$i] = m2h($contador);
  $contador += $tempo;
  $i++;
}

//retorna horarios utilizados no dia especifico
$rs = $oConexao->query("SELECT TIME_FORMAT(c.start, '%H:%i') as horario FROM consulta c WHERE  DATE(c.start) = '".$hoje."' AND c.situacao != 6 AND c.idprofissional = ".$id." AND TIME_FORMAT(c.start, '%H:%i') BETWEEN '".$rw->horainicialatendimento."' AND '".$rw->horafinalatendimento."'");
//retorna pesquisa de um horario especifico, se tem uma consulta marcada no intervalo de um determinado horario
//SELECT TIME_FORMAT(c.start, '%H:%i') as horario FROM consulta c WHERE  DATE(c.start) = '2015-03-18' AND c.situacao != 6 AND c.idmedico = 1 
//AND '09:45' BETWEEN TIME_FORMAT(c.start, '%H:%i') AND TIME_FORMAT(c.end, '%H:%i')
while($dados = $rs->fetch(PDO::FETCH_OBJ)){
  if (($key = array_search($dados->horario, $horarios)) !== false) {
      unset($horarios[$key]);
  }
}
print_r($horarios);
?>
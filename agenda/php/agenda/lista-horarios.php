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

$medico = $_GET["idmedico"];
$oConexao    = new PDO("mysql:host=localhost;dbname=agendar;charset=utf8" , "root", "");
$slct = $oConexao->query('SELECT * FROM profissional WHERE idprofissional = '.$medico);
$row  = $slct->fetch(PDO::FETCH_OBJ);
$nomeMedico = $row->nome;
$inicio = h2m($row->horainicio);
$fim = h2m($row->horafim);
$tempo = $row->tempoconsulta;
$contador = $inicio;
$out = array();
while($contador <= $fim){
	$out[] = array(
        'tempo' => $tempo,
        'horario' => m2h($contador)
    );
    //$retorno .= '<option value="'.m2h($contador).'" '.$selected.'>'.m2h($contador).'</option>';
    $contador += $tempo;
}
echo json_encode($out);
exit;
?>
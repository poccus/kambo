<?php 
session_start();

include_once "../../conf/config.php";
include_once "../../utils/funcoes.php";
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


$id						    = $_GET['idconsulta'];
$duracao                    = $_GET['duracaoRemarcacao'];
$horario                    = $_GET['horarioRemarcacao'];
$data_consulta              = formata_data($_GET['dataRemarcacao']);
$datacadastro				= date('Y-m-d h:i:s');
$idusuario					= 1;//$_SESSION['usuario'];


$datainicio = $data_consulta." ".$horario;
$tmp = h2m($horario)+$duracao;
$tmphora = m2h($tmp);
$datafim = $data_consulta." ".$tmphora;
/*$horas = explode("-", $horario);
$horario = $horas[0];
$datainicio = $data_consulta." ".$horas[1].":00";
$datafim = $data_consulta." ".$horas[1].":00";*/

$msg = array();
header('Content-type: application/json');

try{

    //executa a instrução de consulta 
    $oConexao->beginTransaction();

        $stmt = $oConexao->prepare("UPDATE consulta SET start = ?, end = ?, situacao = 1 WHERE id = ?");
        $stmt->bindValue(1, $datainicio);
        $stmt->bindValue(2, $datafim);
        $stmt->bindValue(3, $id);
        $stmt->execute();
        
        $oConexao->commit();

        // echo "As informações foram atualizadas e salvas.&nbsp; <a href='?pg=modulo/galeria/index'>voltar para lista</a>";
        //MENSAGEM DE SUCESSO
        $msg['msg'] = 'success';
        echo json_encode($msg);
        die();

}catch (PDOException $e){
    $oConexao->rollBack();
    // echo $e->getMessage();
    // echo "Erro grave ao salvar os dados da galeria, consulte o administrador.&nbsp; <a href='?pg=modulo/galeria/index'>voltar para lista</a>";
    //MENSAGEM DE SUCESSO
    $msg['msg'] = 'error';
    $msg['exception'] = $e->getMessage();
    echo json_encode($msg);
	die();
}

?>
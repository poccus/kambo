<?php 
session_start();
require_once('../../conf/phpmailer/class.phpmailer.php');
include_once "../../conf/config.php";
$oConexao = Conexao::getInstance();

//include_once "../../conf/conexao.php";
//include_once "../../conf/funcoes.php";
function formata_data( $data ) {
    if( $data == '' ) return ''; 
  $d = explode('/', $data);
  return $d[2] . '-' .$d[1] . '-' . $d[0];
}

function h2m($hora) {
      $tmp = explode(":", $hora);
      return ($tmp[1]+($tmp[0]*60));
    }
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
//$oConexao    = new PDO("mysql:host=localhost;dbname=agendar;charset=utf8" , "root", "");

$paciente                       = $_GET['paciente'];
$nome						    = $_GET['nome'];
$telefone                       = $_GET['telefone'];
$email                          = $_GET['email'];
$medico					        = $_GET['profissional'];
$horario                        = $_GET['horario'];
$data_consulta                  = formata_data($_GET['data_consulta']);
//$obs                          = $_GET['obs'];
$duracao                        = $_GET['duracao'];
$datacadastro				    = date('Y-m-d h:i:s');
$idusuario					    = 1;//$_SESSION['usuario'];

$datainicio = $data_consulta." ".$horario;
$horafinal = m2h(h2m($horario)+$duracao);
$datafim = $data_consulta." ".$horafinal;
/*$horas = explode("-", $horario);
$horario = $horas[0];
$datainicio = $data_consulta." ".$horas[1].":00";
$datafim = $data_consulta." ".$horas[1].":00";
*/

/*$tmp = explode("-", $nome);
$paciente = trim($tmp[0]);
*/

$msg = array();
header('Content-type: application/json');

try{

    //executa a instrução de consulta 
    $oConexao->beginTransaction();

    //se for paciente novo
    if($paciente == 0){
        //insere o paciente
        $stmt = $oConexao->prepare("INSERT INTO paciente (nome, telefone, email, datacadastro) VALUES (?, ?, ?, ?)");
        $stmt->bindValue(1, $nome);
        $stmt->bindValue(2, $telefone);
        $stmt->bindValue(3, $email);
        $stmt->bindValue(4, $datacadastro);
        $stmt->execute();

        $paciente = $oConexao->lastInsertId();
        //insere a consulta
        $stmt = $oConexao->prepare("INSERT INTO consulta (idpaciente, idprofissional, start, end, situacao, datacadastro, idusuario) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindValue(1, $paciente);
        $stmt->bindValue(2, $medico);
        $stmt->bindValue(3, $datainicio);
        $stmt->bindValue(4, $datafim);
        $stmt->bindValue(5, "1");
        $stmt->bindValue(6, $datacadastro);
        $stmt->bindValue(7, $idusuario);
        $stmt->execute();
        
        $oConexao->commit();

        //envia o email para o paciente, avisando da marcação da consulta
        // $mail             = new PHPMailer();
        // $mail->SMTPDebug  = 2;
        // $mail->SMTPAuth   = true;                  // enable SMTP authentication
        // $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
        // $mail->Host       = "177.92.248.75";      // sets GMAIL as the SMTP server
        // $mail->Port       = 587;                   // set the SMTP port for the GMAIL server
        // $mail->Username   = "contato@kambotecnologia.com.br";  // GMAIL username
        // $mail->Password   = "email@123";
        // $mail->SetFrom('contato@kambotecnologia.com.br', 'SPECIALITES');
        // $mail->Subject    = "Confirmação Agendamento de Consulta";
        // $body    = "Sua consulta está agendada para o dia ".$datainicio;
        // $mail->MsgHTML($body);
        
        // $stmt = $oConexao->query("SELECT * FROM paciente WHERE id = ".$paciente);
        // $row = $stmt->fetch(PDO::FETCH_OBJ);
        
        // $mail->AddAddress($row->email);
        // if(!$mail->Send()) {
        //     //echo "Mailer Error: " . $mail->ErrorInfo;
        // } else {
        //     //echo "Message sent!";
        // }


        // echo "Cadastro salvo com sucesso.&nbsp; <a href='?pg=modulo/galeria-formulario'>voltar para galeria</a>";
        //MENSAGEM DE SUCESSO
        $msg['msg'] = 'success';
        echo json_encode($msg);
        die();

    //se o paciente ja existe
    }else{

        $stmt = $oConexao->prepare("INSERT INTO consulta (idpaciente, idprofissional, start, end, situacao, datacadastro, idusuario) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindValue(1, $paciente);
        $stmt->bindValue(2, $medico);
        $stmt->bindValue(3, $datainicio);
        $stmt->bindValue(4, $datafim);
        $stmt->bindValue(5, "1");
        $stmt->bindValue(6, $datacadastro);
        $stmt->bindValue(7, $idusuario);
        $stmt->execute();
        
        $oConexao->commit();

        //envia o email para o paciente, avisando da marcação da consulta
        // $mail             = new PHPMailer();
        // $mail->SMTPDebug  = 2;
        // $mail->SMTPAuth   = true;                  // enable SMTP authentication
        // $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
        // $mail->Host       = "177.92.248.75";      // sets GMAIL as the SMTP server
        // $mail->Port       = 587;                   // set the SMTP port for the GMAIL server
        // $mail->Username   = "contato@kambotecnologia.com.br";  // GMAIL username
        // $mail->Password   = "email@123";
        // $mail->SetFrom('contato@kambotecnologia.com.br', 'SPECIALITES');
        // $mail->Subject    = "Confirmação Agendamento de Consulta";
        // $body    = "Sua consulta está agendada para o dia ".$datainicio;
        // $mail->MsgHTML($body);

        // $stmt = $oConexao->query("SELECT * FROM paciente WHERE id = ".$paciente);
        // $row = $stmt->fetch(PDO::FETCH_OBJ);

        // $mail->AddAddress($row->email);
        // if(!$mail->Send()) {
        //     echo "Mailer Error: " . $mail->ErrorInfo;
        // } else {
        //     //echo "Message sent!";
        // }


        // echo "Cadastro salvo com sucesso.&nbsp; <a href='?pg=modulo/galeria-formulario'>voltar para galeria</a>";
        //MENSAGEM DE SUCESSO
        $msg['msg'] = 'success';
        echo json_encode($msg);
        die();
    }

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
<?php
session_start();

require_once('../../conf/phpmailer/class.phpmailer.php');
include_once "../../conf/config.php";
$oConexao = Conexao::getInstance();
$msg = array();
header('Content-type: application/json');

$id                     = $_GET['id'];
//$data                   = $_GET['data'];

try{
	$stmt = $oConexao->query("SELECT p.nome as nome, p.email as email, c.start FROM consulta c, paciente p WHERE c.idpaciente = p.id AND c.id = ".$id);
	if($stmt->rowCount()>0){
		//$tabela = "";
    	//$tabela .= '<table width="100%">';
    	//$tabela .= '<tr><td>Paciente</td><td>Email</td></tr>';

	 	while($row = $stmt->fetch(PDO::FETCH_OBJ)){
			$mail             = new PHPMailer();
        	$mail->SMTPDebug  = 2;
        	$mail->SMTPAuth   = true;                  // enable SMTP authentication
        	$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
        	$mail->Host       = "177.92.248.75";      // sets GMAIL as the SMTP server
        	$mail->Port       = 587;                   // set the SMTP port for the GMAIL server
        	$mail->Username   = "contato@kambotecnologia.com.br";  // GMAIL username
        	$mail->Password   = "email@123";
        	$mail->SetFrom('contato@kambotecnologia.com.br', 'SPECIALITES');
        	$mail->Subject    = "Confirmação Agendamento de Consulta";
        	$body    = "Sua consulta está agendada para o dia ".$row->start;
        	$mail->MsgHTML($body);
        	$mail->AddAddress($row->email);
        	if(!$mail->Send()) {
            	$msg['msg'] = 'error';
				$msg['tipoerro'] = $mail->ErrorInfo;
				echo json_encode($msg);
				die();
        	}
		}
		

		$msg['msg'] = 'success';
		$msg['resultado'] = "Email de lembrete enviado com sucesso!";
		echo json_encode($msg);
		die();
	}else{
		$msg['msg'] = 'nenhum';
		//$msg['resultado'] = $tabela;
		echo json_encode($msg);
		die();
	}
}catch (PDOException $e){

    //MENSAGEM DE SUCESSO
    $msg['msg'] = 'error';
    $msg['exception'] = $e->getMessage();
    echo json_encode($msg);
	die();
}
?>
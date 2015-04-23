<?php 
session_start();

include_once "../../conf/conexao.php";
include_once "../../conf/funcoes.php";


$nome				= $_GET['nome'];
$telefone           = $_GET['telefone'];
$nascimento         = formata_data($_GET['nascimento']);
$email              = $_GET['email'];
$datacadastro		= date('Y-m-d h:i:s');
$idusuario			= 1;//$_SESSION['usuario'];

$msg = array();
header('Content-type: application/json');

try{

    //executa a instrução de consulta 
    $oConexao->beginTransaction();

        $stmt = $oConexao->prepare("INSERT paciente (nome, telefone, datanascimento, email) VALUES (?, ?, ?, ?)");
        $stmt->bindValue(1, $nome);
        $stmt->bindValue(2, $telefone);
        $stmt->bindValue(3, $nascimento);
        $stmt->bindValue(4, $email);
        $stmt->execute();
        $id = $oConexao->lastInsertId();
        $oConexao->commit();

         
        $msg['retorno'] = $id.'-'.$nome;
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
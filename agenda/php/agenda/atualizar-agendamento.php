<?php 
session_start();

include_once "../../conf/config.php";
$oConexao = Conexao::getInstance();


$id						    = $_GET['id'];
$tipo					    = $_GET['tipo'];
$datacadastro				= date('Y-m-d h:i:s');
$idusuario					= 1;//$_SESSION['usuario'];

$msg = array();
header('Content-type: application/json');

try{

    //executa a instrução de consulta 
    $oConexao->beginTransaction();

    //pesquisa o profissional para o retorno da lista de espera
    $stmt = $oConexao->query("SELECT idprofissional FROM consulta WHERE id = ".$id);
    $row = $stmt->fetch(PDO::FETCH_OBJ);
    $idprofissional = $row->idprofissional;
    
    if( $tipo == "chegou" ){

        $stmt = $oConexao->prepare("UPDATE consulta SET situacao = ? WHERE id = ?");
        $stmt->bindValue(1, 4);
        $stmt->bindValue(2, $id);
        $stmt->execute();
        
        $oConexao->commit();

        // echo "As informações foram atualizadas e salvas.&nbsp; <a href='?pg=modulo/galeria/index'>voltar para lista</a>";
        //MENSAGEM DE SUCESSO
        $msg['profissional'] = $idprofissional;
        $msg['msg'] = 'success';
        echo json_encode($msg);
        die();

    }
    if( $tipo == "confirmaragendamento" ){

        $stmt = $oConexao->prepare("UPDATE consulta SET situacao = ? WHERE id = ?");
        $stmt->bindValue(1, 3);
        $stmt->bindValue(2, $id);
        $stmt->execute();
        
        $oConexao->commit();

        // echo "As informações foram atualizadas e salvas.&nbsp; <a href='?pg=modulo/galeria/index'>voltar para lista</a>";
        //MENSAGEM DE SUCESSO
        $msg['profissional'] = $idprofissional;
        $msg['msg'] = 'success';
        echo json_encode($msg);
        die();

    }

    if( $tipo == "confirmaratendimento" ){

        $stmt = $oConexao->prepare("UPDATE consulta SET situacao = ? WHERE id = ?");
        $stmt->bindValue(1, 5);
        $stmt->bindValue(2, $id);
        $stmt->execute();
        
        $oConexao->commit();

        // echo "As informações foram atualizadas e salvas.&nbsp; <a href='?pg=modulo/galeria/index'>voltar para lista</a>";
        //MENSAGEM DE SUCESSO
        $msg['profissional'] = $idprofissional;
        $msg['msg'] = 'success';
        echo json_encode($msg);
        die();

    }
    
    if( $tipo == "cancelar" ){

        $stmt = $oConexao->prepare("UPDATE consulta SET situacao = ? WHERE id = ?");
        $stmt->bindValue(1, 6);
        $stmt->bindValue(2, $id);
        $stmt->execute();
        
        $oConexao->commit();

        // echo "As informações foram atualizadas e salvas.&nbsp; <a href='?pg=modulo/galeria/index'>voltar para lista</a>";
        //MENSAGEM DE SUCESSO
        $msg['profissional'] = $idprofissional;
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
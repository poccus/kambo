<?php 
session_start();

include_once "../../conf/conexao.php";
include_once "../../utils/funcoes.php";
$oConexao = Conexao::getInstance();

//sessao dados
$usuariologado                       = $_SESSION['usuario'];

$excecao_data                        = formata_data( $_POST['excecao_data'] );
$excecao_profissional                      = $_POST['excecao_profissional'];
$excecao_inicio                      = $_POST['excecao_inicio'];
$excecao_fim                         = $_POST['excecao_fim'];
$idexcecao                           = isset( $_POST['idexcecao'] ) ? ( $_POST['idexcecao'] != '' ? $_POST['idexcecao'] : 0 ) : 0;

$msg = array();
header('Content-type: application/json');

try{

    //inicia da transação 
    $oConexao->beginTransaction();

    if( $idexcecao != 0 ){

        $stmt = $oConexao->prepare("UPDATE excecao SET idprofissional = ?, data = ?, horainicialatendimento = ?, horafinalatendimento = ? WHERE id = ?");
        $stmt->bindValue(1, $excecao_profissional);
        $stmt->bindValue(2, $excecao_data);
        $stmt->bindValue(3, $excecao_inicio);
        $stmt->bindValue(4, $excecao_fim);
        $stmt->bindValue(5, $idexcecao);

        $stmt->execute();

        $oConexao->commit();

        //MENSAGEM DE SUCESSO
        $msg['msg'] = 'success';
        $msg['idexcecao'] = $idexcecao;

        echo json_encode($msg);
        exit();

    }else{

        $stmt = $oConexao->prepare("INSERT INTO excecao (idprofissional, data, horainicialatendimento, horafinalatendimento, datacadastro)
                                    VALUES (?, ?, ?, ?, now())");
        $stmt->bindValue(1, $excecao_profissional);
        $stmt->bindValue(2, $excecao_data);
        $stmt->bindValue(3, $excecao_inicio);
        $stmt->bindValue(4, $excecao_fim);
        $stmt->execute();

        // echo $stmt->queryString;
        // echo $stmt->fullStmt;
        $idExcecaoNew = $oConexao->lastInsertId('id');

        $oConexao->commit();

        //mensagem de sucesso e dados salvos
        $msg['msg'] = 'success';
        $msg['idexcecao'] = $idExcecaoNew;
        echo json_encode($msg);
        exit();

    }//end if
    
}catch (PDOException $e){
    $oConexao->rollBack();
    //mensagem de erro
    $msg['msg'] = 'error';
    $msg['error'] = $e->getMessage();
    echo json_encode($msg);
    exit();
}

?>
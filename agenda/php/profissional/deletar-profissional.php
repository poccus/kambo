<?php 
session_start();

include_once "../../conf/conexao.php";
include_once "../../utils/funcoes.php";
$oConexao = Conexao::getInstance();

try{

    //executa a instrução de consulta 
    $oConexao->beginTransaction();

    //APAGAR DADOS DE FORMA MULTIPLAS
    $contagem = count( isset( $_POST['itemselecionado']) );

    $profissionalNaoDeletado = 0;

    if( isset( $_POST['id'] ) ){

        $idprofissional = $_POST['id'];

        $result = $oConexao->prepare("SELECT DISTINCT(idprofissional) AS idprofissional FROM consulta WHERE idprofissional = ?");
        $result->bindValue(1, $idprofissional);
        $result->execute();
        $num = $result->rowCount();

        if ( $num <= 0 ) {
            $stmt = $oConexao->prepare("DELETE FROM profissional_diastrabalho WHERE idprofissional = ?");
            $stmt->bindValue(1, $idprofissional);
            $stmt->execute();

            $stmt = $oConexao->prepare("DELETE FROM profissional WHERE idprofissional = ?");
            $stmt->bindValue(1, $idprofissional);
            $stmt->execute();
        }else{
            $profissionalNaoDeletado++;
        }

        //CONFIRMAR OS DELETES
        $oConexao->commit();

        if ($profissionalNaoDeletado > 0) {
            $msg['msg'] = 'error';
            $msg['error'] = 'As informações não foram deletadas, pois existe registro de agendamento à este Profissional.';
        }else{
            // echo "As informações foram deletadas com sucesso.";
            $msg['msg'] = 'success';
        }

        echo json_encode($msg);
        die();
        
    }


    if( isset( $_POST['itemselecionado'] )  && $contagem > 0 ){

        foreach ( $_POST['itemselecionado'] as $item ) {

            $result = $oConexao->prepare("SELECT DISTINCT(idprofissional) AS idprofissional FROM consulta WHERE idprofissional = ?");
            $result->bindValue(1, $item);
            $result->execute();
            $num = $result->rowCount();

            if ( $num <= 0 ) {
                $stmt = $oConexao->prepare("DELETE FROM profissional_diastrabalho WHERE idprofissional = ?");
                $stmt->bindValue(1, $item);
                $stmt->execute();

                $stmt = $oConexao->prepare("DELETE FROM profissional WHERE idprofissional = ?");
                $stmt->bindValue(1, $item);
                $stmt->execute();
            }else{
                $profissionalNaoDeletado++;
            }

        }

        //CONFIRMAR OS DELETES
        $oConexao->commit();

        if ($profissionalNaoDeletado > 0) {
            $msg['msg'] = 'error';
            $msg['error'] = 'As informações não foram deletadas, pois existe registro de agendamento à este Profissional.';
        }else{
            // echo "As informações foram deletadas com sucesso.";
            $msg['msg'] = 'success';
        }

        echo json_encode($msg);
        die();
    }
    
}catch (PDOException $e){
    $oConexao->rollBack();
    //mensagem de erro
    $msg['msg'] = 'error';
    $msg['error'] = $e->getMessage();
    echo json_encode($msg);
    exit();
}

?>
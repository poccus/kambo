<?php 
session_start();

include_once "../../conf/conexao.php";
include_once "../../utils/funcoes.php";
$oConexao = Conexao::getInstance();

//sessao dados
$usuariologado                       = $_SESSION['usuario'];

$profissional_nome                   = strip_tags($_POST['profissional_nome']);
$profissional_crm                    = strip_tags($_POST['profissional_crm']);
$profissional_telefone               = strip_tags($_POST['profissional_telefone']);
$profissional_email                  = strip_tags($_POST['profissional_email']);
$tempo_consulta                      = $_POST['tempo_consulta'];
$valor_consulta                      = $_POST['valor_consulta'];
$horainicio_atendimento              = $_POST['horainicio_atendimento'];
$horafinal_atendimento               = $_POST['horafinal_atendimento'];
$horainicioalmoco_atendimento        = $_POST['horainicioalmoco_atendimento'];
$horafinalalmoco_atendimento         = $_POST['horafinalalmoco_atendimento'];
$dia_atendimento                     = $_POST['dia_atendimento'];
$idprofissional                      = isset( $_POST['idprofissional'] ) ? ( $_POST['idprofissional'] != '' ? $_POST['idprofissional'] : 0 ) : 0;

$msg = array();
header('Content-type: application/json');

try{

    //inicia da transação 
    $oConexao->beginTransaction();

    if( $idprofissional != 0 ){

        $stmt = $oConexao->prepare("DELETE FROM profissional_diastrabalho WHERE idprofissional = ?");
        $stmt->bindValue(1, $idprofissional);
        $stmt->execute();

        $stmt = $oConexao->prepare("UPDATE profissional SET nome = ?, crm = ?, email = ?, telefone = ?, tempoconsulta = ?, horainicialatendimento = ?, horafinalatendimento = ?, horainicialalmoco = ?, horafinalalmoco = ?, valorprocedimento = ? WHERE idprofissional = ?");
        $stmt->bindValue(1, $profissional_nome);
        $stmt->bindValue(2, $profissional_crm);
        $stmt->bindValue(3, $profissional_email);
        $stmt->bindValue(4, $profissional_telefone);
        $stmt->bindValue(5, $tempo_consulta);
        $stmt->bindValue(6, $horainicio_atendimento);
        $stmt->bindValue(7, $horafinal_atendimento);
        $stmt->bindValue(8, $horainicioalmoco_atendimento);
        $stmt->bindValue(9, $horafinalalmoco_atendimento);
        $stmt->bindValue(10, converteValorMonetario($valor_consulta) );
        $stmt->bindValue(11, $idprofissional);

        $stmt->execute();

        if( sizeof( $dia_atendimento ) >= 1 ){
            for ( $i = 0; $i < sizeof( $dia_atendimento ); $i++ ) {

                $stmt = $oConexao->prepare("INSERT INTO profissional_diastrabalho (idprofissional, dia)
                                            VALUES (?, ?)");
                $stmt->bindValue(1, $idprofissional);
                $stmt->bindValue(2, $dia_atendimento[$i]);
                $stmt->execute();
            }
        }

        $oConexao->commit();

        //MENSAGEM DE SUCESSO
        $msg['msg'] = 'success';
        $msg['idprofissional'] = $idprofissional;

        echo json_encode($msg);
        exit();

    }else{

        $stmt = $oConexao->prepare("INSERT INTO profissional (nome, crm, email, telefone, tempoconsulta, horainicialatendimento, horafinalatendimento, valorprocedimento, datacadastro)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, now())");
        $stmt->bindValue(1, $profissional_nome);
        $stmt->bindValue(2, $profissional_crm);
        $stmt->bindValue(3, $profissional_email);
        $stmt->bindValue(4, $profissional_telefone);
        $stmt->bindValue(5, $tempo_consulta);
        $stmt->bindValue(6, $horainicio_atendimento);
        $stmt->bindValue(7, $horafinal_atendimento);
        $stmt->bindValue(8, converteValorMonetario($valor_consulta) );
        $stmt->execute();

        // echo $stmt->queryString;
        // echo $stmt->fullStmt;
        $idprofissionalNew = $oConexao->lastInsertId('idprofissional');

        if( sizeof( $dia_atendimento ) >= 1 ){
            for ( $i = 0; $i < sizeof( $dia_atendimento ); $i++ ) {

                $stmt = $oConexao->prepare("INSERT INTO profissional_diastrabalho (idprofissional, dia)
                                            VALUES (?, ?)");
                $stmt->bindValue(1, $idprofissionalNew);
                $stmt->bindValue(2, $dia_atendimento[$i]);
                $stmt->execute();
            }
        }

        $oConexao->commit();

        //mensagem de sucesso e dados salvos
        $msg['msg'] = 'success';
        $msg['idprofissional'] = $idprofissionalNew;
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
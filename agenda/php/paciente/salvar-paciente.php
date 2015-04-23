<?php 
session_start();

include_once "../../conf/conexao.php";
include_once "../../utils/funcoes.php";
$oConexao = Conexao::getInstance();

//sessao dados
$usuariologado                       = $_SESSION['usuario'];

$paciente_nome                       = strip_tags($_POST['paciente_nome']);
$paciente_datanascimento             = formata_data( $_POST['paciente_datanascimento'] );
$paciente_rg                         = strip_tags($_POST['paciente_rg']);
$paciente_cpf                        = strip_tags($_POST['paciente_cpf']);
$paciente_email                      = strip_tags($_POST['paciente_email']);
$paciente_sexo                       = $_POST['paciente_sexo'];
$paciente_observacao                 = $_POST['paciente_observacao'];
$paciente_status                     = $_POST['paciente_status'];
$paciente_celular                    = $_POST['paciente_celular'];
$paciente_sms                        = $_POST['paciente_sms'];
$paciente_telefone                   = $_POST['paciente_telefone'];
$paciente_cep                        = $_POST['paciente_cep'];
$paciente_logradouro                 = $_POST['paciente_logradouro'];
$paciente_complemento                = $_POST['paciente_complemento'];
$paciente_numero                     = $_POST['paciente_numero'];
$paciente_bairro                     = $_POST['paciente_bairro'];
$paciente_cidade                     = $_POST['paciente_cidade'];
$paciente_estado                     = $_POST['paciente_estado'];
$paciente_pais                       = $_POST['paciente_pais'];
$idpaciente                          = isset( $_POST['idpaciente'] ) ? ( $_POST['idpaciente'] != '' ? $_POST['idpaciente'] : 0 ) : 0;

$msg = array();
header('Content-type: application/json');

try{

    //inicia da transação 
    $oConexao->beginTransaction();

    if( $idpaciente != 0 ){

        $stmt = $oConexao->prepare("UPDATE paciente SET nome = ?, datanascimento = ?, rg = ?, cpf = ?, sexo = ?, observacao = ?, email = ?, telefone = ?, celular = ?, sms = ?, status = ?, cep = ? , logradouro = ? , numero = ?, complemento = ?, bairro = ?, cidade = ?, idestado = ?, idpais = ? WHERE id = ?");
        $stmt->bindValue(1, $paciente_nome);
        $stmt->bindValue(2, $paciente_datanascimento);
        $stmt->bindValue(3, $paciente_rg);
        $stmt->bindValue(4, $paciente_cpf);
        $stmt->bindValue(5, $paciente_sexo);
        $stmt->bindValue(6, $paciente_observacao);
        $stmt->bindValue(7, $paciente_email);
        $stmt->bindValue(8, $paciente_telefone);
        $stmt->bindValue(9, $paciente_celular);
        $stmt->bindValue(10, $paciente_sms);
        $stmt->bindValue(11, $paciente_status);
        $stmt->bindValue(12, $paciente_cep);
        $stmt->bindValue(13, $paciente_logradouro);
        $stmt->bindValue(14, $paciente_numero);
        $stmt->bindValue(15, $paciente_complemento);
        $stmt->bindValue(16, $paciente_bairro);
        $stmt->bindValue(17, $paciente_cidade);
        $stmt->bindValue(18, $paciente_estado);
        $stmt->bindValue(19, $paciente_pais);
        $stmt->bindValue(20, $idpaciente);

        $stmt->execute();

        $oConexao->commit();

        //MENSAGEM DE SUCESSO
        $msg['msg'] = 'success';
        $msg['idpaciente'] = $idpaciente;

        echo json_encode($msg);
        exit();

    }else{

        $stmt = $oConexao->prepare("INSERT INTO paciente (nome, datanascimento, rg, cpf, sexo, observacao, email, telefone, celular, sms, status, datacadastro, cep, logradouro, numero, complemento, bairro, cidade, idestado, idpais)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, now(), ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindValue(1, $paciente_nome);
        $stmt->bindValue(2, $paciente_datanascimento);
        $stmt->bindValue(3, $paciente_rg);
        $stmt->bindValue(4, $paciente_cpf);
        $stmt->bindValue(5, $paciente_sexo);
        $stmt->bindValue(6, $paciente_observacao);
        $stmt->bindValue(7, $paciente_email);
        $stmt->bindValue(8, $paciente_telefone);
        $stmt->bindValue(9, $paciente_celular);
        $stmt->bindValue(10, $paciente_sms);
        $stmt->bindValue(11, $paciente_status);
        $stmt->bindValue(12, $paciente_cep);
        $stmt->bindValue(13, $paciente_logradouro);
        $stmt->bindValue(14, $paciente_numero);
        $stmt->bindValue(15, $paciente_complemento);
        $stmt->bindValue(16, $paciente_bairro);
        $stmt->bindValue(17, $paciente_cidade);
        $stmt->bindValue(18, $paciente_estado);
        $stmt->bindValue(19, $paciente_pais);
        $stmt->execute();

        // echo $stmt->queryString;
        // echo $stmt->fullStmt;
        $idPacienteNew = $oConexao->lastInsertId('id');

        $oConexao->commit();

        //mensagem de sucesso e dados salvos
        $msg['msg'] = 'success';
        $msg['idpaciente'] = $idPacienteNew;
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
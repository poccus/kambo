<?php 
session_start();

include_once "../../conf/conexao.php";
include_once "../../conf/funcoes.php";
// $oConexao = Conexao::getInstance();

//sessao dados
$idUsuarioPai                         = $_SESSION['usuario'];

$pacienteNome                         = strip_tags($_POST['paciente_nome']);
$pacienteDataNascimento               = strip_tags($_POST['paciente_data_nascimento']);
$pacienteTelefone                     = strip_tags($_POST['paciente_telefone']);
$pacienteEmail                        = strip_tags($_POST['paciente_email']);

$pacienteIdPaciente                   = isset( $_POST['idpaciente'] ) ? ( $_POST['idpaciente'] != '' ? $_POST['idpaciente'] : 0 ) : 0;

$msg = array();
header('Content-type: application/json');

// $msg['idpacientepai'] = $idMedicoPai;
// $msg['nome'] = $pacienteNome;
// $msg['datnasc'] = $pacienteDataNascimento;
// $msg['sex'] = $pacienteIdSexo;
// $msg['cpf'] = $pacienteCpf;
// $msg['orgao'] = $pacienteIdUnidadeOrganizacional;
// $msg['carg'] = $pacienteCargo;
// $msg['login'] = $pacienteLogin;
// $msg['stat'] = $pacienteStatus;
// $msg['lograd'] = $pacienteLogradouro;
// $msg['numer'] = $pacienteNumero;
// $msg['comple'] = $pacienteComplemento;
// $msg['bairr'] = $pacienteBairro;
// $msg['munic'] = $pacienteIdMunicipio;
// $msg['cep'] = $pacienteCep;
// $msg['telcel'] = $pacienteTelCelular;
// $msg['telinst'] = $pacienteTelInstitucional;
// $msg['emailinst'] = $pacienteEmailInstitucional;
// $msg['emailpess'] = $pacienteEmailPessoal;
// $msg['fot'] = $pacienteFoto;
// echo json_encode($msg);
// die();
// exit();

try{

    //inicia da transação 
    $oConexao->beginTransaction();

    if( $pacienteIdPaciente != 0 ){

        $stmt = $oConexao->prepare("UPDATE paciente SET nome = ?, datanascimento = ?, telefone = ?, email = ? 
                                    WHERE id = ?");
        $stmt->bindValue(1, $pacienteNome);
        $stmt->bindValue(2, date_format((DateTime::createFromFormat('d/m/Y', $pacienteDataNascimento)), 'Y-m-d'));
        $stmt->bindValue(3, $pacienteTelefone);
        $stmt->bindValue(4, $pacienteEmail);
        $stmt->bindValue(5, $pacienteIdPaciente);

        $stmt->execute();

        // $msg['query'] = $stmt->debugDumpParams();
        // $msg['msg'] = 'success';
        // $msg['idpaciente'] = $pacienteIdMedico;

        // echo json_encode($msg);
        // die();
        // exit();

        $oConexao->commit();

        //MENSAGEM DE SUCESSO
        $msg['msg'] = 'success';
        $msg['idpaciente'] = $pacienteIdPaciente;

        echo json_encode($msg);
        exit();

    }else{

        $stmt = $oConexao->prepare("INSERT INTO paciente (nome, datanascimento, telefone, email)
                                    VALUES (?, ?, ?, ?)");
        $stmt->bindValue(1, $pacienteNome);
        $stmt->bindValue(2, date_format((DateTime::createFromFormat('d/m/Y', $pacienteDataNascimento)), 'Y-m-d'));
        $stmt->bindValue(3, $pacienteTelefone);
        $stmt->bindValue(4, $pacienteEmail);
        $stmt->execute();

        // echo $stmt->queryString;
        // echo $stmt->fullStmt;
        $idPacienteNew = $oConexao->lastInsertId('id');

        $oConexao->commit();

        //MENSAGEM DE SUCESSO
        $msg['msg'] = 'success';
        $msg['idpaciente'] = $idPacienteNew;
        echo json_encode($msg);
        exit();

    }//end if
    
}catch (PDOException $e){
    $oConexao->rollBack();
    //echo $e->getMessage();
    //MENSAGEM DE SUCESSO
    $msg['msg'] = 'error';
    $msg['erro'] = $e->getMessage();
    echo json_encode($msg);
    exit();
	exit();
}

?>
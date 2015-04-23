<?php
session_start();

function formataTexto($texto){
	$texto = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde|cedil);/', '$1',$texto);
	return $texto;
}

include_once "../../conf/conexao.php";
include_once "../../conf/funcoes.php";

// $pdo = Conexao::getInstance();
$pdo = $oConexao;

// header('Content-type: application/json');

//PARAMENTRO
$query = $_GET['query'];

$stmt = $pdo->query("SELECT id, nome, datanascimento, telefone, email FROM paciente WHERE nome LIKE '%".$query."%'  ORDER BY nome");
$dados = '';
$i = 1;

if($stmt->execute()){
	$numRows = $stmt->rowCount();
	// echo "NUMERO DE LINHAS: ".$numRows;
	if($stmt->rowCount() > 0){		

		$dados .= '{';
		$dados .= '"query": "Unit",';
		$dados .= '"suggestions": [';
		while($row = $stmt->fetch(PDO::FETCH_OBJ)){
			if($i == $numRows){
				$dados .= '"'. formataTexto( $row->nome ).'"';
			}else{
				$dados .= '"'. formataTexto( $row->nome ).'", ';
			}
			$i++;
		}
		$dados .= ']}';

	}else{
		echo 'false';
	}
}else{
	echo 'false';
}

header('Content-type: application/json');
// echo "TOTAL I: ".$i;
echo $dados;
// $dados = array('DADOS' => $tests ) ;
// echo json_encode($dados);

// header('Content-type: application/json');
// // echo '[{"id":"Branta canadensis","label":"Greater Canada Goose","value":"Greater Canada Goose"},{"id":"Branta hutchinsii","label":"Lesser Canada Goose","value":"Lesser Canada Goose"}]';
// echo '{
//     "query": "Unit",
//     "suggestions": ["United Arab Emirates", "United Kingdom", "United States"]
// }';
?>
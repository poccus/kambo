<?php
  session_start();
  //date_default_timezone_set('America/Bogota');
  try {
    $db    = new PDO("mysql:host=localhost;dbname=agendar;charset=utf8" , "root", "");
    $id = $_GET['id'];
 
    //$sql = mysql_query("SELECT * FROM acao WHERE idacao = '$id'") or die("Erro ao consultar");
    //$rs = $db->query("SELECT p.nome, p.telefone, p.datanascimento, p.datacadastro, p.email FROM consulta c, paciente p WHERE c.idpaciente = p.id AND c.id = ".$id);
    $rs = $db->query("SELECT *  FROM consulta c, paciente p WHERE c.idpaciente = p.id AND c.id = ".$id);
    $retorno = "";
    //$rs->execute();
    //echo $rs->fullStmt;

    while($row = $rs->fetch(PDO::FETCH_OBJ)){
      $retorno .= '<div class="row"><div class="control-group"><div class="col-md-4">';
      $retorno .= '<span style="font-family:Open Sans, Verdana, Arial;font-size: 13px;">Paciente: </span><br>';
      $retorno .= '<span style="font-family:Open Sans, Verdana, Arial;font-size: 13px;">Data de Nascimento: </span><br>';
      $retorno .= '<span style="font-family:Open Sans, Verdana, Arial;font-size: 13px;">Telefone: </span><br>';
      $retorno .= '<span style="font-family:Open Sans, Verdana, Arial;font-size: 13px;">Email: </span><br>';
      $retorno .= '<span style="font-family:Open Sans, Verdana, Arial;font-size: 13px;">Cliente desde: </span><br>';
      $retorno .= '</div><div class="col-md-8">';
      $retorno .= '<span style="font-weight: 600; color:#009bc9;">'.$row->nome."</span><br>";
      $retorno .= '<span style="font-weight: 600; color:#009bc9;">'.$row->datanascimento."</span><br>";
      $retorno .= '<span style="font-weight: 600; color:#009bc9;">'.$row->telefone."</span><br>";
      $retorno .= '<span style="font-weight: 600; color:#009bc9;">'.$row->email."</span><br>";
      $retorno .= '<span style="font-weight: 600; color:#009bc9;">'.$row->datacadastro."</span><br>";
      $retorno .= '</div></div></div>';

      $retorno .= '<fieldset><legend>Consulta</legend>';
      $tmp = explode(" ", $row->start);
      $retorno .= '<div class="row"><div class="control-group"><div class="col-md-6">';
      $retorno .= $tmp[0];
      $retorno .= '</div><div class="col-md-6">';
      $retorno .= $tmp[1];
      $retorno .= '</div></div></div></fieldset>';

      $retorno .= '<hr>';
      $retorno .= '<div class="row"><div class="control-group"><div class="col-md-12">';
      $retorno .= "<a href='#' class='btn btn-default btn-xs' title='O Paciente Chegou' id='chegou' >&nbsp;<i class='glyphicon glyphicon-user'></i>&nbsp;Chegou</a> 
<a href='#' class='btn btn-success btn-xs' title='Confirmar Atendimento' id='confirmaratendimento'>&nbsp;<i class='glyphicon glyphicon-thumbs-up'></i>&nbsp;Confirmar Atendimento</a>  
<a href='#' class='btn btn-warning btn-xs' title='Remarcar' id='remarcar' >&nbsp;<i class='glyphicon glyphicon-share-alt'></i>&nbsp;Remarcar</a>  
<a href='#' class='btn btn-danger btn-xs' title='Cancelar Agendamento' id='cancelar' >&nbsp;<i class='glyphicon glyphicon-remove'></i>&nbsp;Cancelar</a> ";
      $retorno .= '</div></div></div>';
    }
    echo $retorno;
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
  /*$retorno ="";
  if($sql){
	//$contexto = '{"DADOS":[ ';  
	while($l = mysql_fetch_array($sql)){
		

		$imagemStatus = "";
		$retorno .= "<div id='conteudo'><div id='carregando'></div><br>";
      	if($l["status"]==1){
        	//$imagemStatus = "<a href='#' title='Concluído' class='btn btn-success btn-xs'><i class='glyphicon glyphicon-ok'></i></a>";
        	$imagemStatus = "<img src='img/ok.gif' width='18' heigth='18'>";
      	}else{
        	//$imagemStatus = "<a href='#' title='Pendente' class='btn btn-danger btn-xs'><i class='glyphicon glyphicon-warning-sign'></i></a>";
        	$imagemStatus = "<img src='img/atraso.gif' width='18' heigth='18'>";
      	}
	    $retorno .= "<table width='100%' align='center'><tr><td>Descrição:</td><td><strong>".$l['descricao']."</strong></td></tr>";
	    $retorno .="<tr><td>Responsável:</td><td><strong>".$l['responsavel']."</strong></td></tr>";
        $retorno .= "<tr><td>Data Limite:</td><td><strong>".$l['data_limite']."</strong></td></tr><tr><td>Status:</td><td>".$imagemStatus."</td></tr></table>";
		$retorno .= "</div>";
		//return $retorno;
	}
	//$contexto .= "]}";  
  	//echo $contexto;
  	echo $retorno;
  }else{
	echo 'false';
  }*/


?>

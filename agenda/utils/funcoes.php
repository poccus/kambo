<?php
function formata_data( $data ) {
	if( $data == '' ) return ''; 
  $d = explode('/', $data);
  return $d[2] . '-' .$d[1] . '-' . $d[0];
}
function data_volta( $data ) {
	if( $data == '' || $data == '0000-00-00') return '';
  $d = explode('-', $data);
  return $d[2] . '/' .$d[1] . '/' . $d[0];
}
function hora( $hora ) { //Deixa a hora 20:00
  $h = explode(':', $hora);
  return $h[0] . ':' . $h[1];
}
function getSemana($dia, $completo = 0) {
  switch($dia) {
    case 1: 
      $r = 'SEG'; $comp = 'Segunda-feira'; break;
    case 2: 
      $r = 'TER'; $comp = 'Terça-feira'; break;
    case 3: 
      $r = 'QUA'; $comp = 'Quarta-feira'; break;
    case 4: 
      $r = 'QUI'; $comp = 'Quinta-feira'; break;
    case 5: 
      $r = 'SEX'; $comp = 'Sexta-feira'; break;
    case 6: 
      $r = 'SAB'; $comp = 'Sábado'; break;
    case 7: 
      $r = 'DOM'; $comp = 'Domingo'; break;  
  }
  if ( $completo == 1 )
    return $comp;
  else 
    return $r;
}
function getSemana2($dia, $completo = 0) {
  switch($dia) {
    case 1: 
      $r = 'Seg'; $comp = 'Segunda-feira'; break;
    case 2: 
      $r = 'Ter'; $comp = 'Terça-feira'; break;
    case 3: 
      $r = 'Qua'; $comp = 'Quarta-feira'; break;
    case 4: 
      $r = 'Qui'; $comp = 'Quinta-feira'; break;
    case 5: 
      $r = 'Sex'; $comp = 'Sexta-feira'; break;
    case 6: 
      $r = 'Sab'; $comp = 'Sábado'; break;
    case 7: 
      $r = 'Dom'; $comp = 'Domingo'; break;  
  }
  if ( $completo == 1 )
    return $comp;
  else 
    return $r;
}

function getDiaSemana($dia, $completo = 0) {
  switch($dia) {
    case 1: 
      $r = 'Dom'; $comp = 'Domingo'; break;  
    case 2: 
      $r = 'Seg'; $comp = 'Segunda-feira'; break;
    case 3: 
      $r = 'Ter'; $comp = 'Terça-feira'; break;
    case 4: 
      $r = 'Qua'; $comp = 'Quarta-feira'; break;
    case 5: 
      $r = 'Qui'; $comp = 'Quinta-feira'; break;
    case 6: 
      $r = 'Sex'; $comp = 'Sexta-feira'; break;
    case 7: 
      $r = 'Sab'; $comp = 'Sábado'; break;
  }
  if ( $completo == 1 )
    return $comp;
  else 
    return $r;
}

function hoje( $data ) {
  $dt = explode( '/', $data );
  return getSemana( date("N", mktime(0, 0, 0, $dt[1], $dt[0], intval($dt[2]) ) ), 1 );
}
function timeDiff($firstTime,$lastTime)
{
  $firstTime=strtotime($firstTime);
  $lastTime=strtotime($lastTime);
  $timeDiff=$lastTime-$firstTime;
  return $timeDiff;
}
function separa_hora($hora, $op) { //$op = minutos = 1; hora = 0
  $hr = explode(':', $hora);
  return $hr[$op];
}
function dataExtenso($dt) {
  $da = explode( '/', $dt );
  return $da[0] . ' de ' . getMes( $da[1] ) . ' de ' . $da[2];
}
function dataExtensoTimeline($dt) {
  $da = explode( '/', $dt );
  $diasemana = date("w", mktime(0,0,0,$da[1],$da[0],$da[2]) );
  return getSemana2($diasemana, 0) . '  ' . getMes2( $da[1] ) . '  ' . $da[0]. ' '. $da[2];
}
function getMes($m) {
  switch ($m){
  	case 1: $mes = "Janeiro"; break;
  	case 2: $mes = "Fevereiro"; break;
  	case 3: $mes = "Março"; break;
  	case 4: $mes = "Abril"; break;
  	case 5: $mes = "Maio"; break;
  	case 6: $mes = "Junho"; break;
  	case 7: $mes = "Julho"; break;
  	case 8: $mes = "Agosto"; break;
  	case 9: $mes = "Setembro"; break;
  	case 10: $mes = "Outubro"; break;
  	case 11: $mes = "Novembro"; break;
  	case 12: $mes = "Dezembro"; break;
  }
  return $mes;
}
function getMes2($m) {
  switch ($m){
    case 1: $mes = "Jan"; break;
    case 2: $mes = "Fev"; break;
    case 3: $mes = "Mar"; break;
    case 4: $mes = "Abr"; break;
    case 5: $mes = "Mai"; break;
    case 6: $mes = "Jun"; break;
    case 7: $mes = "Jul"; break;
    case 8: $mes = "Ago"; break;
    case 9: $mes = "Set"; break;
    case 10: $mes = "Out"; break;
    case 11: $mes = "Nov"; break;
    case 12: $mes = "Dez"; break;
  }
  return $mes;
}

function colocaAcentoMaiusculo( $texto ) {
  $array1 = array(   "á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç"); 
                       
  $array2 = array(   "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç" ); 
  return str_replace( $array1, $array2, $texto ); 
}

function retira_acentos( $texto ) {
  $array1 = array(   "á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç" 
                     , "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç" ); 
  $array2 = array(   "a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c" 
                     , "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C" ); 
  return str_replace( $array1, $array2, $texto ); 
}
// Cria uma função que retorna o timestamp de uma data no formato DD/MM/AAAA
function geraTimestamp($data) {
$partes = explode('/', $data);
return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
}

function calculaDiferencaDatas($data_inicial, $data_final){
// Usa a função criada e pega o timestamp das duas datas:
$time_inicial = geraTimestamp($data_inicial);
$time_final = geraTimestamp($data_final);

// Calcula a diferença de segundos entre as duas datas:
$diferenca = $time_final - $time_inicial; // 19522800 segundos

// Calcula a diferença de dias
$dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias

// Exibe uma mensagem de resultado:
//echo "A diferença entre as datas ".$data_inicial." e ".$data_final." é de <strong>".$dias."</strong> dias";
  return $dias;
}
function apelidometadatos($variavel){
  /*$a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ ,;:./';
  $b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr______';
  //$string = utf8_decode($string);
  $string = strtr($string, utf8_decode($a), $b); //substitui letras acentuadas por "normais"
  $string = str_replace(" ","",$string); // retira espaco
  $string = strtolower($string); // passa tudo para minusculo*/
  $string = strtolower( ereg_replace("[^a-zA-Z0-9-]", "-", strtr(utf8_decode(trim($variavel)), utf8_decode("áàãâéêíóôõúüñçÁÀÃÂÉÊÍÓÔÕÚÜÑÇ"),"aaaaeeiooouuncAAAAEEIOOOUUNC-")) );
  return utf8_encode($string); //finaliza, gerando uma saída para a funcao
}

function getExtensaoArquivo( $extensao ){
  switch ($extensao) {
    case 'image/jpeg':  $ext = ".jpeg"; break;
    case 'image/jpg':   $ext = ".jpg";  break;
    case 'image/pjpeg': $ext = ".pjpg"; break;
    case 'image/JPEG':  $ext = ".JPEG"; break;
    case 'image/gif':   $ext = ".gif";  break;
    case 'image/png':   $ext = ".png";  break;
    case 'video/webm':  $ext = ".webm"; break;
    case 'video/mp4':   $ext = ".mp4";  break;
    case 'video/flv':   $ext = ".flv";  break;
    case 'video/webm':   $ext = ".webm";break;
    case 'audio/mp4':   $ext = ".acc";  break;
    case 'audio/mpeg':   $ext = ".mp3"; break;
    case 'audio/ogg':   $ext = ".ogg"; break;
  }
  return $ext;
}

function uploadArquivoPermitido( $arquivo ){
  $tiposPermitidos = array('image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png', 'video/webm', 'video/mp4', 'video/ogv', 'audio/mp3', 'audio/mp4', 'audio/mpeg', 'audio/ogg');
  if (array_search($arquivo, $tiposPermitidos) === false) {
    return false;
  }else{
    return true;
  }//end if
}

function converteValorMonetario($valor){
  $valor = str_replace('.', '', $valor);
  $valor = str_replace('.', '', $valor);
  $valor = str_replace('.', '', $valor);
  $valor = str_replace(',', '.', $valor);
  return $valor;
}

function valorMonetario($valor){
  $valor = number_format($valor, 2, ',', '.');
  return $valor;
}


function verificarloginduplicado($usuario, $idsessao, $query){
  $oConexao     = Conexao::getInstance();
  $retorno    = true;
  $querysessao  = $oConexao->query( $query );
  $qtdsessao    = $querysessao->rowCount();
  if( $qtdsessao == 0 ) {
      $retorno = false;
  }
  return $retorno;
}

function historicoacesso( $pagina, $apelido, $operacao, $usuario, $ip ){
  $oConn       = Conexao::getInstance();
  $retorno     = true;
  if( $pagina != '' && $operacao != '' ){
    $rsUsuarioHist = $oConn->prepare("SELECT count(id) total FROM usuario_hist WHERE datacadastro = now() AND ip = ? AND apelido = ? AND operacao = ?");
    $rsUsuarioHist->bindValue(1, $ip);
    $rsUsuarioHist->bindValue(2, $apelido);
    $rsUsuarioHist->bindValue(3, $operacao);
    $rsUsuarioHist->execute();
    $countUsuarioHist = $rsUsuarioHist->fetch( PDO::FETCH_OBJ )->total;
    if( $countUsuarioHist <= 0 ){
      $usuarioHist = $oConn->prepare("INSERT INTO usuario_hist(pagina, apelido, operacao, datacadastro, idusuario, ip) VALUES(?, ?, ?, now(), ?, ?)");
      $usuarioHist->bindValue(1, $pagina);
      $usuarioHist->bindValue(2, $apelido);
      $usuarioHist->bindValue(3, $operacao);
      $usuarioHist->bindValue(4, $usuario);
      $usuarioHist->bindValue(5, $ip);
      // $usuarioHist->bindValue(6, $ip);
      if( !$usuarioHist->execute() ){
        $retorno = false;
      }
    }
  }
  return $retorno;
}

function envia_email($email, $assunto, $msg, $emaile, $nome_email) {
  // Inclui o arquivo class.phpmailer.php localizado na pasta phpmailer 
  // Inicia a classe PHPMailer
  $mail = new PHPMailer();
  // Define os dados do servidor e tipo de conexão 
  // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
  /////////////   ********     NUNCA MUDAR ISSO     **********    //////////
  $mail->IsSMTP(); // Define que a mensagem será SMTP
  $mail->Host = "mail.ac.gov.br"; // Endereço do servidor SMTP
  $mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
  $mail->SMTPSecure = "tls";
  $mail->SMTP_PORT = "587";
  $mail->Username = "sistemas.sai"; // Usuário do servidor SMTP
  $mail->Password = "tAKARzCO"; // Senha do servidor SMTP
  $mail->Mailer = "smtp";
  
  // Define o remetente
  // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
  $mail->From = $emaile;
  $mail->FromName = $nome_email; // Seu nome
  
  // Define os destinatário(s)
  // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
  $mail->AddAddress($email);
  
  // Define os dados técnicos da Mensagem
  // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
  $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
  $mail->CharSet = 'utf-8'; // Charset da mensagem (opcional)
  
  // Define a mensagem (Texto e Assunto)
  // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
  $mail->Subject = $assunto; // Assunto da mensagem
  $mail->Body = $msg;
//  $mail->AltBody = "Este é o corpo da mensagem de teste, em Texto Plano! \r\n ";
  
  // Define os anexos (opcional)
  // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
  //$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
  
  // Envia o e-mail
  $enviado = $mail->Send();
  
  // Limpa os destinatários e os anexos
  $mail->ClearAllRecipients();
  //$mail->ClearAttachments();
  
  // Exibe uma mensagem de resultado
  if ($enviado) {
    return true;
  } else {
    return false;
  }
}

?>
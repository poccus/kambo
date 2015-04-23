<?php 
  session_start();
  include_once "../conf/config.php";
  include_once "../utils/funcoes.php";
  $oConexao = Conexao::getInstance();
?>
<!-- TEMPLATE -->
<?php include('../template/head.php'); ?>
<?php 
// VERIFICACAO DE ACESSO DO MODULO
$moduloid           = $_SESSION['moduloid'];
$moduloapelido      = $_SESSION['moduloapelido'];
$modulopagina       = $_SESSION['modulopagina'];
$submoduloid        = $_SESSION['submoduloid'];
$submoduloapelido   = $_SESSION['submoduloapelido'];

//VERIFICAR SE O USUARIO TEM ACESSO AO GESTOR DE CARGOS
if( !in_array('/usuario', $modulopagina) ){
  echo "<script>
      $(document).ready(function(){
        defaultModal.util.openmodalpermissao({
        open: {show: true, backdrop: 'static', keyboard: false},
        title: 'PERMISSÃO - ACESSO NEGADO',
        loadurl: false,
        container: 'acesso-negado-area'
      });
      });
    </script>";
  exit();
}

//VERIFICAR SE O USUARIO TEM ACESSO AO SUBMODULO
if( $_SESSION['nivelacesso'] == 1 && !in_array('usuarios', $submoduloapelido) ){
  echo "<script>
      $(document).ready(function(){
        defaultModal.util.openmodalpermissao({
        open: {show: true, backdrop: 'static', keyboard: false},
        title: 'PERMISSÃO - ACESSO NEGADO',
        loadurl: false,
        container: 'acesso-negado-area'
      });
      });
    </script>";
  exit();
}else if( $_SESSION['nivelacesso'] == 2 && !in_array('usuarios', $submoduloapelido) ){
  echo "<script>
      $(document).ready(function(){
        defaultModal.util.openmodalpermissao({
        open: {show: true, backdrop: 'static', keyboard: false},
        title: 'PERMISSÃO - ACESSO NEGADO',
        loadurl: false,
        container: 'acesso-negado-area'
      });
      });
    </script>";
  exit();
}else if( $_SESSION['nivelacesso'] == 3 && !in_array('usuarios', $submoduloapelido) ){
  echo "<script>
      $(document).ready(function(){
        defaultModal.util.openmodalpermissao({
        open: {show: true, backdrop: 'static', keyboard: false},
        title: 'PERMISSÃO - ACESSO NEGADO',
        loadurl: false,
        container: 'acesso-negado-area'
      });
      });
    </script>";
  exit();
}
?>

<?php 

  //PARAMETRO POR URL AMIGAVEL NA POSIÇÃO 01 - PADRÃO
  $param = Url::getURL( 1 );
  $param = $_GET['id'] == '' || $_POST['id'] == '' ? $param : 0;
  $sql = "SELECT pagina, operacao, date_format(datacadastro, '%d/%m/%Y') as datacadastro, date_format(datacadastro, '%d/%m/%Y %h:%i:%s') as data, ip FROM usuario_hist WHERE idusuario = $param";
  $orderby = " ORDER BY id DESC LIMIT 0, 500";

  //PEGAR O DADOS DE ACORDO COM A CONSULTA NO BANCO DE DADOS
  $resultado = $oConexao->query($sql.$orderby);

  //PEGAR O TOTAL DE DADOS NA TABELA
  $totalresultado = $oConexao->query($sql.$orderby)->rowCount();

  //CONTAGEM DE DADOS
  $contador = 0;

  $detalhes .= "<div class='common-content margin-top-5-5em margin-bottom-10em'>";
  $detalhes .= '<table border="0" width="100%" align="center" class="table table-condensed table-striped">
                  <tr>
                    <td align="center" colspan="5" class="border-none background-none">';
      $detalhes .= '<img src="'.PORTAL_URL.'img/acrebrasao.jpg" alt="Foto" width="65" height="65" class="img-circle img-responsive"><br>GOVERNO DO ESTADO DO ACRE<br>SECRETARIA DE ARTICULAÇÃO INSTITUCIONAL E POLÍTICA<br>CONTROLE DAS NOMEAÇÕES DO PODER EXECUTIVO<br><br><h3>HISTÓRICO DE ACESSO AO SISTEMA';
      $detalhes .= '</td>
                  </tr>';
    if($totalresultado <= 0 || $totalresultado == NULL){
      $detalhes .= '<tr><td colspan="5">Nenhum registro encontrado.</td></tr>';
    }else{            
    $detalhes .= '<tr>
                    <td>#</td>
                    <td>PAGINA</td>
                    <td>OPERAÇÃO</td>
                    <td>DATA</td>
                    <td>IP</td>
                  </tr>';
      while ( $row = $resultado->fetch(PDO::FETCH_ASSOC) ) {           
        $detalhes .= '<tr>
                        <td>'.($i+1).'</td>
                        <td>'.$row['pagina'].'</td>
                        <td>'.$row['operacao'].'</td>
                        <td>'.$row['data'].'</td>
                        <td>'.$row['ip'].'</td>
                      </tr>';
        $i++;              
      }                
    }

  $detalhes .= '</table>'; 
  $detalhes .= "</div>";
  echo $detalhes;               
?>
<?php 
  //VARIAVEIS DE LOGIN E HISTORICO DE ACESSO
  $idsessao       = session_id();
  $pagina_historico   = 'Usuários';
  $apelido_historico  = 'usuarios';
  $operacao_historico = 'Imprimir';
  $ip_historico   = $_SERVER['REMOTE_ADDR'];
?>
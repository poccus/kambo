<?php 
  session_start();
  include_once "../conf/config.php";
  include_once "../utils/funcoes.php";
  $oConexao = Conexao::getInstance();
?>
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
<script type="text/javascript">
  
</script>

<div id="load-box-container-dasboard" class="control-group display-n">
  <div class="col-md-12 padding-0 margin-bottom-10em">
    <div class="text-center">
      <img src="<?=PORTAL_URL; ?>img/load.gif"> <br/>processando...
    </div>
  </div>
</div>

<link href="<?=CSS_FOLDER?>datepicker.css" rel="stylesheet" type="text/css" />
<link href="<?=CSS_FOLDER?>datepicker3.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=JS_FOLDER?>bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?=JS_FOLDER?>bootstrap-datepicker.pt-BR.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#datainicio').datepicker({format: "dd/mm/yyyy", language: "pt-BR", autoclose: true});
    $('#datafinal').datepicker({format: "dd/mm/yyyy", language: "pt-BR", autoclose: true});
  });
</script>

<div class="row">
  <div class="control-group">
    <div class="col-md-5 margin-bottom-1em">
        <label for="datainicio">Data de início</label>
        <div class="input-group date">
          <input id="datainicio" type="text" class="form-control" value="<?=isset( $_REQUEST['datainicio'] ) && $_REQUEST['datainicio'] != '' ? $_REQUEST['datainicio'] : '' ?>"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
        </div>
    </div>
    <div class="col-md-5 margin-bottom-1em">
      <label for="datafinal">Data final</label>
      <div class="input-group date">
        <input id="datafinal" type="text" class="form-control" value="<?=isset( $_REQUEST['datafinal'] ) && $_REQUEST['datafinal'] != '' ? $_REQUEST['datafinal'] : '' ?>"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
      </div>
    </div>
    <div class="col-md-2 margin-top-1-9em padding-0">
      <a id="filtrar-historico-usuario" class="btn btn-default" title="Filtrar"><i class="glyphicon glyphicon-search"></i> Filtrar</a>
    </div>
  </div>
</div>

<?php 

  //PARAMETRO POR URL AMIGAVEL NA POSIÇÃO 01 - PADRÃO
  $param = Url::getURL( 1 );
  $param = $_GET['id'] == '' || $_POST['id'] == '' ? $param : 0;

  $sql = "SELECT pagina, operacao, date_format(datacadastro, '%d/%m/%Y') as datacadastro, date_format(datacadastro, '%d/%m/%Y %h:%i:%s') as data, ip FROM usuario_hist WHERE idusuario = $param";
  // $dadosConsulta = isset( $_REQUEST['datainicio'] ) && $_REQUEST['datainicio'] != '' ? ' AND datacadastro >= "'.formata_data($_REQUEST['datainicio']).'"' : ' ';
  // $dadosConsulta .= isset( $_REQUEST['datafinal'] ) && $_REQUEST['datafinal'] != '' ? ' AND datacadastro <= "'.formata_data($_REQUEST['datafinal']).'"' : '';
  $dadosConsulta = isset( $_REQUEST['datainicio'] ) && $_REQUEST['datainicio'] != '' ? ' AND datacadastro BETWEEN "'.formata_data($_REQUEST['datainicio']).' 00:00:00"' : ' ';
  $dadosConsulta .= isset( $_REQUEST['datafinal'] ) && $_REQUEST['datafinal'] != '' ? ' AND "'.formata_data($_REQUEST['datafinal']).' 23:59:59"' : '';
  $orderby = " ORDER BY id DESC";
  // $historico = $oConexao->query("SELECT pagina, operacao, date_format(datacadastro, '%d/%m/%Y') as datacadastro, ip FROM usuario_hist WHERE idusuario = $param ORDER BY id DESC LIMIT 0,15");
  // $historico->execute();
  // $qtdhistorico = $historico->rowCount();
  $dataaux = '';

  //TOTAL DE PAGINACAO
  $total = 15; 
  $paginacao = isset( $_GET['paginacao'] ) ? $_GET['paginacao'] : 1;
  $inicio = ( $paginacao - 1 ) * $total;
  $limite = " LIMIT $inicio, $total";

  //PEGAR O DADOS DE ACORDO COM A CONSULTA NO BANCO DE DADOS
  // echo $sql.$dadosConsulta.$orderby.$limite;
  $resultado = $oConexao->query($sql.$dadosConsulta.$orderby.$limite);

  //PEGAR O TOTAL DE DADOS NA TABELA
  $totalresultado = $oConexao->query($sql.$dadosConsulta)->rowCount();
// 
  //CALCULAR O TOTAL DE PAGINAS
  $totaldepaginas = ceil( $totalresultado / $total );

  // Calcula os intervalos iniciais e finais
  // para saber quais registros vamos mostrar
  $fim = $total * $paginacao;
  $inicio = ($fim - $total);

  //CONTAGEM DE DADOS
  $contador = 0;

?>

<table class="table table-striped table-bordered">
  <?php if($totalresultado <= 0 || $totalresultado == NULL){ ?>
      <tr><td colspan="5">Nenhum registro encontrado.</td></tr>
  <?php }else{ ?>
  <tbody id="table-last-appointments">
    <?php 
     if( $totalresultado > 0 ){
      $x = 0;
      $contador = $inicio;
      while( $row = $resultado->fetch(PDO::FETCH_ASSOC) ){
        if( $dataaux != $row['datacadastro'] ){
    ?>
    <tr>
      <td colspan="5"><?=dataExtenso($row['datacadastro'])?> - <?=hoje($row['datacadastro'])?></td>
    </tr>
    <?    if( $x == 0 ){ ?>
    <tr>
      <td>#</td>
      <td>PAGINA</td>
      <td>OPERAÇÃO</td>
      <td>DATA</td>
      <td>IP</td>
    </tr>
    <?php 
          $x++;
          $i = 0;
          }//end if
          $dataaux = $row['datacadastro'];
        }//end if
    ?>
    <tr>
      <td><?=($contador+1)?></td>
      <td><?=$row['pagina']?></td>
      <td><?=$row['operacao']?></td>
      <td><?=$row['data']?></td>
      <td><?=$row['ip']?></td>
    </tr>
    <?php 
      $i++;
      $contador++;
      }//end while
    }//end if
    ?>
  </tbody>
  <?php } ?>
</table>

<!-- INICIO DA PAGINACAO -->
<ul class="pagination pagination-sm no-margin">
    <?php if ($paginacao == 1): ?>
        <li class="disabled"><span>Primeira</span></li>
        <li class="disabled"><span>Anterior</span></li>
    <?php else: ?>
        <li><a id="paginacao-historico-usuario" href="?paginacao=1&datainicio=<?=$_REQUEST['datainicio']?>&datafinal=<?=$_REQUEST['datafinal']?>"><span>Primeira</span></a></li>
        <li><a id="paginacao-historico-usuario" href="?paginacao=<?php print $paginacao-1;?>&datainicio=<?=$_REQUEST['datainicio']?>&datafinal=<?=$_REQUEST['datafinal']?>"><span>Anterior</span></a></li>
    <?php endif; ?>
    <!-- mostra até cinco páginas antes da página atual -->
    <?php foreach(array_reverse(range($paginacao-1, $paginacao-5)) as $pagina): ?>
        <?php if ($pagina > 0): ?>
            <li><a id="paginacao-historico-usuario" href="?paginacao=<?php print $pagina; ?>&datainicio=<?=$_REQUEST['datainicio']?>&datafinal=<?=$_REQUEST['datafinal']?>"><?php print $pagina; ?></a></li>
        <?php endif; ?>
    <?php endforeach; ?>
    <!-- mostra a página atual para o usuário -->
   <li class="disabled"><span><?php print $paginacao; ?></span></li>
    <!-- mostra até cinco página após a página atual -->
    <?php foreach( range($paginacao+1, $paginacao+5) as $pagina): ?>
        <?php if ($pagina < $totaldepaginas): ?>
        <li><a id="paginacao-historico-usuario" href="?paginacao=<?php print $pagina; ?>&datainicio=<?=$_REQUEST['datainicio']?>&datafinal=<?=$_REQUEST['datafinal']?>"><?php print $pagina; ?></a></li>
        <?php endif; ?>
    <?php endforeach; ?>
    <!-- mostra os links para a próxima página
    e para a última página da lista -->
    <?php if ($paginacao == $totaldepaginas): ?>
        <li class="active"><span>Pr&oacute;xima</span></li>
        <li class="active"><span>&Uacute;ltima</span></li>
    <?php else: ?>
        <li><a id="paginacao-historico-usuario" href="?paginacao=<?php print $paginacao+1; ?>&datainicio=<?=$_REQUEST['datainicio']?>&datafinal=<?=$_REQUEST['datafinal']?>"><span>Pr&oacute;xima</span></a></li>
        <li><a id="paginacao-historico-usuario" href="?paginacao=<?php print $totaldepaginas;?>&datainicio=<?=$_REQUEST['datainicio']?>&datafinal=<?=$_REQUEST['datafinal']?>"><span>&Uacute;ltima</span></a></li>
    <?php endif; ?>
</ul>
<!-- FIM DA PAGINACAO -->
<div class="pull-right">
  <?php echo "Mostrando $paginacao de $totaldepaginas com $totalresultado registros.";?>
</div>
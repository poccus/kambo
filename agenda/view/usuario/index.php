<?php 
  session_start();

  // INCLUDE TEMPLATE
  include_once "conf/config.php";
  $oConexao = Conexao::getInstance();

  include('template/header.php');
  include('template/menubar.php');
  include('template/asideright.php');
  
?>

<?php 
// // VERIFICACAO DE ACESSO DO MODULO
$moduloid           = $_SESSION['moduloid'];
$moduloapelido      = $_SESSION['moduloapelido'];
$modulopagina       = $_SESSION['modulopagina'];
$submoduloapelido   = $_SESSION['submoduloapelido'];
$acaosubmodulo      = $_SESSION['acaousuario'];

// //VARIAVEIS DE LOGIN E HISTORICO DE ACESSO
// $idsessao       = session_id();
// $pagina_historico   = 'Usuários';
// $apelido_historico  = 'usuarios';
// $operacao_historico = 'Visualizar';
// $ip_historico   = $_SERVER['REMOTE_ADDR'];

// // VERIFICAR SE O USUARIO TEM ACESSO A ESTA AREA
// if( !in_array('/usuario', $modulopagina) ){
//   echo "<script>
//       $(document).ready(function(){
//         defaultModal.util.openmodalpermissao({
//         open: {show: true, backdrop: 'static', keyboard: false},
//         title: 'PERMISSÃO - ACESSO NEGADO',
//         loadurl: false,
//         container: 'acesso-negado-area'
//       });
//       });
//     </script>";
//   exit();
// }

?>
<div class="content clearfix">

  <?php if( isset($_POST['type']) ){ 
    $alert_type = $_POST['type'] == 'success' ? 'alert-success' : 'alert-danger';
    $alert_icon = $_POST['type'] == 'success' ? '<span class="glyphicon glyphicon-ok"></span>' : '<span class="glyphicon glyphicon-remove"></span>'; 
  ?>
  <div class="col-sm-12">
    <div id="return-feedback" class="alert <?=$alert_type?>">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <div id="msg-feedback"><?=$alert_icon?>  <?=$_POST['feedback']?> <?=$_POST['error']?></div>
    </div>
  </div><!-- FEEDBACK MESSAGE -->
  <?php } ?>

  <aside class="col-sm-2">
    <h2>Usuário</h2>
    <p class="help-text">Busca por:</p>
    <form id="filtro" name="filtrolista" method="post" action="" enctype="multipart/form-data">
      <div class="form-group">
        <select name="filtrotipo" class="selectpicker form-control input-sm">
          <option value="nome" <?=isset($_REQUEST['filtrotipo']) && $_REQUEST['filtrotipo'] == 'nome' ? "selected='true'" : ''?> >Nome</option>
        </select>
      </div>
      <div class="form-group">
        <input name="filtrocampo" id="filtrocampo" class="form-control input-sm" type="text" placeholder="Busca" value="<?=isset($_REQUEST['filtrocampo']) ? $_REQUEST['filtrocampo'] : ''; ?>">
        <button type="submit" name="filtrobusca" id="filtrobusca" class="btn btn-default btn-sm relative-btn pull-right"><i class="glyphicon glyphicon-search"></i></button>
      </div>
    </form>
  </aside><!-- ASIDE BAR  -->

  <div class="col-sm-10"><!-- BEGIN PANEL -->

    <div class="row options">
      <div id="check" class="pull-left">
          <!-- Single button -->
          <div class="btn-group">
            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              <i class="glyphicon glyphicon-cog"></i> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
              <li id="rmv-li-slc" class="disabled"><a id="rmv-item-slc" href="#">Remover selecionados</a></li>
            </ul>
          </div>
      </div>
      <div id="actions" class="list-commands pull-right">
          <a href="<?=PORTAL_URL?>view/usuario/formulario" class="btn btn-success btn-sm pull-right"><i class="glyphicon glyphicon-plus"></i> Adicionar</a>
      </div>
    </div><!-- END ACTIONS -->

    <div class="row options">
        <form id="form-table" method='post' action='' enctype="multipart/form-data">
            <div class="table-responsive margin-top-0-5em">          
            <table id="tabela-lista-dados" class="table table-striped">
            <thead>
              <tr>
                <?php //if( $_SESSION['nivelacesso'] == 1 ){ ?>
                <th width="1%" scope="col">
                    <input type="checkbox" name="marcatodos" id="marcartodos" value="all">
                </th>
                <?php //} ?>
                <th width="1%" scope="col">#</th>
                <th width="10%" scope="col">Foto</th>
                <th width="29%" scope="col">Nome</th>
                <th width="20%" scope="col">Login</th>
                <th width="10%" scope="col">Status</th>
                <th width="10%" scope="col">Logado</th>
                <th width="15%" scope="col"></th>
              </tr>
            </thead>
            <tbody>
            <?php    

              //pegando dados do modulo de acesso do usuário, para listar os usuário de acordo com a sua permissão de acesso do modulo.
              $mod_id = implode(',', $moduloid);
                
              //DADOS DA CONSULTA
              $sql = "SELECT u.idusuario, u.nome, u.login, u.perfil, u.online, u.foto, u.liberado, u.datacadastro, mu.idmodulo FROM usuario u INNER JOIN modulo_usuario mu ON ( u.idusuario = mu.idusuario ) ";
              $dadosPesquisa .= isset($_REQUEST['filtrocampo']) && $_REQUEST['filtrocampo'] != '' && $_REQUEST['filtrotipo'] == 'nome' ? ' WHERE mu.idmodulo in('.$mod_id.') AND u.liberado <= 2 AND u.nome LIKE "%'. $_REQUEST['filtrocampo'] .'%" OR u.login LIKE "'. $_REQUEST['filtrocampo'] .'"' : ' AND mu.idmodulo in('.$mod_id.') AND u.liberado <= 2';
              $orderby = " GROUP BY u.nome ORDER BY u.idusuario DESC";

              //TOTAL DE PAGINACAO
              $total = 10; 
              $paginacao = isset( $_GET['paginacao'] ) ? $_GET['paginacao'] : 1;
              $inicio = ( $paginacao - 1 ) * $total;
              $limite = " LIMIT $inicio, $total";

              //PEGAR O DADOS DE ACORDO COM A CONSULTA NO BANCO DE DADOS
              // echo $sql.$dadosPesquisa.$orderby.$limite;
              $resultado = $oConexao->query($sql.$dadosPesquisa.$orderby.$limite);

              //PEGAR O TOTAL DE DADOS NA TABELA
              $totalresultado = $oConexao->query($sql.$dadosPesquisa.$orderby)->rowCount();

              //CALCULAR O TOTAL DE PAGINAS
              $totaldepaginas = ceil( $totalresultado / $total );

              // Calcula os intervalos iniciais e finais
              // para saber quais registros vamos mostrar
              $fim = $total * $paginacao;
              $inicio = ($fim - $total);

              //CONTAGEM DE DADOS
              $contador = 0;

            ?>
            <?php if($totalresultado <= 0 || $totalresultado == NULL){ ?>
                    <tr><td colspan="6">Nenhum registro encontrado.</td></tr>
            <?php }else{
                  $contador = $inicio;
                  while($row = $resultado->fetch(PDO::FETCH_ASSOC)){
                ?>

                <tr>
                  <?php //if( $_SESSION['nivelacesso'] == 1 ){ ?>
                  <th scope="col-xs-1">
                      <input type="checkbox" name="itemselecionado[]" id="itemselecionado-<?=$row['idusuario']?>" value="<?=$row['idusuario']?>">
                  </th>
                  <?php //} ?>  
                  <td><?=$contador+1?></td>
                  <td>
                    <?php 
                    $foto                   = $row['foto'] == '' || $row['foto'] == NULL ? 'icon-user-default.png' : $row['foto'];
                    $pasta                  = PORTAL_URL."upload/user/";
                    ?>
                    <img src="<?php echo $pasta.$foto; ?>" width="42" height="42" />
                  </td>
                  <td>
                    <?php //if( $_SESSION['nivelacesso'] == 1 ){//APENAS ADMINISTRADOR PODE EDITAR O ITEM ?> 
                      <a href="<?=PORTAL_URL?>view/usuario/formulario/<?=$row['idusuario']?>" title="editar item">
                        <?=$row['nome']?>
                      </a>  
                      <?php //}else{ ?>
                        <?php //echo $row['nome']; ?>
                      <?php //}?>
                  </td>
                  <td><?=$row['login']?></td>
                  <?php if($row['liberado'] == 1){?>
                    <td class="text-center"><span class="label label-success pull-left" id="status-unidade-<?php echo $row['idusuario'];?>">Ativo</span></td>
                  <?php }else{ ?>
                  <td class="text-center"><span class="label label-warning pull-left" id="status-unidade-<?php echo $row['idusuario'];?>">Inativo</span></td>
                  <?php } ?>
                  <?php if($row['online'] == 1){?>
                    <td class="text-center"><span class="label label-success pull-left">Online</span></td>
                  <?php }else{ ?>
                  <td class="text-center"><span class="label label-danger pull-left">Offline</span></td>
                  <?php } ?>
                   <td class="text-right">
                    <a id="historico-item" class="btn btn-default btn-sm" href="javascript:;" title="histórico de acesso" rel="<?=$row['idusuario']?>"><i class="glyphicon glyphicon-list"></i></a>
                    <a id="redefinirsenha-item" class="btn btn-warning btn-sm" href="javascript:;" title="redefinir senha" rel="<?=$row['idusuario']?>"><i class="glyphicon glyphicon-lock"></i></a>
                    <a id="detalhe-item" class="btn btn-info btn-sm" href="javascript:;" title="visualizar item" rel="<?=$row['idusuario']?>"><i class="glyphicon glyphicon-zoom-in"></i></a>
                  </td>
                </tr>
                
              <?php $contador++; } } ?>
            
            </tbody>
          </table>
          <?php if($totalresultado >= 1 || $totalresultado != NULL){ ?>
          <!-- INICIO DA PAGINACAO -->
          <ul class="pagination pagination-sm no-margin pull-right">
              <?php if ($paginacao == 1): ?>
                  <li class="disabled"><span>Primeira</span></li>
                  <li class="disabled"><span>Anterior</span></li>
              <?php else: ?>
                  <li><a href="?paginacao=1&filtrotipo=<?=$_REQUEST['filtrotipo']?>"><span>Primeira</span></a></li>
                  <li><a href="?paginacao=<?php print $paginacao-1;?>&filtrotipo=<?=$_REQUEST['filtrotipo']?>"><span>Anterior</span></a></li>
              <?php endif; ?>
              <!-- mostra até cinco páginas antes da página atual -->
              <?php foreach(array_reverse(range($paginacao-1, $paginacao-5)) as $pagina): ?>
                  <?php if ($pagina > 0): ?>
                      <li><a href="?paginacao=<?php print $pagina; ?>&filtrotipo=<?=$_REQUEST['filtrotipo']?>"><?php print $pagina; ?></a></li>
                  <?php endif; ?>
              <?php endforeach; ?>
              <!-- mostra a página atual para o usuário -->
             <li class="disabled"><span><?php print $paginacao; ?></span></li>
              <!-- mostra até cinco página após a página atual -->
              <?php foreach( range($paginacao+1, $paginacao+5) as $pagina): ?>
                  <?php if ($pagina < $totaldepaginas): ?>
                  <li><a href="?paginacao=<?php print $pagina; ?>&filtrotipo=<?=$_REQUEST['filtrotipo']?>"><?php print $pagina; ?></a></li>
                  <?php endif; ?>
              <?php endforeach; ?>
              <!-- mostra os links para a próxima página
              e para a última página da lista -->
              <?php if ($paginacao == $totaldepaginas): ?>
                  <li class="active"><span>Pr&oacute;xima</span></li>
                  <li class="active"><span>&Uacute;ltima</span></li>
              <?php else: ?>
                  <li><a href="?paginacao=<?php print $paginacao+1; ?>&filtrotipo=<?=$_REQUEST['filtrotipo']?>"><span>Pr&oacute;xima</span></a></li>
                  <li><a href="?paginacao=<?php print $totaldepaginas;?>&filtrotipo=<?=$_REQUEST['filtrotipo']?>"><span>&Uacute;ltima</span></a></li>
              <?php endif; ?>
          </ul>
          <!-- FIM DA PAGINACAO -->
          <div class="pull-left text-pagination">
            <?php echo "Mostrando $paginacao de $totaldepaginas com $totalresultado registros.";?>
          </div>
          <?php } ?> 

          </div>
        </form>
    </div><!-- END LIST TABLE -->

  </div><!-- END PANEL -->  

</div><!-- END CONTENT -->

<script src="<?=PORTAL_URL?>ajax/usuario/lista.js"></script>
<?php  include('template/footer.php'); ?>
$(document).ready(function () {

  /* busca de médico */
  $('.selectpicker').selectpicker();

  /* mascaras */
  $('#paciente_datanascimento').inputmask("99/99/9999");
  $('#paciente_cpf').inputmask("999.999.999-99");
  $('#paciente_celular').inputmask("(99)9999-9999[9]");
  $('#paciente_telefone').inputmask("(99)9999-9999[9]");
  $('#paciente_cep').inputmask("99999-999");

  /* variaveis */
  var $optionsubmit = null;

  /* enviar formulário */
  $("#createform").validate({
      rules: {
        paciente_nome: { required: true, minlength: 5 },
        paciente_celular: {required: true}
      },
      messages: {
        paciente_nome: { required: "O nome do paciente é obrigatório", minlength:"Informe no mínimo 5 caracteres" },
        paciente_celular:  "O celular é obrigatório"
      },
      //função para enviar após a validação
      submitHandler: function( form ){

        $('#return-feedback').hide();
        $('#msg-feedback').html('');
        $('.preload-submit').show();
        projetouniversal.util.getjson({
          url : PORTAL_URL+"php/paciente/salvar-paciente",
          data : $(form).serialize(),
          success : onSuccessSend,
          error : onError
        });
        function onSuccessSend(obj){
          switch( $optionsubmit ){
            case '_save':
              /* salvar e voltar para listagem */
              if( obj.msg == 'success' ){
                $('.preload-submit').hide();
                //enviar paramentros a url de listagem com mensagem
                postToURL(PORTAL_URL+'view/paciente/index', {id: obj.id, feedback: 'Cadastro efetuado com sucesso', type: 'success'});
              }else if( obj.msg == 'error' ){
                $('.preload-submit').hide();
                postToURL(PORTAL_URL+'view/paciente/index', {error: obj.error, feedback: 'Ocorreu um erro ao realizar a operação', type: 'error'});
              }
            break;
            case '_continue':
              /* salvar e continuar na pagina */
              if( obj.msg == 'success' ){
                $('.preload-submit').hide();
                $('#return-feedback').show();
                $('#return-feedback').addClass('alert-success');
                $('#msg-feedback').html('<i class="glyphicon glyphicon-ok"></i> Cadastro efetuado com sucesso');
                $('#return-feedback').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
              }else if( obj.msg == 'error' ){
                $('#return-feedback').show();
                $('#return-feedback').addClass('alert-danger');
                $('#msg-feedback').html('<i class="glyphicon glyphicon-remove"></i> Ocorreu um erro ao realizar a operação');
                $('#return-feedback').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
              }
            break;
            case '_addanother':
              /* salvar e adicionar outro */
              if( obj.msg == 'success' ){
                $('.preload-submit').hide();
                $('#return-feedback').show();
                $('#return-feedback').addClass('alert-success');
                $('#msg-feedback').html('<i class="glyphicon glyphicon-ok"></i> Cadastro efetuado com sucesso');
                $('#return-feedback').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
                $('#createform').parent().find('input:text, input:password, input:file, select, textarea').val('');
                $('#createform').parent().find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
                $('#paciente_nome').focus();
                $('#paciente_status').prop("checked", true);
                $('#paciente_sms').prop("checked", true);
                $('#deletaritem').hide();
                $('#idpaciente').val('');
              }else if( obj.msg == 'error' ){
                $('#return-feedback').show();
                $('#return-feedback').addClass('alert-danger');
                $('#msg-feedback').html('<i class="glyphicon glyphicon-remove"></i> Ocorreu um erro ao realizar a operação');
                $('#return-feedback').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
              }
            break;
          }
        }//end function
        return false;
      }
  });

  /* enviar e editar */
  $('button#enviarformulario').livequery( "click", function(){
    $optionsubmit = $(this).val();
  });

  /* enviar e editar */
  $('button#enviareditar').livequery( "click", function(){
    $optionsubmit = $(this).val();
  });

  /* enviar e adicionar outro */
  $('button#enviaradicionar').livequery( "click", function(){
    $optionsubmit = $(this).val();
  });

  /* deletar item */
  //deletar item selecionado
  $('button#deletaritem').livequery( "click", function(){
    bootbox.dialog({
      title: "Confirmação",
      message: "Você deseja realmente excluir o paciente selecionado?",
      buttons: {
        main: {
          label: "Não",
          className: "btn-default"
        },
        danger: {
          label: "Sim",
          className: "btn-danger",
          callback: function() {
            $('body').modalmanager('loading');
            $idpaciente = $('#idpaciente').val(); 
            if( $idpaciente != '' || $idpaciente != null ){
              projetouniversal.util.getjson({
                url : PORTAL_URL+"php/paciente/deletar-paciente",
                data : {id: $idpaciente},
                success : onSuccessDelete,
                error : onError
              });
            }//end if
            function onSuccessDelete(obj){
              if( obj.msg == 'success' ){
                postToURL(PORTAL_URL+'view/paciente/index', {feedback: 'As informações foram deletadas com sucesso', type: 'success'});
              }else if( obj.msg == 'error' ){
                postToURL(PORTAL_URL+'view/paciente/index', {error: obj.error, feedback: '', type: 'error'});
              }
            }
          }
        }
      }
    });
    return false; 
  });

  /* buscar CEP */
  $('input#paciente_cep').livequery( "blur", function(){
    var $cep = $(this).val();
    $cep = $cep.replace(/\D/g,"");
    var $url="http://cep.correiocontrol.com.br/"+$cep+".json";
    $('#preload-cep').show();
    $.getJSON( $url, function(resultado) {
      $('#paciente_logradouro').val(resultado.logradouro);
      $('#paciente_bairro').val(resultado.bairro);
      $('#paciente_cidade').val(resultado.localidade);
      $('select#paciente_estado').find('option').each(function() {
          if( $(this).attr('rel') ==  resultado.uf){
            $(this).attr('selected', true);
            $('select#paciente_estado').selectpicker('val', $(this).val());
          }
      });
      $('select#paciente_pais').selectpicker('val', '30');
      $('#preload-cep').hide();
    }).fail(function (resultado) {
      $('#preload-cep').hide();
      if( resultado.status == 0 ){
        $('#return-status').addClass('alert-danger');
        $('#return-status').html('<i class="glyphicon glyphicon-remove"></i> Houve um problema de conexão com servidor, por favor verifique sua conexão de internet');  
        $('#return-status').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
      }else if( resultado.status == 200){
        $('#return-status').addClass('alert-danger');
        $('#return-status').html('<i class="glyphicon glyphicon-remove"></i> O CEP informado não está em nossa base de dados, por favor verifique');
        $('#return-status').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
      }
    });
  });

  /* erro do envio ajax */
  function onError(args) {
    console.log( 'onError: ' + args );
  }
  
});



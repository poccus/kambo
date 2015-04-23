$(document).ready(function () {

  /* busca de médico */
  $('.selectpicker').selectpicker();

  /* mascaras */
  $('#profissional_telefone').inputmask("(99)9999-9999[9]");
  $('#tempo_consulta').inputmask('decimal', { rightAlign: false });
  $('#horainicio_atendimento').inputmask("99:99");
  $('#horafinal_atendimento').inputmask("99:99");
  $('#horainicioalmoco_atendimento').inputmask("99:99");
  $('#horafinalalmoco_atendimento').inputmask("99:99");

  /* variaveis */
  var $optionsubmit = null;

  /* enviar formulário */
  $("#createform").validate({
      rules: {
        profissional_nome: { required: true, minlength: 5 },
        profissional_crm: {required: true},
        horainicio_atendimento: {required: true},
        horafinal_atendimento: {required: true}
      },
      messages: {
        profissional_nome: { required: "O nome do profissional é obrigatório", minlength:"Informe no mínimo 5 caracteres" },
        profissional_crm:  "O registro é obrigatório",
        horainicio_atendimento:   "O Campo é obrigatório",
        horafinal_atendimento:   "O Campo é obrigatório"
      },
      //função para enviar após a validação
      submitHandler: function( form ){

        $('#return-feedback').hide();
        $('#msg-feedback').html('');
        $('.preload-submit').show();
        projetouniversal.util.getjson({
          url : PORTAL_URL+"php/profissional/salvar-profissional",
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
                postToURL(PORTAL_URL+'view/profissional/index', {id: obj.id, feedback: 'Cadastro efetuado com sucesso', type: 'success'});
              }else if( obj.msg == 'error' ){
                $('.preload-submit').hide();
                postToURL(PORTAL_URL+'view/profissional/index', {error: obj.error, feedback: 'Ocorreu um erro ao realizar a operação', type: 'error'});
              }
            break;
            case '_continue':
              /* salvar e continuar na pagina */
              if( obj.msg == 'success' ){
                $('.preload-submit').hide();
                $('#return-feedback').show();
                $('#return-feedback').addClass('alert-success');
                $('#msg-feedback').html('<span class="glyphicon glyphicon-ok"></span> Cadastro efetuado com sucesso');
                $('#return-feedback').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
              }else if( obj.msg == 'error' ){
                $('#return-feedback').show();
                $('#return-feedback').addClass('alert-danger');
                $('#msg-feedback').html('<span class="glyphicon glyphicon-remove"></span> Ocorreu um erro ao realizar a operação');
                $('#return-feedback').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
              }
            break;
            case '_addanother':
              /* salvar e adicionar outro */
              if( obj.msg == 'success' ){
                $('.preload-submit').hide();
                $('#return-feedback').show();
                $('#return-feedback').addClass('alert-success');
                $('#msg-feedback').html('<span class="glyphicon glyphicon-ok"></span> Cadastro efetuado com sucesso');
                $('#return-feedback').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
                $('#createform').parent().find('input:text, input:password, input:file, select, textarea').val('');
                $('#createform').parent().find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
                $('#profissional_nome').focus();
                $('#idprofissional').val('');
                $('#deletaritem').hide();
              }else if( obj.msg == 'error' ){
                $('#return-feedback').show();
                $('#return-feedback').addClass('alert-danger');
                $('#msg-feedback').html('<span class="glyphicon glyphicon-remove"></span> Ocorreu um erro ao realizar a operação');
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
      message: "Você deseja realmente excluir o médico selecionado?",
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
            $idprofissional = $('#idprofissional').val(); 
            if( $idprofissional != '' || $idprofissional != null ){
              projetouniversal.util.getjson({
                url : PORTAL_URL+"php/profissional/deletar-profissional",
                data : {id: $idprofissional},
                success : onSuccessDelete,
                error : onError
              });
            }//end if
            function onSuccessDelete(obj){
              if( obj.msg == 'success' ){
                postToURL(PORTAL_URL+'view/profissional/index', {feedback: 'As informações foram deletadas com sucesso', type: 'success'});
              }else if( obj.msg == 'error' ){
                postToURL(PORTAL_URL+'view/profissional/index', {error: obj.error, feedback: '', type: 'error'});
              }
            }
          }
        }
      }
    });
    return false; 
  });


  /* erro do envio ajax */
  function onError(args) {
    console.log( 'onError: ' + args );
  }
  
});





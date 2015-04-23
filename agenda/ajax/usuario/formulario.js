$(document).ready(function(){

	/* mascaras */
    $('#usuario_celular').inputmask("(99)9999-9999[9]");

    /* variaveis */
  	var $optionsubmit = null;

	$('#createform').validate({
		//regras e mensagens para os campos
		rules: {
			usuario_nome: { required: true, minlength: 5 },
			usuario_login: { required: true, minlength: 5 },
			usuario_email: { required: true, email: true },
            usuario_senha: { required: true, minlength: 6 },
            usuario_confirmasenha: { required: true, equalTo: "#usuario_senha" },
            usuario_perfil: { required: true }
        },
		messages: {
			usuario_nome: { required: 'Preencha o campo nome', minlength: 'No mínimo 5 letras' },
			usuario_login: { required: 'Preencha o campo login', minlength: 'No mínimo 5 caracteres' },
			usuario_email: { required: "O campo email é obrigatório.", email: "O campo e-mail deve conter um email válido." },
			usuario_senha: { required: "O campo senha é obrigatório.", minlength: 'Informe uma senha de mínimo 6 caracteres, incluindo números e letras e caracteres especiais' },
            usuario_confirmasenha: { required: "O campo confirmação de senha é obrigatório.", equalTo: "O campo confirmação de senha deve ser identico ao campo senha." },
            usuario_perfil: { required: "Selecione o perfil de acesso." } 
		},
		//função para enviar após a validação
		submitHandler: function( form ){

			$('#return-feedback').hide();
        	$('#msg-feedback').html('');
        	$('.preload-submit').show();

            var options = { 
                data: $('#createform').serialize(),
                type: 'post',
                url: PORTAL_URL+'php/usuario/salvar-usuario',
                complete: function(obj){
	                //converter o resultado em JSON
            		obj = JSON.parse( obj.responseText );
	    			switch( $optionsubmit ){
			            case '_save':
			              /* salvar e voltar para listagem */
			              if( obj.msg == 'success' ){
			                $('.preload-submit').hide();
			                //enviar paramentros a url de listagem com mensagem
			                postToURL(PORTAL_URL+'view/usuario/index', {id: obj.id, feedback: 'Cadastro efetuado com sucesso', type: 'success'});
			              }else if( obj.msg == 'error' ){
			                $('.preload-submit').hide();
			                postToURL(PORTAL_URL+'view/usuario/index', {error: obj.error, feedback: 'Ocorreu um erro ao realizar a operação', type: 'error'});
			              }else if( obj.msg = 'upload'){
			              	$('.preload-submit').hide();
			              	$('#return-status').addClass('alert-danger');
					        $('#return-status').html('<i class="glyphicon glyphicon-remove"></i> '+obj.error);  
					        $('#return-status').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
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
			              }else if( obj.msg = 'upload'){
			              	$('.preload-submit').hide();
			              	$('#return-status').addClass('alert-danger');
					        $('#return-status').html('<i class="glyphicon glyphicon-remove"></i> '+obj.error);  
					        $('#return-status').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
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
			                $('#usuario_nome').focus();
			                $('#usuario_liberado').prop("checked", true);
                			$('#usuario_receberemail').prop("checked", true);
                			$('#usuario_senha').removeAttr('disabled');
                			$('#usuario_confirmasenha').removeAttr('disabled');
			                $('#idusuario').val('');
			                $('#deletaritem').hide();
			                $('#addperfilprofissional').hide();
			              }else if( obj.msg == 'error' ){
			                $('#return-feedback').show();
			                $('#return-feedback').addClass('alert-danger');
			                $('#msg-feedback').html('<span class="glyphicon glyphicon-remove"></span> Ocorreu um erro ao realizar a operação');
			                $('#return-feedback').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
			              }else if( obj.msg = 'upload'){
			              	$('.preload-submit').hide();
			              	$('#return-status').addClass('alert-danger');
					        $('#return-status').html('<i class="glyphicon glyphicon-remove"></i> '+obj.error);  
					        $('#return-status').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
			              }
			            break;
			          }//end switch case
                },
                error: function(response){
                    $('#submit-btn').show();
                    $('#submit-loading').hide();
                    $('#alerta-retorno').addClass('alert-danger');
                    $('#alerta-retorno').show();
                    $('#mensagem-retorno').html(response.responseText);
                    //LIMPAR FORMULARIO
                    $('#cadastro_form').resetForm();
                    $('html, body').animate({scrollTop:0}, 'slow');
                }
            };

	    	//ENVIAR DADOS VIA AJAX
	    	$('#createform').ajaxSubmit(options);
	
	    	return false;
		}

	});

	/* verificar o login e email do usuário para não ter duplicidade */
    $( "#usuario_login, #usuario_email" ).blur(function() {
		var login			= $(this).val();
		var inputtype		= $(this).attr('rel');
		var queryString   	= {login:login};

		if( inputtype == 'login' ){ $('#preload-login').show(); }else{ $('#preload-email').show(); }
		projetouniversal.util.getjson({
			url : PORTAL_URL+"php/usuario/verificar-existencia-usuario",
			data : queryString,
			success : onValidarLogin,
			error : onError
		});
		function onValidarLogin(obj){
			if( inputtype == 'login' ){ $('#preload-login').hide(); }else{ $('#preload-email').hide(); }
			if( obj.msg == 'success' ){
				if( obj.loginexistente >= 1 ){
					$('#return-status').addClass('alert-danger');
			        $('#return-status').html('<i class="glyphicon glyphicon-remove"></i> Por favor utilize outro login ou e-mail, pois já está em uso.');  
			        $('#return-status').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
	            	$('#usuario_login').focus();
				}
			}else if( obj.msg == 'error' ){
            	$('#return-status').addClass('alert-danger');
		        $('#return-status').html('<i class="glyphicon glyphicon-remove"></i> Erro grave ao tentar verificar a existência do login. Erro de referência:  ' + obj.msg_error);  
		        $('#return-status').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );	
            }
		}
        return false;
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

	/* habilitar inserção dos campos que o perfil de recepcionista ter acesso ao profissionais */
	$('#usuario_perfil').change(function() {

		if( $(this).val() == 2 ){
			// mostrar
			$('#addperfilprofissional').slideDown( 100 );
			// ocultar
			$('div#perfilrecepcionista').each(function(index, item){ $(item).hide(); });
			$('#perfilrecepcionista').slideUp( 100 );
		}else{
			if( $('div#perfilrecepcionista').length >= 1 ){
				$('div#perfilrecepcionista').each(function(index, item){ $(item).show(); });
			}
			$('div#perfilrecepcionista:last').hide();
			// ocultar
			$('#addperfilprofissional').slideUp( 100 );
		}

		if ( $(this).val() == 3 ){
			// mostrar
			$('#addperfilrecepcionista').slideDown( 100 );
		}else{
			// ocultar
			$('#addperfilrecepcionista').slideUp( 100 );
		}

		if( $(this).val() == 1 ){
			$('#addperfilprofissional').hide();
			$('div#perfilrecepcionista').each(function(index, item){ $(item).hide(); });
		}

	});

	/* adicionar item de profissional ao perfil de recepcionista */
	$('a#add-item-perfil-rcp').click( function(event) {
		var $profissional 		= $('#usuario_profissional').val();
		var $nameprofissional 	= $("#usuario_profissional option:selected").text();
		// validando o campo
		if( $profissional == '' ) {
			$('#return-status').addClass('alert-danger');
          	$('#return-status').html('<i class="glyphicon glyphicon-info-sign"></i> Por favor selecione um Profissional');  
          	$('#return-status').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
		}else{
			// remove item selecionado da input select
			$('#usuario_profissional').find('option').each(function(){
	          if( $(this).attr('value')  == $profissional ){
	            $(this).attr('disabled','disabled');
	          }
	        });
			// funcao para adicionar itens
			additemperfilrecepcionista( $profissional, $nameprofissional );
		}
	});

	/* editar item de profissional do perfil de recepcionista */
	$('a#edit-item-perfil-rcp').click( function(event) {
		var $listaprofissional =  $('#usuario_profissional').clone(true);
		var $profissional 	   =  $(this).parent().parent().find('div:nth-child(3) input#usuario_profissional_id').val();
		//limpar lista após o input hidden a lista
		$(this).parent().parent().find('div:nth-child(3) input#usuario_profissional_id').nextAll().remove();

		//liberar o profissional da lista
		$listaprofissional.find('option').each(function(){
          if( $(this).attr('value')  == $profissional ){
            $(this).removeAttr('disabled');
            $(this).attr('selected','selected');
          }
        });
		$(this).parent().parent().find('div:nth-child(3) label#nameprofissional').slideUp( 100 );
		$(this).parent().parent().find('div:nth-child(3) label#nameprofissional').html('');
		//adiciona a lista após o input hidden
		$(this).parent().parent().find('div:nth-child(3) input#usuario_profissional_id').after($listaprofissional);
		//habilitar o salvar
		$(this).parent().parent().find('div:nth-child(4) a#save-item-perfil-rcp').show();
		$(this).parent().parent().find('div:nth-child(4) a#edit-item-perfil-rcp').hide();
	});

	/* salvar edicao do item após selecionar o profissional */
	$('a#save-item-perfil-rcp').click( function(event) {
		var $profissionalprevious 	=  $(this).parent().parent().find('div:nth-child(3) input#usuario_profissional_id').val();
		var $profissional 	   		=  $(this).parent().parent().find('div:nth-child(3) input#usuario_profissional_id').val( $(this).parent().parent().find('div:nth-child(3) #usuario_profissional').val() );
		$(this).parent().parent().find('div:nth-child(3) label#nameprofissional').html('');
		$(this).parent().parent().find('div:nth-child(3) label#nameprofissional').html( $(this).parent().parent().find('div:nth-child(3) #usuario_profissional option:selected').text() );
		$(this).parent().parent().find('div:nth-child(3) label#nameprofissional').show();
		$(this).parent().parent().find('div:nth-child(3) #usuario_profissional').slideUp( 100 );
		//limpar lista após o input hidden a lista
		$(this).parent().parent().find('div:nth-child(3) input#usuario_profissional_id').nextAll().remove();
		// habiltar o editar e ocultar o salvar
		$(this).parent().parent().find('div:nth-child(4) a#save-item-perfil-rcp').hide();
		$(this).parent().parent().find('div:nth-child(4) a#edit-item-perfil-rcp').show();

		//liberar valor anterior e bloquear o novo valor na lista de profissional
		$profissional = $(this).parent().parent().find('div:nth-child(3) input#usuario_profissional_id').val();
		$('#usuario_profissional').find('option').each(function(){
          if( $(this).attr('value')  == $profissionalprevious ){
            $(this).removeAttr('disabled');
          }
          if( $(this).attr('value')  == $profissional  ){
            $(this).attr('disabled','disabled');
          }
        });

	});

	/* deletar item de profissional do perfil de recepcionista */
	$('a#delete-item-perfil-rcp').click( function(event) {
		var $profissional = $(this).parent().parent().find('div:nth-child(3) input#usuario_profissional_id').val();
		//liberar valor na lista de profissional
		$('#usuario_profissional').find('option').each(function(){
          if( $(this).attr('value')  == $profissional ){
            $(this).removeAttr('disabled');
          }
        });
		$(this).parent().parent().remove();
	});

	/* acao para marcar todos os modulos de acesso de uma vez só */
    $('#marcartodossubmodulos').click( function(event) {
        if(this.checked) { // check select status
            $('input[name="submodulos[]"]').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"              
            });
            $( 'input[name="submodulos_acao[]"]' ).each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"              
            });
        }else{
            $('input[name="submodulos[]"]').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                      
            });
            $( 'input[name="submodulos_acao[]"]' ).each(function() { //loop through each checkbox
                this.checked = false;  //select all checkboxes with class "checkbox1"              
            });  
        }
    });

	/* acao para marcar as ações do modulo */
	$('input#father-submodulo-acao').livequery('click', function(){
		if(this.checked) { // check select status
            $( $(this).parent().parent().find('input[name="submodulos_acao[]"]') ).each(function() { 
                this.checked = true;           
            });
        }else{
            $( $(this).parent().parent().find('input[name="submodulos_acao[]"]') ).each(function() { 
                this.checked = false;                    
            });  
        }
	});

	/* acao para marcar o modulo pai do modulo selecionado */
	$('input#sun-submodulo_acao').livequery('change', function(){
		$i = $(this).parent().parent().find('input[name="submodulos_acao[]"]:checked').length;
		if(this.checked) { 
			if( $i >= 1 ){
				$(this).parent().parent().parent().find('input#father-submodulo-acao').prop('checked', true);
			}
			$( $(this).parent().parent().find('input[name="submodulos_acao[]"]:checked') ).each(function() { //loop through each checkbox
                $itemvalue = $(this).val()
                $itemvalue = $itemvalue.split(',');
                // console.log( $itemvalue[0] );
                if( $itemvalue[0] >= 2 &&  $itemvalue[0] <= 4 ){
                	$(this).parent().parent().find('input[name="submodulos_acao[]"]:first').prop('checked', true);
                }                     
            }); 
        }else{
        	if( $i == 0 ){
        		$(this).parent().parent().parent().find('input#father-submodulo-acao').prop('checked', false);
        	}
        }
	});

	/* funcao para adicionar item de profissional */
	function additemperfilrecepcionista( profissional, nameprofissional ){
		var $itemclone 				= $('#perfilrecepcionista').clone(true);
		
		$itemclone.find('div:nth-child(3) label#nameprofissional').html(nameprofissional);
		$itemclone.find('div:nth-child(3) input#usuario_profissional_id').val(profissional);
		$itemclone.show();

		if( profissional != null ){
			$('#addperfilrecepcionista').after( $itemclone );
		}//end if
	}

	/* deletar item */
  	//deletar item selecionado
	$('button#deletaritem').livequery( "click", function(){
	    bootbox.dialog({
	      title: "Confirmação",
	      message: "Você deseja realmente excluir o usuário selecionado?",
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
	            $idusuario = $('#idusuario').val(); 
	            if( $idusuario != '' || $idusuario != null ){
	              projetouniversal.util.getjson({
	                url : PORTAL_URL+"php/usuario/deletar-usuario",
	                data : {id: $idusuario},
	                success : onSuccessDelete,
	                error : onError
	              });
	            }//end if
	            function onSuccessDelete(obj){
	              if( obj.msg == 'success' ){
	                postToURL(PORTAL_URL+'view/usuario/index', {feedback: 'As informações foram deletadas com sucesso', type: 'success'});
	              }else if( obj.msg == 'error' ){
	                postToURL(PORTAL_URL+'view/usuario/index', {error: obj.error, feedback: '', type: 'error'});
	              }
	            }
	          }
	        }
	      }
	    });
	    return false; 
	});

	//FUNCAO DE RETORNO DE ERRO DO AJAX
  	function onError(args) {
	  console.log( 'onError: ' + args.msg );
	}

});
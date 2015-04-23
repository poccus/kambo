$(document).ready(function(){

	//mostrar mensagem
	$('#return-feedback').slideDown( 300 ).delay( 5000 ).fadeOut( 800 );
	
	//VARIAVEL UNIVERSAL
	var idUnidade = 0;
	var itemArray = [];
	var queryString = '';
	var idnovasenha = 0;
	
	// PARTE DE DELETA(INATIVAR) CATEGORIA
	$('a#operacaoDangerUnidade').livequery( "click", function(){
		//VERIFICAR SE EXISTE ALGUM ITEM SELECIONADO PARA EXCLUIÇÃO OU INATIVAÇÃO MULTIPLAS
		var count = 0;
        $('input[name="itemselecionado[]"]').each(function() { 
            if(this.checked == true){
            	itemArray.push( $(this).val() );
                count++;
            }             
        });
        if(count > 0){
        	if(count == 1){
        		$('#nomeUnidade').html('Para '+count+' item selecionado');
        	}else{
        		$('#nomeUnidade').html('Para os '+count+' itens selecionados');
        	}
        	//PASSANDO O PARAMENTRO PARA EXCLUIR
        	queryString = $('#form-table').formSerialize();
        }else{
        	//PASSANDO O PARAMENTRO PARA EXCLUIR
        	if( $('#id').val() != '' ){
        		idUnidade = $('#id').val();
        		var nomeUnidade = $('#nome').val();
        	}else{
	        	idUnidade = $(this).attr('rel');
	        	var nomeUnidade = $(this).attr('data-rel');
	        }

        	queryString = {id:idUnidade}

			
			$('#nomeUnidade').html(nomeUnidade);
			$('#idUnidade').val(idUnidade);

        }  
		$('#myModalRemove').modal('show');
		return false;
	});
	//------------------------------------------------------------


	// FUNCAO PARA DELETAR
	$('#submit-btn-deletar').livequery( "click", function(){
		// AJAX DE DELETAR
        $.ajax({
				type: "POST",
				url: PORTAL_URL+"usuario/php/deletar-usuario",
				data: queryString,
				cache: false,
				success: function(retorno){
				    $('#alerta-retorno').addClass('alert-success');
                    $('#alerta-retorno').show();
                    $('#mensagem-retorno').html(retorno);
                    // EXCLUIR OS ITENS SELECIONADO DA TABELA
					if( itemArray.length > 0 ){
					   for (i = 0; i < itemArray.length; i++) {
					   		$("#tr-id-"+itemArray[i]).fadeOut('slow', function(){}).remove();
					   }
					}else{   
                    	$("#tr-id-"+idUnidade).fadeOut('slow', function(){}).remove();
                	}
                	// LIMPAR A LISTA DO ARRAY
					itemArray = [];	
                    $('#myModalRemove').modal('hide');
                    $('.modal-backdrop').remove();

                    //URL
					var pathname = [ PORTAL_URL+'usuario/index.php' , PORTAL_URL+'usuario/index' ];
					$('#container-dashboard').load(pathname[0]);
					//MODIFICAR A URL DO BROWSER
		            history.pushState({}, '', pathname[1]);

				},
				beforeSend: function(){
     				//$('#submit-btn-deletar').hide();
                    $('.notificacao-loading').show();
                    $('#alerta-retorno').hide();
                    $('#mensagem-retorno').html('');
   				},
			    complete: function(){
                    $('#submit-btn-deletar').show();
                    $('.notificacao-loading').hide();
			    }
		});	
        return false;        
	});
	//------------------------------------------------------------


	// FUNCAO PARA INATIVAR CATEGORIA
	$('#submit-btn-inativar').livequery( "click", function(){
		// AJAX DE INATIVAR CATEGORIA
        $.ajax({
				type: "POST",
				url: PORTAL_URL+"usuario/php/inativar-usuario",
				data: queryString,
				cache: false,
				success: function(retorno){
				    $('#alerta-retorno').addClass('alert-success');
                    $('#alerta-retorno').show();
                    $('#mensagem-retorno').html(retorno);
                    // INATIVAR OS ITENS SELECIONADO DA TABELA
					if( itemArray.length > 0 ){
					   for (i = 0; i < itemArray.length; i++) {
					   		$("tbody #status-unidade-"+itemArray[i]).addClass('label-warning');
					   		$("tbody #status-unidade-"+itemArray[i]).html('Inativo');
					   }
					}else{   
                    	$("tbody #status-unidade-"+idUnidade).addClass('label-warning');
                    	$("tbody #status-unidade-"+idUnidade).html('Inativo');
                	}
                	// LIMPAR A LISTA DO ARRAY
					itemArray = [];
					//LIMPAR OS CHECKBOX
					clearcheckebox();
                    $('#myModalRemove').modal('hide');
				},
				beforeSend: function(){
     				//$('#submit-btn-inativar').hide();
                    $('.notificacao-loading').show();
                    $('#alerta-retorno').hide();
                    $('#mensagem-retorno').html('');
   				},
			    complete: function(){
                    //$('#submit-btn-inativar').show();
                    $('.notificacao-loading').hide();
			    }
		});	
        return false;        
	});
	//------------------------------------------------------------

	// PARTE DE (ATIVAR)
	$('a#operacaoSuccessUnidade').livequery( "click", function(){
		//VERIFICAR SE EXISTE ALGUM ITEM SELECIONADO PARA ATIVAÇÃO MULTIPLAS
		var count = 0;
        $('input[name="itemselecionado[]"]').each(function() { 
            if(this.checked == true){
            	itemArray.push( $(this).val() );
                count++;
            }             
        });
        // PEGAR DADOS SELECIONADOS
        queryString = $('#form-table').formSerialize();
        // AJAX DE ATIVAR
        $.ajax({
				type: "POST",
				url: PORTAL_URL+"usuario/php/ativar-usuario",
				data: queryString,
				cache: false,
				success: function(retorno){
				    $('#alerta-retorno').addClass('alert-success');
                    $('#alerta-retorno').show();
                    $('#mensagem-retorno').html(retorno);
                    //ATIVAR OS ITENS SELECIONADO DA TABELA
					if( itemArray.length > 0 ){
					   for (i = 0; i < itemArray.length; i++) {
					   		$("tbody #status-unidade-"+itemArray[i]).removeClass();
					   		$("tbody #status-unidade-"+itemArray[i]).addClass('label label-success pull-left');
					   		$("tbody #status-unidade-"+itemArray[i]).html('Ativo');
					   }
					}
                	// LIMPAR A LISTA DO ARRAY
					itemArray = [];
					//LIMPAR OS CHECKBOX
					clearcheckebox();
                    $('#myModalRemove').modal('hide');
				},
				beforeSend: function(){
     				//$('#submit-btn-inativar').hide();
                    $('.notificacao-loading').show();
                    $('#alerta-retorno').hide();
                    $('#mensagem-retorno').html('');
   				},
			    complete: function(){
                    //$('#submit-btn-inativar').show();
                    $('.notificacao-loading').hide();
			    }
		});
		return false;
	});
	//------------------------------------------------------------


	$('a#habilitaEditarCadastro').livequery( "click", function(){
		$('#nome').removeAttr('readonly');
		$('#foto').attr("disabled", false);
		$('#login').removeAttr('readonly');
		$('#email').removeAttr('readonly');
		$('#celular').removeAttr('readonly');
		$('input[name=liberado]').attr("disabled",false);
		$('#acesso').removeAttr('readonly');
		$('#marcartodossubmodulos').attr('disabled',false);
	  	$('input[name="modulos[]"]').attr('disabled',false);
	    $('input[name="submodulos[]"]').attr('disabled',false);
	    $('input[name="submodulos_acao[]"]').attr('disabled',false);
	    $('input[name="receberemail"]').attr('disabled',false);
	    $('#submit-btn').attr('disabled',false);
	    return false;
	});
	//---------------------------------------------------------------------------


	//funcao para habilitar a redefinição de senha do usuário
	$('a#habilitarRedefinirSenha').livequery('click', function(){
		defaultModal.util.openmodal({
			open: {show: true, backdrop: true, keyboard: false},
			title: 'Redefinir senha do usuário',
			loadurl: true,
			container: PORTAL_URL+'usuario/redefinir-senha',
			submitlabel: 'Redefinir senha',
			submitoptions : 'btn-success',
			submitnewid: 'default-modal-submit-redefinir-senha'
		});
		//atribuindo o id do usuario para editar a senha do mesmo
		idnovasenha = $('#id').val();
		return false;
	});

	//funcao para visualizar o histórico de acesso do usuário
	$('a#historicousuario').livequery('click', function(){
		defaultModal.util.openmodal({
			open: {show: true, backdrop: true, keyboard: false},
			title: 'Histórico do usuário',
			loadurl: true,
			container: PORTAL_URL+'usuario/usuario-historico/'+$('#id').val(),
			submitlabel: 'Impressão completa',
			submitoptions : 'btn-success',
			submitnewid: 'default-modal-submit-historico-usuario'
		});
		return false;
	});

	$('a#filtrar-historico-usuario').livequery('click', function(){
		var datainicio = $('#datainicio').val();
		var datafinal  = $('#datafinal').val();
		console.log(datainicio);
		if( datainicio == '' || datafinal == '' ){

		}else{
			$('#default-modal .modal-body #default-modal-container').html('');
			$('#default-modal .modal-body #default-modal-container').load(PORTAL_URL+'usuario/usuario-historico/'+$('#id').val()+'?datainicio='+datainicio+'&datafinal='+datafinal);
		}
		return false;
	});

	$('a#paginacao-historico-usuario').livequery('click', function(){
		var pagina = $(this).attr('href');
		$('#default-modal .modal-body #default-modal-container').html('');
		$('#default-modal .modal-body #default-modal-container').load(PORTAL_URL+'usuario/usuario-historico/'+$('#id').val()+pagina);
		return false;
	});

	$('#default-modal-submit-historico-usuario').livequery('click', function(){
		window.open(PORTAL_URL+'usuario/imprimir-historico-usuario/'+$('#id').val(), '_blank');
		return false;
	});



	$('#default-modal-submit-redefinir-senha').livequery('click', function(){
		var data = $('#formRedefinirSenha').formSerialize();
		var dataAdicional = '&idusuario='+idnovasenha;

		//limpar aviso dos campos
		$('#senhaNova').removeClass('error');
      	$('#senhaNova').nextAll().remove();
		$('#senhaNovaRepita').removeClass('error');
      	$('#senhaNovaRepita').nextAll().remove();

		if( $('#senhaNova').val() == ''  ||  $('#senhaNovaRepita').val() == '' ){
			$('#senhaNova').addClass('error');
          	$('#senhaNova').after( '<label for="senhaNova" class="error">O campo não pode ser vazio</label>' );
          	$('#senhaNova').focus();
			$('#senhaNovaRepita').addClass('error');
          	$('#senhaNovaRepita').after( '<label for="senhaNova" class="error">O campo não pode ser vazio</label>' );
		}else if( $('#senhaNova').val() !=  $('#senhaNovaRepita').val() ){
			$('#senhaNovaRepita').addClass('error');
          	$('#senhaNovaRepita').after( '<label for="senhaNova" class="error">Senha diferentes, por favor repita a senha corretamente</label>' );
          	$('#senhaNovaRepita').focus();
		}else{
			$('#default-modal-loading').show();
			$('#default-modal-submit-redefinir-senha').attr('disabled', true);
			$('#default-modal-cancelar').attr('disabled', true);
			data = data + dataAdicional;
			projetouniversal.util.getjson({
				url : PORTAL_URL+"usuario/php/redefinir-senha",
				method : 'GET',
				contentType : "application/json",
				data : data,
				success : onSuccessRedefinirSenha,
				error : onError
			});
		}
		return false;
	});
	function onSuccessRedefinirSenha(obj){
		$('#default-modal-loading').hide();
		defaultModal.util.closemodal();
		if(obj.msg == 'error'){
        	$('#alerta-retorno').addClass('alert-danger');
        	$('#alerta-retorno').show();
        	$('#mensagem-retorno').html('Error ao tentar efetuar o cadastro.');
        }else if(obj.msg == 'success' ){
        	$('#alerta-retorno').addClass('alert-success');
        	$('#alerta-retorno').show();
        	$('#mensagem-retorno').html(obj.msg_success);
        }
        $('#default-modal-submit-redefinir-senha').attr('disabled', false);
			$('#default-modal-cancelar').attr('disabled', false);
	}
	//---------------------------------------------------------------------------


	function clearcheckebox(){
		$('input[name="itemselecionado[]"]').each(function() { 
            this.checked = false;             
        });
        //OCULTAR O BOTÃO DA OPERAÇÃO DE INATIVAR E EXCLUIR
        $('#operacao-excluir-selecionado').hide();
	}


	$('input#father-submodulo-acao').livequery('click', function(){
		if(this.checked) { // check select status
            $( $(this).parent().parent().find('input[name="submodulos_acao[]"]') ).each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"              
            });
        }else{
            $( $(this).parent().parent().find('input[name="submodulos_acao[]"]') ).each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                      
            });  
        }
	});

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
	

	//FUNCAO DE RETORNO DE ERRO DO AJAX
  	function onError(args) {
	  console.log( 'onError: ' + args );
	}

});	
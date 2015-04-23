$(document).ready(function(){

	//VARIAVEL UNIVERSAL
	var idUnidade = 0;
	var itemArray = [];
	var queryString = '';


	// PARTE DE RESETAR SENHA USUARIO
	$('a#operacaoAlterarSenha').livequery( "click", function(){
		$('#myModalAlterarSenha').modal('show');
		return false;
	});
	//------------------------------------------------------------


	// PARTE DE DELETA(INATIVAR) USUARIO
	$('a#operacaoDangerUnidade').livequery( "click", function(){
		var acao = $(this).attr('rel');
		// console.log('DADOS: '+acao);
		if(acao == 'inativar'){
			$('#submit-btn-inativar').show();
			$('#submit-btn-deletar').hide();
			$('#submit-btn-resetar-senha').hide();
		}else if(acao == 'excluir'){
			$('#submit-btn-inativar').hide();
			$('#submit-btn-deletar').show();
			$('#submit-btn-resetar-senha').hide();
		}else if(acao == 'resetar_senha'){
			$('#submit-btn-inativar').hide();
			$('#submit-btn-deletar').hide();
			$('#submit-btn-resetar-senha').show();
		}
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
        	idUnidade = $(this).attr('rel');
        	queryString = {id:idUnidade}

			var nomeUnidade = $(this).attr('data-rel');
			$('#nomeUnidade').html(nomeUnidade);
			$('#idUnidade').val(idUnidade);
        }
		$('#myModalRemove').modal('show');
		return false;
	});
	//------------------------------------------------------------


	// FUNCAO PARA DELETAR USUARIO
	$('#submit-btn-deletar').livequery( "click", function(){
		// AJAX DE DELETAR USUARIO
        $.ajax({
				type: "POST",
				url: "../../php/paciente/deletar-paciente.php",
				data: queryString,
				cache: false,
				success: function(retorno){
					var result = retorno.split("//");
					// console.log(result[0]);
					if (result[0] == 1) {
                    	$('#alerta-retorno').removeClass('alert-warning');
					    $('#alerta-retorno').addClass('alert-success');
	                    $('#alerta-retorno').show();
	                    $('#mensagem-retorno').html(result[1]);
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
					}else{
                    	$('#alerta-retorno').removeClass('alert-success');
                    	$('#alerta-retorno').addClass('alert-warning');
	                    $('#alerta-retorno').show();
	                    $('#mensagem-retorno').html(result[1]);
						itemArray = [];	
					}
                    $('#myModalRemove').modal('hide');
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


	// FUNCAO PARA INATIVAR USUARIO
	$('#submit-btn-inativar').livequery( "click", function(){
		// AJAX DE INATIVAR USUARIO
        $.ajax({
				type: "POST",
				url: "../../php/paciente/paciente-inativar.php",
				data: queryString,
				cache: false,
				success: function(retorno){
				    $('#alerta-retorno').addClass('alert-success');
                    $('#alerta-retorno').show();
                    $('#mensagem-retorno').html(retorno);
                    // INATIVAR OS ITENS SELECIONADO DA TABELA
					if( itemArray.length > 0 ){
					   for (i = 0; i < itemArray.length; i++) {
					   		$("tbody span#status-unidade-"+itemArray[i]).addClass('label-warning');
					   		$("tbody span#status-unidade-"+itemArray[i]).html('Inativo');
					   		// console.log(itemArray[i]);
					   }
					}else{   
                    	$("tbody span#status-unidade-"+idUnidade).addClass('label-warning');
                    	$("tbody span#status-unidade-"+idUnidade).html('Inativo');
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


	// FUNCAO PARA RESETAR SENHA DO USUARIO
	$('#submit-btn-resetar-senha').livequery( "click", function(){
		// AJAX DE RESETAR SENHA DO USUARIO
        $.ajax({
				type: "POST",
				url: "../../php/usuario/usuario-resetar-senha.php",
				data: queryString,
				cache: false,
				success: function(retorno){
				    $('#alerta-retorno').addClass('alert-success');
                    $('#alerta-retorno').show();
                    $('#mensagem-retorno').html(retorno);
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


	// FUNCAO PARA ALTERAR SENHA DO USUARIO
	$('#submit-btn-alterar-senha').livequery( "click", function(){
		// AJAX DE ALTERAR SENHA DO USUARIO

        queryString = $('#form-altera-senha').formSerialize();
        $.ajax({
				type: "POST",
				url: "../../php/usuario/usuario-alterar-senha.php",
				data: queryString,
				cache: false,
				success: function(retorno){
					var result = retorno.split("//");
					// console.log(result[0]);
					if (result[0] == 1) {
					    $('#alerta-retorno').addClass('alert-success');
	                    $('#alerta-retorno').show();
	                    $('#mensagem-retorno').html(result[1]);
	                	// LIMPAR A LISTA DO ARRAY
						itemArray = [];
	                    $('#myModalAlterarSenha').modal('hide');
	                    $('#usuario_senha_antiga').removeClass('error').parent().find('label.error').remove();
	                    alert('Senha alterada com sucesso');
					}else{
	                    $('#usuario_senha_antiga').removeClass('error').parent().find('label.error').remove();
	                    $('#usuario_senha_antiga').addClass('error').after( '<label id="error" class="error">'+result[1]+'</label>' );
					}
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


	// FUNCAO PARA ALTERAR SENHA DO USUARIO
	$('#usuario_senha_confirma').livequery( "keyup", function(){
		// AJAX DE ALTERAR SENHA DO USUARIO
        var senhaNova = $('#usuario_senha_nova').val();
        var senhaNovaConfirma = $('#usuario_senha_confirma').val();
	    $(this).removeClass('error').parent().find('label.error').remove();
        if(senhaNova != senhaNovaConfirma){
        	$(this).addClass('error').after( '<label id="error" class="error">Senhas diferentes</label>' );
        }
        return false;        
	});
	//------------------------------------------------------------


	// PARTE DE (ATIVAR) A USUARIO
	$('a#operacaoSuccessUnidade').livequery( "click", function(){

		//VERIFICAR SE EXISTE ALGUM ITEM SELECIONADO PARA ATIVAÇÃO MULTIPLAS
		var count = 0;
        $('input[name="itemselecionado[]"]').each(function() { 
            if(this.checked == true){
            	itemArray.push( $(this).val() );
                count++;
            }             
        });
        if( count >= 1 ){
	        // PEGAR DADOS SELECIONADOS
	        queryString = $('#form-table').formSerialize();
	        // AJAX DE ATIVAR USUARIO
	        $.ajax({
					type: "POST",
					url: "../../php/usuario/usuario-ativar.php",
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
		}else{
			alert('Por favor, selecione um item para ativar');
		}
		return false;
	});
	//------------------------------------------------------------

	function clearcheckebox(){
		$('input#marcartodos').removeAttr('checked');
		$('input[name="itemselecionado[]"]').each(function() { 
            this.checked = false;             
        });
        //OCULTAR O BOTÃO DA OPERAÇÃO DE INATIVAR E EXCLUIR
        $('#operacao-excluir-selecionado').hide();
	}

	//FUNCAO AUTOCOMPLETAR
    $('#search_nome').autocomplete({  
      serviceUrl: "../../php//paciente/lista-paciente.php",
      minChars:1,
      maxHeight:150,
      onSelect: function (suggestion) {
       //FUNCAO
      }
    });

    //FUNÇÃO PARA EXIBIR OPCOES DO REGISTRO AO SELECIONAR CHECKBOX
    // $('input[name="itemselecionado[]"]').click( function() {
    //     var count = 0;
    //     $('input[name="itemselecionado[]"]').each(function() { 
    //         if(this.checked == true){
    //             count++;
    //         }             
    //     });
    //     if(count > 0){
    //     	console.log('ss');
    //         $('#operacao-excluir-selecionado').show();
    //     }else{
    //         $('#operacao-excluir-selecionado').hide();
    //     }  
    // });

});	
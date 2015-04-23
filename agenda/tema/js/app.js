(function($) {

	"use strict";

	var options = {
		events_source: '../../php/agenda/events.json.php',
		view: 'day',
		tmpl_path: PORTAL_URL+'tmpls/',
		tmpl_cache: false,
		day: '2015-03-18',
		onAfterEventsLoad: function(events) {
			if(!events) {
				return;
			}
			var list = $('#eventlist');
			list.html('');

			$.each(events, function(key, val) {
				$(document.createElement('li'))
					.html('<a href="' + val.url + '">' + val.title + '</a>')
					.appendTo(list);
			});
		},
		onAfterViewLoad: function(view) {
			$('.page-header h3').text(this.getTitle());
			$('.btn-group button').removeClass('active');
			$('button[data-calendar-view="' + view + '"]').addClass('active');
		},
		language: 'pt-BR',
		modal: '#events-modal',
		time_start: '08:00',
		time_end: '18:00',
		time_split: '30',
		classes: {
			months: {
				general: 'label'
			}
		}
	};

	var calendar = $('#calendar').calendar(options);

	$('.btn-group button[data-calendar-nav]').each(function() {
		var $this = $(this);
		$this.click(function() {
			calendar.navigate($this.data('calendar-nav'));
		});
	});

	$('.btn-group button[data-calendar-view]').each(function() {
		var $this = $(this);
		$this.click(function() {
			calendar.view($this.data('calendar-view'));
		});
	});

	$('#first_day').change(function(){
		var value = $(this).val();
		value = value.length ? parseInt(value) : null;
		calendar.setOptions({first_day: value});
		calendar.view();
	});

	$('#language').change(function(){
		calendar.setLanguage($(this).val());
		calendar.view();
	});

	$('#events-in-modal').change(function(){
		var val = $(this).is(':checked') ? $(this).val() : null;
		calendar.setOptions({modal: val});
	});
	$('a.event-item').click(function(e){
		//alert("OI");
		return false;
	});
	$('#events-modal .modal-header, #events-modal .modal-footer').click(function(e){
		//alert("Modal");
/*		var conteudo = "<a href='#' class='btn btn-default btn-xs' title='O Paciente Chegou' id='chegouxxx' >&nbsp;<i class='glyphicon glyphicon-user'></i>&nbsp;</a>Chegou ";
		conteudo += "<a href='#' class='btn btn-success btn-xs' title='Confirmar Atendimento' id='confirmaratendimentoxxx'>&nbsp;<i class='glyphicon glyphicon-thumbs-up'></i>&nbsp;</a> Confirmar Atendimento "
		conteudo += "<a href='#' class='btn btn-warning btn-xs' title='Remarcar' id='remarcarxxx' >&nbsp;<i class='glyphicon glyphicon-share-alt'></i>&nbsp;</a> Remarcar "
		conteudo += "<a href='#' class='btn btn-danger btn-xs' title='Cancelar Agendamento' id='cancelarxxx' >&nbsp;<i class='glyphicon glyphicon-remove'></i>&nbsp;</a> Cancelar ";
		$("#conteudo").html(conteudo);*/
		//alert("OI");
		//e.preventDefault();
		//e.stopPropagation();
	});
}(jQuery));
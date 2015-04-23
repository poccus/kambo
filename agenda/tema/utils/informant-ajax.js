var count = 0;
var __actionMessage = false;
var openPopUpToSendMail;
function atualizaLoad() {
	var elem = $( "loadCenterContent" );
	if (count == 0) {
		$( "loadCenter" ).removeClassName( "loadCenterMaior" );
		$( "loadCenter" ).addClassName( "loadCenter" );
		elem.innerHTML = "Carregando";
	}

	count++;
	if (count == 10) {
		$( "loadCenter" ).removeClassName( "loadCenter" );
		$( "loadCenter" ).addClassName( "loadCenterMaior" );
		elem.innerHTML = "Ainda Carregando";
	}
	if (elem.innerHTML.indexOf( "....." ) != -1) {
		if (count >= 10) {
			$( "loadCenter" ).removeClassName( "loadCenter" );
			$( "loadCenter" ).addClassName( "loadCenterMaior" );
			elem.innerHTML = "Ainda Carregando";
		} else {
			$( "loadCenter" ).removeClassName( "loadCenterMaior" );
			$( "loadCenter" ).addClassName( "loadCenter" );
			elem.innerHTML = "Carregando";
		}
	}
	elem.innerHTML = elem.innerHTML + ".";
}
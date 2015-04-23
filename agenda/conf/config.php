<?php

//DEFINIR TIMEZONE PADRÃO
date_default_timezone_set("Brazil/East");

//OCULTAR OS WARNING DO PHP
//error_reporting(E_ALL ^ E_WARNING);
//ini_set("display_errors", 0 );

// DEFININDO OS DADOS DE ACESSO AO BANCO DE DADOS
define("DB",'mysql');
define("DB_HOST","localhost");
define("DB_NAME","agenda");
define("DB_USER","root");
define("DB_PASS","root");

//Producao
// define("DB_PASS","S41@s1stem4s");

//Desenvolvimento 
// CONFIGURACOES PADRAO DO SISTEMA
define("PORTAL_URL", 'http://localhost/agenda/');
define("TITULOSISTEMA", 'SPECIALITES :: Clínica Odontológica');
define("FAVICONSISTEMA", 'http://localhost/agenda/upload/favicon.png');
define("LOGO_DASHBOARD", 'http://localhost/agenda/tema/img/logo-gestor-de-cargos.svg');
define("CSS_FOLDER", 'http://localhost/agenda/tema/css/');
define("JS_FOLDER", 'http://localhost/agenda/tema/js/');
define("UTILS_FOLDER", 'http://localhost/agenda/tema/utils/');
define("ASSETS", 'http://localhost/agenda/tema/assets/');

//CONFIGURACAO DE ENVIO DE E-MAIL
define ('EMAIL_NOME', 'DDSIS');
define ('EMAIL_ENDERECO','sistamas.sai@ac.gov.br');
define ('URL_ENDERECO','http://sai.ac.gov.br');
define ('EMAIL_TITULO','Notificação - DDSIS');
define ('EMAIL_DESENVOLVIMENTO', nl2br('Secretaria de Estado de Articulação Institucional - SAI/DESENVOLVIMENTO DE SISTEMAS'));

// ADICIONAR CLASSE DE CONECAO
include_once("Conexao.class.php");

?>

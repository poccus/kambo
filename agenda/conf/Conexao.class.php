<?php

class Conexao extends PDO {

    private static $instancia;

    public function Conexao($dsn, $username = "", $password = "") {
        // O construtro abaixo é o do PDO
        parent::__construct($dsn, $username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }

    public static function getInstance() {
        // Se o a instancia não existe eu faço uma
        if(!isset( self::$instancia )){
            try {
                self::$instancia = new Conexao(DB.":host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
            } catch ( PDOException $e ) {
                $e->getMessage();  
            }
        }
        // Se já existe instancia na memória eu retorno ela
        return self::$instancia;
    }
}
?>
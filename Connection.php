<?php

namespace gpviamaquinas\Repository;

use PDO;

/**
 * Classe responsável por realizar a conexão ao banco de dados
 */
class Connection
{
    /**
     * Manipulador do PDO
     *
     * @var \PDO
     */
    private $conn = null;

    private static $instance;

    private function __construct()
    {
        $hostname = BD["host"];
        $dbname = BD["database"];
        $dbuser = BD["username"];
        $dbpasswd = BD["password"];

        try {
            $this->conn = new PDO('mysql:host=' . $hostname . ';dbname=' . $dbname . ';charset=utf8', $dbuser, $dbpasswd);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES,TRUE);

        } catch (\Exception $ex) {
            echo 'Erro ao conectar ao banco de dados';
        }
    }
    
    public static function getInstance()
    {
        if(!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * Retorna manipulador do PDO para uso no código
     *
     * @param boolean $transaction
     * @return \PDO
     */
    public function getHandler($transaction = false): \PDO
    {
        if($transaction) {
            $this->conn->beginTransaction();
        }

        return $this->conn;
    }

    /**
     * Caso esteja em uma transaction, faz o commit (Efetiva) das mudanças
     *
     * @return void
     */
    public function doCommit()
    {
        if($this->conn->inTransaction()) {
            $this->conn->commit();
        }
    }

    
    /**
     * Caso esteja em uma transaction faz o rollback (descarta) das alterações 
     *
     * @return void
     */
    public function doRollback()
    {
        if($this->conn->inTransaction()) {
            $this->conn->rollBack();
        }
    }
}

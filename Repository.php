<?php

namespace Gpvia\Repository;

use \PDO;
/**
 * Essa classe gerencia a consulta feita em um repositório do BD
 */
class Repository{

    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    private function getGeneric($filtro)
    {
        $connection = $this->connection;             
        try {            
            $consulta = $connection
                ->getHandler()
                ->prepare($filtro->getQuery());
            
            if($consulta){
                
                $consulta->execute($filtro->getBindedValues());

                return $consulta;
            }
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
        
        return null;
    }

    public function getConsulta($filtro)
    {
        $stmt = $this->getGeneric($filtro);
        if($stmt){
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    public function getConsultaUnica($filtro)
    {
    	$stmt = $this->getGeneric($filtro);
        if($stmt){
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

}
?>

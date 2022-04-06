<?php

namespace Gpvia\Repository;
/**
 * Essa classe consulta o Banco de Dados utilizando o PDO 
 */
class PDO_Filtros {

    private $Sql;
    private $Where = array();
    private $Campos = array();
    private $Orders = array();
    private $Group = array();
    private $bindedValues;
    private $Limit;
    private $LimitInicio;
    
    /*########################   COLETANDO DADOS   ##########################*/
    public function FilterIgual($Campo, $Valor, $Logica = "")
    {
        if($Valor != "")
        {
            $this->Where($Campo, "=", $Valor, $Logica);
        }
    }

    public function FilterLike($Campo, $Valor)
    {
        if($Valor != "")
        {
            $this->Where($Campo, "LIKE", "%".$Valor."%", "AND");
        }
    }

    public function From($Table)
    {
        $Sql = "SELECT %1s FROM " . $Table;
        $this->Sql = $Sql;

        return $this;
    }

    public function Field($Campos)
    {
        $this->Campos = $Campos;

        return $this;
    }

    public function Where($Campo, $Condicao = null, $Valor = null, $Logica = "", $Alias = "", $AliasResto = "")
    {
        if(is_null($Condicao))
        { 
            if (!is_null($Campo))
            {
                $Where =    [
                            'condicao' => $Campo,
                            'logica' => $Logica
                            ];

                $this->Where[] = $Where;
            }
        }
        else
        {
            if (!is_null($Campo))
            {
                $Where =    [
                            'condicao' => $Alias . $Campo . $AliasResto . " " . $Condicao . ' :' . $Campo,
                            'logica' => $Logica
                            ];

                $this->Where[] = $Where;
                $this->bindedValues[':' . $Campo] = $Valor;
            }
            
        }
        
        return $this;
    }

    public function parseWhere()
    {
        $whereStr = "";
        if(is_array($this->Where) && count($this->Where)) {
            $whereStr = " WHERE ";

            foreach($this->Where as $w) { 
                $whereStr .= $w["condicao"] . " " . $w["logica"] . " ";
            }
        }

        return $whereStr;
    }

    public function Order($Campo, $Ordem)
    {
        $this->Orders[] = $Campo . " " . $Ordem;     

        return $this;
    }

    public function Group($Campo)
    {
        $this->Group[] = $Campo;     

        return $this;
    }

    //$limit = filter_has_var(INPUT_GET, 'limit') ? filter_input(INPUT_GET, 'limit', FILTER_SANITIZE_NUMBER_INT) : LIMIT;
    public function Limit($Limite)
    {
        $this->Limit = intval($Limite);

        return $this;
    }

    //$inicio = filter_has_var(INPUT_GET, 'inicio') ? filter_input(INPUT_GET, 'inicio', FILTER_SANITIZE_NUMBER_INT) : 0;
    public function Inicio($inicio)
    {
    	$this->LimitInicio = intval($inicio);

    	return $this;
    }

    /*##########################  PREPARANDO A CONSULTA   ##########################*/

    public function getQuery()
    {
        $Fields = is_array($this->Campos) && count($this->Campos) > 0 ? implode(', ', $this->Campos) : '*';        
        $Order = is_array($this->Orders) && count($this->Orders) > 0 ? ' ORDER BY ' . implode(', ', $this->Orders) : '';
        $Group = is_array($this->Group) && count($this->Group) > 0 ? ' GROUP BY ' . implode(', ', $this->Group) : '';
        $Limit = $this->Limit ? ' LIMIT ' . ($this->LimitInicio ? $this->LimitInicio . ', ' . $this->Limit : $this->Limit) : '';

        return sprintf($this->Sql . $this->parseWhere() . $Group . $Order . $Limit, $Fields);
    }
    
    public function getBindedValues()
    {
    	return $this->bindedValues;
    }
}
?>
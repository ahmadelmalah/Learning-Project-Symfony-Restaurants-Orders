<?php
namespace AppBundle\Utils;

class QueryFilter
{
    private $queryFilterArray = array();

    public function __construct(){

    }

    public function setArray(Array $array){
      $this->queryFilterArray = $array;
    }

    public function getArray(){
      return $this->queryFilterArray;
    }

    public function addFilter($key, $value, $condition = true){
        if($condition == false) return;
        if(isset($value) == false) return;
        if(!$value) return;

        $this->queryFilterArray[$key] = $value;
    }
}

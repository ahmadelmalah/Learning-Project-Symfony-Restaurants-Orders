<?php
namespace AppBundle\Utils;

class QueryFilter
{
    private $queryFilterArray = array();
    //private $returnStrategy = NULL;

    public function __construct(){

    }

    public function setArray(Array $array){
      $this->queryFilterArray = $array;
    }

    public function getArray(){
      return $this->queryFilterArray;
    }

    public function getSQLFilter(){
      $numItems = count($this->queryFilterArray);
      $i = 0;
      $filter = '';

      foreach ($this->queryFilterArray as $key => $value) {
        if (is_array($value)){
          $filter .= 'f.' . $key . ' in(' . implode($value,',') . ')';
        }else{
          $filter .= 'f.' . $key . '=' . $value;
        }

        if(++$i != $numItems) {
          $filter .= ' AND ';
        }
      }
      return $filter;
    }

    public function addFilter(string $key, $value, bool $condition = true){
        if($condition == false) return;
        if(isset($value) == false) return;
        if(!$value) return;

        $this->queryFilterArray[$key] = $value;
    }
}

<?php
namespace AppBundle\Utils;

use Symfony\Component\Config\Definition\Exception\Exception;

class PaginatorUtil
{
  private $num_of_pages;
  private $num_of_items;
  private $item_per_page;
  private $current_page;

  public function __construct($num_of_items, $item_per_page){
    $this->num_of_items = $num_of_items;
    $this->item_per_page = $item_per_page;

    $this->calcNumberOfPages();
  }

  public function calcNumberOfPages(){
    $this->num_of_pages = ceil($this->num_of_items/ $this->item_per_page);
  }

  public function navigateToPage($page){
    if(is_numeric($page) == false){
        throw new Exception("Wrong Page Number! Invalid Entry..");
    }
    if($page < 1){
        $page = 1;
    }
    if($page > $this->num_of_pages){
        $page = $this->num_of_pages;
    }
    $this->current_page = $page;
  }

  public function hasNext(){
    $this->checkValidPageOrFail();
    return $this->current_page < $this->num_of_pages;
  }

  public function getNext(){
    return $this->current_page + 1;
  }

  public function hasPrevious(){
    $this->checkValidPageOrFail();
    return $this->current_page > 1;
  }

  public function getPrevious(){
    return $this->current_page - 1;
  }

  public function getCurrentPage(){
    return $this->current_page;
  }

  public function getLastPage(){
    return $this->num_of_pages;
  }

  public function checkValidPageOrFail(){
    if(!isset($this->current_page)){
      throw new Exception("Invalid Page");
    }
    return true;
  }


  public function getNumberOfPages(){
    return $this->num_of_pages;
  }
}

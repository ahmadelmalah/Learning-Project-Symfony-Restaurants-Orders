<?php
namespace AppBundle\Utils;

use Symfony\Component\Config\Definition\Exception\Exception;

class PaginatorUtil
{
  private $num_of_pages;
  private $num_of_items;
  private $item_per_page;
  private $current_page;

  public function __construct(int $num_of_items, int $item_per_page, int $current_page = 1){
    $this->num_of_items = $num_of_items;
    $this->item_per_page = $item_per_page;

    $this->calcNumberOfPages();
    $this->navigateToPage($current_page);
  }

  public function calcNumberOfPages(){
    $this->num_of_pages = ceil($this->num_of_items/ $this->item_per_page);
  }

  public function navigateToPage(int $page){
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

  public function hasNextPage(){
    $this->checkValidPageOrFail();
    return $this->current_page < $this->num_of_pages;
  }

  public function hasPreviousPage(){
    $this->checkValidPageOrFail();
    return $this->current_page > 1;
  }

  public function hasMoreThanOnePage(){
    $this->checkValidPageOrFail();
    return $this->num_of_pages > 1;
  }

  public function getNextPageNumber(){
    $this->checkValidPageOrFail();
    return $this->current_page + 1;
  }

  public function getCurrentPageNumber(){
    $this->checkValidPageOrFail();
    return $this->current_page;
  }

  public function getPreviousPageNumber(){
    $this->checkValidPageOrFail();
    return $this->current_page - 1;
  }

  public function getLastPageNumber(){
    $this->checkValidPageOrFail();
    return $this->num_of_pages;
  }

  public function checkValidPageOrFail(){
    if(!isset($this->current_page)){
      throw new Exception("Invalid Page");
    }
    return true;
  }
}

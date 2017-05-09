<?php
namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;

class AdminService
{
    protected $entityManager;
    protected $cache;

    /**
    * Admin Service constructor.
    *
    * @param EntityManager $entityManager
    * @param $cacheService
    */
    public function __construct(EntityManager $entityManager, $cacheService)
    {
        $this->entityManager = $entityManager;
        $this->cache = $cacheService;
    }

    /**
    * Gets the total number of some entity
    *
    * @param string $entity
    *
    * @return integer indicates the number of items
    */
    public function getTotal(string $entity){
        $cacheKey = "count_{$entity}";

        //if data is cached, return it and exit
        if($this->isCached()){
          return $this->cache->fetch($cacheKey);
        }

        $response = $this->getTotalFromRepo($entity);
        $this->saveDataToCache($cacheKey, $response);

        return $response;
    }

    /**
    * Gets the total number of some entity from its repo
    *
    * @param string $entity
    *
    * @return integer indicates the number of items
    */
    public function getTotalFromRepo(string $entity){
      switch ($entity) {
        case 'users':
          $repoName = 'AppBundle:User';
          break;
        case 'restaurants':
          $repoName = 'AppBundle:Restaurant';
          break;
        case 'forders':
          $repoName = 'AppBundle:Forder';
          break;
        case 'items':
          $repoName = 'AppBundle:Item';
          break;

      }
      return $this->entityManager->getRepository($repoName)->getTotal();
    }

    /**
    * Saves key-value paired data to cache
    *
    * @param string $key
    * @param string $value
    */
    public function saveDataToCache(string $key, string $value){
      $this->cache->save($key, $value, 60*15);
      $this->checkAdminCache();
    }

    /**
    * checks if cached data are complete .. general admin cache flag is on
    */
    public function checkAdminCache(){
      if ($this->cache->fetch('count_users') == false) return;
      if ($this->cache->fetch('count_restaurants') == false) return;
      if ($this->cache->fetch('count_forders') == false) return;
      if ($this->cache->fetch('count_items') == false) return;

      $this->cache->save('cache_admin', 1, 60*15);
    }

    /**
    * checks if data are cached
    *
    * @return bool indicates is the admin cache flag is working
    */
    public function isCached(){
      if($this->cache->fetch("cache_admin") == 1){
          return true;
      }
      return false;
    }
}

<?php
namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;

class AdminService
{
    protected $em;
    protected $cache;

    /**
    * Helper constructor.
    * @param EntityManager $entityManager
    */
    public function __construct(EntityManager $entityManager, $cacheService)
    {
        $this->em = $entityManager;
        $this->cache = $cacheService;
    }

    public function getTotal($entity){
        $cacheKey = "count_{$entity}";

        //if data is cached, return it and exit
        if($this->isCached()){
          return $this->cache->fetch($cacheKey);
        }

        $response = $this->getTotalFromRepo($entity);
        $this->saveDataToCache($cacheKey, $response);

        return $response;
    }

    public function getTotalFromRepo($entity){
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
      return $this->em->getRepository($repoName)->getTotal();
    }

    public function saveDataToCache($key, $value){
      $this->cache->save($key, $value);
      $this->checkAdminCache();
    }

    public function checkAdminCache(){
      if ($this->cache->fetch('count_users') == false) return;
      if ($this->cache->fetch('count_restaurants') == false) return;
      if ($this->cache->fetch('count_forders') == false) return;
      if ($this->cache->fetch('count_items') == false) return;

      $this->cache->save('cache_admin', 1);
    }

    public function isCached(){
      if($this->cache->fetch("cache_admin") == 1){
          return true;
      }
      return false;
    }
}

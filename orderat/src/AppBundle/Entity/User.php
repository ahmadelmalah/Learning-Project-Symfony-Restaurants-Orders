<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="fos_user")

 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Forder", mappedBy="user")
     */
    private $forders;

    /**
    * @ORM\OneToMany(targetEntity="Item", mappedBy="user")
    */
    private $items;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Group")
     * @ORM\JoinTable(name="fos_user_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
     protected $groups;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Add item
     *
     * @param \AppBundle\Entity\Item $item
     *
     * @return User
     */
    public function addItem(\AppBundle\Entity\Item $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \AppBundle\Entity\Item $item
     */
    public function removeItem(\AppBundle\Entity\Item $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Add forder
     *
     * @param \AppBundle\Entity\Forder $forder
     *
     * @return User
     */
    public function addForder(\AppBundle\Entity\Forder $forder)
    {
        $this->forders[] = $forder;

        return $this;
    }

    /**
     * Remove forder
     *
     * @param \AppBundle\Entity\Forder $forder
     */
    public function removeForder(\AppBundle\Entity\Forder $forder)
    {
        $this->forders->removeElement($forder);
    }

    /**
     * Get forders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getForders()
    {
        return $this->forders;
    }
}

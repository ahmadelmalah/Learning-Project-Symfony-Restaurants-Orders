<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Restaurant
 *
 * @ORM\Table(name="restaurant")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RestaurantRepository")
 */
class Restaurant
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      minMessage = "Restaurant name must be at least 3 characters long",
     *      max = 20,
     *      maxMessage = "Restaurant name must be not more that 20 characters long",

     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="menuurl", type="string", length=255, nullable=true)
     */
    private $menuurl;

    /**
     * @ORM\OneToMany(targetEntity="RestaurantPhone", mappedBy="restaurant")
     */
    private $phones;

    /**
     * @ORM\OneToMany(targetEntity="Forder", mappedBy="restaurant")
     */
    private $forders;

    public function __construct()
    {
        //$this->phones = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Restaurant
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set menuurl
     *
     * @param string $menuurl
     *
     * @return Restaurant
     */
    public function setMenuurl($menuurl)
    {
        $this->menuurl = $menuurl;

        return $this;
    }

    /**
     * Get menuurl
     *
     * @return string
     */
    public function getMenuurl()
    {
        return $this->menuurl;
    }

    /**
     * Add phone
     *
     * @param \AppBundle\Entity\RestaurantPhone $phone
     *
     * @return Restaurant
     */
    public function addPhone(\AppBundle\Entity\RestaurantPhone $phone)
    {
        $this->phones[] = $phone;

        return $this;
    }

    /**
     * Remove phone
     *
     * @param \AppBundle\Entity\RestaurantPhone $phone
     */
    public function removePhone(\AppBundle\Entity\RestaurantPhone $phone)
    {
        $this->phones->removeElement($phone);
    }

    /**
     * Get phones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * Add forder
     *
     * @param \AppBundle\Entity\Forder $forder
     *
     * @return Restaurant
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

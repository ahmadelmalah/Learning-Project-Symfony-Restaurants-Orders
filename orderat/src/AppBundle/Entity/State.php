<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * State
 *
 * @ORM\Table(name="state")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StateRepository")
 */
 class State
{
    const ACTIVE    = 1;
    const READY     = 2;
    const WAITING   = 3;
    const DELIVERED = 4;
    const COMPLETE  = 5;

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
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Forder", mappedBy="state")
     */
    private $forders;



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
     * @return State
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
     * Constructor
     */
    public function __construct()
    {
        $this->forders = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add forder
     *
     * @param \AppBundle\Entity\Forder $forder
     *
     * @return State
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

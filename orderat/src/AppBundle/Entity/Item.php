<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Item
 *
 * @ORM\Table(name="item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ItemRepository")
 */
class Item
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
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\Length(
     *      min = 3,
     *      minMessage = "Item name must be at least 3 characters long",
     *      max = 20,
     *      maxMessage = "Item name must be not more that 20 characters long",
     * )
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @Assert\Range(
     *      min = 1,
     *      max = 99,
     *      minMessage = "Quantity should be at least 1",
     *      maxMessage = "Quantity should not be more than 99"
     * )
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="Forder", inversedBy="items")
     * @ORM\JoinColumn(name="forder_id", referencedColumnName="id")
     */
     private $forder;

     /**
      * @ORM\ManyToOne(targetEntity="User", inversedBy="items")
      * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
      */
      private $user;

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
     * @return Item
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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Item
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set forder
     *
     * @param \AppBundle\Entity\Forder $forder
     *
     * @return Item
     */
    public function setForder(\AppBundle\Entity\Forder $forder = null)
    {
        $this->forder = $forder;

        return $this;
    }

    /**
     * Get forder
     *
     * @return \AppBundle\Entity\Forder
     */
    public function getForder()
    {
        return $this->forder;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Item
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}

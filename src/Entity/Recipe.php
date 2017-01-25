<?php

namespace MyApi\Entity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="recipe")
 * @ORM\Entity
 **/
class Recipe
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"overview", "detail"})
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Groups({"overview", "detail"})
     **/
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"detail"})
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(
     *      value=0,
     *      message="This universe is not so lucky. This value must be at least zero"
     *      )
     **/
    private $energy;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"overview", "detail"})
     * @Assert\NotBlank()
     **/
    private $servings;


    /**
     * Get id
     *
     * @return integer
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
     * @return Recipe
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
     * Set energy
     *
     * @param integer $energy
     *
     * @return Recipe
     */
    public function setEnergy($energy)
    {
        $this->energy = $energy;

        return $this;
    }

    /**
     * Get energy
     *
     * @return integer
     */
    public function getEnergy()
    {
        return $this->energy;
    }


    /**
     * Set servings
     *
     * @param integer $servings
     *
     * @return Recipe
     */
    public function setServings($servings)
    {
        $this->servings = $servings;

        return $this;
    }

    /**
     * Get servings
     *
     * @return integer
     */
    public function getServings()
    {
        return $this->servings;
    }
}

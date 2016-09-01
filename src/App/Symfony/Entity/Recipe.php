<?php

namespace App\Symfony\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Recipe
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $ingredients;

    /**
     * @var User|null
     */
    private $createdBy;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     *
     * @return Recipe
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
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
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Recipe
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param QuantifiableIngredient $ingredient
     *
     * @return Recipe
     */
    public function addIngredient(QuantifiableIngredient $ingredient)
    {
        $this->ingredients->add($ingredient);
        $ingredient->setRecipe($this);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }

    /**
     * @param QuantifiableIngredient $ingredient
     *
     * @return boolean
     */
    public function hasIngredient(QuantifiableIngredient $ingredient)
    {
        return $this->ingredients->contains($ingredient);
    }

    /**
     * @var QuantifiableIngredient $ingredient
     *
     * @return Recipe
     */
    public function removeIngredient(QuantifiableIngredient $ingredient)
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }

    /**
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param User $createdBy
     *
     * @return Recipe
     */
    public function setCreatedBy(User $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }
}

<?php

namespace App\Symfony\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Knp\Rad\User\HasPassword;
use Knp\Rad\User\HasSalt;
use Doctrine\Common\Collections\ArrayCollection;

class User implements UserInterface, HasPassword, HasSalt
{
    use HasSalt\HasSalt;
    use HasPassword\HasPassword;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $role;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $salt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $recipes;

    /**
     * @param string $role
     */
    public function __construct($role = 'ROLE_USER')
    {
        $this->role = $role;
        $this->recipes = new ArrayCollection();
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
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     *
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return [$this->role];
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }

    /**
     * @param Recipe $recipe
     *
     * @return User
     */
    public function addRecipe(Recipe $recipe)
    {
        $this->recipes->add($recipe);

        $recipe->setUser($this);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecipes()
    {
        return $this->recipes;
    }

    /**
     * @param Recipe $recipe
     *
     * @return boolean
     */
    public function hasRecipe(Recipe $recipe)
    {
        return $this->recipes->contains($recipe);
    }

    /**
     * @param Recipe $recipe
     *
     * @return User
     */
    public function removeRecipe(Recipe $recipe)
    {
        $this->recipes->removeElement($recipe);

        return $this;
    }
}

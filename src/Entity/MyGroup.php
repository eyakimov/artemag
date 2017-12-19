<?php

namespace App\Entity;

use App\Repository\MyGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MyGroupRepository")
 */
class MyGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="myrole", type="string", length=255, options={"default":"user"})
     */
    private $myrole;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="mygroups")
     */
    private $myuser;

    public function __construct() {

        $this->myuser = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Group
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Add myuser
     *
     * @param App\Entity\User $myuser
     * @return Group
     */
    public function addMyuser(App\Entity\User $myuser) {
        $this->myuser[] = $myuser;

        return $this;
    }

    /**
     * Remove myuser
     *
     * @param App\Entity\User $myuser
     */
    public function removeMyuser(App\Entity\User $myuser) {
        $this->myuser->removeElement($myuser);
    }

    /**
     * Get myuser
     *
     * @return Collection 
     */
    public function getMyuser():Collection {
        return $this->myuser;
    }


    /**
     * Set myrole
     *
     * @param string $myrole
     * @return MyGroup
     */
    public function setMyrole($myrole)
    {
        $this->myrole = $myrole;

        return $this;
    }

    /**
     * Get myrole
     *
     * @return string 
     */
    public function getMyrole()
    {
        return $this->myrole;
    }
}

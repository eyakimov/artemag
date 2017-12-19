<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User implements AdvancedUserInterface{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @Assert\Length(
     *     min = 5,
     *     minMessage = "Password should by at least 5 chars long"
     * )
     * @Assert\Length(max = 4096)
     */
    private $plainPassword;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @var string $email
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true, options={"default":null})
     */
    private $registeredAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true, options={"default":null})
     */
    private $lastVisited;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", options={"default":0})
     */
    private $isEnabled;


    /**
     * @ORM\ManyToOne(targetEntity="MyGroup", inversedBy="myuser")
     * @ORM\JoinColumn(name="groupId", referencedColumnName="id")
     */
    private $mygroups;

    /**
     * roles for users
     */
    private $roles = array();

    /**
     *
     * @var double
     * @ORM\Column(type="decimal", precision=11, scale=2, options={"default":0})
     */
    private $cash;
    
    public function __construct() {

        $this->registeredAt = null;
        $this->lastVisited = null;
        $this->isEnabled = 1;
        $this->cash=0;
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getPlainPassword() {
        return $this->plainPassword;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRegisteredAt(): ?\DateTime {
        return $this->registeredAt;
    }

    public function getLastVisited(): ?\DateTime {
        return $this->lastVisited;
    }

    public function getIsEnabled() {
        return $this->isEnabled;
    }

    public function getCash() {
        return $this->cash;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
        return $this;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
        return $this;
    }

    public function setPlainPassword($plainPassword) {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function setRegisteredAt(\DateTime $registeredAt) {
        $this->registeredAt = $registeredAt;
        return $this;
    }

    public function setLastVisited(\DateTime $lastVisited) {
        $this->lastVisited = $lastVisited;
        return $this;
    }

    public function setIsEnabled($isEnabled) {
        $this->isEnabled = $isEnabled;
        return $this;
    }

    public function setCash(decimal $cash) {
        $this->cash = $cash;
        return $this;
    }

    
    public function eraseCredentials() {
        
    }

    /**
     * Returns the roles or permissions granted to the user for security.
     */
    public function getRoles() {

        if ($this->mygroups->getMyrole() == 'admins') {
            $roles[] = 'ROLE_ADMIN';
        } elseif ($this->mygroups->getMyrole() == 'superadmins') {
            $roles[] = 'ROLE_SUPER_ADMIN';
        }

        // guarantees that a user always has at least one role for security
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles) {
        $this->roles = $roles;
    }

        /**
     * Set mygroups
     *
     * @param \AppBundle\Entity\Group $mygroups
     * @return User
     */
    public function setMygroups(App\Entity\MyGroup $mygroups = null) {
        $this->mygroups = $mygroups;

        return $this;
    }

    /**
     * Get mygroups
     *
     * @return App\Entity\Group 
     */
    public function getMygroups() {
        return $this->mygroups;
    }
    
    public function getSalt() {
        
    }

    public function isAccountNonExpired(): bool {
        
    }

    public function isAccountNonLocked(): bool {
        
    }

    public function isCredentialsNonExpired(): bool {
        
    }

    public function isEnabled(): bool {
        if(1===$this->isEnabled){
        return true;
        }
        return false;
    }

}

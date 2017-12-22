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
class User implements AdvancedUserInterface, \Serializable{

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
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="users")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    private $group;

    /**
     * roles for users
     */
    private $roles;

    /**
     *
     * @var double
     * @ORM\Column(type="decimal", precision=11, scale=2, options={"default":0})
     */
    private $cash;
    
    /**
     * @ORM\OneToMany(targetEntity="Cart", mappedBy="user")
     */
    private $users;

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

    public function getRegisteredAt(): \DateTime {
        return $this->registeredAt;
    }

    public function getLastVisited(): \DateTime {
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
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function setPlainPassword($plainPassword) {
        $this->plainPassword = $plainPassword;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setRegisteredAt(\DateTime $registeredAt) {
        $this->registeredAt = $registeredAt;
    }

    public function setLastVisited(\DateTime $lastVisited) {
        $this->lastVisited = $lastVisited;
    }

    public function setIsEnabled($isEnabled) {
        $this->isEnabled = $isEnabled;
    }

    public function setCash(decimal $cash) {
        $this->cash = $cash;
    }

    public function getGroup(): Group {
        return $this->group;
    }

    public function setGroup($group) {
        $this->group = $group;
    }
    
    public function getUsers() {
        return $this->users;
    }

    public function setUsers($users) {
        $this->users = $users;
    }
    
    public function eraseCredentials() {
        
    }

    /**
     * Returns the roles or permissions granted to the user for security.
     */
    public function getRoles() {
        if($this->getGroup()->getName()=='user'){
            $roles[] = 'ROLE_USER';
        }
        if($this->getGroup()->getName()=='admin'){
            $roles[] = 'ROLE_ADMIN';
        }
        if($this->getGroup()->getName()=='superadmin'){
            $roles[] = 'ROLE_SUPER_ADMIN';
        }
        $roles[] = 'ROLE_ANONYMOUS';
        return array_unique($roles);
    }

    public function setRoles(array $roles) {
        $this->roles = $roles;
    }
    
    public function getSalt() {
        
    }

    public function isAccountNonExpired(): bool {
        return true;
    }

    public function isAccountNonLocked(): bool {
        return true;
    }

    public function isCredentialsNonExpired(): bool {
        return true;
    }

    public function isEnabled(): bool {
        if(1==$this->isEnabled){
        return true;
        }
        return false;
    }
    
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isEnabled,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->isEnabled,
        ) = unserialize($serialized);
    }
}

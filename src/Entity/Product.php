<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product {

    const NUM_ITEMS = 10;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private $id;

    /**
     *
     * @var string
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     *
     * @var string
     * @ORM\Column(type="text", length=2048)
     */
    private $description;

    /**
     *
     * @var float
     * @ORM\Column(type="decimal", precision=11, scale=2)
     */
    private $price;

    /**
     *
     * @var integer
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private $amount;

    /**
     * @var Collection\Category[]
     * Many Products have Many Categories.
     * @ORM\ManyToMany(targetEntity="Category")
     * @ORM\JoinTable(name="goods",
     *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     *      )
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="Cart", mappedBy="product")
     */
    private $products;

    public function __construct() {
        $this->categories = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setPrice(float $price) {
        $this->price = $price;
    }

    public function getCategories(): Collection {
        return $this->categories;
    }

    public function setCategories(array $categories): void {
        $this->categories = $categories;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function setAmount($amount) {
        $this->amount = $amount;
    }

    public function getProducts() {
        return $this->products;
    }

    public function setProducts($products) {
        $this->products = $products;
    }

}

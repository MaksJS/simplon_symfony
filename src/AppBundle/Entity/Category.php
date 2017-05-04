<?php

    namespace AppBundle\Entity;

    use Doctrine\ORM\Mapping as ORM;
    use Doctrine\Common\Collections\ArrayCollection;
    use Symfony\Component\Validator\Constraints as Assert;
    use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
    
    /**
     * @ORM\Entity
     * @ORM\Table(name="categories")
     * @UniqueEntity("designation")
     */
    class Category {

        /**
         * @ORM\Column(type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        private $id;

        /**
         * @ORM\Column(type="string", length=100, unique=true)
         * @Assert\NotBlank(message="The designation cannot be blank.")
         */
        private $designation;
        
        /**
         * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
         */
        private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

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
     * Set designation
     *
     * @param string $designation
     *
     * @return Category
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * Get designation
     *
     * @return string
     */
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * Add product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return Category
     */
    public function addProduct(\AppBundle\Entity\Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \AppBundle\Entity\Product $product
     */
    public function removeProduct(\AppBundle\Entity\Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }
}

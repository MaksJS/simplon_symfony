<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @UniqueEntity("reference")
 */
class Product {

    use TimestampableEntity, SoftDeleteableEntity;

    const PER_PAGE = 10;

    /**
    * @ORM\Column(type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
    * @ORM\Column(type="string", length=100, unique=true)
    * @Assert\NotBlank(message="The reference cannot be blank.")
    */
    private $reference;

    /**
    * @ORM\Column(type="decimal", scale=2)
    * @Assert\NotBlank(message="The price cannot be blank.")
    * @Assert\Type(type="float", message="The price is not a valid float.")
    */
    private $price;

    /**
    * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
    * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="SET NULL")
    */
    private $category;

    /**
    * @ORM\OneToMany(targetEntity="InvoiceLine", mappedBy="product")
    */
    private $invoiceLines;

    /**
     * Constructor
     */
    public function __contruct() {
        $this->invoiceLines = new ArrayCollection();
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
     * Set reference
     *
     * @param string $reference
     *
     * @return Product
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return Product
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add invoiceLine
     *
     * @param \AppBundle\Entity\InvoiceLine $invoiceLine
     *
     * @return Product
     */
    public function addInvoiceLine(\AppBundle\Entity\InvoiceLine $invoiceLine)
    {
        $this->invoiceLines[] = $invoiceLine;

        return $this;
    }

    /**
     * Remove invoiceLine
     *
     * @param \AppBundle\Entity\InvoiceLine $invoiceLine
     */
    public function removeInvoiceLine(\AppBundle\Entity\InvoiceLine $invoiceLine)
    {
        $this->invoiceLines->removeElement($invoiceLine);
    }

    /**
     * Get invoiceLines
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvoiceLines()
    {
        return $this->invoiceLines;
    }

    public function __toString() {
        return $this->reference;
    }
}

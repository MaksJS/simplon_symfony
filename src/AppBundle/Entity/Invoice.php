<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Invoice
 *
 * @ORM\Table(name="invoice")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InvoiceRepository")
 */
class Invoice
{

    const CLOSED = 0;
    const OPENED = 1;

    const PER_PAGE = 2;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="state", type="smallint")
     */
    private $state = self::OPENED;

    /**
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="invoices")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\NotBlank(message="The client cannot be blank.")
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity="InvoiceLine", mappedBy="invoice")
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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set state
     *
     * @param integer $state
     *
     * @return Invoice
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Get designation
     *
     * @return string
     */
    public function getDesignation()
    {
        return "invoice-".str_pad($this->id, 4, '0', STR_PAD_LEFT); // invoice-001
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        $total = 0;
        foreach($this->getInvoiceLines() as $invoiceLine) {
            $total += $invoiceLine->getTotal();
        }
        return $total;
    }

    /**
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return Invoice
     */
    public function setClient(\AppBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \AppBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Add invoiceLine
     *
     * @param \AppBundle\Entity\InvoiceLine $invoiceLine
     *
     * @return Invoice
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

    public function isOpened() {
        return $this->state === self::OPENED;
    }

    public function isClosed() {
        return $this->state === self::CLOSED;
    }

    public function __toString() {
        return $this->getDesignation();
    }
}

<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
    private $state;
 
    public function __contruct() {
        $this->state = self::OPENED;
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
        return "invoice-".str_pad($this->id, 2, '0', STR_PAD_LEFT); // invoice-001
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        return 0;
    }
}


<?php

namespace PruebaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Factura
 *
 * @ORM\Table(name="factura")
 * @ORM\Entity(repositoryClass="PruebaBundle\Repository\FacturaRepository")
 */
class Factura
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="numFactura", type="float", unique=true)
     */
    private $numFactura;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaVencimiento", type="datetime")
     */
    private $fechaVencimiento;

    /**
     * @var bool
     *
     * @ORM\Column(name="pago", type="boolean")
     */
    private $pago;

    /**
     * @ORM\ManyToOne(targetEntity="servicio", inversedBy="facturas")
     * @ORM\JoinColumn(name="id_servicio", referencedColumnName="id")
     */
    private $service;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set numFactura.
     *
     * @param int $numFactura
     *
     * @return Factura
     */
    public function setNumFactura($numFactura)
    {
        $this->numFactura = $numFactura;

        return $this;
    }

    /**
     * Get numFactura.
     *
     * @return int
     */
    public function getNumFactura()
    {
        return $this->numFactura;
    }

    /**
     * Set fechaVencimiento.
     *
     * @param \DateTime $fechaVencimiento
     *
     * @return Factura
     */
    public function setFechaVencimiento($fechaVencimiento)
    {
        $this->fechaVencimiento = $fechaVencimiento;

        return $this;
    }

    /**
     * Get fechaVencimiento.
     *
     * @return \DateTime
     */
    public function getFechaVencimiento()
    {
        return $this->fechaVencimiento;
    }

    /**
     * Set pago.
     *
     * @param bool $pago
     *
     * @return Factura
     */
    public function setPago($pago)
    {
        $this->pago = $pago;

        return $this;
    }

    /**
     * Get pago.
     *
     * @return bool
     */
    public function getPago()
    {
        return $this->pago;
    }

    /**
     * Set service.
     *
     * @param \PruebaBundle\Entity\servicio|null $service
     *
     * @return Factura
     */
    public function setService(\PruebaBundle\Entity\servicio $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service.
     *
     * @return \PruebaBundle\Entity\servicio|null
     */
    public function getService()
    {
        return $this->service;
    }
}

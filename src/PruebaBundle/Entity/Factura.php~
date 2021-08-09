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
     * @ORM\Column(name="fechaVencimiento", type="date")
     */
    private $fechaVencimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="periodo", type="string", length=25)
     */
    private $periodo;

    /**
     * @var bool
     *
     * @ORM\Column(name="pago", type="boolean")
     */
    private $pago;

     /**
    * @ORM\ManyToOne(targetEntity="Expediente", inversedBy="facturas")
    * @ORM\JoinColumn(name="id_expediente", referencedColumnName="id")
    */
    private $expediente;



    /**
     * @ORM\ManyToMany(targetEntity="servicio", inversedBy="facturas", orphanRemoval=true)
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->service = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add service.
     *
     * @param \PruebaBundle\Entity\servicio $service
     *
     * @return Factura
     */
    public function addService(\PruebaBundle\Entity\servicio $service)
    {
        $this->service[] = $service;

        return $this;
    }

    /**
     * Remove service.
     *
     * @param \PruebaBundle\Entity\servicio $service
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeService(\PruebaBundle\Entity\servicio $service)
    {
        return $this->service->removeElement($service);
    }

    public function __toString(){
        return strval($this->getNumFactura());
    }

    /**
     * Set periodo.
     *
     * @param string $periodo
     *
     * @return Factura
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;

        return $this;
    }

    /**
     * Get periodo.
     *
     * @return string
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }



    /**
     * Set expediente.
     *
     * @param \PruebaBundle\Entity\Expediente|null $expediente
     *
     * @return Factura
     */
    public function setExpediente(\PruebaBundle\Entity\Expediente $expediente = null)
    {
        $this->expediente = $expediente;

        return $this;
    }

    /**
     * Get expediente.
     *
     * @return \PruebaBundle\Entity\Expediente|null
     */
    public function getExpediente()
    {
        return $this->expediente;
    }

    /**
     * Add expediente.
     *
     * @param \PruebaBundle\Entity\Factura $expediente
     *
     * @return Factura
     */
    public function addExpediente(\PruebaBundle\Entity\Factura $expediente)
    {
        $this->expediente[] = $expediente;

        return $this;
    }

    /**
     * Remove expediente.
     *
     * @param \PruebaBundle\Entity\Factura $expediente
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeExpediente(\PruebaBundle\Entity\Factura $expediente)
    {
        return $this->expediente->removeElement($expediente);
    }
}

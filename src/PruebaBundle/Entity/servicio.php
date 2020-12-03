<?php

namespace PruebaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * servicio
 *
 * @ORM\Table(name="servicio")
 * @ORM\Entity(repositoryClass="PruebaBundle\Repository\servicioRepository")
 */
class servicio
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
     * @var int
     *
     * @ORM\Column(name="referencia", type="integer")
     */
    private $referencia;

    /**
     * @var string
     *
     * @ORM\Column(name="compania", type="string", length=255)
     */
    private $compania;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="ciudad", type="string", length=255)
     */
    private $ciudad;

    /**
     * @var bool
     *
     * @ORM\Column(name="estado", type="boolean")
     */
    private $estado;


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
     * Set referencia
     *
     * @param integer $referencia
     *
     * @return servicio
     */
    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;

        return $this;
    }

    /**
     * Get referencia
     *
     * @return int
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * Set compa�ia
     *
     * @param string $compania
     *
     * @return servicio
     */
    public function setCompania($compania)
    {
        $this->compania = $compania;

        return $this;
    }

    /**
     * Get compania
     *
     * @return string
     */
    public function getCompania()
    {
        return $this->compania;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return servicio
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set ciudad
     *
     * @param string $ciudad
     *
     * @return servicio
     */
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Get ciudad
     *
     * @return string
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     *
     * @return servicio
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return bool
     */
    public function getEstado()
    {
        return $this->estado;
    }
}


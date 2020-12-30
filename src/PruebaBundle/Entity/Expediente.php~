<?php

namespace PruebaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Expediente
 *
 * @ORM\Table(name="Expediente")
 * @ORM\Entity(repositoryClass="PruebaBundle\Repository\ExpedienteRepository")
 */
class Expediente
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
     * @var integer
     *
     * @ORM\Column(name="numeroExpe", type="integer")
     */
    private $numeroExpe;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=25)
     */
    private $estado;


    /**
     * @ORM\ManyToOne(targetEntity="servicio", inversedBy="expedientes")
     * @ORM\JoinColumn(name="id_servicio", referencedColumnName="id")
     */
    private $service;


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
     * Set numeroExpe
     *
     * @param integer $numeroExpe
     *
     * @return Expediente
     */
    public function setNumeroExpe($numeroExpe)
    {
        $this->numeroExpe = $numeroExpe;

        return $this;
    }

    /**
     * Get numeroExpe
     *
     * @return int
     */
    public function getNumeroExpe()
    {
        return $this->numeroExpe;
    }

    /**
     * Set estado
     *
     * @param string $estado
     *
     * @return Expediente
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }


    /**
     * Set service
     *
     * @param \PruebaBundle\Entity\servicio $service
     *
     * @return Expediente
     */
    public function setService(\PruebaBundle\Entity\servicio $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \PruebaBundle\Entity\servicio
     */
    public function getService()
    {
        return $this->service;
    }
}

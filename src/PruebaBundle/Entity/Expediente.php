<?php

namespace PruebaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Expediente
 *
 * @ORM\Table(name="expediente")
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
     * @var int
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
     * @var int
     *
     * @ORM\Column(name="referencia", type="integer")
     */
    private $referencia;


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
     * Set referencia
     *
     * @param integer $referencia
     *
     * @return Expediente
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
}


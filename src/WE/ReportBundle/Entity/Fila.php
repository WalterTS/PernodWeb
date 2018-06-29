<?php

namespace WE\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Activacion
 *
 * @ORM\Table(name="fila")
 * @ORM\Entity()
 */
class Fila {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @var integer
     *
     * @ORM\OneToMany(targetEntity="Valor", mappedBy="fila")
     */
    private $valores;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Activacion", inversedBy="filas")
     * @ORM\JoinColumn(name="activacion_id", referencedColumnName="id")
     */
    private $activacion;
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->valores = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add valore
     *
     * @param \WE\ReportBundle\Entity\Valor $valore
     *
     * @return Fila
     */
    public function addValore(\WE\ReportBundle\Entity\Valor $valore)
    {
        $this->valores[] = $valore;

        return $this;
    }

    /**
     * Remove valore
     *
     * @param \WE\ReportBundle\Entity\Valor $valore
     */
    public function removeValore(\WE\ReportBundle\Entity\Valor $valore)
    {
        $this->valores->removeElement($valore);
    }

    /**
     * Get valores
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getValores()
    {
        return $this->valores;
    }

    /**
     * Set activacion
     *
     * @param \WE\ReportBundle\Entity\Activacion $activacion
     *
     * @return Fila
     */
    public function setActivacion(\WE\ReportBundle\Entity\Activacion $activacion = null)
    {
        $this->activacion = $activacion;

        return $this;
    }

    /**
     * Get activacion
     *
     * @return \WE\ReportBundle\Entity\Activacion
     */
    public function getActivacion()
    {
        return $this->activacion;
    }
    
    public function __toString() {
        return $this->getActivacion()->getMarca()->getNombre();
    }
}

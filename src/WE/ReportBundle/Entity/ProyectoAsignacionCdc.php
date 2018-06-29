<?php

namespace WE\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProyectoAsignacionCdc
 *
 * @ORM\Table(name="proyecto_asignacion_cdc")
 * @ORM\Entity()
 */
class ProyectoAsignacionCdc {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="CDC")
     * @ORM\JoinColumn(name="cdc_id", referencedColumnName="id",nullable=true)
     */
    private $cdc;

    /**
     * @var integer
     *
     * @ORM\Column(name="total",type="integer")
     */
    private $total;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Proyecto", inversedBy="asignacionesCdc")
     * @ORM\JoinColumn(name="proyecto_id", referencedColumnName="id")
     */
    private $proyecto;


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
     * Set total
     *
     * @param integer $total
     *
     * @return ProyectoAsignacionCdc
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set cdc
     *
     * @param \WE\ReportBundle\Entity\CDC $cdc
     *
     * @return ProyectoAsignacionCdc
     */
    public function setCdc(\WE\ReportBundle\Entity\CDC $cdc = null)
    {
        $this->cdc = $cdc;

        return $this;
    }

    /**
     * Get cdc
     *
     * @return \WE\ReportBundle\Entity\CDC
     */
    public function getCdc()
    {
        return $this->cdc;
    }

    /**
     * Set proyecto
     *
     * @param \WE\ReportBundle\Entity\Proyecto $proyecto
     *
     * @return ProyectoAsignacionCdc
     */
    public function setProyecto(\WE\ReportBundle\Entity\Proyecto $proyecto = null)
    {
        $this->proyecto = $proyecto;

        return $this;
    }

    /**
     * Get proyecto
     *
     * @return \WE\ReportBundle\Entity\Proyecto
     */
    public function getProyecto()
    {
        return $this->proyecto;
    }
}

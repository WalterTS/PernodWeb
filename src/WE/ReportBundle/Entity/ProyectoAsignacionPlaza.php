<?php

namespace WE\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProyectoAsignacionPlaza
 *
 * @ORM\Table(name="proyecto_asignacion_plaza")
 * @ORM\Entity()
 */
class ProyectoAsignacionPlaza {

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
     * @ORM\ManyToOne(targetEntity="Plaza")
     * @ORM\JoinColumn(name="plaza_id", referencedColumnName="id",nullable=true)
     */
    private $plaza;

    /**
     * @var integer
     *
     * @ORM\Column(name="total",type="integer")
     */
    private $total;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Proyecto", inversedBy="asignacionesSquare")
     * @ORM\JoinColumn(name="proyecto_id", referencedColumnName="id")
     */
    private $proyecto;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set total
     *
     * @param integer $total
     *
     * @return ProyectoAsignacion
     */
    public function setTotal($total) {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return integer
     */
    public function getTotal() {
        return $this->total;
    }

    /**
     * Set plaza
     *
     * @param \WE\ReportBundle\Entity\Plaza $plaza
     *
     * @return ProyectoAsignacion
     */
    public function setPlaza(\WE\ReportBundle\Entity\Plaza $plaza = null) {
        $this->plaza = $plaza;

        return $this;
    }

    /**
     * Get plaza
     *
     * @return \WE\ReportBundle\Entity\Plaza
     */
    public function getPlaza() {
        return $this->plaza;
    }

    /**
     * Set proyecto
     *
     * @param \WE\ReportBundle\Entity\Proyecto $proyecto
     *
     * @return ProyectoAsignacion
     */
    public function setProyecto(\WE\ReportBundle\Entity\Proyecto $proyecto = null) {
        $this->proyecto = $proyecto;
        return $this;
    }

    /**
     * Get proyecto
     *
     * @return \WE\ReportBundle\Entity\Proyecto
     */
    public function getProyecto() {
        return $this->proyecto;
    }

}

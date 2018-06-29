<?php

namespace WE\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProyectoAsignacionRegion
 *
 * @ORM\Table(name="proyecto_asignacion_region")
 * @ORM\Entity()
 */
class ProyectoAsignacionRegion {

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
     * @ORM\ManyToOne(targetEntity="Region")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id",nullable=true)
     */
    private $region;

    /**
     * @var integer
     *
     * @ORM\Column(name="total",type="integer")
     */
    private $total;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Proyecto", inversedBy="asignacionesRegion")
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
     * @return ProyectoAsignacionRegion
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
     * Set region
     *
     * @param \WE\ReportBundle\Entity\Region $region
     *
     * @return ProyectoAsignacionRegion
     */
    public function setRegion(\WE\ReportBundle\Entity\Region $region = null)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return \WE\ReportBundle\Entity\Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set proyecto
     *
     * @param \WE\ReportBundle\Entity\Proyecto $proyecto
     *
     * @return ProyectoAsignacionRegion
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

<?php

namespace WE\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @ExclusionPolicy("all")
 * Activacion
 *
 *
 * @ORM\Table(name="plaza")
 * @ORM\Entity
 */
class Plaza {

    /**
     * @var integer
     * @Expose
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="nombre", type="string",length=70)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="abreviacion", type="string",length=4)
     */
    private $abreviacion;

    /**
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CDC", mappedBy="plaza", cascade={"persist"})
     */
    private $cdcs;
    
       /**
     *
     * @ORM\ManyToOne(targetEntity="Region", inversedBy="plazas")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     */
    private $region;
    
       /**
     *
     * @var integer
     *
     * @ORM\ManyToMany(targetEntity="Proyecto", mappedBy="plazas",cascade={"persist"})
     * @ORM\JoinTable(name="proyecto_plaza")
     */
    private $proyectos;

    /**
     * Constructor
     */
    public function __construct() {
        $this->cdcs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Plaza
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Add cdc
     *
     * @param \WE\ReportBundle\Entity\CDC $cdc
     *
     * @return Plaza
     */
    public function addCdc(\WE\ReportBundle\Entity\CDC $cdc) {
        $this->cdcs[] = $cdc;

        return $this;
    }

    /**
     * Remove cdc
     *
     * @param \WE\ReportBundle\Entity\CDC $cdc
     */
    public function removeCdc(\WE\ReportBundle\Entity\CDC $cdc) {
        $this->cdcs->removeElement($cdc);
    }

    /**
     * Get cdcs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCdcs() {
        return $this->cdcs;
    }

    public function __toString() {
        return $this->getNombre();
    }


    /**
     * Set abreviacion
     *
     * @param string $abreviacion
     *
     * @return Plaza
     */
    public function setAbreviacion($abreviacion)
    {
        $this->abreviacion = $abreviacion;

        return $this;
    }

    /**
     * Get abreviacion
     *
     * @return string
     */
    public function getAbreviacion()
    {
        return $this->abreviacion;
    }

    /**
     * Set region
     *
     * @param \WE\ReportBundle\Entity\Region $region
     *
     * @return Plaza
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
     * Add proyecto
     *
     * @param \WE\ReportBundle\Entity\Proyecto $proyecto
     *
     * @return Plaza
     */
    public function addProyecto(\WE\ReportBundle\Entity\Proyecto $proyecto)
    {
        $this->proyectos[] = $proyecto;

        return $this;
    }

    /**
     * Remove proyecto
     *
     * @param \WE\ReportBundle\Entity\Proyecto $proyecto
     */
    public function removeProyecto(\WE\ReportBundle\Entity\Proyecto $proyecto)
    {
        $this->proyectos->removeElement($proyecto);
    }

    /**
     * Get proyectos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProyectos()
    {
        return $this->proyectos;
    }
}

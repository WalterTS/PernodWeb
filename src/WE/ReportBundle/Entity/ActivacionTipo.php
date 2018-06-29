<?php

namespace WE\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @ExclusionPolicy("all")
 * ActivacionTipo
 *
 * @ORM\Table(name="activacion_tipo")
 * @ORM\Entity(repositoryClass="WE\ReportBundle\Repository\ActivacionTipoRepository")
 */
class ActivacionTipo {

    /**
     * @var int
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
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ActivacionTipoTalento", mappedBy="activacion_tipo", cascade={"persist"})
     */
    private $talento;

    /**
     *
     * @var integer
     * 
     * @ORM\ManyToMany(targetEntity="ActivacionTipoMaterial", cascade={"persist"})
     * @ORM\JoinTable(name="activacion_tipo_with_material")
     */
    private $materiales;

    /**
     *
     * @var integer
     * 
     * @ORM\ManyToMany(targetEntity="Proyecto", mappedBy="activaciones_tipo",cascade={"persist"})
     * @ORM\JoinTable(name="activacion_tipo_proyecto")
     */
    private $proyectos;

    /**
     * @var boolean
     * @ORM\Column(name="degustacion", type="boolean")
     */
    private $degustacion;

    /**
     * @var string
     * @ORM\Column(name="degustacion_total", type="integer")
     */
    private $degustacion_total;

    /**
     * @var boolean
     * @ORM\Column(name="giveaway", type="boolean")
     */
    private $giveaway;

    /**
     * @var boolean
     * @ORM\Column(name="giveaway_material", type="string",length=80)
     */
    private $giveaway_material;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->proyectos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add proyecto
     *
     * @param \WE\ReportBundle\Entity\Proyecto $proyecto
     *
     * @return ActivacionTipo
     */
    public function addProyecto(\WE\ReportBundle\Entity\Proyecto $proyecto) {
        $this->proyectos[] = $proyecto;

        return $this;
    }

    /**
     * Remove proyecto
     *
     * @param \WE\ReportBundle\Entity\Proyecto $proyecto
     */
    public function removeProyecto(\WE\ReportBundle\Entity\Proyecto $proyecto) {
        $this->proyectos->removeElement($proyecto);
    }

    /**
     * Get proyectos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProyectos() {
        return $this->proyectos;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return ActivacionTipo
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
     * Add talento
     *
     * @param \WE\ReportBundle\Entity\ActivacionTipoTalento $talento
     *
     * @return ActivacionTipo
     */
    public function addTalento(\WE\ReportBundle\Entity\ActivacionTipoTalento $talento) {
        $this->talento[] = $talento;

        return $this;
    }

    /**
     * Remove talento
     *
     * @param \WE\ReportBundle\Entity\ActivacionTipoTalento $talento
     */
    public function removeTalento(\WE\ReportBundle\Entity\ActivacionTipoTalento $talento) {
        $this->talento->removeElement($talento);
    }

    /**
     * Get talento
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTalento() {
        return $this->talento;
    }

    /**
     * Add materiale
     *
     * @param \WE\ReportBundle\Entity\ActivacionTipoMaterial $materiale
     *
     * @return ActivacionTipo
     */
    public function addMateriale(\WE\ReportBundle\Entity\ActivacionTipoMaterial $materiale) {
        $this->materiales[] = $materiale;

        return $this;
    }

    /**
     * Remove materiale
     *
     * @param \WE\ReportBundle\Entity\ActivacionTipoMaterial $materiale
     */
    public function removeMateriale(\WE\ReportBundle\Entity\ActivacionTipoMaterial $materiale) {
        $this->materiales->removeElement($materiale);
    }

    /**
     * Get materiales
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMateriales() {
        return $this->materiales;
    }


    /**
     * Set degustacion
     *
     * @param boolean $degustacion
     *
     * @return ActivacionTipo
     */
    public function setDegustacion($degustacion)
    {
        $this->degustacion = $degustacion;

        return $this;
    }

    /**
     * Get degustacion
     *
     * @return boolean
     */
    public function getDegustacion()
    {
        return $this->degustacion;
    }

    /**
     * Set degustacionTotal
     *
     * @param integer $degustacionTotal
     *
     * @return ActivacionTipo
     */
    public function setDegustacionTotal($degustacionTotal)
    {
        $this->degustacion_total = $degustacionTotal;

        return $this;
    }

    /**
     * Get degustacionTotal
     *
     * @return integer
     */
    public function getDegustacionTotal()
    {
        return $this->degustacion_total;
    }

    /**
     * Set giveaway
     *
     * @param boolean $giveaway
     *
     * @return ActivacionTipo
     */
    public function setGiveaway($giveaway)
    {
        $this->giveaway = $giveaway;

        return $this;
    }

    /**
     * Get giveaway
     *
     * @return boolean
     */
    public function getGiveaway()
    {
        return $this->giveaway;
    }

    /**
     * Set giveawayMaterial
     *
     * @param string $giveawayMaterial
     *
     * @return ActivacionTipo
     */
    public function setGiveawayMaterial($giveawayMaterial)
    {
        $this->giveaway_material = $giveawayMaterial;

        return $this;
    }

    /**
     * Get giveawayMaterial
     *
     * @return string
     */
    public function getGiveawayMaterial()
    {
        return $this->giveaway_material;
    }
    
    public function __toString() {
        return $this->getNombre();
    }
}

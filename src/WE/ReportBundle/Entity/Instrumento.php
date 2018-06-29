<?php

namespace WE\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Instrumento
 *
 * @ORM\Table(name="instrumento")
 * @ORM\Entity(repositoryClass="WE\ReportBundle\Entity\Repository\InstrumentoRepository")
 */
class Instrumento {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text")
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="instrucciones", type="text")
     */
    private $instrucciones;

    /**
     * @var integer 
     *
     * @ORM\OneToMany(targetEntity="Seccion", mappedBy="instrumento",cascade={"persist"})
     */
    private $secciones;

    /**
     * @var integer 
     *
     * @ORM\OneToMany(targetEntity="Reporte", mappedBy="instrumento",cascade={"persist"})
     */
    private $reportes;

    /**
     *  @var integer
     *
     * @ORM\ManyToMany(targetEntity="Marca", inversedBy="instrumentos",cascade={"persist"})
     */
    private $marcas;

    /**
     * Constructor
     */

    /**
     * Constructor
     */
    public function __construct() {
        $this->secciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->marcas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Instrumento
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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Instrumento
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * Set instrucciones
     *
     * @param string $instrucciones
     *
     * @return Instrumento
     */
    public function setInstrucciones($instrucciones) {
        $this->instrucciones = $instrucciones;

        return $this;
    }

    /**
     * Get instrucciones
     *
     * @return string
     */
    public function getInstrucciones() {
        return $this->instrucciones;
    }

    /**
     * Add seccione
     *
     * @param \WE\ReportBundle\Entity\Seccion $seccione
     *
     * @return Instrumento
     */
    public function addSeccione(\WE\ReportBundle\Entity\Seccion $seccione) {
        $seccione->setInstrumento($this);
        $this->secciones[] = $seccione;
        return $this;
    }

    /**
     * Remove seccione
     *
     * @param \WE\ReportBundle\Entity\Seccion $seccione
     */
    public function removeSeccione(\WE\ReportBundle\Entity\Seccion $seccione) {
        $this->secciones->removeElement($seccione);
    }

    /**
     * Get secciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSecciones() {
        return $this->secciones;
    }

    /**
     * Add marca
     *
     * @param \WE\ReportBundle\Entity\Marca $marca
     *
     * @return Instrumento
     */
    public function addMarca(\WE\ReportBundle\Entity\Marca $marca) {
        $this->marcas[] = $marca;

        return $this;
    }

    /**
     * Remove marca
     *
     * @param \WE\ReportBundle\Entity\Marca $marca
     */
    public function removeMarca(\WE\ReportBundle\Entity\Marca $marca) {
        $this->marcas->removeElement($marca);
    }

    /**
     * Get marcas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMarcas() {
        return $this->marcas;
    }

    public function __toString() {
        return $this->getNombre();
    }


    /**
     * Add reporte
     *
     * @param \WE\ReportBundle\Entity\Reporte $reporte
     *
     * @return Instrumento
     */
    public function addReporte(\WE\ReportBundle\Entity\Reporte $reporte)
    {
        $this->reportes[] = $reporte;

        return $this;
    }

    /**
     * Remove reporte
     *
     * @param \WE\ReportBundle\Entity\Reporte $reporte
     */
    public function removeReporte(\WE\ReportBundle\Entity\Reporte $reporte)
    {
        $this->reportes->removeElement($reporte);
    }

    /**
     * Get reportes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReportes()
    {
        return $this->reportes;
    }
}

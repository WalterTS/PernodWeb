<?php

namespace WE\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Proyecto
 *
 * @ORM\Table(name="proyecto")
 * @ORM\Entity(repositoryClass="WE\ReportBundle\Repository\ProyectoRepository")
 */
class Proyecto {

    /**
     * Constructor
     */
    public function __construct() {
        $this->activaciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->activaciones_tipo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->regiones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->plazas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cdcs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->asignacionesRegion = new \Doctrine\Common\Collections\ArrayCollection();
        $this->asignacionesSquare = new \Doctrine\Common\Collections\ArrayCollection();
        $this->asignacionesCdc = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @ORM\Column(name="nombre", type="string")
     */
    private $nombre;

    /**
     * @var datetime
     *
     * @ORM\Column(name="fecha_inicio", type="datetime")
     */
    private $fecha_inicio;

    /**
     * @var datetime
     *
     * @ORM\Column(name="fecha_fin", type="datetime")
     */
    private $fecha_fin;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Marca", inversedBy="proyectos")
     * @ORM\JoinColumn(name="marca_id", referencedColumnName="id")
     */
    private $marca;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_activaciones",type="integer")
     */
    private $total_activaciones;

    /**
     * @var integer
     *
     * @ORM\Column(name="kpi_tipo",type="integer")
     */
    private $kpi_tipo;

    /**
     * @var integer
     *
     * @ORM\Column(name="kpi_total",type="integer")
     */
    private $kpi_total;

    /**
     * @var integer
     *
     * @ORM\Column(name="maximo_plaza",type="integer")
     */
    private $maximo_plaza;

    /**
     * @var integer
     *
     * @ORM\Column(name="tiempo_cancelacion",type="integer")
     */
    private $tiempo_cancelacion;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Agencia", inversedBy="proyectos")
     * @ORM\JoinColumn(name="agencia_id", referencedColumnName="id")
     */
    private $agencia;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(name="responsable_id", referencedColumnName="id")
     */
    private $responsable;

    /**
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Activacion", mappedBy="proyecto", cascade={"remove"})
     */
    private $activaciones;

    /**
     *  @var integer
     *
     * @ORM\ManyToMany(targetEntity="ActivacionTipo", inversedBy="proyectos",cascade={"persist"})
     */
    private $activaciones_tipo;

    /**
     *  @var integer
     *
     * @ORM\ManyToMany(targetEntity="Region", inversedBy="proyectos",cascade={"persist"})
     */
    private $regiones;

    /**
     *  @var integer
     *
     * @ORM\ManyToMany(targetEntity="Plaza", inversedBy="proyectos",cascade={"persist"})
     */
    private $plazas;

    /**
     *  @var integer
     *
     * @ORM\ManyToMany(targetEntity="CDC", inversedBy="proyectos",cascade={"persist"})
     */
    private $cdcs;

    /**
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ProyectoAsignacionRegion", mappedBy="proyecto", cascade={"persist"})
     */
    private $asignacionesRegion;

    /**
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ProyectoAsignacionPlaza", mappedBy="proyecto", cascade={"persist"})
     */
    private $asignacionesSquare;

    /**
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ProyectoAsignacionCdc", mappedBy="proyecto", cascade={"persist"})
     */
    private $asignacionesCdc;

    /**
     *
     * @ORM\Column(name="descripcion", type="text")
     */
    private $descripcion;

    /**
     * @var integer
     *
     * @ORM\Column(name="kpi_impactos",type="integer")
     */
    private $kpi_impactos;

    /**
     * @var integer
     *
     * @ORM\Column(name="kpi_degustaciones",type="integer")
     */
    private $kpi_degustaciones;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Proyecto
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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     *
     * @return Proyecto
     */
    public function setFechaInicio($fechaInicio) {
        $this->fecha_inicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime
     */
    public function getFechaInicio() {
        return $this->fecha_inicio;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     *
     * @return Proyecto
     */
    public function setFechaFin($fechaFin) {
        $this->fecha_fin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime
     */
    public function getFechaFin() {
        return $this->fecha_fin;
    }

    /**
     * Set totalActivaciones
     *
     * @param integer $totalActivaciones
     *
     * @return Proyecto
     */
    public function setTotalActivaciones($totalActivaciones) {
        $this->total_activaciones = $totalActivaciones;

        return $this;
    }

    /**
     * Get totalActivaciones
     *
     * @return integer
     */
    public function getTotalActivaciones() {
        return $this->total_activaciones;
    }

    /**
     * Set marca
     *
     * @param \WE\ReportBundle\Entity\Marca $marca
     *
     * @return Proyecto
     */
    public function setMarca(\WE\ReportBundle\Entity\Marca $marca = null) {
        $this->marca = $marca;

        return $this;
    }

    /**
     * Get marca
     *
     * @return \WE\ReportBundle\Entity\Marca
     */
    public function getMarca() {
        return $this->marca;
    }

    /**
     * Add activacione
     *
     * @param \WE\ReportBundle\Entity\Activacion $activacione
     *
     * @return Proyecto
     */
    public function addActivacione(\WE\ReportBundle\Entity\Activacion $activacione) {
        $this->activaciones[] = $activacione;
        $activacione->setProyecto($this);
        return $this;
    }

    /**
     * Remove activacione
     *
     * @param \WE\ReportBundle\Entity\Activacion $activacione
     */
    public function removeActivacione(\WE\ReportBundle\Entity\Activacion $activacione) {
        $this->activaciones->removeElement($activacione);
    }

    /**
     * Get activaciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActivaciones() {
        return $this->activaciones;
    }

    public function __toString() {
        return $this->getNombre() . ': ' . $this->getMarca()->getNombre();
    }

    /**
     * Add activacionesTipo
     *
     * @param \WE\ReportBundle\Entity\ActivacionTipo $activacionesTipo
     *
     * @return Proyecto
     */
    public function addActivacionesTipo(\WE\ReportBundle\Entity\ActivacionTipo $activacionesTipo) {
        $this->activaciones_tipo[] = $activacionesTipo;

        return $this;
    }

    /**
     * Remove activacionesTipo
     *
     * @param \WE\ReportBundle\Entity\ActivacionTipo $activacionesTipo
     */
    public function removeActivacionesTipo(\WE\ReportBundle\Entity\ActivacionTipo $activacionesTipo) {
        $this->activaciones_tipo->removeElement($activacionesTipo);
    }

    /**
     * Get activacionesTipo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActivacionesTipo() {
        return $this->activaciones_tipo;
    }

    /**
     * Set maximoPlaza
     *
     * @param integer $maximoPlaza
     *
     * @return Proyecto
     */
    public function setMaximoPlaza($maximoPlaza) {
        $this->maximo_plaza = $maximoPlaza;

        return $this;
    }

    /**
     * Get maximoPlaza
     *
     * @return integer
     */
    public function getMaximoPlaza() {
        return $this->maximo_plaza;
    }

    /**
     * Set tiempoCancelacion
     *
     * @param integer $tiempoCancelacion
     *
     * @return Proyecto
     */
    public function setTiempoCancelacion($tiempoCancelacion) {
        $this->tiempo_cancelacion = $tiempoCancelacion;

        return $this;
    }

    /**
     * Get tiempoCancelacion
     *
     * @return integer
     */
    public function getTiempoCancelacion() {
        return $this->tiempo_cancelacion;
    }

    /**
     * Set agencia
     *
     * @param \WE\ReportBundle\Entity\Agencia $agencia
     *
     * @return Proyecto
     */
    public function setAgencia(\WE\ReportBundle\Entity\Agencia $agencia = null) {
        $this->agencia = $agencia;

        return $this;
    }

    /**
     * Get agencia
     *
     * @return \WE\ReportBundle\Entity\Agencia
     */
    public function getAgencia() {
        return $this->agencia;
    }

    /**
     * Set kpiTipo
     *
     * @param integer $kpiTipo
     *
     * @return Proyecto
     */
    public function setKpiTipo($kpiTipo) {
        $this->kpi_tipo = $kpiTipo;

        return $this;
    }

    /**
     * Get kpiTipo
     *
     * @return integer
     */
    public function getKpiTipo() {
        return $this->kpi_tipo;
    }

    /**
     * Set kpiTotal
     *
     * @param integer $kpiTotal
     *
     * @return Proyecto
     */
    public function setKpiTotal($kpiTotal) {
        $this->kpi_total = $kpiTotal;

        return $this;
    }

    /**
     * Get kpiTotal
     *
     * @return integer
     */
    public function getKpiTotal() {
        return $this->kpi_total;
    }

    /**
     * Add regione
     *
     * @param \WE\ReportBundle\Entity\Region $regione
     *
     * @return Proyecto
     */
    public function addRegione(\WE\ReportBundle\Entity\Region $regione) {
        $this->regiones[] = $regione;

        return $this;
    }

    /**
     * Remove regione
     *
     * @param \WE\ReportBundle\Entity\Region $regione
     */
    public function removeRegione(\WE\ReportBundle\Entity\Region $regione) {
        $this->regiones->removeElement($regione);
    }

    /**
     * Get regiones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegiones() {
        return $this->regiones;
    }

    /**
     * Add plaza
     *
     * @param \WE\ReportBundle\Entity\Plaza $plaza
     *
     * @return Proyecto
     */
    public function addPlaza(\WE\ReportBundle\Entity\Plaza $plaza) {
        $this->plazas[] = $plaza;

        return $this;
    }

    /**
     * Remove plaza
     *
     * @param \WE\ReportBundle\Entity\Plaza $plaza
     */
    public function removePlaza(\WE\ReportBundle\Entity\Plaza $plaza) {
        $this->plazas->removeElement($plaza);
    }

    /**
     * Get plazas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlazas() {
        return $this->plazas;
    }

    /**
     * Add cdc
     *
     * @param \WE\ReportBundle\Entity\CDC $cdc
     *
     * @return Proyecto
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

    /**
     * Set responsable
     *
     * @param \WE\ReportBundle\Entity\Usuario $responsable
     *
     * @return Proyecto
     */
    public function setResponsable(\WE\ReportBundle\Entity\Usuario $responsable = null) {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return \WE\ReportBundle\Entity\Usuario
     */
    public function getResponsable() {
        return $this->responsable;
    }

    /**
     * Add asignacionesRegion
     *
     * @param \WE\ReportBundle\Entity\ProyectoAsignacionRegion $asignacionesRegion
     *
     * @return Proyecto
     */
    public function addAsignacionesRegion(\WE\ReportBundle\Entity\ProyectoAsignacionRegion $asignacionesRegion) {
        $this->asignacionesRegion[] = $asignacionesRegion;
        $asignacionesRegion->setProyecto($this);
        return $this;
    }

    /**
     * Remove asignacionesRegion
     *
     * @param \WE\ReportBundle\Entity\ProyectoAsignacionRegion $asignacionesRegion
     */
    public function removeAsignacionesRegion(\WE\ReportBundle\Entity\ProyectoAsignacionRegion $asignacionesRegion) {
        $this->asignacionesRegion->removeElement($asignacionesRegion);
    }

    /**
     * Get asignacionesRegion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAsignacionesRegion() {
        return $this->asignacionesRegion;
    }

    /**
     * Add asignacionesCdc
     *
     * @param \WE\ReportBundle\Entity\ProyectoAsignacionCdc $asignacionesCdc
     *
     * @return Proyecto
     */
    public function addAsignacionesCdc(\WE\ReportBundle\Entity\ProyectoAsignacionCdc $asignacionesCdc) {
        $this->asignacionesCdc[] = $asignacionesCdc;
        $asignacionesCdc->setProyecto($this);
        return $this;
    }

    /**
     * Remove asignacionesCdc
     *
     * @param \WE\ReportBundle\Entity\ProyectoAsignacionCdc $asignacionesCdc
     */
    public function removeAsignacionesCdc(\WE\ReportBundle\Entity\ProyectoAsignacionCdc $asignacionesCdc) {
        $this->asignacionesCdc->removeElement($asignacionesCdc);
    }

    /**
     * Get asignacionesCdc
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAsignacionesCdc() {
        return $this->asignacionesCdc;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Proyecto
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

    public function getPlazasString() {
        $plazas = array();
        if ($this->getPlazas()) {
            foreach ($this->getPlazas() as $plaza) {
                $plazas[] = $plaza->getNombre();
            }
        }

        return implode(",", $plazas);
    }

    public function getCdcsString() {
        $cdcs = array();
        if ($this->getCdcs()) {
            foreach ($this->getCdcs() as $cdc) {
                $cdcs[] = $cdc->getNombre();
            }
        }

        return implode(",", $cdcs);
    }

    public function getRegionesString() {
        $regiones = array();
        if ($this->getRegiones()) {
            foreach ($this->getRegiones() as $region) {
                $regiones[] = $region->getNombre();
            }
        }

        return implode(",", $regiones);
    }

    /**
     * Set kpiImpactos
     *
     * @param integer $kpiImpactos
     *
     * @return Proyecto
     */
    public function setKpiImpactos($kpiImpactos) {
        $this->kpi_impactos = $kpiImpactos;

        return $this;
    }

    /**
     * Get kpiImpactos
     *
     * @return integer
     */
    public function getKpiImpactos() {
        return $this->kpi_impactos;
    }

    /**
     * Set kpiDegustaciones
     *
     * @param integer $kpiDegustaciones
     *
     * @return Proyecto
     */
    public function setKpiDegustaciones($kpiDegustaciones) {
        $this->kpi_degustaciones = $kpiDegustaciones;

        return $this;
    }

    /**
     * Get kpiDegustaciones
     *
     * @return integer
     */
    public function getKpiDegustaciones() {
        return $this->kpi_degustaciones;
    }

    /**
     * Add asignacionesSquare
     *
     * @param \WE\ReportBundle\Entity\ProyectoAsignacionPlaza $asignacionesSquare
     *
     * @return Proyecto
     */
    public function addAsignacionesSquare(\WE\ReportBundle\Entity\ProyectoAsignacionPlaza $asignacionesSquare) {
        $this->asignacionesSquare[] = $asignacionesSquare;
        $asignacionesSquare->setProyecto($this);
        return $this;
    }

    /**
     * Remove asignacionesSquare
     *
     * @param \WE\ReportBundle\Entity\ProyectoAsignacionPlaza $asignacionesSquare
     */
    public function removeAsignacionesSquare(\WE\ReportBundle\Entity\ProyectoAsignacionPlaza $asignacionesSquare) {
        $this->asignacionesSquare->removeElement($asignacionesSquare);
    }

    /**
     * Get asignacionesSquare
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAsignacionesSquare() {
        return $this->asignacionesSquare;
    }

    public function getActivacionesEjecutadas() {
        return $this->getActivaciones()->filter(function($activacion) {
                    return $activacion->getStatus()->getId() == 5;
                });
    }

    public function getActivacionesFaltantes() {
       return $this->getTotalActivaciones() - $this->getActivacionesEjecutadas()->count();
    }

}

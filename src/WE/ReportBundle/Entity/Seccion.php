<?php
namespace WE\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Seccion
 *
 * @ORM\Table(name="seccion")
 * @ORM\Entity(repositoryClass="WE\ReportBundle\Entity\Repository\SeccionRepository")
 */
class Seccion
{
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
     * @ORM\Column(name="instrucciones", type="text")
     */
    private $instrucciones;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Instrumento", inversedBy="secciones")
     * @ORM\JoinColumn(name="instrumento_id", referencedColumnName="id")
     */
    private $instrumento;

    /**
     * @var integer 
     *
     * @ORM\OneToMany(targetEntity="Columna", mappedBy="seccion",cascade={"persist"})
     */
    private $columnas;

    /**
     * Constructor por default para secciones
     * 
     */
    public function __construct() {
        $this->columnas = new ArrayCollection();
    }

    /**
     * Regresa el texto del nombre de la sección, para combo boxes
     * 
     * @return string
     */
    public function __toString() {
        return $this->nombre;
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
     * Set nombre
     *
     * @param string $nombre
     * @return Seccion
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set instrucciones
     *
     * @param string $instrucciones
     * @return Seccion
     */
    public function setInstrucciones($instrucciones)
    {
        $this->instrucciones = $instrucciones;

        return $this;
    }

    /**
     * Get instrucciones
     *
     * @return string 
     */
    public function getInstrucciones()
    {
        return $this->instrucciones;
    }

    /**
     * Set instrumento
     *
     * @param \WE\ReportBundle\Entity\Instrumento $instrumento
     * @return Seccion
     */
    public function setInstrumento(\WE\ReportBundle\Entity\Instrumento $instrumento = null)
    {
        $this->instrumento = $instrumento;

        return $this;
    }

    /**
     * Get instrumento
     *
     * @return \WE\ReportBundle\Entity\Instrumento 
     */
    public function getInstrumento()
    {
        return $this->instrumento;
    }

    /**
     * Add columnas
     *
     * @param \WE\ReportBundle\Entity\Columna $columnas
     * @return Seccion
     */
    public function addColumna(\WE\ReportBundle\Entity\Columna $columnas)
    {
        $this->columnas[] = $columnas;
        $columnas->setSeccion($this);
        return $this;
    }

    /**
     * Remove columnas
     *
     * @param \WE\ReportBundle\Entity\Columna $columnas
     */
    public function removeColumna(\WE\ReportBundle\Entity\Columna $columnas)
    {
        $this->columnas->removeElement($columnas);
    }

    /**
     * Get columnas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getColumnas()
    {
        return $this->columnas;
    }
}

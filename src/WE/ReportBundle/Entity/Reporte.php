<?php

namespace WE\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reporte
 *
 * @ORM\Table(name="reporte")
 * @ORM\Entity(repositoryClass="WE\ReportBundle\Repository\ReporteRepository")
 */
class Reporte {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="key_reference", type="string",length=30)
     */
    private $key_reference;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Instrumento", inversedBy="reportes")
     * @ORM\JoinColumn(name="instrumento_id", referencedColumnName="id")
     */
    private $instrumento;

    /**
     *
     * @var integer
     *
     * @ORM\ManyToMany(targetEntity="Columna",cascade={"persist"})
     * @ORM\JoinTable(name="reporte_columna")
     */
    private $columnas;

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
    public function __construct()
    {
        $this->columnas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set keyReference
     *
     * @param string $keyReference
     *
     * @return Reporte
     */
    public function setKeyReference($keyReference)
    {
        $this->key_reference = $keyReference;

        return $this;
    }

    /**
     * Get keyReference
     *
     * @return string
     */
    public function getKeyReference()
    {
        return $this->key_reference;
    }

    /**
     * Set instrumento
     *
     * @param \WE\ReportBundle\Entity\Instrumento $instrumento
     *
     * @return Reporte
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
     * Add columna
     *
     * @param \WE\ReportBundle\Entity\Columna $columna
     *
     * @return Reporte
     */
    public function addColumna(\WE\ReportBundle\Entity\Columna $columna)
    {
        $this->columnas[] = $columna;

        return $this;
    }

    /**
     * Remove columna
     *
     * @param \WE\ReportBundle\Entity\Columna $columna
     */
    public function removeColumna(\WE\ReportBundle\Entity\Columna $columna)
    {
        $this->columnas->removeElement($columna);
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
    
    public function __toString() {
        return $this->getInstrumento()->getNombre();
    }
}

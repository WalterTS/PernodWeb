<?php

namespace WE\ReportBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TipoColumna
 *
 * @ORM\Table(name="tipo_columna")
 * @ORM\Entity(repositoryClass="WE\ReportBundle\Entity\Repository\TipoColumnaRepository")
 */
class TipoColumna {

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
     * @ORM\Column(name="tipo_columna", type="string", length=150)
     */
    private $tipoColumna;

    /**
     * @var integer
     *
     * @ORM\Column(name="longitud_minima",type="integer")
     */
    private $longitud_minima;

    /**
     * @var integer
     *
     * @ORM\Column(name="longitud_maxima",type="integer")
     */
    private $longitud_maxima;

    /**
     * @var integer
     * 
     * @ORM\OneToMany(targetEntity="Columna", mappedBy="tipo")
     */
    private $columnas;

    /**
     * Constructor por default
     * 
     */
    public function __construct() {
        $this->columnas = new ArrayCollection();
    }

    /**
     * Regresa el texto del tipo de columna, para combo boxes
     * 
     * @return string
     */
    public function __toString() {
        return $this->tipoColumna;
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
     * Set tipoColumna
     *
     * @param string $tipoColumna
     * @return TipoColumna
     */
    public function setTipoColumna($tipoColumna) {
        $this->tipoColumna = $tipoColumna;

        return $this;
    }

    /**
     * Get tipoColumna
     *
     * @return string 
     */
    public function getTipoColumna() {
        return $this->tipoColumna;
    }

    /**
     * Add columnas
     *
     * @param \WE\ReportBundle\Entity\Columna $columnas
     * @return TipoColumna
     */
    public function addColumna(\WE\ReportBundle\Entity\Columna $columnas) {
        $this->columnas[] = $columnas;

        return $this;
    }

    /**
     * Remove columnas
     *
     * @param \WE\ReportBundle\Entity\Columna $columnas
     */
    public function removeColumna(\WE\ReportBundle\Entity\Columna $columnas) {
        $this->columnas->removeElement($columnas);
    }

    /**
     * Get columnas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getColumnas() {
        return $this->columnas;
    }


    /**
     * Set longitudMinima
     *
     * @param integer $longitudMinima
     *
     * @return TipoColumna
     */
    public function setLongitudMinima($longitudMinima)
    {
        $this->longitud_minima = $longitudMinima;

        return $this;
    }

    /**
     * Get longitudMinima
     *
     * @return integer
     */
    public function getLongitudMinima()
    {
        return $this->longitud_minima;
    }

    /**
     * Set longitudMaxima
     *
     * @param integer $longitudMaxima
     *
     * @return TipoColumna
     */
    public function setLongitudMaxima($longitudMaxima)
    {
        $this->longitud_maxima = $longitudMaxima;

        return $this;
    }

    /**
     * Get longitudMaxima
     *
     * @return integer
     */
    public function getLongitudMaxima()
    {
        return $this->longitud_maxima;
    }
}

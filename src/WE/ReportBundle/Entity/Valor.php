<?php

namespace WE\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Valor
 *
 * @ORM\Table(name="valor")
 * @ORM\Entity(repositoryClass="WE\ReportBundle\Entity\Repository\ValorRepository")
 */
class Valor {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Fila", inversedBy="valores")
     * @ORM\JoinColumn(name="fila_id", referencedColumnName="id")
     */
    private $fila;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Columna", inversedBy="valores")
     * @ORM\JoinColumn(name="columna_id", referencedColumnName="id")
     */
    private $columna;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Item")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     */
    private $item;

    /**
     * @var string
     * 
     * @ORM\Column(name="valor", type="text",nullable=true)
     */
    private $valor;

    /**
     * Constructor por default para valores
     * 
     */
    public function __construct() {
        
    }

    /**
     * Regresa el texto de la valor, para combo boxes
     * 
     * @return string
     */
    public function __toString() {
        return $this->item;
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
     * Set activacion
     *
     * @param integer $activacion
     * @return Valor
     */
    public function setActivacion($activacion) {
        $this->activacion = $activacion;

        return $this;
    }

    /**
     * Get activacion
     *
     * @return \WE\ReportBundle\Entity\Activacion 
     */
    public function getActivacion() {
        return $this->activacion;
    }

    /**
     * Set columna
     *
     * @param \WE\ReportBundle\Entity\Columna $columna
     * @return Valor
     */
    public function setColumna(\WE\ReportBundle\Entity\Columna $columna = null) {
        $this->columna = $columna;

        return $this;
    }

    /**
     * Get columna
     *
     * @return \WE\ReportBundle\Entity\Columna 
     */
    public function getColumna() {
        return $this->columna;
    }

    /**
     * Set item
     *
     * @param \WE\ReportBundle\Entity\Item $item
     * @return Valor
     */
    public function setItem(\WE\ReportBundle\Entity\Item $item = null) {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return \WE\ReportBundle\Entity\Item 
     */
    public function getItem() {
        return $this->item;
    }

    /**
     * Set valor
     *
     * @param string $valor
     *
     * @return Valor
     */
    public function setValor($valor) {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return string
     */
    public function getValor() {
        return $this->valor;
    }


    /**
     * Set fila
     *
     * @param \WE\ReportBundle\Entity\Fila $fila
     *
     * @return Valor
     */
    public function setFila(\WE\ReportBundle\Entity\Fila $fila = null)
    {
        $this->fila = $fila;

        return $this;
    }

    /**
     * Get fila
     *
     * @return \WE\ReportBundle\Entity\Fila
     */
    public function getFila()
    {
        return $this->fila;
    }
}

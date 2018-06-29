<?php

namespace WE\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Item
 *
 * @ORM\Table(name="item")
 * @ORM\Entity(repositoryClass="WE\ReportBundle\Entity\Repository\ItemRepository")
 */
class Item {

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
     * @ORM\Column(name="texto", type="string", length=255)
     */
    private $texto;

    /**
     * @var integer
     *
     * @ORM\Column(name="orden", type="integer")
     */
    private $orden;

    /**
     * @var string
     *
     * @ORM\Column(name="valor", type="string", length=100,nullable=true)
     */
    private $valor;

    /**
     *  @var integer
     *
     * @ORM\ManyToMany(targetEntity="Columna", inversedBy="items",cascade={"persist"})
     */
    private $columnas;

   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->columnas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set texto
     *
     * @param string $texto
     *
     * @return Item
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;

        return $this;
    }

    /**
     * Get texto
     *
     * @return string
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * Set orden
     *
     * @param integer $orden
     *
     * @return Item
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;

        return $this;
    }

    /**
     * Get orden
     *
     * @return integer
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Set valor
     *
     * @param string $valor
     *
     * @return Item
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return string
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Add columna
     *
     * @param \WE\ReportBundle\Entity\Columna $columna
     *
     * @return Item
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
        return $this->getTexto();
    }
}

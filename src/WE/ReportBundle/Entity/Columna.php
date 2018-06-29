<?php
namespace WE\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Columna
 *
 * @ORM\Table(name="columna")
 * @ORM\Entity(repositoryClass="WE\ReportBundle\Entity\Repository\ColumnaRepository")
 */
class Columna
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="text")
     */
    private $texto;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="TipoColumna", inversedBy="columnas")
     * @ORM\JoinColumn(name="tipo_id", referencedColumnName="id")
     */
    private $tipo;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Seccion", inversedBy="columnas")
     * @ORM\JoinColumn(name="seccion_id", referencedColumnName="id")
     */
    private $seccion;


    /**
     *
     * @var integer
     * 
     * @ORM\Column(name="posicion", type="integer")
     */
    private $posicion;

    /**
     *
     * @var integer
     *
     * @ORM\OneToMany(targetEntity="Valor", mappedBy="columna")
     */
    private $valores;
    
    /**
     *
     * @var integer
     *
     * @ORM\ManyToMany(targetEntity="Item", mappedBy="columnas",cascade={"persist"})
     * @ORM\JoinTable(name="columna_item")
     */
    private $items;
    
    public function __toString() {
        return (string)$this->getId() . ' ' . $this->getTexto();
    }

   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->valores = new \Doctrine\Common\Collections\ArrayCollection();
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Columna
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
     * Set posicion
     *
     * @param integer $posicion
     *
     * @return Columna
     */
    public function setPosicion($posicion)
    {
        $this->posicion = $posicion;

        return $this;
    }

    /**
     * Get posicion
     *
     * @return integer
     */
    public function getPosicion()
    {
        return $this->posicion;
    }

    /**
     * Set tipo
     *
     * @param \WE\ReportBundle\Entity\TipoColumna $tipo
     *
     * @return Columna
     */
    public function setTipo(\WE\ReportBundle\Entity\TipoColumna $tipo = null)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return \WE\ReportBundle\Entity\TipoColumna
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set seccion
     *
     * @param \WE\ReportBundle\Entity\Seccion $seccion
     *
     * @return Columna
     */
    public function setSeccion(\WE\ReportBundle\Entity\Seccion $seccion = null)
    {
        $this->seccion = $seccion;

        return $this;
    }

    
    /**
     * Get seccion
     *
     * @return \WE\ReportBundle\Entity\Seccion
     */
    public function getSeccion()
    {
        return $this->seccion;
    }

    /**
     * Add valore
     *
     * @param \WE\ReportBundle\Entity\Valor $valore
     *
     * @return Columna
     */
    public function addValore(\WE\ReportBundle\Entity\Valor $valore)
    {
        $this->valores[] = $valore;

        return $this;
    }

    /**
     * Remove valore
     *
     * @param \WE\ReportBundle\Entity\Valor $valore
     */
    public function removeValore(\WE\ReportBundle\Entity\Valor $valore)
    {
        $this->valores->removeElement($valore);
    }

    /**
     * Get valores
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getValores()
    {
        return $this->valores;
    }

    /**
     * Add item
     *
     * @param \WE\ReportBundle\Entity\Item $item
     *
     * @return Columna
     */
    public function addItem(\WE\ReportBundle\Entity\Item $item)
    {
        $this->items[] = $item;
        $item->addColumna($this);
        return $this;
    }

    /**
     * Remove item
     *
     * @param \WE\ReportBundle\Entity\Item $item
     */
    public function removeItem(\WE\ReportBundle\Entity\Item $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }
}

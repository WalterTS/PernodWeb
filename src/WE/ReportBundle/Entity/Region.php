<?php

namespace WE\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
* @ExclusionPolicy("all")
 * Region
 *
 * @ORM\Table(name="region")
 * @ORM\Entity(repositoryClass="WE\ReportBundle\Repository\RegionRepository")
 */
class Region {
 
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
     * @ORM\Column(name="nombre", type="string",length=40)
     */
    private $nombre;

    /**
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Usuario", mappedBy="region", cascade={"persist"})
     */
    private $usuarios;

    /**
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Plaza", mappedBy="region", cascade={"persist"})
     */
    private $plazas;
    
     /**
     *
     * @var integer
     *
     * @ORM\ManyToMany(targetEntity="Proyecto", mappedBy="regiones",cascade={"persist"})
     * @ORM\JoinTable(name="proyecto_region")
     */
    private $proyectos;

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
        $this->plazas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Region
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
     * Add plaza
     *
     * @param \WE\ReportBundle\Entity\Plaza $plaza
     *
     * @return Region
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
     * Add usuario
     *
     * @param \WE\ReportBundle\Entity\Usuario $usuario
     *
     * @return Region
     */
    public function addUsuario(\WE\ReportBundle\Entity\Usuario $usuario)
    {
        $this->usuarios[] = $usuario;

        return $this;
    }

    /**
     * Remove usuario
     *
     * @param \WE\ReportBundle\Entity\Usuario $usuario
     */
    public function removeUsuario(\WE\ReportBundle\Entity\Usuario $usuario)
    {
        $this->usuarios->removeElement($usuario);
    }

    /**
     * Get usuarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }
    
    public function __toString() {
        return $this->getNombre();
    }

    /**
     * Add proyecto
     *
     * @param \WE\ReportBundle\Entity\Proyecto $proyecto
     *
     * @return Region
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

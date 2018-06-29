<?php

namespace WE\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @ExclusionPolicy("all")
 * Agencia
 *
 * @ORM\Table(name="agencia")
 * @ORM\Entity(repositoryClass="WE\ReportBundle\Repository\AgenciaRepository")
 */
class Agencia {

    /**
     * @var int
     * @Expose
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Expose
     * @ORM\Column(name="nombre", type="string")
     */
    private $nombre;

    /**
     * @var integer
     * 
     * @ORM\OneToMany(targetEntity="Usuario", mappedBy="agencia")
     */
    private $usuarios;

    /**
     * @var integer
     * 
     * @ORM\OneToMany(targetEntity="Proyecto", mappedBy="agencia")
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
        $this->usuarios = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Agencia
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
     * Add usuario
     *
     * @param \WE\ReportBundle\Entity\Usuario $usuario
     *
     * @return Agencia
     */
    public function addUsuario(\WE\ReportBundle\Entity\Usuario $usuario) {
        $this->usuarios[] = $usuario;

        return $this;
    }

    /**
     * Remove usuario
     *
     * @param \WE\ReportBundle\Entity\Usuario $usuario
     */
    public function removeUsuario(\WE\ReportBundle\Entity\Usuario $usuario) {
        $this->usuarios->removeElement($usuario);
    }

    /**
     * Get usuarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuarios() {
        return $this->usuarios;
    }


    /**
     * Add proyecto
     *
     * @param \WE\ReportBundle\Entity\Proyecto $proyecto
     *
     * @return Agencia
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
    
    public function __toString() {
        return $this->getNombre();
    }
}

<?php

namespace WE\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
/**
 * Marca
 *
 * @ORM\Table(name="usuario_activacion",uniqueConstraints={@UniqueConstraint(name="usuario_activacion", columns={"usuario_id", "activacion_id"})})
 * @ORM\Entity(repositoryClass="WE\ReportBundle\Entity\Repository\UsuarioActivacionRepository")
 */
class UsuarioActivacion {

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
     * @ORM\Column(name="tipo", type="string",length=70)
     */
    private $tipo;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="activaciones")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    private $usuario;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Activacion", inversedBy="usuarios")
     * @ORM\JoinColumn(name="activacion_id", referencedColumnName="id")
     */
    private $activacion;

    /**
     * Constructor
     */
    public function __construct() {
        $this->cdcs = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Marca
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
     * Add cdc
     *
     * @param \WE\ReportBundle\Entity\CDC $cdc
     *
     * @return Marca
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
     * Set tipo
     *
     * @param string $tipo
     *
     * @return UsuarioActivacion
     */
    public function setTipo($tipo) {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo() {
        return $this->tipo;
    }

    /**
     * Set usuario
     *
     * @param \WE\ReportBundle\Entity\Usuario $usuario
     *
     * @return UsuarioActivacion
     */
    public function setUsuario(\WE\ReportBundle\Entity\Usuario $usuario = null) {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \WE\ReportBundle\Entity\Usuario
     */
    public function getUsuario() {
        return $this->usuario;
    }

    /**
     * Set activacion
     *
     * @param \WE\ReportBundle\Entity\Activacion $activacion
     *
     * @return UsuarioActivacion
     */
    public function setActivacion(\WE\ReportBundle\Entity\Activacion $activacion = null) {
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
        
    public function __toString() {
        return (string) $this->getActivacion()->getId();
    }

}

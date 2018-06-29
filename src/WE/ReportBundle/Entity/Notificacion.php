<?php

namespace WE\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notificacion
 *
 * @ORM\Table(name="notificacion")
 * @ORM\Entity(repositoryClass="WE\ReportBundle\Repository\NotificacionRepository")
 */
class Notificacion {

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
     * @ORM\Column(name="titulo", type="string",length=200)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="contenido", type="text")
     */
    private $contenido;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="notificaciones_recibidas")
     * @ORM\JoinColumn(name="user_to_id", referencedColumnName="id")
     */
    private $user_to;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="notificaciones_enviadas")
     * @ORM\JoinColumn(name="user_from_id", referencedColumnName="id", nullable=true)
     */
    private $user_from;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status",type="boolean")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="text",nullable=true)
     */
    private $path;

    /**
     * @var datetime
     *
     * @ORM\Column(name="fecha", type="datetime",nullable=true)
     */
    private $fecha;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set titulo
     *
     * @param string $titulo
     *
     * @return Notificacion
     */
    public function setTitulo($titulo) {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string
     */
    public function getTitulo() {
        return $this->titulo;
    }

    /**
     * Set contenido
     *
     * @param string $contenido
     *
     * @return Notificacion
     */
    public function setContenido($contenido) {
        $this->contenido = $contenido;

        return $this;
    }

    /**
     * Get contenido
     *
     * @return string
     */
    public function getContenido() {
        return $this->contenido;
    }

    /**
     * Set userTo
     *
     * @param \WE\ReportBundle\Entity\Usuario $userTo
     *
     * @return Notificacion
     */
    public function setUserTo(\WE\ReportBundle\Entity\Usuario $userTo = null) {
        $this->user_to = $userTo;

        return $this;
    }

    /**
     * Get userTo
     *
     * @return \WE\ReportBundle\Entity\Usuario
     */
    public function getUserTo() {
        return $this->user_to;
    }

    /**
     * Set userFrom
     *
     * @param \WE\ReportBundle\Entity\Usuario $userFrom
     *
     * @return Notificacion
     */
    public function setUserFrom(\WE\ReportBundle\Entity\Usuario $userFrom = null) {
        $this->user_from = $userFrom;

        return $this;
    }

    /**
     * Get userFrom
     *
     * @return \WE\ReportBundle\Entity\Usuario
     */
    public function getUserFrom() {
        return $this->user_from;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Notificacion
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus() {
        return $this->status;
    }

    public function __toString() {
        return $this->getTitulo();
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Notificacion
     */
    public function setPath($path) {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath() {
        return $this->path;
    }

    public function getSender() {
        return $this->getUserFrom() ? $this->getUserFrom()->getUsername() : 'PRM Pernod';
    }


    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Notificacion
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }
}

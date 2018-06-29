<?php

namespace WE\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ActivacionComentario
 *
 * @ORM\Table(name="activacion_comentario")
 * @ORM\Entity(repositoryClass="WE\ReportBundle\Repository\ActivacionComentarioRepository")
 */
class ActivacionComentario {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="notificaciones_enviadas")
     * @ORM\JoinColumn(name="user_from_id", referencedColumnName="id", nullable=false)
     */
    private $user_from;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="text", nullable=true)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="rating", type="text", nullable=true)
     */
    private $rating;

    /**
     * @var string
     *
     * @ORM\Column(name="comentario", type="text", nullable=false)
     */
    private $comentario;

    /**
     * @var datetime
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=false)
     */
    private $fecha;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Activacion", inversedBy="comentarios")
     * @ORM\JoinColumn(name="activacion_id", referencedColumnName="id", nullable=false)
     */
    private $activacion;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set comentario
     *
     * @param string $comentario
     *
     * @return ActivacionComentario
     */
    public function setComentario($comentario) {
        $this->comentario = $comentario;

        return $this;
    }

    /**
     * Get comentario
     *
     * @return string
     */
    public function getComentario() {
        return $this->comentario;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return ActivacionComentario
     */
    public function setFecha($fecha) {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha() {
        return $this->fecha;
    }

    /**
     * Set userFrom
     *
     * @param \WE\ReportBundle\Entity\Usuario $userFrom
     *
     * @return ActivacionComentario
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
     * Set activacion
     *
     * @param \WE\ReportBundle\Entity\Activacion $activacion
     *
     * @return ActivacionComentario
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


    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return ActivacionComentario
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set rating
     *
     * @param string $rating
     *
     * @return ActivacionComentario
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return string
     */
    public function getRating()
    {
        return $this->rating;
    }
}

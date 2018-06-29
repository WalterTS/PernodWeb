<?php

namespace WE\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ActivacionLog
 *
 * @ORM\Table(name="activacion_log")
 * @ORM\Entity(repositoryClass="WE\ReportBundle\Repository\ActivacionLogRepository")
 */
class ActivacionLog {

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
     * @ORM\Column(name="tipo", type="string",length=100)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="contenido", type="array", nullable=true)
     */
    private $contenido;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Activacion", inversedBy="logs")
     * @ORM\JoinColumn(name="activacion_id", referencedColumnName="id")
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
     * Set tipo
     *
     * @param string $tipo
     *
     * @return ActivacionLog
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
     * Set contenido
     *
     * @param string $contenido
     *
     * @return ActivacionLog
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
     * Set activacion
     *
     * @param \WE\ReportBundle\Entity\Activacion $activacion
     *
     * @return ActivacionLog
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

}

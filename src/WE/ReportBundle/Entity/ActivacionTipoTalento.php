<?php

namespace WE\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ActivacionTipoTalento
 *
 * @ORM\Table(name="activacion_tipo_talento")
 * @ORM\Entity(repositoryClass="WE\ReportBundle\Repository\ActivacionTipoTalentoRepository")
 */
class ActivacionTipoTalento {

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
     * @ORM\Column(name="tipo", type="string",length=40)
     */
    private $tipo;

    /**
     * @var string
     * @ORM\Column(name="total", type="integer")
     */
    private $total;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="ActivacionTipo", inversedBy="talento")
     * @ORM\JoinColumn(name="activacion_tipo_id", referencedColumnName="id")
     */
    private $activacion_tipo;

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
     * @return ActivacionTipoTalento
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
     * Set total
     *
     * @param integer $total
     *
     * @return ActivacionTipoTalento
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set activacionTipo
     *
     * @param \WE\ReportBundle\Entity\ActivacionTipo $activacionTipo
     *
     * @return ActivacionTipoTalento
     */
    public function setActivacionTipo(\WE\ReportBundle\Entity\ActivacionTipo $activacionTipo = null)
    {
        $this->activacion_tipo = $activacionTipo;

        return $this;
    }

    /**
     * Get activacionTipo
     *
     * @return \WE\ReportBundle\Entity\ActivacionTipo
     */
    public function getActivacionTipo()
    {
        return $this->activacion_tipo;
    }
    public function __toString() {
        return $this->getTipo();
    }
}

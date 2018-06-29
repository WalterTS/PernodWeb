<?php

namespace WE\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ActivacionTipoMaterial
 *
 * @ORM\Table(name="activacion_tipo_material")
 * @ORM\Entity(repositoryClass="WE\ReportBundle\Repository\ActivacionTipoMaterialRepository")
 */
class ActivacionTipoMaterial {

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
     * @ORM\Column(name="nombre", type="string",length=80)
     */
    private $nombre;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }


    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return ActivacionTipoMaterial
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }
    
    public function __toString() {
        return $this->getNombre();
    }
}

<?php

namespace WE\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @ExclusionPolicy("all")
 * Ciudad
 *
 * @ORM\Table(name="cdc")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class CDC {
    //AGREGAR IMAGEN
    //AGREGAR DIRECCIÓN
    //AGREGAR DESCRIPCION

    /**
     * @var integer
     * @Expose
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="capacidad", type="integer")
     */
    private $capacidad;

    /**
     * @var integer
     * 
     * @ORM\ManyToOne(targetEntity="CDCType", inversedBy="cdcs")
     * @ORM\JoinColumn(name="tipo_id", referencedColumnName="id")
     */
    private $tipo;

    /**
     * @var integer
     * @Expose
     * @ORM\ManyToOne(targetEntity="Plaza", inversedBy="cdcs")
     * @ORM\JoinColumn(name="plaza_id", referencedColumnName="id")
     */
    private $plaza;

    /**
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Activacion", mappedBy="cdc", cascade={"persist"})
     */
    private $activaciones;

    /**
     *  @var integer
     *  @
     * @ORM\ManyToMany(targetEntity="Marca", inversedBy="cdcs",cascade={"persist"})
     */
    private $marcas;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="nombre", type="string",length=70)
     */
    private $nombre;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="propietario", type="string",length=100,nullable=true)
     */
    private $propietario;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="cliente", type="string",length=70,nullable=true)
     */
    private $cliente;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="cliente_id", type="string",length=70,nullable=true)
     */
    private $cliente_id;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="cdc_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     *
     * @var integer
     *
     * @ORM\ManyToMany(targetEntity="Usuario", mappedBy="cdcs",cascade={"persist"})
     * @ORM\JoinTable(name="cdc_usuario")
     */
    private $usuarios;

    /**
     *
     * @var integer
     *
     * @ORM\ManyToMany(targetEntity="Proyecto", mappedBy="cdcs",cascade={"persist"})
     * @ORM\JoinTable(name="proyecto_cdc")
     */
    private $proyectos;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @var \DateTime
     */
    private $updatedAt;

    public function setImageFile(File $image = null) {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile() {
        return $this->imageFile;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function getImage() {
        return $this->image;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->marcas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return CDC
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
     * Set tipo
     *
     * @param \WE\ReportBundle\Entity\CDCType $tipo
     *
     * @return CDC
     */
    public function setTipo(\WE\ReportBundle\Entity\CDCType $tipo = null) {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return \WE\ReportBundle\Entity\CDCType
     */
    public function getTipo() {
        return $this->tipo;
    }

    /**
     * Set plaza
     *
     * @param \WE\ReportBundle\Entity\Plaza $plaza
     *
     * @return CDC
     */
    public function setPlaza(\WE\ReportBundle\Entity\Plaza $plaza = null) {
        $this->plaza = $plaza;

        return $this;
    }

    /**
     * Get plaza
     *
     * @return \WE\ReportBundle\Entity\Plaza
     */
    public function getPlaza() {
        return $this->plaza;
    }

    /**
     * Add marca
     *
     * @param \WE\ReportBundle\Entity\Marca $marca
     *
     * @return CDC
     */
    public function addMarca(\WE\ReportBundle\Entity\Marca $marca) {
        $this->marcas[] = $marca;

        return $this;
    }

    /**
     * Remove marca
     *
     * @param \WE\ReportBundle\Entity\Marca $marca
     */
    public function removeMarca(\WE\ReportBundle\Entity\Marca $marca) {
        $this->marcas->removeElement($marca);
    }

    /**
     * Get marcas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMarcas() {
        return $this->marcas;
    }

    /**
     * Set capacidad
     *
     * @param integer $capacidad
     *
     * @return CDC
     */
    public function setCapacidad($capacidad) {
        $this->capacidad = $capacidad;

        return $this;
    }

    /**
     * Get capacidad
     *
     * @return integer
     */
    public function getCapacidad() {
        return $this->capacidad;
    }

    /**
     * Add activacione
     *
     * @param \WE\ReportBundle\Entity\Activacion $activacione
     *
     * @return CDC
     */
    public function addActivacione(\WE\ReportBundle\Entity\Activacion $activacione) {
        $this->activaciones[] = $activacione;

        return $this;
    }

    /**
     * Remove activacione
     *
     * @param \WE\ReportBundle\Entity\Activacion $activacione
     */
    public function removeActivacione(\WE\ReportBundle\Entity\Activacion $activacione) {
        $this->activaciones->removeElement($activacione);
    }

    /**
     * Get activaciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActivaciones() {
        return $this->activaciones;
    }

    public function __toString() {
        $plaza = $this->getPlaza() != null ? $this->getPlaza()->getNombre() : 'SIN PLAZA';
        return $this->getNombre() . ' @ ' . $plaza;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return CDC
     */
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * Add usuario
     *
     * @param \WE\ReportBundle\Entity\Usuario $usuario
     *
     * @return CDC
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
     * @return CDC
     */
    public function addProyecto(\WE\ReportBundle\Entity\Proyecto $proyecto) {
        $this->proyectos[] = $proyecto;

        return $this;
    }

    /**
     * Remove proyecto
     *
     * @param \WE\ReportBundle\Entity\Proyecto $proyecto
     */
    public function removeProyecto(\WE\ReportBundle\Entity\Proyecto $proyecto) {
        $this->proyectos->removeElement($proyecto);
    }

    /**
     * Get proyectos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProyectos() {
        return $this->proyectos;
    }

    /**
     * Set cliente
     *
     * @param string $cliente
     *
     * @return CDC
     */
    public function setCliente($cliente) {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return string
     */
    public function getCliente() {
        return $this->cliente;
    }

    /**
     * Set clienteId
     *
     * @param string $clienteId
     *
     * @return CDC
     */
    public function setClienteId($clienteId) {
        $this->cliente_id = $clienteId;

        return $this;
    }

    /**
     * Get clienteId
     *
     * @return string
     */
    public function getClienteId() {
        return $this->cliente_id;
    }


    /**
     * Set propietario
     *
     * @param string $propietario
     *
     * @return CDC
     */
    public function setPropietario($propietario)
    {
        $this->propietario = $propietario;

        return $this;
    }

    /**
     * Get propietario
     *
     * @return string
     */
    public function getPropietario()
    {
        return $this->propietario;
    }
}

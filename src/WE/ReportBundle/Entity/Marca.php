<?php

namespace WE\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @ExclusionPolicy("all")
 * Marca
 * @Vich\Uploadable
 * @ORM\Table(name="marca")
 * @ORM\Entity(repositoryClass="WE\ReportBundle\Repository\MarcaRepository")
 */
class Marca {

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
     * @ORM\Column(name="nombre", type="string",length=70)
     */
    private $nombre;

    /**
     *
     * @var integer
     *
     * @ORM\ManyToMany(targetEntity="CDC", mappedBy="marcas",cascade={"persist"})
     * @ORM\JoinTable(name="cdc_marca")
     */
    private $cdcs;

    /**
     *
     * @var integer
     *
     * @ORM\ManyToMany(targetEntity="Instrumento", mappedBy="marcas",cascade={"persist"})
     * @ORM\JoinTable(name="instrumento_marca")
     */
    private $instrumentos;

    /**
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Proyecto", mappedBy="marca", cascade={"persist"})
     */
    private $proyectos;

    /**
     * @var integer
     *
     * @ORM\Column(name="objetivo",type="integer")
     */
    private $objetivo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="cdc_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    /**
     *
     * @var integer
     *
     * @ORM\ManyToMany(targetEntity="Usuario", mappedBy="marcas",cascade={"persist"})
     * @ORM\JoinTable(name="marca_usuario")
     */
    private $usuarios;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="color", type="string",length=7)
     */
    private $color;

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
     * Add activacione
     *
     * @param \WE\ReportBundle\Entity\Activacion $activacione
     *
     * @return Marca
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

    /**
     * Set objetivo
     *
     * @param string $objetivo
     *
     * @return Marca
     */
    public function setObjetivo($objetivo) {
        $this->objetivo = $objetivo;

        return $this;
    }

    /**
     * Get objetivo
     *
     * @return string
     */
    public function getObjetivo() {
        return $this->objetivo;
    }

    /**
     * Set copeo
     *
     * @param string $copeo
     *
     * @return Marca
     */
    public function setCopeo($copeo) {
        $this->copeo = $copeo;

        return $this;
    }

    /**
     * Get copeo
     *
     * @return string
     */
    public function getCopeo() {
        return $this->copeo;
    }

    /**
     * Set botellas
     *
     * @param string $botellas
     *
     * @return Marca
     */
    public function setBotellas($botellas) {
        $this->botellas = $botellas;

        return $this;
    }

    /**
     * Get botellas
     *
     * @return string
     */
    public function getBotellas() {
        return $this->botellas;
    }

    /**
     * Add instrumento
     *
     * @param \WE\ReportBundle\Entity\Istrumento $instrumento
     *
     * @return Marca
     */
    public function addInstrumento(\WE\ReportBundle\Entity\Instrumento $instrumento) {
        $this->instrumentos[] = $instrumento;

        return $this;
    }

    /**
     * Remove instrumento
     *
     * @param \WE\ReportBundle\Entity\Istrumento $instrumento
     */
    public function removeInstrumento(\WE\ReportBundle\Entity\Instrumento $instrumento) {
        $this->instrumentos->removeElement($instrumento);
    }

    /**
     * Get instrumentos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInstrumentos() {
        return $this->instrumentos;
    }

    public function __toString() {
        return $this->getNombre();
    }

    /**
     * Add proyecto
     *
     * @param \WE\ReportBundle\Entity\Proyecto $proyecto
     *
     * @return Marca
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Marca
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
     * @return Marca
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
     * Set color
     *
     * @param string $color
     *
     * @return Marca
     */
    public function setColor($color) {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor() {
        return $this->color;
    }

}

<?php

namespace WE\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @ExclusionPolicy("all")
 * Activacion
 *
 * @ORM\Table(name="activacion")
 * @ORM\Entity(repositoryClass="WE\ReportBundle\Entity\Repository\ActivacionRepository")
 */
class Activacion {

    /**
     * @var integer
     * @Expose
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var datetime
     * @Expose
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @Expose
     * @ORM\ManyToOne(targetEntity="CDC", inversedBy="activaciones")
     * @ORM\JoinColumn(name="cdc_id", referencedColumnName="id")
     */
    private $cdc;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Proyecto", inversedBy="activaciones")
     * @ORM\JoinColumn(name="proyecto_id", referencedColumnName="id")
     */
    private $proyecto;

    /**
     * @Expose
     * @ORM\ManyToOne(targetEntity="ActivacionStatus")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    private $status;

    /**
     *
     * @var ArrayCollection
     * @Expose
     * @ORM\OneToMany(targetEntity="ActivacionComentario", mappedBy="activacion", cascade={"persist"})
     */
    private $comentarios;

    /**
     *
     * @var ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="UsuarioActivacion", mappedBy="activacion", cascade={"persist"})
     */
    private $usuarios;

    /**
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Fila", mappedBy="activacion", cascade={"persist"})
     */
    private $filas;

    /**
     * @var integer
     *
     * @ORM\Column(name="total",type="integer",options={"default":0})
     */
    private $total;

    /**
     * @var integer
     *
     * @ORM\Column(name="copeo",type="integer",options={"default":0})
     */
    private $copeo;

    /**
     * @var float
     *
     * @ORM\Column(name="botellas",type="integer",options={"default":0})
     */
    private $botellas;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="ActivacionTipo")
     * @ORM\JoinColumn(name="tipo_id", referencedColumnName="id")
     */
    private $tipo;

    /**
     * @ORM\OneToMany(targetEntity="Gallery", mappedBy="activacion",cascade={"persist","remove"})
     */
    private $images;

    /**
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ActivacionLog", mappedBy="activacion", cascade={"persist"})
     */
    private $logs;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->usuarios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Activacion
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
     * Set cdc
     *
     * @param \WE\ReportBundle\Entity\CDC $cdc
     *
     * @return Activacion
     */
    public function setCdc(\WE\ReportBundle\Entity\CDC $cdc = null) {
        $this->cdc = $cdc;

        return $this;
    }

    /**
     * Get cdc
     *
     * @return \WE\ReportBundle\Entity\CDC
     */
    public function getCdc() {
        return $this->cdc;
    }

    /**
     * Add usuario
     *
     * @param \WE\ReportBundle\Entity\UsuarioActivacion $usuario
     *
     * @return Activacion
     */
    public function addUsuario(\WE\ReportBundle\Entity\UsuarioActivacion $usuario) {
        $usuario->setActivacion($this);

        if ($this->usuarios->contains($usuario)) {
            return;
        }
        $this->usuarios[] = $usuario;
        return $this;
    }

    /**
     * Remove usuario
     *
     * @param \WE\ReportBundle\Entity\UsuarioActivacion $usuario
     */
    public function removeUsuario(\WE\ReportBundle\Entity\UsuarioActivacion $usuario) {
        if (!$this->usuarios->contains($usuario)) {
            return;
        }
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

    public function __toString() {
        return ' @' . $this->getFecha()->format('Y-m-d');
    }

    /**
     * Set total
     *
     * @param integer $total
     *
     * @return Activacion
     */
    public function setTotal($total) {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return integer
     */
    public function getTotal() {
        return $this->total;
    }

    /**
     * Set copeo
     *
     * @param integer $copeo
     *
     * @return Activacion
     */
    public function setCopeo($copeo) {
        $this->copeo = $copeo;

        return $this;
    }

    /**
     * Get copeo
     *
     * @return integer
     */
    public function getCopeo() {
        return $this->copeo;
    }

    /**
     * Set botellas
     *
     * @param integer $botellas
     *
     * @return Activacion
     */
    public function setBotellas($botellas) {
        $this->botellas = $botellas;

        return $this;
    }

    /**
     * Get botellas
     *
     * @return integer
     */
    public function getBotellas() {
        return $this->botellas;
    }

    /**
     * Add fila
     *
     * @param \WE\ReportBundle\Entity\Fila $fila
     *
     * @return Activacion
     */
    public function addFila(\WE\ReportBundle\Entity\Fila $fila) {
        $this->filas[] = $fila;

        return $this;
    }

    /**
     * Remove fila
     *
     * @param \WE\ReportBundle\Entity\Fila $fila
     */
    public function removeFila(\WE\ReportBundle\Entity\Fila $fila) {
        $this->filas->removeElement($fila);
    }

    /**
     * Get filas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFilas() {
        return $this->filas;
    }

    /**
     * Set proyecto
     *
     * @param \WE\ReportBundle\Entity\Proyecto $proyecto
     *
     * @return Activacion
     */
    public function setProyecto(\WE\ReportBundle\Entity\Proyecto $proyecto = null) {
        $this->proyecto = $proyecto;

        return $this;
    }

    /**
     * Get proyecto
     *
     * @return \WE\ReportBundle\Entity\Proyecto
     */
    public function getProyecto() {
        return $this->proyecto;
    }

    /**
     * Set status
     *
     * @param \WE\ReportBundle\Entity\ActicacionStatus $status
     *
     * @return Activacion
     */
    public function setStatus(\WE\ReportBundle\Entity\ActivacionStatus $status = null) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \WE\ReportBundle\Entity\ActicacionStatus
     */
    public function getStatus() {
        return $this->status;
    }

    public function isValidUser($user, $tipo) {
        $usuarios = $this->getUsuarios()->filter(function($usuario) use ($user, $tipo) {
            return $usuario->getUsuario() == $user && $usuario->getTipo() == $tipo;
        });

        return $usuarios->count() > 0 ? true : false;
    }

    /**
     * Add image
     *
     * @param \WE\ReportBundle\Entity\Gallery $image
     *
     * @return Activacion
     */
    public function addImage(\WE\ReportBundle\Entity\Gallery $image) {
        $this->images[] = $image;
        $image->setActivacion($this);
        return $this;
    }

    /**
     * Remove image
     *
     * @param \WE\ReportBundle\Entity\Gallery $image
     */
    public function removeImage(\WE\ReportBundle\Entity\Gallery $image) {
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages() {
        return $this->images;
    }

    /**
     * Add comentario
     *
     * @param \WE\ReportBundle\Entity\ActivacionComentario $comentario
     *
     * @return Activacion
     */
    public function addComentario(\WE\ReportBundle\Entity\ActivacionComentario $comentario) {
        $this->comentarios[] = $comentario;

        return $this;
    }

    /**
     * Remove comentario
     *
     * @param \WE\ReportBundle\Entity\ActivacionComentario $comentario
     */
    public function removeComentario(\WE\ReportBundle\Entity\ActivacionComentario $comentario) {
        $this->comentarios->removeElement($comentario);
    }

    /**
     * Get comentarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComentarios() {
        return $this->comentarios;
    }

    /**
     * Set tipo
     *
     * @param \WE\ReportBundle\Entity\ActivacionTipo $tipo
     *
     * @return Activacion
     */
    public function setTipo(\WE\ReportBundle\Entity\ActivacionTipo $tipo = null) {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return \WE\ReportBundle\Entity\ActivacionTipo
     */
    public function getTipo() {
        return $this->tipo;
    }

    public function hasSupervisors() {
        return $this->findUsers('Supervisor')->count() && $this->getStatus()->getId() != 5 ? true : false;
    }

    protected function findUsers($tipo) {
        return $this->getUsuarios()->filter(function($usuario) use ($tipo) {
                    return $usuario->getTipo() === $tipo;
                });
    }

    /**
     * Add log
     *
     * @param \WE\ReportBundle\Entity\ActivacionLog $log
     *
     * @return Activacion
     */
    public function addLog(\WE\ReportBundle\Entity\ActivacionLog $log) {
        $this->logs[] = $log;

        return $this;
    }

    /**
     * Remove log
     *
     * @param \WE\ReportBundle\Entity\ActivacionLog $log
     */
    public function removeLog(\WE\ReportBundle\Entity\ActivacionLog $log) {
        $this->logs->removeElement($log);
    }

    /**
     * Get logs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLogs() {
        return $this->logs;
    }

    public function getRawComentarios() {
        return $this->getComentarios()->filter(function($comentario) {
                    return $comentario->getTipo() == null;
                });
    }

    public function getComentariosByActivacion() {
        return $this->filterComentario('Comentario');
    }

    public function getPromocionesByActivacion() {
        return $this->filterComentario('Promoción');
    }

    protected function filterComentario($tipo) {
        return $this->getComentarios()->filter(function($comentario) use ($tipo) {
                    return $comentario->getTipo() === $tipo;
                });
    }

}

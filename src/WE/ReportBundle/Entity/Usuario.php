<?php

namespace WE\ReportBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="WE\ReportBundle\Entity\Repository\UsuarioRepository")
 * @ORM\Table(name="usuario")
 * @Vich\Uploadable
 */
class Usuario extends BaseUser {

    const ROLES_LIST = array('Ejecutivo de Cuenta' => 'ROLE_USER_EJECUTIVO',
        'KAM' => 'ROLE_USER_KAM',
        'Gerente' => 'ROLE_USER_GERENTE',
        'Ejecutivo' => 'ROLE_USER_EJECUTIVO',
        'Ejecutivo de Cuenta' => 'ROLE_USER_CUENTA',
        'Productor' => 'ROLE_USER_PRODUCTOR',
        'Supervisor' => 'ROLE_USER_SUPERVISOR');

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @Expose
     * @ORM\Column(name="nombre", type="string", length=70,nullable=true)
     */
    private $nombre;

    /**
     *
     * @var ArrayCollection
     * @Expose 
     * @ORM\OneToMany(targetEntity="UsuarioActivacion", mappedBy="usuario", cascade={"persist"})
     */
    private $activaciones;

    /**
     * @var integer
     * @Expose
     * @ORM\OneToMany(targetEntity="Notificacion", mappedBy="user_to")
     * @ORM\OrderBy({"status"="ASC","fecha"="DESC"})
     */
    private $notificaciones_recibidas;

    /**
     * @var integer
     * 
     * @ORM\OneToMany(targetEntity="Notificacion", mappedBy="user_from")
     */
    private $notificaciones_enviadas;

    /**
     *  @var integer
     *
     * @ORM\ManyToMany(targetEntity="Marca", inversedBy="usuarios",cascade={"persist"})
     */
    private $marcas;

    /**
     *  @var integer
     *
     * @ORM\ManyToMany(targetEntity="CDC", inversedBy="usuarios",cascade={"persist"})
     */
    private $cdcs;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Region", inversedBy="usuarios")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     */
    private $region;

    /**
    * @Expose 
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Agencia", inversedBy="usuarios")
     * @ORM\JoinColumn(name="agencia_id", referencedColumnName="id")
     */
    private $agencia;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="cdc_images", fileNameProperty="image", nullable=true)
     * @var File
     */
    private $imageFile;

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
        parent::__construct();
    }

    /**
     * Add activacione
     *
     * @param \WE\ReportBundle\Entity\UsuarioActivacion $activacione
     *
     * @return Usuario
     */
    public function addActivacione(\WE\ReportBundle\Entity\UsuarioActivacion $activacione) {
        $this->activaciones[] = $activacione;

        return $this;
    }

    /**
     * Remove activacione
     *
     * @param \WE\ReportBundle\Entity\UsuarioActivacion $activacione
     */
    public function removeActivacione(\WE\ReportBundle\Entity\UsuarioActivacion $activacione) {
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
     * Add notificacionesRecibida
     *
     * @param \WE\ReportBundle\Entity\Notificacion $notificacionesRecibida
     *
     * @return Usuario
     */
    public function addNotificacionesRecibida(\WE\ReportBundle\Entity\Notificacion $notificacionesRecibida) {
        $this->notificaciones_recibidas[] = $notificacionesRecibida;

        return $this;
    }

    /**
     * Remove notificacionesRecibida
     *
     * @param \WE\ReportBundle\Entity\Notificacion $notificacionesRecibida
     */
    public function removeNotificacionesRecibida(\WE\ReportBundle\Entity\Notificacion $notificacionesRecibida) {
        $this->notificaciones_recibidas->removeElement($notificacionesRecibida);
    }

    /**
     * Get notificacionesRecibidas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotificacionesRecibidas() {
        return $this->notificaciones_recibidas;
    }

    /**
     * Add notificacionesEnviada
     *
     * @param \WE\ReportBundle\Entity\Notificacion $notificacionesEnviada
     *
     * @return Usuario
     */
    public function addNotificacionesEnviada(\WE\ReportBundle\Entity\Notificacion $notificacionesEnviada) {
        $this->notificaciones_enviadas[] = $notificacionesEnviada;

        return $this;
    }

    /**
     * Remove notificacionesEnviada
     *
     * @param \WE\ReportBundle\Entity\Notificacion $notificacionesEnviada
     */
    public function removeNotificacionesEnviada(\WE\ReportBundle\Entity\Notificacion $notificacionesEnviada) {
        $this->notificaciones_enviadas->removeElement($notificacionesEnviada);
    }

    /**
     * Get notificacionesEnviadas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotificacionesEnviadas() {
        return $this->notificaciones_enviadas;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Usuario
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

    public function getUnreadNotifications() {
        return $this->getNotificacionesRecibidas()->filter(function($reporte) {
                    return $reporte->getStatus() == false;
                });
    }

    /**
     * Add marca
     *
     * @param \WE\ReportBundle\Entity\Marca $marca
     *
     * @return Usuario
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
     * Add cdc
     *
     * @param \WE\ReportBundle\Entity\CDC $cdc
     *
     * @return Usuario
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
     * Set region
     *
     * @param \WE\ReportBundle\Entity\Region $region
     *
     * @return Usuario
     */
    public function setRegion(\WE\ReportBundle\Entity\Region $region = null) {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return \WE\ReportBundle\Entity\Region
     */
    public function getRegion() {
        return $this->region;
    }

    /**
     * Set agencia
     *
     * @param \WE\ReportBundle\Entity\Agencia $agencia
     *
     * @return Usuario
     */
    public function setAgencia(\WE\ReportBundle\Entity\Agencia $agencia = null) {
        $this->agencia = $agencia;

        return $this;
    }

    /**
     * Get agencia
     *
     * @return \WE\ReportBundle\Entity\Agencia
     */
    public function getAgencia() {
        return $this->agencia;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Usuario
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

    public function getTipo() {
        $tipos = (array_intersect_key(array_flip(Usuario::ROLES_LIST), array_combine($this->getRoles(), $this->getRoles())));
        return implode(",", $tipos);
    }

    public function getCdcsString() {
        $cdcs = array();
        if ($this->getCdcs()) {
            foreach ($this->getCdcs() as $cdc) {
                $cdcs[] = $cdc->getNombre();
            }
        }

        return implode(",", $cdcs);
    }
    
    public function __toString() {
        return $this->getNombre()? $this->getNombre() : 'N/A';
    }

}

<?php

namespace WE\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Image
 *
 * @ORM\Table(name="gallery")
 * @ORM\Entity(repositoryClass="WE\ReportBundle\Repository\GalleryRepository")
 * @Vich\Uploadable
 */
class Gallery {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="nombre", type="string")
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="cdc_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Activacion", inversedBy="images")
     * @ORM\JoinColumn(name="activacion_id", referencedColumnName="id")
     */
    private $activacion;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(name="created", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

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
     * @return Image
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Image
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
     * Set activacion
     *
     * @param \WE\ReportBundle\Entity\Activacion $activacion
     *
     * @return Image
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
        return $this->getNombre();
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Gallery
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getMetadata() {
        $path = dirname(__DIR__) . '/../../../web/uploads/images/cdc/' . $this->getImage();
        $exif = @exif_read_data($path, 0, true);
        return $exif;
    }

}

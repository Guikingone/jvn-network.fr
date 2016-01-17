<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\ImageRepository")
 * @ORM\HasLifecycleCallBacks()
 */
class Image
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    * @ORM\Column(name="name", type="string", length=255)
    * @Assert\NotBlank
    */
    private $name;

    /**
    * @ORM\Column(name="url", type="string", length=255)
    */
    private $url;

    /**
    * @ORM\Column(name="alt", type="string", length=255)
    */
    private $alt;

    /**
    * @ORM\Column(name="path", type="string", length=255, nullable=true)
    */
    private $path;

    /**
    * @Assert\File(maxSize="6000000")
    */
    private $file;

    private $temp;

    private $filename;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Image
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Set url
     *
     * @param string $url
     *
     * @return Image
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Image
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        if(isset($this->path)){
          $this->temp = $this->path;
          $this->path = null;
        } else {
          $this->path = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getAbsolutePath()
    {
      return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
      return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    public function getUploadRootDir()
    {
      return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    public function getUploadDir()
    {
      return 'img/article';
    }

    /**
    * @ORM\PrePersist()
    * @ORM\PreUpdate()
    */
    public function preUpload()
    {
      /* On génère un nom unique */
      if(null !== $this->getFile()){
        $filename = sha1(uniqid(mt_rand(), true));
        $this->path = $filename.'.'.$this->getFile()->guessExtension();
        $this->url = $this->file->guessExtension();
        $this->alt = $this->file->getClientOriginalName();
      }
    }

    /**
    * @ORM\PostPersist()
    * @ORM\PostUpdate()
    */
    public function upload()
    {
      /* On autorise le fichier à être null si le champ n'est pas requis, puis on déplace le fichier en prenant
      le dossier et le nom du fichier, on définit le chemin via le nom du fichier et on clean la propriété file */
      if(null === $this->getFile()){
        return;
      }

      /* Si on rencontre une erreur, move() renverra automatiquement un message,
      on évite donc de persister une entité contenant une erreur */
      $this->getFile()->move($this->getUploadRootDir(), $this->path);

      /* Si on a une ancienne image, on la supprime et on nettoie le chemin */
      if(isset($this->temp)){
        unlink($this->getUploadRootDir().'/'.$this->temp);
        $this->temp = null;
      }
      $this->file = null;
    }

    /**
    * @ORM\PreRemove()
    */
    public function removeUpload()
    {
      /* On détache l'image de l'article afin de pouvoir supprimer sans soucis */
      $file = $this->getAbsolutePath();
      if($file){
        unlink($file);
      }
    }
}

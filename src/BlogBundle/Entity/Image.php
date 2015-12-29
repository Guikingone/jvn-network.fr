<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\ImageRepository")
 * HasLifecycleCallBacks()
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
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

    // On se sert de $file pour le formulaire et l'enregistrement des images
    private $file;

    // On se sert de $tempfilename pour sauvegarder temporairement le nom du fichier
    private $tempfilename;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
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

    public function setFile(UploadedFile $file = null)
    {
      $this->file = $file;
    }

    public function getFile()
    {
      return $this->file;
      /* On vérifie si l'entité stocker déjà un fichier puis on sauvegarde l'extension du fichier et
      on réinitialise les valeurs des alt et url */
      if(null !== $this->url){
        $this->tempfilename = $this->url;
        $this->alt = null;
        $this->url = null;
      }
    }

    /**
    * @ORM\PrePersist()
    * @ORM\PreUpdate()
    */
    public function preUpload()
    {
      if(null === $this->file)
      {
        return;
      }

      $this->url = $this->file->guessExtension();
      $this->alt = $this->file->getClientOriginalName();

    }

    /**
    * @ORM\PostPersist()
    * @ORM\PostUpdate()
    */
    public function upload()
    {
      /* Si il n'y a pas de fichier, on ne fait rien, sinon, on récupère le nom du fichier original,
      on déplace le fichier dans le dossier souhaité, on sauvegarde le nom du fichier dans $url et on créer
      l'attribut alt de la balise <img> */
      if(null === $this->file){
        return;
      }

      // Si on avait un anien fichier, on le supprime
      if(null != $this->tempfilename){
        $oldfile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tempfilename;
        if(file_exists($oldfile)){
          unlink($oldfile);
        }
      }

      // On déplace le fichier dans le dossier souhaité en indiquant :
      $this->file->move(
        $this->getUploadDir(),   // Le répetoire de sauvegarde
        $this->id.'.'.$this->url // Le nom du fichier, ici : id.extension
      );
    }

    /**
    * @ORM\PreRemove()
    */
    public function preRemove()
    {
      // On sauvegarde temporairement le nom du fichier car il dépend de l'id
      $this->tempfilename = $this->getUploadRootDir().'/'.$this->id.'.'.$this->url;
    }

    /**
    * @ORM\PostRemove()
    */
    public function removeUpload()
    {
      // On utilise le nom sauvegarde pour supprimer le fichier (l'id n'est pas accessible en postRemove)
      if(file_exists($this->tempfilename)){
        unlink($this->tempfilename);
      }
    }
    public function getUploadDir()
    {
      // On retourne le chemin relatif de l'image pour le navigateur
      return 'uploads/img';
    }

    protected function getUploadRootDir()
    {
      // On retourne le chemin relatif pour le code PHP
      return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    public function getWebPath()
    {
      // Cette fonction permet de retrouver le chemin de l'image rapidement depuis la vue Twig
      return $this->getUploadDir().'/'.$this->getId().'.'.$this->getUrl();
    }
}

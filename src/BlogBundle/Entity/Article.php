<?php

namespace BlogBundle\Entity;

use Doctrine\Commom\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
// Ce use définit les contraintes via l'outil Validator ! A conserver !
use CoreBundle\Validator\AntiFlood;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\ArticleRepository")
 * @ORM\HasLifecycleCallBacks()
 */
class Article
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
     * @ORM\Column(name="titre", type="string", length=255)
     * @Assert\length(min=15)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="auteur", type="string", length=255)
     * @Assert\length(min=3)
     */
    private $auteur;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_publication", type="datetime")
     */
    private $datePublication;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     * @Assert\NotBlank()
     */
     // On ajoute le système de validation AntiFlood afin d'éviter les articles à la chaîne
    private $contenu;

    /**
    * @ORM\OneToOne(targetEntity="BlogBundle\Entity\Image", cascade={"persist", "remove"})
    * @Assert\Valid()
    * Comme on veut pouvoir créer une annonce sans image, on n'ajoute pas nullable=false
    * Si on veut que l'annonce puisse exister sans image, on ajouterais nullable=true
    * On rajoute "remove" afin que tout suppression d'article entraîne la suppression de l'image liée
    */
    private $image;

    /**
    * @ORM\ManyToMany(targetEntity="BlogBundle\Entity\Category", cascade={"persist"})
    */
    private $category;
    /* Category gère les différents blogs, cet attributs contient la "signature" du blog qui demande l'article,
    si l'article est demandé depuis Team, on fera pointer la catégorie vers Team et ainsi de suite pour chaque
    blog, cela permet de conserver un code propre et non dupliqué */

    /**
    * @ORM\OneToMany(targetEntity="BlogBundle\Entity\Commentaire", mappedBy="article")
    */
    private $commentaires;

    /**
    * @ORM\Column (name="updated_at", type="datetime", nullable=true)
    */
    private $updatedAt;

    /**
    * @ORM\Column (name="categorie", type="string", length=255)
    */
    private $categorie;

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
     * Set titre
     *
     * @param string $titre
     *
     * @return Article
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set auteur
     *
     * @param string $auteur
     *
     * @return Article
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return string
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set datePublication
     *
     * @param \DateTime $datePublication
     *
     * @return Article
     */
    public function setDatePublication($datePublication)
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    /**
     * Get datePublication
     *
     * @return \DateTime
     */
    public function getDatePublication()
    {
        return $this->datePublication;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Article
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set image
     *
     * @param \BlogBundle\Entity\Article $image
     *
     * @return Article
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $image;
    }

    /**
     * Get image
     *
     * @return \BlogBundle\Entity\Article
     */
    public function getImage()
    {
        return $this->image;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->category = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add category
     *
     * @param \BlogBundle\Entity\Category $category
     *
     * @return Article
     */
    public function addCategory(\BlogBundle\Entity\Category $category)
    {
        $this->category[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \BlogBundle\Entity\Category $category
     */
    public function removeCategory(\BlogBundle\Entity\Category $category)
    {
        $this->category->removeElement($category);
    }

    /**
     * Get category
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add commentaire
     *
     * @param \BlogBundle\Entity\Commentaire $commentaire
     *
     * @return Article
     */
    public function addCommentaire(\BlogBundle\Entity\Commentaire $commentaire)
    {
        $this->commentaires[] = $commentaire;

        // On lie l'article au commentaire
        $commentaire->setArticle($this);

        return $this;
        /* Si la relation était falcutative (nullable=true), on aurait effectué
        $commentaires->setArticle(null), autre point important, étant donné la relation définie plus tôt,
        on pourra faire $article->addCommentaire() mais pas $Commentaire->setArticle() !!!
        De cette façon, on conserve la logique établie plus tôt */
    }

    /**
     * Remove commentaire
     *
     * @param \BlogBundle\Entity\Commentaire $commentaire
     */
    public function removeCommentaire(\BlogBundle\Entity\Commentaire $commentaire)
    {
        $this->commentaires->removeElement($commentaire);
    }

    /**
     * Get commentaires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
    * @ORM\PreUpdate
    */
    public function updateDate()
    {
      $this->setUpdatedAt(new \Datetime);
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Article
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set categorie
     *
     * @param string $categorie
     *
     * @return Article
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }
}

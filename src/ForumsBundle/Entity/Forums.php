<?php

namespace ForumsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Forums
 *
 * @ORM\Table(name="forums")
 * @ORM\Entity(repositoryClass="ForumsBundle\Repository\ForumsRepository")
 */
class Forums
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modif", type="datetime")
     */
    private $dateModif;

    /**
     * @var int
     *
     * @ORM\Column(name="nbr_messages", type="integer")
     */
    private $nbrMessages;

    /**
    * @ORM\ManyToMany(targetEntity="forumsBundle\Entity\Sujet", cascade={"persist"})
    * @ORM\Column(name="sujets", type="string", length=255)
    */
    private $sujets;


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
     * Set nom
     *
     * @param string $nom
     *
     * @return Forums
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set dateModif
     *
     * @param \DateTime $dateModif
     *
     * @return Forums
     */
    public function setDateModif($dateModif)
    {
        $this->dateModif = $dateModif;

        return $this;
    }

    /**
     * Get dateModif
     *
     * @return \DateTime
     */
    public function getDateModif()
    {
        return $this->dateModif;
    }

    /**
     * Set nbrMessages
     *
     * @param integer $nbrMessages
     *
     * @return Forums
     */
    public function setNbrMessages($nbrMessages)
    {
        $this->nbrMessages = $nbrMessages;

        return $this;
    }

    /**
     * Get nbrMessages
     *
     * @return int
     */
    public function getNbrMessages()
    {
        return $this->nbrMessages;
    }

    /**
     * Set sujets
     *
     * @param string $sujets
     *
     * @return Forums
     */
    public function setSujets($sujets)
    {
        $this->sujets = $sujets;

        return $this;
    }

    /**
     * Get sujets
     *
     * @return string
     */
    public function getSujets()
    {
        return $this->sujets;
    }
}

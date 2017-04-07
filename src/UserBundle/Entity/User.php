<?php

/*
 * This file is part of the jvn-network project.
 *
 * (c) Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User.
 *
 * @ORM\Table(name="_user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Cette adresse mail est déjà utilisée !")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    protected $facebook_id;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $facebook_id
     */
    public function setFacebookId($facebook_id)
    {
        $this->facebook_id = $facebook_id;
    }

    /**
     * @return int
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    public function eraseCredentials()
    {
    }
}

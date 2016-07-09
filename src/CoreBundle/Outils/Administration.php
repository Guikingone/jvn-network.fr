<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 09/07/2016
 * Time: 18:32
 */

namespace CoreBundle\Outils;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage

class Administration
{
    /**
     * @var EntityManager
     */
    protected $doctrine;

    /**
     * @var TokenStorage
     */
    protected $user;

    /**
     * @var FormFactory
     */
    protected $formbuilder;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var Router
     */
    protected $router;

    public function __construct()
    {

    }
}
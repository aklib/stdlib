<?php

/**
 * AbstractAwareForm.php
 * 
 * @since 01.04.2018
 * @author Alexej Kisselev <alexej.kisselev@gmail.com>
 */

namespace Aklib\Stdlib\Form;

use Interop\Container\ContainerInterface;
use Zend\Form\Form;
use Aklib\Stdlib\Initializer\ServiceManagerAware;
use Aklib\Stdlib\Initializer\EntityManagerAware;
use Aklib\Stdlib\Initializer\AuthenticationAware;

abstract class AbstractAwareForm extends Form implements ServiceManagerAware, EntityManagerAware, AuthenticationAware {

    /**
     * service manager
     *
     * @var \Interop\Container\ContainerInterface
     */
    protected $sm;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var Entity User
     */
    protected $user;

    public function __construct($name = null, $options = []) {
        parent::__construct($name, $options);
    }

    public function init($options = []) {
        $this->createForm($options);
    }

    /**
     * Set the service manager.
     *
     * @param  ServiceLocatorInterface $sm
     * @return self
     */
    public function setServiceManager(ContainerInterface $sm) {
        $this->sm = $sm;
        return $this;
    }

    /**
     * Get the service manager.
     *
     * @return \Interop\Container\ContainerInterface
     */
    public function getServiceManager(): ContainerInterface {
        return $this->sm;
    }

    /**
     * Gets \Doctrine\ORM\EntityManager
     * @return Doctrine\ORM\EntityManager
     */
    public function getEntityManager() {
        return $this->em;
    }

    /**
     * Sets Doctrine\ORM\EntityManager
     * @return GcBase\Controller\AbstractController
     */
    public function setEntityManager($em) {
        $this->em = $em;
    }
    
    /**
     * Implemetation of AuthenticationAware
     * @return user
     */
    public function getCurrentUser() {
        return $this->user;
    }
    
    /**
     * Implemetation of AuthenticationAware
     */
    public function setCurrentUser($user) {
        $this->user = $user;
    }

    /**
     *
     * @param string $message
     * @param string $status info/error/success/default
     * @return \GcBase\Controller\AbstractController
     */
    protected function addMessage($message, $status) {
        switch ($status) {
            case 'success':
                $this->flashMessenger()->addSuccessMessage($message);
                break;
            case 'error':
                $this->flashMessenger()->addErrorMessage($message);
                break;
            case 'info':
                $this->flashMessenger()->addInfoMessage($message);
                break;
            case 'debug':
                error_log("debug: $message");
                break;
            default:
                $this->flashMessenger()->addMessage($message);
        }
        return $this;
    }

    //  ============= ABSTRACT ============   
    public abstract function createForm($param = null);
}

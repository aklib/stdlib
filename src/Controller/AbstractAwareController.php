<?php

/**
 * $Id:$
 * $HeadURL:$
 * $Date:$
 * $Author:$
 * $Revision: $
 * 
 * AbstractAwareController.php
 * 
 * @since 01.04.2018
 * @author Alexej Kisselev <alexej.kisselev@gmail.com>
 */
namespace Aklib\Stdlib\Controller;

use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Aklib\Stdlib\Initializer\ServiceManagerAware;
use Aklib\Stdlib\Initializer\EntityManagerAware;
use Aklib\Stdlib\Initializer\AuthenticationAware;


abstract class AbstractAwareController extends AbstractActionController implements ServiceManagerAware, EntityManagerAware, AuthenticationAware {
    
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
    
    protected $identity;

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
    public function getServiceManager() {
        return $this->sm;
    }

    /**
     * Gets Doctrine\ORM\EntityManager
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

    public function getCurrentUser() {
        return $this->identity;
    }

    public function setCurrentUser($user) {
        $this->identity = $user;
    }

}
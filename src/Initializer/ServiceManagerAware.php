<?php

/**
 * ServiceManagerAware.php
 * 
 * @since 04.05.2018
 * @author Alexej Kisselev <alexej.kisselev@gmail.com>
 */

namespace Aklib\Stdlib\Initializer;

use Interop\Container\ContainerInterface;

interface ServiceManagerAware {

    /**
     * 
     * @return ContainerInterface
     */
    public function getServiceManager();

    public function setServiceManager(ContainerInterface $sm);
}

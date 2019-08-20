<?php

/**
 * 
 * InvokableAwareFactory.php
 * 
 * Initializes ServiceManager and EntityManager etc. for invocable instance
 * 
 * @since 01.04.2018
 * @author Alexej Kisselev <alexej.kisselev@gmail.com>
 */

namespace Aklib\Stdlib\ServiceManager;
/**
 * 
 */
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Aklib\Stdlib\Initializer\ServiceManagerAware;
use Aklib\Stdlib\Initializer\EntityManagerAware;
use Aklib\Stdlib\Initializer\AuthenticationAware;
use Aklib\Stdlib\Initializer\TranslatorAware;

class InvokableAwareFactory implements FactoryInterface {

    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        $instance = (null === $options) ? new $requestedName() : new $requestedName($options);
        if ($instance instanceof ServiceManagerAware) {
            $instance->setServiceManager($container);
        }
        //  set doctrine EntityManager if exists
        if ($instance instanceof EntityManagerAware && $container->has(\Doctrine\ORM\EntityManager::class)) {
            $instance->setEntityManager($container->get(\Doctrine\ORM\EntityManager::class));
        }
        //  set doctrine User if exists
        if ($instance instanceof AuthenticationAware && $container->has('authentication')) {
            /* @var $authService \MyAuthentication\Service\AuthenticationService */
            $authService = $container->get('authentication');
            $identity = $authService->getIdentity();
            $instance->setCurrentUser($identity);
        }

        if ($instance instanceof TranslatorAware) {
            if ($container->has('MvcTranslator')) {
                $instance->setTranslator($container->get('MvcTranslator'));
            }
            elseif ($container->has(TranslatorInterface::class)) {
                $instance->setTranslator($container->get(TranslatorInterface::class));
            }
            elseif ($container->has('Translator')) {
                $instance->setTranslator($container->get('Translator'));
            }
        }
        //  call init function if exists
        if (method_exists($instance, 'init')) {
            $instance->init($options);
        }
        return $instance;
    }

}

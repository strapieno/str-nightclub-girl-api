<?php
namespace Strapieno\NightClubGirl\Api;

use Zend\ModuleManager\Feature\HydratorProviderInterface;
use Zend\Mvc\MvcEvent;

/**
 * Class Module
 */
class Module implements HydratorProviderInterface
{
    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getHydratorConfig()
    {
        return include __DIR__ . '/config/hydrator.config.php';
    }

    /**
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $events = $e->getApplication()->getEventManager();
        // TODO recover from configuration
        $listenerManager = $e->getApplication()->getServiceManager()->get('listenerManager');
        $events->attach($listenerManager->get('Strapieno\NightClubGirl\Api\V1\Listener\NotFoundListener'));
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/',
                ],
            ],
        ];
    }
}

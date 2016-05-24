<?php
namespace Strapieno\NightClubGirl\Api\V1\Listener;

use Matryoshka\Model\Wrapper\Mongo\Criteria\ActiveRecord\ActiveRecordCriteria;
use Strapieno\NightClub\Model\NightClubModelAwareInterface;
use Strapieno\NightClub\Model\NightClubModelAwareTrait;
use Strapieno\NightClubGirl\Model\GirlModelAwareInterface;
use Strapieno\NightClubGirl\Model\GirlModelAwareTrait;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\RouteMatch;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

/**
 * Class NotFoundListener
 */
class NotFoundListener implements ListenerAggregateInterface, GirlModelAwareInterface
{
    const NAME_CONFIG = 'nightclub-girl-not-found';

    use ListenerAggregateTrait;
    use GirlModelAwareTrait;

    /**
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'notFound'], -600);
    }

    public function notFound(Event $e)
    {
        $config = $e->getTarget()->getServiceManager()->get('Config');

        if (!isset($config[self::NAME_CONFIG]) || !is_array($config[self::NAME_CONFIG])) {
            return;
        }

        /** @var $routerMatch RouteMatch */
        $routeMatch = $e->getTarget()->getMvcEvent()->getRouteMatch();
        $match = $routeMatch->getMatchedRouteName();

        if (in_array($match, $config[self::NAME_CONFIG])) {
            $nightClubId = $routeMatch->getParam('girl_id');
            $entity = $this->getNightClubGirlModelService()
                ->find((new ActiveRecordCriteria())->setId($nightClubId))
                ->current();

            if (!$entity) {
                return new ApiProblemResponse(
                    new ApiProblem(404, 'Entity not found')
                );
            }
        }
    }
}
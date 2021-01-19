<?php
use Cake\Event\EventManager;

/**
 * Ensure the pagination params from a service are written to the request object
 * of every controller. This happens by attaching a closure to the event manager
 * instance of each controller.
 */
EventManager::instance()->on('Controller.initialize', function ($event) {
    $controller = $event->getSubject();
    $controller->getEventManager()->on('Service.afterPaginate', function ($event) use ($controller) {
        $controller->setRequest($event->getSubject()->addPagingParamToRequest($controller->getRequest()));
    });
});

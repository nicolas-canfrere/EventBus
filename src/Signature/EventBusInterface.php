<?php

namespace Loxodonta\EventBus\Signature;

/**
 * Interface EventBusInterface
 */
interface EventBusInterface
{
    /**
     * @param EventHandlerInterface $handler
     *
     * @return EventBusInterface
     */
    public function registerHandler(EventHandlerInterface $handler): EventBusInterface;

    /**
     * @param EventHandlerInterface $handler
     *
     * @return bool
     */
    public function hasHandler(EventHandlerInterface $handler): bool;

    /**
     * @param EventInterface $event
     */
    public function dispatch(EventInterface $event): void;

    /**
     * @param string $eventName
     *
     * @return bool
     */
    public function hasHandlersForEvent(string $eventName): bool;
}

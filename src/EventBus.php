<?php

namespace Loxodonta\EventBus;

use Loxodonta\EventBus\Signature\EventBusInterface;
use Loxodonta\EventBus\Signature\EventHandlerInterface;

/**
 * Class EventBus
 */
class EventBus implements EventBusInterface
{
    private array $handlers = [];

    /**
     * @inheritDoc
     */
    public function registerHandler(EventHandlerInterface $handler): EventBusInterface
    {
        $eventName = $handler->listenTo();
        if (!array_key_exists($eventName, $this->handlers)) {
            $this->handlers[$eventName] = [];
        }
        $this->handlers[$eventName][] = $handler;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function hasHandler(EventHandlerInterface $handler): bool
    {
        $eventName = $handler->listenTo();
        return array_key_exists($eventName, $this->handlers) &&
               in_array($handler, $this->handlers[$eventName], true)
            ;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(object $event): void
    {
        $eventName = get_class($event);

        if ($this->hasHandlersForEvent($eventName)) {
            foreach ($this->handlers[$eventName] as $handler) {
                $handler->handle($event);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function hasHandlersForEvent(string $eventName): bool
    {
        return array_key_exists($eventName, $this->handlers) &&
               !empty($this->handlers[$eventName]);
    }
}

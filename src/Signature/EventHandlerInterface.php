<?php

namespace Loxodonta\EventBus\Signature;

/**
 * Interface EventHandlerInterface
 */
interface EventHandlerInterface
{
    /**
     * @param object $event
     */
    public function handle($event): void;

    /**
     * @return string
     */
    public function listenTo(): string;
}

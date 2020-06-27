<?php

namespace Loxodonta\EventBus\Tests;

use Loxodonta\EventBus\EventBus;
use Loxodonta\EventBus\Exception\EventHasNoHandlerException;
use Loxodonta\EventBus\Signature\EventBusInterface;
use Loxodonta\EventBus\Signature\EventHandlerInterface;
use Loxodonta\EventBus\Tests\Fake\SimpleEvent;
use PHPUnit\Framework\TestCase;

/**
 * Class EventBusTest
 */
class EventBusTest extends TestCase
{
    /**
     * @test
     */
    public function itMustImplementEventBusInterface()
    {
        $eventBus = new EventBus();

        $this->assertInstanceOf(EventBusInterface::class, $eventBus);
    }

    /**
     * @test
     */
    public function itCanRegisterHandlers()
    {
        $eventHandler = $this->createMock(EventHandlerInterface::class);

        $eventBus = new EventBus();

        $eventBus->registerHandler($eventHandler);

        $this->assertTrue($eventBus->hasHandler($eventHandler));
    }

    /**
     * @test
     */
    public function itCanDispatchEvent()
    {
        $event = new SimpleEvent();
        $eventBus = new EventBus();
        $eventHandler = $this->mockHandler(get_class($event));

        $eventBus->registerHandler($eventHandler);
        $eventHandler->expects($this->once())->method('handle')
            ->with($event);


        $eventBus->dispatch($event);
    }

    /**
     * @test
     */
    public function itMustThrowExceptionIfNoHandlerForEvent()
    {
        $event = new SimpleEvent();
        $eventBus = new EventBus();

        $this->expectException(EventHasNoHandlerException::class);

        $eventBus->dispatch($event);
    }

    /**
     * @test
     */
    public function hasHandlersForEvent()
    {
        $event = new SimpleEvent();
        $eventBus = new EventBus();

        $firstHandler = $this->mockHandler(get_class($event));
        $secondHandler = $this->mockHandler(get_class($event));

        $eventBus
            ->registerHandler($firstHandler)
            ->registerHandler($secondHandler)
        ;

        $this->assertFalse($eventBus->hasHandlersForEvent('fake'));
        $this->assertTrue($eventBus->hasHandlersForEvent(get_class($event)));
    }

    /**
     * @test
     */
    public function itCanRegisterMultipleHandlerForOneEvent()
    {
        $event = new SimpleEvent();
        $eventBus = new EventBus();

        $firstHandler = $this->mockHandler(get_class($event));
        $secondHandler = $this->mockHandler(get_class($event));

        $eventBus
            ->registerHandler($firstHandler)
            ->registerHandler($secondHandler)
        ;

        $firstHandler->expects($this->once())->method('handle')
                     ->with($event);
        $secondHandler->expects($this->once())->method('handle')
                     ->with($event);

        $eventBus->dispatch($event);
    }

    private function mockHandler($eventName)
    {
        $eventHandler = $this->getMockBuilder(EventHandlerInterface::class)
                             ->onlyMethods(['handle', 'listenTo'])->getMock();
        $eventHandler->method('listenTo')->willReturn($eventName);

        return $eventHandler;
    }
}

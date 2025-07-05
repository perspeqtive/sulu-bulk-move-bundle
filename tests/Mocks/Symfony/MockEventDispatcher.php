<?php

declare(strict_types=1);

namespace PERSPEQTIVE\SuluBulkMoveBundle\Tests\Mocks\Symfony;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MockEventDispatcher implements EventDispatcherInterface
{
    public array $events = [];
    public array $addedListener = [];

    public function dispatch(object $event, ?string $eventName = null): object
    {
        $this->events[] = $event;

        return $event;
    }

    public function addListener(string $eventName, callable $listener, int $priority = 0): void
    {
        $this->addedListener[] = ['eventname' => $eventName, 'callable' => $listener, 'priority' => $priority];
    }

    public function addSubscriber(EventSubscriberInterface $subscriber): void
    {
    }

    public function removeListener(string $eventName, callable $listener): void
    {
    }

    public function removeSubscriber(EventSubscriberInterface $subscriber): void
    {
    }

    public function getListeners(?string $eventName = null): array
    {
    }

    public function getListenerPriority(string $eventName, callable $listener): ?int
    {
    }

    public function hasListeners(?string $eventName = null): bool
    {
    }
}

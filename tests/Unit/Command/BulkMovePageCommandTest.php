<?php

declare(strict_types=1);

namespace PERSPEQTIVE\SuluBulkMoveBundle\Tests\Unit\Command;

use PERSPEQTIVE\SuluBulkMoveBundle\Command\BulkMovePageCommand;
use PERSPEQTIVE\SuluBulkMoveBundle\Tests\Mocks\MockBulkMover;
use PERSPEQTIVE\SuluBulkMoveBundle\Tests\Mocks\Symfony\MockEventDispatcher;
use PHPUnit\Framework\TestCase;
use Sulu\Bundle\PageBundle\Document\PageDocument;
use Sulu\Bundle\PageBundle\Domain\Event\PageMovedEvent;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

use function trim;

class BulkMovePageCommandTest extends TestCase
{
    private BulkMovePageCommand $command;
    private MockBulkMover $mover;
    private MockEventDispatcher $eventDispatcher;

    protected function setUp(): void
    {
        $this->mover = new MockBulkMover();
        $this->eventDispatcher = new MockEventDispatcher();
        $this->command = new BulkMovePageCommand(
            $this->mover,
            $this->eventDispatcher,
        );
    }

    public function testExecute(): void
    {
        $input = new ArrayInput([
            'source' => 'source-123',
            'target' => 'target-123',
            'locale' => 'de',
        ]);
        $output = new BufferedOutput();
        $this->command->run($input, $output);

        self::assertEquals('source-123', $this->mover->calledWith[0]['source']);
        self::assertEquals('target-123', $this->mover->calledWith[0]['target']);
        self::assertEquals('de', $this->mover->calledWith[0]['locale']);
    }

    public function testEventDispatcherIsBeeingListenedTo(): void
    {
        $input = new ArrayInput([
            'source' => 'source-123',
            'target' => 'target-123',
            'locale' => 'de',
        ]);
        $output = new BufferedOutput();

        $this->command->run($input, $output);

        self::assertEquals(PageMovedEvent::class, $this->eventDispatcher->addedListener[0]['eventname']);

        $parent = new PageDocument();
        $parent->setTitle('Parent page title');

        $movedPage = new PageDocument();
        $movedPage->setTitle('Page title');
        $movedPage->setParent($parent);

        $event = new PageMovedEvent($movedPage, null, null, 'previousParentTitle', null);
        $this->eventDispatcher->addedListener[0]['callable']($event);
        $outputContent = $output->fetch();
        self::assertSame('Moved page from "previousParentTitle" to new parent "Parent page title": Page title', trim($outputContent));
    }
}

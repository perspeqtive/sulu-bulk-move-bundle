<?php

declare(strict_types=1);

namespace PERSPEQTIVE\SuluBulkMoveBundle\Command;

use PERSPEQTIVE\SuluBulkMoveBundle\Mover\BulkMover;
use Psr\EventDispatcher\EventDispatcherInterface;
use Sulu\Bundle\PageBundle\Domain\Event\PageMovedEvent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BulkMoveNavigationCommand extends Command
{

    public function __construct(
        private BulkMover $mover,
        private EventDispatcherInterface $dispatcher
    ) {

    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->addArgument('source', InputArgument::REQUIRED, 'UUID of the source parent page')
            ->addArgument('target', InputArgument::REQUIRED, 'UUID of the target parent page')
            ->addArgument('locale', InputArgument::REQUIRED, 'The locale of the pages')
            ->setDescription('Moves all children of a page to another parent');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->applyOutputListener($output);

        $this->moveChildren($input);

        return Command::SUCCESS;
    }

    private function applyOutputListener(OutputInterface $output): void
    {
        $this->dispatcher->addListener(PageMovedEvent::class, function (PageMovedEvent $event) use ($output) {
            $this->onPageMoved($event, $output);
        });
    }

    private function moveChildren(InputInterface $input): void
    {
        $sourceUuid = $input->getArgument('source');
        $targetUuid = $input->getArgument('target');
        $locale = $input->getArgument('locale');
        $this->mover->move($sourceUuid, $targetUuid, $locale);
    }

    private function onPageMoved(PageMovedEvent $event, OutputInterface $output): void
    {
        $output->writeln(sprintf(
            'Sulu PageMovedEvent: Moved "%s" from "%s" to new parent "%s"',
            $event->getPageDocument()->getTitle(),
            $event->getPageDocument()->getParent()->getTitle(),
            $event->getEventContext()['newParentTitle'] ?? ''
        ));
    }
}
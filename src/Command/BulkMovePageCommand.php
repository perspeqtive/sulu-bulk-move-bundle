<?php

declare(strict_types=1);

namespace PERSPEQTIVE\SuluBulkMoveBundle\Command;

use PERSPEQTIVE\SuluBulkMoveBundle\Mover\BulkMoverInterface;
use Sulu\Bundle\PageBundle\Domain\Event\PageMovedEvent;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use function sprintf;

#[AsCommand(name: 'perspeqtive:sulu:bulk-move')]
class BulkMovePageCommand extends Command
{
    public function __construct(
        private readonly BulkMoverInterface $mover,
        private readonly EventDispatcherInterface $dispatcher,
    ) {
        parent::__construct();
    }

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
        /** @var string $sourceUuid */
        $sourceUuid = $input->getArgument('source');
        /** @var string $targetUuid */
        $targetUuid = $input->getArgument('target');
        /** @var string $locale */
        $locale = $input->getArgument('locale');
        $this->mover->move($sourceUuid, $targetUuid, $locale);
    }

    private function onPageMoved(PageMovedEvent $event, OutputInterface $output): void
    {
        /** @var string $parentTitle */
        $parentTitle = $event->getEventContext()['previousParentTitle'] ?? 'unknown';
        /** @var string $newParentTitle */
        $newParentTitle = $event->getEventContext()['newParentTitle'] ?? 'unknown';
        $output->writeln(sprintf(
            'Moved page from "%s" to new parent "%s": %s',
            $parentTitle,
            $newParentTitle,
            $event->getPageDocument()->getTitle(),
        ));
    }
}

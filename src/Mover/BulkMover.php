<?php

declare(strict_types=1);

namespace PERSPEQTIVE\SuluBulkMoveBundle\Mover;

use Sulu\Bundle\PageBundle\Document\BasePageDocument;
use Sulu\Component\DocumentManager\DocumentManagerInterface;

readonly class BulkMover implements BulkMoverInterface
{
    public function __construct(
        private DocumentManagerInterface $documentManager,
    ) {
    }

    public function move(string $sourceUuid, string $targetUuid, string $locale): void
    {
        /** @var BasePageDocument $source */
        $source = $this->documentManager->find($sourceUuid, $locale);

        /** @var BasePageDocument $child */
        foreach ($source->getChildren() as $child) {
            $this->documentManager->move($child, $targetUuid);
        }

        $this->documentManager->flush();
    }
}

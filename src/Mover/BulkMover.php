<?php

declare(strict_types=1);

namespace PERSPEQTIVE\SuluBulkMoveBundle\Mover;

use Sulu\Component\DocumentManager\DocumentManagerInterface;

readonly class BulkMover
{

    public function __construct(
        private DocumentManagerInterface $documentManager,
    )
    {
    }

    public function move(string $sourceUuid, string $targetUuid, string $locale): void
    {
        $source = $this->documentManager->find($sourceUuid, $locale);
        $target = $this->documentManager->find($targetUuid, $locale);

        $children = $source->getChildren();

        foreach ($children as $child) {
            $this->documentManager->move($child, $target);
        }

        $this->documentManager->flush();
    }

}
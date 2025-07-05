<?php

declare(strict_types=1);

namespace PERSPEQTIVE\SuluBulkMoveBundle\Tests\Mocks;

use PERSPEQTIVE\SuluBulkMoveBundle\Mover\BulkMoverInterface;

class MockBulkMover implements BulkMoverInterface
{
    public array $calledWith = [];

    public function move(string $sourceUuid, string $targetUuid, string $locale): void
    {
        $this->calledWith[] = ['source' => $sourceUuid, 'target' => $targetUuid, 'locale' => $locale];
    }
}

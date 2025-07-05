<?php

declare(strict_types=1);

namespace PERSPEQTIVE\SuluBulkMoveBundle\Mover;

interface BulkMoverInterface
{
    public function move(string $sourceUuid, string $targetUuid, string $locale): void;
}

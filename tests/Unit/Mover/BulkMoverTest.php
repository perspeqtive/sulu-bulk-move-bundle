<?php

declare(strict_types=1);

namespace PERSPEQTIVE\SuluBulkMoveBundle\Tests\Unit\Mover;

use PERSPEQTIVE\SuluBulkMoveBundle\Mover\BulkMover;
use PERSPEQTIVE\SuluBulkMoveBundle\Tests\Mocks\Sulu\MockDocumentManager;
use PHPUnit\Framework\TestCase;
use Sulu\Bundle\PageBundle\Document\PageDocument;
use Sulu\Component\DocumentManager\Exception\DocumentNotFoundException;

class BulkMoverTest extends TestCase
{
    public function testMoveWithSourceNotFound(): void
    {
        $this->expectException(DocumentNotFoundException::class);
        $documentManager = new MockDocumentManager();
        $mover = new BulkMover($documentManager);
        $mover->move('source-123', 'target-123', 'de');
    }

    public function testMoveWithNoChildren(): void
    {
        $documentManager = new MockDocumentManager();
        $documentManager->findResult['source-123'] = $this->buildPageDocument();
        $mover = new BulkMover($documentManager);
        $mover->move('source-123', 'target-123', 'de');

        self::assertCount(0, $documentManager->moved);
    }

    public function testMoveWith2Children(): void
    {
        $documentManager = new MockDocumentManager();
        $documentManager->findResult['source-123'] = $this->buildPageDocument(2);
        $mover = new BulkMover($documentManager);
        $mover->move('source-123', 'target-123', 'de');

        self::assertCount(2, $documentManager->moved['target-123']);
    }

    private function buildPageDocument(int $childrenCount = 0): PageDocument
    {
        $doc = new PageDocument();
        $children = $doc->getChildren();
        for ($i = 0; $i < $childrenCount; ++$i) {
            $children->append($this->buildPageDocument(0));
        }

        return $doc;
    }
}

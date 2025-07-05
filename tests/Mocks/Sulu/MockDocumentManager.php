<?php

declare(strict_types=1);

namespace PERSPEQTIVE\SuluBulkMoveBundle\Tests\Mocks\Sulu;

use Sulu\Component\DocumentManager\DocumentManagerInterface;
use Sulu\Component\DocumentManager\Exception\DocumentNotFoundException;

class MockDocumentManager implements DocumentManagerInterface
{
    public array $moved = [];

    public function __construct(public array $findResult = [])
    {
    }

    public function find($identifier, $locale = null, array $options = [])
    {
        if (!isset($this->findResult[$identifier])) {
            throw new DocumentNotFoundException();
        }

        return $this->findResult[$identifier];
    }

    public function create($alias)
    {
    }

    public function persist($document, $locale = null, array $options = [])
    {
    }

    public function remove($document)
    {
    }

    public function removeLocale($document, $locale)
    {
    }

    public function move($document, $destId): void
    {
        $this->moved[$destId][] = $document;
    }

    public function copy($document, $destPath)
    {
    }

    public function copyLocale($document, $srcLocale, $destLocale)
    {
    }

    public function reorder($document, $destId)
    {
    }

    public function publish($document, $locale = null, array $options = [])
    {
    }

    public function unpublish($document, $locale)
    {
    }

    public function removeDraft($document, $locale)
    {
    }

    public function restore($document, $locale, $version, array $options = [])
    {
    }

    public function refresh($document)
    {
    }

    public function flush()
    {
    }

    public function clear()
    {
    }

    public function createQuery($query, $locale = null, array $options = [])
    {
    }
}

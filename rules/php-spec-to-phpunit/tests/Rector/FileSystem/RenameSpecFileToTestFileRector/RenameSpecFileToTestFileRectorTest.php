<?php

declare(strict_types=1);

namespace Rector\PhpSpecToPHPUnit\Tests\Rector\FileSystem\RenameSpecFileToTestFileRector;

use Iterator;
use Rector\PhpSpecToPHPUnit\Rector\FileSystem\RenameSpecFileToTestFileRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RenameSpecFileToTestFileRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture', '*.php');
    }

    protected function getRectorClass(): string
    {
        return RenameSpecFileToTestFileRector::class;
    }
}

<?php

declare(strict_types=1);

namespace Rector\Nette\Tests\Rector\Class_\MoveFinalGetUserToCheckRequirementsClassMethodRector;

use DG\BypassFinals;
use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Rector\Nette\Rector\Class_\MoveFinalGetUserToCheckRequirementsClassMethodRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class MoveFinalGetUserToCheckRequirementsClassMethodRectorTest extends AbstractRectorTestCase
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
        // so the fixture can inherit from final method without Fatal Error
        BypassFinals::enable();

        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    protected function getRectorClass(): string
    {
        return MoveFinalGetUserToCheckRequirementsClassMethodRector::class;
    }
}

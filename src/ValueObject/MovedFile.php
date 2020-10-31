<?php

declare(strict_types=1);

namespace Rector\Core\ValueObject;

use Symplify\SmartFileSystem\SmartFileInfo;

final class MovedFile
{
    /**
     * @var SmartFileInfo
     */
    private $oldFileInfo;

    /**
     * @var string
     */
    private $newPath;

    /**
     * @var string
     */
    private $fileContent;

    public function __construct(SmartFileInfo $oldFileInfo, string $newPath, string $fileContent)
    {
        $this->oldFileInfo = $oldFileInfo;
        $this->newPath = $newPath;
        $this->fileContent = $fileContent;
    }

    public function getOldFileInfo(): SmartFileInfo
    {
        return $this->oldFileInfo;
    }

    public function getNewPath(): string
    {
        return $this->newPath;
    }

    public function getFileContent(): string
    {
        return $this->fileContent;
    }
}

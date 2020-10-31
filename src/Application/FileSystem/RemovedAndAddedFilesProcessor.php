<?php

declare(strict_types=1);

namespace Rector\Core\Application\FileSystem;

use Rector\Core\Configuration\Configuration;
use Rector\Core\PhpParser\Printer\NodesWithFileDestinationPrinter;
use Rector\Core\ValueObject\MovedFile;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\SmartFileSystem\SmartFileSystem;

/**
 * Adds and removes scheduled file
 */
final class RemovedAndAddedFilesProcessor
{
    /**
     * @var RemovedAndAddedFilesCollector
     */
    private $removedAndAddedFilesCollector;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;

    /**
     * @var NodesWithFileDestinationPrinter
     */
    private $nodesWithFileDestinationPrinter;

    /**
     * @var SmartFileSystem
     */
    private $smartFileSystem;

    public function __construct(
        Configuration $configuration,
        SmartFileSystem $smartFileSystem,
        NodesWithFileDestinationPrinter $nodesWithFileDestinationPrinter,
        RemovedAndAddedFilesCollector $removedAndAddedFilesCollector,
        SymfonyStyle $symfonyStyle
    ) {
        $this->removedAndAddedFilesCollector = $removedAndAddedFilesCollector;
        $this->configuration = $configuration;
        $this->symfonyStyle = $symfonyStyle;
        $this->nodesWithFileDestinationPrinter = $nodesWithFileDestinationPrinter;
        $this->smartFileSystem = $smartFileSystem;
    }

    public function run(): void
    {
        $this->processAddedFiles();
        $this->processDeletedFiles();
        $this->processMovedFiles();
    }

    private function processAddedFiles(): void
    {
        foreach ($this->removedAndAddedFilesCollector->getAddedFilePathsWithContents() as $filePathWithContent) {
            if ($this->configuration->isDryRun()) {
                $message = sprintf('File "%s" will be added:', $filePathWithContent->getFilePath());
                $this->symfonyStyle->note($message);
            } else {
                $this->smartFileSystem->dumpFile(
                    $filePathWithContent->getFilePath(),
                    $filePathWithContent->getFileContent()
                );
                $message = sprintf('File "%s" was added:', $filePathWithContent->getFilePath());
                $this->symfonyStyle->note($message);
            }

            $this->symfonyStyle->writeln($filePathWithContent->getFileContent());
        }

        foreach ($this->removedAndAddedFilesCollector->getNodesWithFileDestination() as $nodesWithFileDestination) {
            $fileContent = $this->nodesWithFileDestinationPrinter->printNodesWithFileDestination(
                $nodesWithFileDestination
            );

            if ($this->configuration->isDryRun()) {
                $message = sprintf('File "%s" will be added:', $nodesWithFileDestination->getFileDestination());
                $this->symfonyStyle->note($message);
            } else {
                $this->smartFileSystem->dumpFile($nodesWithFileDestination->getFileDestination(), $fileContent);
                $message = sprintf('File "%s" was added:', $nodesWithFileDestination->getFileDestination());
                $this->symfonyStyle->note($message);
            }

            $this->symfonyStyle->writeln('----------------------------------------');
            $this->symfonyStyle->writeln($fileContent);
            $this->symfonyStyle->writeln('----------------------------------------');
        }
    }

    private function processDeletedFiles(): void
    {
        foreach ($this->removedAndAddedFilesCollector->getRemovedFiles() as $removedFile) {
            $relativePath = $removedFile->getRelativeFilePathFromDirectory(getcwd());

            if ($this->configuration->isDryRun()) {
                $message = sprintf('File "%s" will be removed', $relativePath);
                $this->symfonyStyle->warning($message);
            } else {
                $message = sprintf('File "%s" was removed', $relativePath);
                $this->symfonyStyle->warning($message);
                $this->smartFileSystem->remove($removedFile->getRealPath());
            }
        }
    }

    private function processMovedFiles(): void
    {
        foreach ($this->removedAndAddedFilesCollector->getMovedFiles() as $movedClassValueObject) {
            if ($this->configuration->isDryRun() && ! StaticPHPUnitEnvironment::isPHPUnitRun()) {
                $this->printFileMoveWarning($movedClassValueObject, 'will be');
            } else {
                $this->printFileMoveWarning($movedClassValueObject, 'was');

                $this->smartFileSystem->remove($movedClassValueObject->getOldFileInfo());

                $this->smartFileSystem->dumpFile(
                    $movedClassValueObject->getNewPath(),
                    $movedClassValueObject->getFileContent()
                );
            }
        }
    }

    private function printFileMoveWarning(MovedFile $movedClass, string $verb): void
    {
        $message = sprintf(
            'File "%s" %s moved to "%s"',
            $movedClass->getOldFileInfo(),
            $verb,
            $movedClass->getNewPath()
        );

        $this->symfonyStyle->warning($message);
    }
}

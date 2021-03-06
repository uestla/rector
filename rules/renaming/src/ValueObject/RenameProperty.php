<?php

declare(strict_types=1);

namespace Rector\Renaming\ValueObject;

final class RenameProperty
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $oldProperty;

    /**
     * @var string
     */
    private $newProperty;

    public function __construct(string $type, string $oldProperty, string $newProperty)
    {
        $this->type = $type;
        $this->oldProperty = $oldProperty;
        $this->newProperty = $newProperty;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getOldProperty(): string
    {
        return $this->oldProperty;
    }

    public function getNewProperty(): string
    {
        return $this->newProperty;
    }
}

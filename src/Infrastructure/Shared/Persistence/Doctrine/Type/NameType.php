<?php

namespace App\Infrastructure\Shared\Persistence\Doctrine\Type;

use App\Domain\Shared\ValueObject\Name;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class NameType extends StringType
{
    const NAME = 'name';

    protected function getUidClass(): string
    {
        return Name::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * Gets the SQL declaration snippet for a field of this type.
     */
    final public function getSQLDeclaration(array $column, AbstractPlatform $platform) : string
    {
        return $platform->getVarcharTypeDeclarationSQL([
            'length' => Name::MAX_LENGTH,
        ]);
    }


    /**
     * Adds an SQL comment to typehint the actual Doctrine Type for reverse schema engineering.
     */
    final public function requiresSQLCommentHint(AbstractPlatform $platform) : bool
    {
        return true;
    }


    /**
     * Converts a value from its database representation to its PHP representation of this type.
     *
     * @param mixed $value The value to convert.
     */
    public function convertToPHPValue($value, AbstractPlatform $platform) : ?Name
    {
        return $value !== null ? Name::fromString($value) : null;
    }


    /**
     * Converts a value from its PHP representation to its database representation of this type.
     *
     * @param Name|null $value The value to convert.
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform) : ?string
    {
        return $value ? (string)$value : null;
    }
}

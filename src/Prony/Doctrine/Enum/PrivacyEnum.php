<?php

declare(strict_types=1);

namespace Prony\Doctrine\Enum;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class PrivacyEnum extends AbstractEnumType
{
    public const PUBLIC = 'public';

    public const PRIVATE = 'private';

    protected static $choices = [
        self::PUBLIC => self::PUBLIC,
        self::PRIVATE => self::PRIVATE,
    ];
}

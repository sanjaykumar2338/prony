<?php

declare(strict_types=1);

namespace Prony\Doctrine\Enum;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class LanguageEnum extends AbstractEnumType
{
    public const EN = 'en';

    public const ES = 'es';

    public const DE = 'de';

    public const FR = 'fr';

    public const PT = 'pt';

    protected static $choices = [
        self::EN => self::EN,
        self::ES => self::ES,
        self::DE => self::DE,
        self::FR => self::FR,
        self::PT => self::PT,
    ];
}

<?php

declare(strict_types=1);

namespace Prony\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PostExtraArray extends Constraint
{
    public $message = 'Extra post data should be in a key-value format';
}

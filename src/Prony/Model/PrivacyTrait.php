<?php

declare(strict_types=1);

namespace Prony\Model;

trait PrivacyTrait
{
    /** @var string */
    protected $privacy;

    public function getPrivacy(): string
    {
        return $this->privacy;
    }

    public function setPrivacy(string $privacy): void
    {
        $this->privacy = $privacy;
    }
}

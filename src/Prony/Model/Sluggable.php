<?php

declare(strict_types=1);

namespace Prony\Model;

trait Sluggable
{
    /** @var string|null */
    protected $slug;

    public function getSlug(): ?string
    {
        return $this->slug;
    }
}

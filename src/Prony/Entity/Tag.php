<?php

declare(strict_types=1);

namespace Prony\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Prony\Doctrine\Enum\PrivacyEnum;
use Prony\Model\PrivacyTrait;
use Prony\Model\Sluggable;
use Prony\Model\WorkspaceAware;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Talav\Component\Resource\Model\ResourceInterface;
use Talav\Component\Resource\Model\ResourceTrait;
use Talav\Component\Resource\Model\Timestampable;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "post"={
 *             "normalization_context"={"groups"={"write-response"}},
 *             "denormalization_context"={"groups"={"post"}},
 *             "validation_groups"={"post"}
 *         },
 *         "get"={
 *             "normalization_context"={"groups"={"get"}}
 *         },
 *     },
 *     itemOperations={
 *         "get"={
 *             "normalization_context"={"groups"={"get"}}
 *         },
 *         "put"={
 *             "normalization_context"={"groups"={"write-response"}},
 *             "denormalization_context"={"groups"={"put"}},
 *             "validation_groups"={"put"}
 *         },
 *         "delete"={}
 *     },
 *     attributes={"order"={"name": "ASC"}}
 * )
 * @UniqueEntity(
 *     fields={"name", "workspace"},
 *     errorPath="name",
 *     groups={"post", "put"}
 * )
 */
class Tag implements ResourceInterface, WorkspaceAware
{
    use ResourceTrait;
    use Timestampable;
    use Sluggable;
    use PrivacyTrait;

    /**
     * @Groups({"get", "put", "post", "write-response"})
     *
     * @Assert\NotNull(groups={"post", "put"})
     * @Assert\Length(
     *     min = 2,
     *     max = 100,
     *     groups={"post", "put"}
     * )
     *
     * @var string|null
     */
    protected $name;

    /**
     * @Groups({"get", "put", "post"})
     *
     * @Assert\NotNull(groups={"post", "put"})
     * @Assert\Regex(pattern = "/#[A-Fa-f0-9]{6}/", groups={"post", "put"})
     *
     * @var string
     */
    protected $color;

    /**
     * @Groups({"get", "get-sub"})
     *
     * @var string|null
     */
    protected $slug;

    /**
     * @Groups({"get", "put", "post"})
     *
     * @Assert\NotNull(groups={"post", "put"})
     * @Assert\Choice(
     *     callback={"Prony\Doctrine\Enum\PrivacyEnum", "getValues"},
     *     groups={"post", "put"}
     * )
     *
     * @var string
     */
    protected $privacy;

    /**
     * @Groups({"post"})
     *
     * @Assert\NotNull(groups={"post", "put"})
     *
     * @var Workspace|null
     */
    protected $workspace;

    public function __construct()
    {
        $this->privacy = PrivacyEnum::PUBLIC;
        $this->color = '#FFFFFF';
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    public function getWorkspace(): ?Workspace
    {
        return $this->workspace;
    }

    public function setWorkspace(?Workspace $workspace): void
    {
        $this->workspace = $workspace;
    }
}

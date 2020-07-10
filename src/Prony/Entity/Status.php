<?php

declare(strict_types=1);

namespace Prony\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
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
 *             "denormalization_context"={"groups"={"post"}}
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
 *             "denormalization_context"={"groups"={"put"}}
 *         },
 *         "delete"={}
 *     },
 *     subresourceOperations={
 *         "api_workspaces_statuses_get_subresource"={
 *             "method"="GET",
 *             "normalization_context"={"groups"={"get-sub"}}
 *         }
 *     },
 *     attributes={"order"={"position": "ASC"}}
 * )
 * @UniqueEntity(
 *     fields={"name", "workspace"},
 *     errorPath="name",
 * )
 */
class Status implements ResourceInterface, WorkspaceAware
{
    use ResourceTrait;
    use Timestampable;
    use Sluggable;
    use PrivacyTrait;

    /**
     * @Groups({"get", "put", "post", "write-response"})
     *
     * @Assert\NotNull
     * @Assert\Length(
     *     min = 2,
     *     max = 100
     * )
     *
     * @var string|null
     */
    protected $name;

    /**
     * @Assert\NotNull
     *
     * @Groups({"get", "put", "post"})
     *
     * @var bool
     */
    protected $isVoteable;

    /**
     * @Groups({"get", "put", "post"})
     *
     * @Assert\NotNull
     *
     * @var bool
     */
    protected $isRoadMap;

    /**
     * @Groups({"get", "put", "post"})
     *
     * @Assert\NotNull
     * @Assert\LessThanOrEqual(
     *     value = 9999,
     * )
     * @Assert\GreaterThanOrEqual(
     *     value = 0,
     * )
     *
     * @var int
     */
    protected $position;

    /**
     * @Groups({"get", "put", "post"})
     *
     * @Assert\NotNull
     * @Assert\Choice(callback={"Prony\Doctrine\Enum\PrivacyEnum", "getValues"})
     *
     * @var string
     */
    protected $privacy;

    /**
     * @Groups({"get", "put", "post"})
     *
     * @Assert\NotNull
     * @Assert\Regex(pattern = "/#[A-Fa-f0-9]{6}/")
     *
     * @var string
     */
    protected $color;

    /**
     * @Assert\NotNull
     *
     * @Groups({"post"})
     *
     * @var Workspace|null
     */
    protected $workspace;

    public function __construct()
    {
        $this->position = 9999;
        $this->isVoteable = true;
        $this->isRoadMap = true;
        $this->color = '#000000';
        $this->privacy = PrivacyEnum::PUBLIC;
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getWorkspace(): ?Workspace
    {
        return $this->workspace;
    }

    public function setWorkspace(?Workspace $workspace): void
    {
        $this->workspace = $workspace;
        $workspace->addStatus($this);
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function isVoteable(): bool
    {
        return $this->isVoteable;
    }

    public function setIsVoteable(bool $isVoteable): void
    {
        $this->isVoteable = $isVoteable;
    }

    public function isRoadMap(): bool
    {
        return $this->isRoadMap;
    }

    public function setIsRoadMap(bool $isRoadMap): void
    {
        $this->isRoadMap = $isRoadMap;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): void
    {
        $this->color = $color;
    }
}

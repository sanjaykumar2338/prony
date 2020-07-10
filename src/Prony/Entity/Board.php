<?php

declare(strict_types=1);

namespace Prony\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Prony\Doctrine\Enum\PrivacyEnum;
use Prony\Model\PrivacyTrait;
use Prony\Model\WorkspaceAware;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Talav\Component\Resource\Model\ResourceInterface;
use Talav\Component\Resource\Model\ResourceTrait;
use Talav\Component\Resource\Model\Timestampable;
use Talav\Component\User\Model\Blameable;

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
 *     attributes={"order"={"position": "ASC"}, "force_eager"=false}
 * )
 *
 * @UniqueEntity(
 *     fields={"name", "workspace"},
 *     errorPath="name",
 * )
 */
class Board implements ResourceInterface, WorkspaceAware
{
    use ResourceTrait;
    use Timestampable;
    use Blameable;
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
     * @Groups({"get", "put", "post"})
     *
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
     * @Assert\Choice(callback={"Prony\Doctrine\Enum\PrivacyEnum", "getValues"})
     *
     * @var string
     */
    protected $privacy;

    /**
     * @Groups({"get"})
     *
     * @var string|null
     */
    protected $slug;

    /**
     * @Groups({"get", "post"})
     *
     * @var Workspace|null
     */
    protected $workspace;

    /** @var Collection */
    protected $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        // try to put it at the end of the list
        $this->position = 9999;
        $this->privacy = PrivacyEnum::PUBLIC;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getWorkspace(): ?Workspace
    {
        return $this->workspace;
    }

    public function setWorkspace(?Workspace $workspace): void
    {
        $this->workspace = $workspace;
        $workspace->addBoard($this);
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function getPostsByStatus(Status $status): Collection
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq('status', $status));

        return $this->getPosts()->matching($criteria);
    }

    public function addPost(Post $post): void
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setBoard($this);
        }
    }
}

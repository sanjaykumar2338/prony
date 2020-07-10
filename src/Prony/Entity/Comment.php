<?php

declare(strict_types=1);

namespace Prony\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Talav\Component\Resource\Model\ResourceInterface;
use Talav\Component\Resource\Model\ResourceTrait;
use Talav\Component\Resource\Model\Timestampable;
use Talav\Component\User\Model\Blameable;
use Talav\Component\User\Model\UserInterface;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "post"={
 *             "normalization_context"={"groups"={"write-response"}},
 *             "denormalization_context"={"groups"={"post"}},
 *             "validation_groups"={"post"}
 *         }
 *     },
 *     itemOperations={
 *         "put"={
 *             "normalization_context"={"groups"={"write-response"}},
 *             "denormalization_context"={"groups"={"put"}},
 *             "validation_groups"={"post"}
 *         },
 *         "get"={
 *             "normalization_context"={"groups"={"get"}}
 *         }
 *     },
 *     attributes={"order"={"score": "DESC"}, "force_eager"=false}
 * )
 */
class Comment implements ResourceInterface
{
    use ResourceTrait;
    use Timestampable;
    use Blameable;

    /**
     * @Groups({"get", "put", "post", "write-response"})
     *
     * @var string | null
     */
    protected $comment;

    /** @var int | null */
    protected $left;

    /** @var int | null */
    protected $level;

    /** @var int | null */
    protected $right;

    /** @var Comment | null */
    protected $root;

    /** @var Comment | null */
    protected $parent;

    /**
     * @Groups({"get", "post"})
     *
     * @var Collection
     */
    protected $children;

    /**
     * @Groups({"get", "post"})
     *
     * @var Media|null
     */
    protected $media;

    /**
     * @Groups({"get"})
     *
     * @var UserInterface
     */
    protected $createdBy;

    /**
     * @Groups({"get"})
     *
     * @var DateTime
     */
    protected $createdAt;

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function getLeft(): ?int
    {
        return $this->left;
    }

    public function setLeft(?int $left): void
    {
        $this->left = $left;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): void
    {
        $this->level = $level;
    }

    public function getRight(): ?int
    {
        return $this->right;
    }

    public function setRight(?int $right): void
    {
        $this->right = $right;
    }

    public function getRoot(): ?self
    {
        return $this->root;
    }

    public function setRoot(?self $root): void
    {
        $this->root = $root;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): void
    {
        $this->parent = $parent;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): void
    {
        $this->media = $media;
    }
}

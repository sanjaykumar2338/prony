<?php

declare(strict_types=1);

namespace Prony\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Prony\Model\Sluggable;
use Prony\Validator\Constraints\PostExtraArray;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
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
 *         "get"={
 *             "normalization_context"={"groups"={"get"}},
 *         },
 *         "put"={
 *             "normalization_context"={"groups"={"write-response"}},
 *             "denormalization_context"={"groups"={"put"}},
 *             "validation_groups"={"post"}
 *         },
 *         "vote"={
 *              "method"="POST",
 *              "path"="/posts/{id}/vote",
 *              "controller"=Prony\Controller\Api\PostVoteAction::class,
 *              "openapi_context"={
 *                  "summary" = "Adds/removes a vote from the current user",
 *                  "description" = "If user has not voted for the post call to this endpoint adds a vote to the post, otherwise it removes a vote"
 *              },
 *              "normalization_context"={"groups"={"write-response"}},
 *              "denormalization_context"={"groups"={}}
 *          }
 *     },
 *     subresourceOperations={
 *          "api_boards_posts_get_subresource"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"get-sub"}}
 *          }
 *     },
 *     attributes={"order"={"score": "DESC"}, "force_eager"=false}
 * )
 */
class Post implements ResourceInterface
{
    use ResourceTrait;
    use Timestampable;
    use Blameable;
    use Sluggable;

    /**
     * @Groups({"get", "get-sub", "put", "post", "write-response"})
     *
     * @Assert\NotNull(groups={"post", "put"})
     * @Assert\Length(
     *     min = 5,
     *     max = 250,
     *     groups={"post", "put"}
     * )
     *
     * @var string|null
     */
    protected $title;

    /**
     * @Groups({"get", "get-sub", "put", "post"})
     *
     * @Assert\NotNull(groups={"post", "put"})
     * @Assert\Length(
     *     min = 10,
     *     max = 64000,
     *     groups={"post", "put"}
     * )
     *
     * @var string|null
     */
    protected $description;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *             "type"="object",
     *             "additionalProperties"={
     *                  "type"="string"
     *              }
     *         }
     *     }
     * )
     *
     * @Groups({"get", "get-sub", "put", "post"})
     *
     * @PostExtraArray(message="prony.post.extra.format",  groups={"post", "put"})
     *
     * @var array
     */
    protected $extra;

    /**
     * @Groups({"get", "get-sub"})
     *
     * @var float
     */
    protected $score;

    /**
     * @Groups({"post"})
     *
     * @Assert\NotNull(groups={"post"})
     *
     * @var Board|null
     */
    protected $board;

    /**
     * @Groups({"get", "get-sub", "write-response"})
     *
     * @var int
     */
    protected $voteCount;

    /**
     * @Groups({"get", "get-sub", "write-response"})
     *
     * @var int
     */
    protected $commentCount;

    /**
     * @Groups({"get", "get-sub", "put"})
     *
     * @Assert\NotNull(groups={"put"})
     *
     * @var Status|null
     */
    protected $status;

    /**
     * @ApiSubresource(maxDepth=2)
     *
     * @Groups({"get"})
     *
     * @var ArrayCollection
     */
    protected $votes;

    /** @var UserInterface */
    protected $statusChangedBy;

    /**
     * @Groups({"get", "get-sub"})
     *
     * @var UserInterface
     */
    protected $createdBy;

    /**
     * @Groups({"get", "get-sub"})
     *
     * @var DateTime
     */
    protected $createdAt;

    /**
     * @Assert\Valid
     *
     * @var Media|null
     */
    protected $media;

    /**
     * @Groups({"get"})
     *
     * @var Comment|null
     */
    protected $rootComment;

    public function __construct()
    {
        $this->votes = new ArrayCollection();
        $this->extra = [];
        $this->score = (float) 0;
        $this->voteCount = 0;
        $this->commentCount = 0;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getBoard(): ?Board
    {
        return $this->board;
    }

    public function setBoard(?Board $board): void
    {
        $this->board = $board;
        $board->addPost($this);
    }

    /**
     * @return ArrayCollection
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Vote $vote): void
    {
        if (!$this->votes->contains($vote)) {
            $this->votes->add($vote);
            $vote->setPost($this);
        }
    }

    public function getStatusChangedBy(): User
    {
        return $this->statusChangedBy;
    }

    public function setStatusChangedBy(User $statusChangedBy): void
    {
        $this->statusChangedBy = $statusChangedBy;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): void
    {
        $this->status = $status;
    }

    public function getExtra(): array
    {
        return $this->extra;
    }

    public function setExtra(array $extra): void
    {
        $this->extra = $extra;
    }

    public function getScore(): float
    {
        return (float) $this->score;
    }

    public function setScore(float $score): void
    {
        $this->score = $score;
    }

    public function getVoteCount(): int
    {
        return $this->voteCount;
    }

    public function setVoteCount(int $voteCount): void
    {
        $this->voteCount = $voteCount;
    }

    public function getCommentCount(): int
    {
        return $this->commentCount;
    }

    public function setCommentCount(int $commentCount): void
    {
        $this->commentCount = $commentCount;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): void
    {
        $this->media = $media;
    }

    public function getRootComment(): ?Comment
    {
        return $this->rootComment;
    }

    public function setRootComment(Comment $rootComment): void
    {
        $this->rootComment = $rootComment;
    }
}

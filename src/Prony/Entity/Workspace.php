<?php

declare(strict_types=1);

namespace Prony\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Prony\Doctrine\Enum\LanguageEnum;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Talav\Component\Resource\Model\ResourceInterface;
use Talav\Component\Resource\Model\ResourceTrait;
use Talav\Component\Resource\Model\Timestampable;
use Talav\Component\User\Model\Blameable;

/**
 * @ApiResource(
 *     collectionOperations={},
 *     itemOperations={"get"},
 *     normalizationContext={"groups"={"get"}},
 * )
 * @UniqueEntity(
 *     fields={"subdomain"},
 *     errorPath="subdomain",
 *     message="prony.workspace.subdomain.taken"
 * )
 */
class Workspace implements ResourceInterface
{
    use Timestampable;
    use ResourceTrait;
    use Blameable;

    /**
     * @Groups({"get"})
     *
     * @Assert\Length(max = 250, groups={"editing"})
     */
    protected ?string $name;

    protected ?string $domain;

    /**
     * @Assert\Length(max = 250, groups={"editing"})
     * @Assert\Regex(pattern = "/^[a-z][a-z0-9_\-]+$/i", message = "prony.workspace.subdomain.wrong_format", groups={"editing"})
     */
    protected ?string $subdomain;

    /** @var string */
    protected $language;

    /**
     * @Assert\NotNull
     * @Assert\Regex(pattern = "/#[A-Fa-f0-9]{6}/")
     *
     * @var string
     */
    protected $color;

    /**
     * @Assert\Valid
     *
     * @var Media|null
     */
    protected $logo;

    /**
     * @Assert\Valid
     *
     * @var Media|null
     */
    protected $icon;

    /** @var bool */
    protected $showRoadMap;

    /** @var string */
    protected $boardListTitle;

    /** @var string */
    protected $roadMapTitle;

    /** @var bool */
    protected $isIndexed;

    /** @var Collection */
    protected $boards;

    /** @var Collection */
    protected $statuses;

    /** @var Collection */
    protected $tags;

    public function __construct()
    {
        $time = new DateTime();
        $this->setCreatedAt($time);
        $this->setUpdatedAt($time);
        $this->boards = new ArrayCollection();
        $this->statuses = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->setColor('#FFFFFF');
        $this->setShowRoadMap(true);
        $this->setIsIndexed(true);
        $this->setLanguage(LanguageEnum::EN);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(?string $domain): void
    {
        $this->domain = $domain;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    public function getLogo(): ?Media
    {
        return $this->logo;
    }

    public function setLogo(?Media $logo): void
    {
        $this->logo = $logo;
    }

    public function getIcon(): ?Media
    {
        return $this->icon;
    }

    public function setIcon(?Media $icon): void
    {
        $this->icon = $icon;
    }

    public function isShowRoadMap(): bool
    {
        return $this->showRoadMap;
    }

    public function setShowRoadMap(bool $showRoadMap): void
    {
        $this->showRoadMap = $showRoadMap;
    }

    public function getBoardListTitle(): string
    {
        return $this->boardListTitle;
    }

    public function setBoardListTitle(string $boardListTitle): void
    {
        $this->boardListTitle = $boardListTitle;
    }

    public function getRoadMapTitle(): string
    {
        return $this->roadMapTitle;
    }

    public function setRoadMapTitle(string $roadMapTitle): void
    {
        $this->roadMapTitle = $roadMapTitle;
    }

    public function getSubdomain(): ?string
    {
        return $this->subdomain;
    }

    public function setSubdomain(?string $subdomain): void
    {
        $this->subdomain = $subdomain;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }

    public function isIndexed(): bool
    {
        return $this->isIndexed;
    }

    public function setIsIndexed(bool $isIndexed): void
    {
        $this->isIndexed = $isIndexed;
    }

    public function getBoards(): Collection
    {
        return $this->boards;
    }

    public function addBoard(Board $board): void
    {
        if (!$this->boards->contains($board)) {
            $this->boards->add($board);
            $board->setWorkspace($this);
        }
    }

    public function normalizeBoards(Board $board = null): void
    {
        if (null !== $board && !$this->boards->contains($board)) {
            return;
        }
        $iterator = $this->boards->getIterator();
        $iterator->uasort(function (Board $first, Board $second) use ($board) {
            return $first->getPosition() > $second->getPosition() || ($first->getPosition() == $second->getPosition() && $second == $board) ? 1 : -1;
        });
        $i = 0;
        foreach ($iterator as $temp) {
            $temp->setPosition($i);
            ++$i;
        }
    }

    public function getStatuses(): Collection
    {
        return $this->statuses;
    }

    public function getPostInitialStatus(): Status
    {
        return $this->statuses->get(0);
    }

    public function getRoadMapStatuses(): Collection
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq('isRoadMap', true));

        return $this->getStatuses()->matching($criteria);
    }

    public function getRoadMapPosts(Status $status): Collection
    {
        $result = new ArrayCollection();
        foreach ($this->getBoards() as $board) {
            foreach ($board->getPostsByStatus($status) as $post) {
                $result->add($post);
            }
        }

        return $result;
    }

    public function addStatus(Status $status): void
    {
        if (!$this->statuses->contains($status)) {
            $this->statuses->add($status);
            $status->setWorkspace($this);
        }
    }

    /**
     * Normalizes positions for statuses
     */
    public function normalizeStatuses(Status $status = null): void
    {
        if (null !== $status && !$this->statuses->contains($status)) {
            return;
        }
        $iterator = $this->statuses->getIterator();
        $iterator->uasort(function (Status $first, Status $second) use ($status) {
            return $first->getPosition() > $second->getPosition() || ($first->getPosition() == $second->getPosition() && $second == $status) ? 1 : -1;
        });
        $i = 0;
        foreach ($iterator as $temp) {
            $temp->setPosition($i);
            ++$i;
        }
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->setBoard($this);
        }
    }
}

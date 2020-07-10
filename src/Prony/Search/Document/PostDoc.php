<?php

declare(strict_types=1);

namespace Prony\Search\Document;

use DateTime;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * @ES\Index(alias="post")
 */
class PostDoc
{
    /** @ES\Id() */
    protected string $id;

    /** @ES\Property(type="text") */
    protected string $title;

    /** @ES\Property(type="text") */
    protected string $description;

    /** @ES\Property(type="keyword") */
    protected string $workspace;

    /** @ES\Property(type="date") */
    protected ?DateTime $createdAt;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getWorkspace()
    {
        return $this->workspace;
    }

    /**
     * @param mixed $workspace
     */
    public function setWorkspace($workspace): void
    {
        $this->workspace = $workspace;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}

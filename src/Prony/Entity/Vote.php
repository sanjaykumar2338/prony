<?php

declare(strict_types=1);

namespace Prony\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Talav\Component\Resource\Model\Creatable;
use Talav\Component\Resource\Model\ResourceInterface;
use Talav\Component\Resource\Model\ResourceTrait;
use Talav\Component\User\Model\CreatedBy;

/**
 * @ApiResource(
 *     collectionOperations={},
 *     itemOperations={},
 *     subresourceOperations={
 *          "api_posts_votes_get_subresource"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"get-sub"}}
 *          }
 *     }
 * )
 */
class Vote implements ResourceInterface
{
    use ResourceTrait;
    use Creatable;
    use CreatedBy;

    /** @var Post */
    protected $post;

    public function getPost(): Post
    {
        return $this->post;
    }

    public function setPost(Post $post): void
    {
        $this->post = $post;
        $post->addVote($this);
    }
}

<?php

declare(strict_types=1);

namespace Prony\Controller\Api;

use Prony\Entity\Post;

class PostVoteAction
{
    public function __invoke(Post $data): Post
    {
        return $data;
    }
}

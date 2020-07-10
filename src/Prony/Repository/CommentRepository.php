<?php

declare(strict_types=1);

namespace Prony\Repository;

use Prony\Entity\Comment;
use Talav\Component\Resource\Repository\TreeResourceRepository;

final class CommentRepository extends TreeResourceRepository
{
    public function getCommentTree(Comment $comment)
    {
        $this->_em->getConfiguration()->addCustomHydrationMode('tree', 'Talav\Component\Resource\Tree\Hydrator\ORM\TreeObjectHydrator');

        return $this->getChildrenQuery($comment, false, null, 'ASC', true)
            ->setHint(\Doctrine\ORM\Query::HINT_INCLUDE_META_COLUMNS, true)
            ->getResult('tree');
    }
}

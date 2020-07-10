<?php

declare(strict_types=1);

namespace Prony\Repository;

use Pagerfanta\Pagerfanta;
use Prony\Entity\Board;
use Talav\Component\Resource\Repository\ResourceRepository;

final class PostRepository extends ResourceRepository
{
    public function getBoardPosts(Board $board): array
    {
        return $this->createQueryBuilder('p')
             ->leftJoin('p.createdBy', 'user')
             ->where('p.board = :board')
             ->setParameter('board', $board)
             ->getQuery()
             ->getResult();
    }

    public function getOrderedPosts(Pagerfanta $pager): array
    {
        $ids = [];
        foreach ($pager->getIterator() as $doc) {
            $ids[] = $doc->getId();
        }

        return $this->createQueryBuilder('post')
            ->where('post.id IN (:ids)')
            // Add the new orderBy clause specifying the order
            ->add('orderBy', 'FIELD(post.id, :ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }
}

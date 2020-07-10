<?php

declare(strict_types=1);

namespace Prony\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Prony\Entity\Board;
use Talav\Component\Resource\Repository\ResourceRepository;

final class BoardRepository extends ResourceRepository
{
//    /**
//     * @var CompanyProvider
//     */
//    private $workspaceProvider;
//
//    public function __construct(EntityManagerInterface $em, ClassMetadata $class, CompanyProvider $workspaceProvider)
//    {
//        parent::__construct($em, $class);
//        $this->workspaceProvider = $workspaceProvider;
//    }
//
//    /**
//     * @return Board[]
//     */
//    public function findAllSorted(): array
//    {
//        return $this->findBy(['company' => $this->workspaceProvider->getWorkspace()], ['position' => 'ASC']);
//    }
//
//    /**
//     * @inheritDoc
//     */
//    public function findOneBy(array $criteria, array $orderBy = null): ?Board
//    {
//        $criteria['company'] = $this->workspaceProvider->getWorkspace();
//        return parent::findOneBy($criteria, $orderBy);
//    }
}

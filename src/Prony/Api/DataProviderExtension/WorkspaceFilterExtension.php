<?php

declare(strict_types=1);

namespace Prony\Api\DataProviderExtension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;
use Prony\Entity\Board;
use Prony\Entity\Status;
use Prony\Entity\Tag;
use Prony\Provider\WorkspaceProvider;

final class WorkspaceFilterExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    /** @var WorkspaceProvider */
    private $workspaceProvider;

    public function __construct(WorkspaceProvider $workspaceProvider)
    {
        $this->workspaceProvider = $workspaceProvider;
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = [])
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        if (!in_array($resourceClass, [Board::class, Tag::class, Status::class])) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(sprintf('%s.workspace = :workspace', $rootAlias));
        $queryBuilder->setParameter('workspace', $this->workspaceProvider->getWorkspace());
    }
}

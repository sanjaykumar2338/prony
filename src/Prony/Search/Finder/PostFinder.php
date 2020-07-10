<?php

declare(strict_types=1);

namespace Prony\Search\Finder;

use ONGR\ElasticsearchBundle\Service\IndexService;
use ONGR\ElasticsearchDSL\Query\TermLevel\TermQuery;
use ONGR\ElasticsearchDSL\Sort\FieldSort;
use Pagerfanta\Pagerfanta;
use Prony\Entity\Workspace;
use Prony\Pagefranta\Adapter\ElasticsearchAdapter;

class PostFinder
{
    protected IndexService $indexService;

    public function __construct(IndexService $indexService)
    {
        $this->indexService = $indexService;
    }

    public function findByWorkspace(Workspace $workspace, int $page): Pagerfanta
    {
        $search = $this->indexService->createSearch();
        $termQuery = new TermQuery('workspace', $workspace->getId()->__toString());
        $search->addQuery($termQuery);
        $search->addSort(new FieldSort('created_at', 'DESC'));
        $pager = new Pagerfanta(new ElasticsearchAdapter($this->indexService, $search));
        $pager->setCurrentPage($page);
        $pager->setMaxPerPage(5);

        return $pager;
    }
}

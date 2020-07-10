<?php

declare(strict_types=1);

namespace Prony\Pagefranta\Adapter;

use ONGR\ElasticsearchBundle\Result\DocumentIterator;
use ONGR\ElasticsearchBundle\Service\IndexService;
use ONGR\ElasticsearchDSL\Search;
use Pagerfanta\Adapter\AdapterInterface;

/**
 * Adapter which calculates pagination from a Elastica Query.
 */
class ElasticsearchAdapter implements AdapterInterface
{
    /** @var Search */
    private $search;

    /** @var ?DocumentIterator */
    private $resultSet;

    /** @var IndexService */
    private $indexService;

    /** @var array */
    private $options;

    /**
     * Used to limit the number of totalHits returned by ElasticSearch.
     * For more information, see: https://github.com/whiteoctober/Pagerfanta/pull/213#issue-87631892
     *
     * @var int|null
     */
    private $maxResults;

    public function __construct(IndexService $indexService, Search $search, array $options = [], $maxResults = null)
    {
        $this->indexService = $indexService;
        $this->search = $search;
        $this->options = $options;
        $this->maxResults = $maxResults;
    }

    /**
     * Returns the result.
     *
     * Will return null if getSlice has not yet been called.
     */
    public function getResultSet(): ?DocumentIterator
    {
        return $this->resultSet;
    }

    public function getNbResults(): int
    {
        if (!$this->resultSet) {
            // https://github.com/ongr-io/ElasticsearchBundle/pull/933
            $this->getSlice(0, 1);
            $count = count($this->resultSet);
            $this->resultSet = null;
        } else {
            $count = count($this->resultSet);
        }

        if (null == $this->maxResults) {
            return $count;
        }

        return min($count, $this->maxResults);
    }

    /**
     * @param int $offset
     * @param int $length
     *
     * @return iterable
     */
    public function getSlice($offset, $length)
    {
        $this->search->setFrom($offset);
        $this->search->setSize($length);

        return $this->resultSet = $this->indexService->findDocuments(
            $this->search
        );
    }
}

<?php

/*
 * This file is part of the FOSElasticaBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\ElasticaBundle\Finder;

use Elastica\Query;
use Elastica\SearchableInterface;
use FOS\ElasticaBundle\Paginator\FantaPaginatorAdapter;
use FOS\ElasticaBundle\Paginator\HybridPaginatorAdapter;
use FOS\ElasticaBundle\Paginator\RawPaginatorAdapter;
use FOS\ElasticaBundle\Paginator\TransformedPaginatorAdapter;
use FOS\ElasticaBundle\Transformer\ElasticaToModelTransformerInterface;
use Pagerfanta\Pagerfanta;

/**
 * Finds elastica documents and map them to persisted objects.
 */
class TransformedFinder implements PaginatedFinderInterface
{
    /**
     * @var SearchableInterface
     */
    protected $searchable;

    /**
     * @var ElasticaToModelTransformerInterface
     */
    protected $transformer;

    public function __construct(SearchableInterface $searchable, ElasticaToModelTransformerInterface $transformer)
    {
        $this->searchable = $searchable;
        $this->transformer = $transformer;
    }

    public function find($query, $limit = null, $options = [])
    {
        $results = $this->search($query, $limit, $options);

        return $this->transformer->transform($results);
    }

    /**
     * @param int|null $limit
     * @param array    $options
     *
     * @return array
     */
    public function findHybrid($query, $limit = null, $options = [])
    {
        $results = $this->search($query, $limit, $options);

        return $this->transformer->hybridTransform($results);
    }

    public function findPaginated($query, $options = [])
    {
        $queryObject = Query::create($query);
        $paginatorAdapter = $this->createPaginatorAdapter($queryObject, $options);

        return new Pagerfanta(new FantaPaginatorAdapter($paginatorAdapter));
    }

    public function createPaginatorAdapter($query, $options = [])
    {
        $query = Query::create($query);

        return new TransformedPaginatorAdapter($this->searchable, $query, $options, $this->transformer);
    }

    public function createHybridPaginatorAdapter($query)
    {
        $query = Query::create($query);

        return new HybridPaginatorAdapter($this->searchable, $query, $this->transformer);
    }

    public function createRawPaginatorAdapter($query, $options = [])
    {
        $query = Query::create($query);

        return new RawPaginatorAdapter($this->searchable, $query, $options);
    }

    /**
     * @param int|null $limit
     * @param array    $options
     *
     * @return array
     */
    protected function search($query, $limit = null, $options = [])
    {
        $queryObject = Query::create($query);
        if (null !== $limit) {
            $queryObject->setSize($limit);
        }
        $results = $this->searchable->search($queryObject, $options)->getResults();

        return $results;
    }
}

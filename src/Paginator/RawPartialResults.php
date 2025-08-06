<?php

/*
 * This file is part of the FOSElasticaBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\ElasticaBundle\Paginator;

use Elastica\Result;
use Elastica\ResultSet;

/**
 * Raw partial results transforms to a simple array.
 */
class RawPartialResults implements PartialResultsInterface
{
    protected $resultSet;

    public function __construct(ResultSet $resultSet)
    {
        $this->resultSet = $resultSet;
    }

    public function toArray(): array
    {
        return array_map(function (Result $result) {
            return $result->getSource();
        }, $this->resultSet->getResults());
    }

    public function getTotalHits(): int
    {
        return $this->resultSet->getTotalHits();
    }

    public function getAggregations(): array
    {
        return $this->resultSet->getAggregations();
    }

    public function getSuggests()
    {
        if ($this->resultSet->hasSuggests()) {
            return $this->resultSet->getSuggests();
        }

        return;
    }
}

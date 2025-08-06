<?php

/*
 * This file is part of the FOSElasticaBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\ElasticaBundle\Provider;

use Pagerfanta\Pagerfanta;

class PagerfantaPager implements PagerInterface
{
    /**
     * @var Pagerfanta
     */
    private $pagerfanta;

    public function __construct(Pagerfanta $pagerfanta)
    {
        $this->pagerfanta = $pagerfanta;
    }

    public function getNbResults()
    {
        return $this->pagerfanta->getNbResults();
    }

    public function getNbPages()
    {
        return $this->pagerfanta->getNbPages();
    }

    public function getCurrentPage()
    {
        return $this->pagerfanta->getCurrentPage();
    }

    public function setCurrentPage($page)
    {
        $this->pagerfanta->setCurrentPage($page);
    }

    public function getMaxPerPage()
    {
        return $this->pagerfanta->getMaxPerPage();
    }

    public function setMaxPerPage($perPage)
    {
        $this->pagerfanta->setMaxPerPage($perPage);
    }

    public function getCurrentPageResults()
    {
        return $this->pagerfanta->getCurrentPageResults();
    }

    /**
     * @return Pagerfanta
     */
    public function getPagerfanta()
    {
        return $this->pagerfanta;
    }
}

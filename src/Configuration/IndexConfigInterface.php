<?php

/*
 * This file is part of the FOSElasticaBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\ElasticaBundle\Configuration;

/**
 * Interface Index config interface.
 *
 * @author Dmitry Balabka <dmitry.balabka@intexsys.lv>
 */
interface IndexConfigInterface
{
    /**
     * @return string
     */
    public function getElasticSearchName();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getSettings();

    /**
     * @param string $typeName
     *
     * @return TypeConfig
     *
     * @throws \InvalidArgumentException
     */
    public function getType($typeName);

    /**
     * @return TypeConfig[]
     */
    public function getTypes();
}

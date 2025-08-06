<?php

/*
 * This file is part of the FOSElasticaBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\ElasticaBundle\Tests\Configuration;

use FOS\ElasticaBundle\Configuration\IndexTemplateConfig;
use PHPUnit\Framework\TestCase;

class IndexTemplateConfigTest extends TestCase
{
    public function testInstantiate()
    {
        $name = 'index_template1';
        $config = [
            'elasticSearchName' => 'index_template_elastic_name1',
            'settings' => [1],
            'template' => 't*',
        ];
        $indexTemplate = new IndexTemplateConfig($name, [], $config);
        $this->assertEquals($name, $indexTemplate->getName());
        $this->assertEquals(
            $config,
            [
                'elasticSearchName' => $indexTemplate->getElasticSearchName(),
                'settings' => $indexTemplate->getSettings(),
                'template' => $indexTemplate->getTemplate(),
            ]
        );
    }

    public function testIncorrectInstantiate()
    {
        $name = 'index_template1';

        $this->expectException(\InvalidArgumentException::class);

        new IndexTemplateConfig($name, [], []);
    }
}

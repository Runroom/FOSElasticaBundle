<?php

/*
 * This file is part of the FOSElasticaBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\ElasticaBundle\Tests\Unit\Doctrine;

use Doctrine\Persistence\ManagerRegistry;
use FOS\ElasticaBundle\Doctrine\RepositoryManager;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
use FOS\ElasticaBundle\Repository;
use PHPUnit\Framework\TestCase;

class CustomRepository
{
}

class NamespacedEntity
{
}

/**
 * @author Richard Miller <info@limethinking.co.uk>
 */
class RepositoryManagerTest extends TestCase
{
    public function testThatGetRepositoryCallsMainRepositoryManager()
    {
        $finderMock = $this->createMock(TransformedFinder::class);
        $registryMock = $this->createMock(ManagerRegistry::class);
        $mainManager = $this->createMock(RepositoryManagerInterface::class);

        $mainManager->method('getRepository')
            ->with($this->equalTo('index/type'))
            ->willReturn(new Repository($finderMock));

        $manager = new RepositoryManager($registryMock, $mainManager);
        $manager->addEntity(NamespacedEntity::class, 'index/type');
        $repository = $manager->getRepository(NamespacedEntity::class);
        $this->assertInstanceOf(Repository::class, $repository);
    }

    public function testGetRepositoryShouldResolveEntityShortName()
    {
        $finderMock = $this->createMock(TransformedFinder::class);
        $registryMock = $this->createMock(ManagerRegistry::class);
        $mainManager = $this->createMock(RepositoryManagerInterface::class);

        // @link https://github.com/doctrine/persistence/pull/204
        if (\method_exists(ManagerRegistry::class, 'getAliasNamespace')) {
            $registryMock->method('getAliasNamespace')
                ->with($this->equalTo('FOSElasticaBundle'))
                ->willReturn((new \ReflectionClass(NamespacedEntity::class))->getNamespaceName());
        }

        $mainManager->method('getRepository')
            ->with($this->equalTo('index/type'))
            ->willReturn(new Repository($finderMock));

        $manager = new RepositoryManager($registryMock, $mainManager);
        $manager->addEntity(NamespacedEntity::class, 'index/type');

        if (\method_exists(ManagerRegistry::class, 'getAliasNamespace')) {
            $repository = $manager->getRepository('FOSElasticaBundle:NamespacedEntity');
        } else {
            $repository = $manager->getRepository(NamespacedEntity::class);
        }

        $this->assertInstanceOf(Repository::class, $repository);
    }
}

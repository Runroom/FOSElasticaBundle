<?php

/*
 * This file is part of the FOSElasticaBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\ElasticaBundle\Tests\Functional;

use FOS\ElasticaBundle\DataCollector\ElasticaDataCollector;
use FOS\ElasticaBundle\Logger\ElasticaLogger;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Extension\CodeExtension;
use Symfony\Bridge\Twig\Extension\HttpKernelExtension;
use Symfony\Bridge\Twig\Extension\HttpKernelRuntime;
use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\RuntimeLoader\RuntimeLoaderInterface;

/**
 * @group functional
 */
class ProfilerTest extends WebTestCase
{
    /** @var ElasticaLogger */
    private $logger;

    /** @var Environment */
    private $twig;

    /** @var ElasticaDataCollector */
    private $collector;

    public function setUp(): void
    {
        $this->logger = new ElasticaLogger($this->createMock(LoggerInterface::class), true);
        $this->collector = new ElasticaDataCollector($this->logger);

        $twigLoaderFilesystem = new FilesystemLoader(__DIR__ . '/../../src/Resources/views/Collector');
        $twigLoaderFilesystem->addPath(__DIR__ . '/../../vendor/symfony/web-profiler-bundle/Resources/views', 'WebProfiler');
        $this->twig = new Environment($twigLoaderFilesystem, ['debug' => true, 'strict_variables' => true]);

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $fragmentHandler = $this->createMock(FragmentHandler::class);
        $loader = $this->getMockBuilder(RuntimeLoaderInterface::class)->getMock();

        if (\class_exists('Symfony\Bridge\Twig\Extension\CodeExtension')) {
            $this->twig->addExtension(new CodeExtension('', '', ''));
        }
        $this->twig->addExtension(new RoutingExtension($urlGenerator));
        $this->twig->addExtension(new HttpKernelExtension($fragmentHandler));

        $urlGenerator->method('generate')->willReturn('');
        $fragmentHandler->method('render')->willReturn('');
        $loader->method('load')->willReturn(new HttpKernelRuntime($fragmentHandler));

        $this->twig->addRuntimeLoader($loader);
    }

    /**
     * @dataProvider queryProvider
     */
    public function testRender($query)
    {
        $connection = [
            'host' => 'localhost',
            'port' => '9200',
            'transport' => 'http',
        ];
        $this->logger->logQuery('index/_search', 'GET', $query, 1, $connection);
        $this->collector->collect($request = new Request(), new Response());

        $output = $this->twig->render('elastica.html.twig', [
            'request' => $request,
            'collector' => $this->collector,
            'queries' => $this->logger->getQueries(),
            'profile_type' => 'request',
        ]);

        $output = str_replace("&quot;", '"', $output);

        $this->assertStringContainsString('{"query":{"match_all":', $output);
        $this->assertStringContainsString('index/_search', $output);
        $this->assertStringContainsString('localhost:9200', $output);
    }

    public function queryProvider()
    {
        return [
            [json_decode('{"query":{"match_all":{}}}', true)],
            ['{"query":{"match_all":{}}}'],
        ];
    }
}

<?php

namespace GuillermoandraeTest\Highrise;

use Guillermoandrae\Highrise\Http\GuzzleAdapter;
use Guillermoandrae\Highrise\Repositories\RepositoryInterface;
use Guillermoandrae\Repositories\RepositoryFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    protected function assertSameLastRequestUri(string $uri, RepositoryInterface $resource)
    {
        $this->assertSame($uri, $resource->getAdapter()->getLastRequest()->getUri()->getPath());
    }

    protected function getMockResource(string $name, string $expectedBody)
    {
        $client = $this->getMockClient(200, [], $expectedBody);
        $adapter = $this->getAdapter($client);
        RepositoryFactory::setNamespace('Guillermoandrae\Highrise\Repositories');
        return RepositoryFactory::factory($name, $adapter);
    }

    protected function getAdapter(Client $client): GuzzleAdapter
    {
        $adapter = new GuzzleAdapter('test', '123456');
        $adapter->setClient($client);
        return $adapter;
    }

    protected function getMockClient($expectedHttpStatusCode, array $expectedHeaders = [], $expectedBody = ''): Client
    {
        $mock = new MockHandler([
            new Response($expectedHttpStatusCode, $expectedHeaders, $expectedBody)
        ]);
        $handler = HandlerStack::create($mock);
        return new Client(['handler' => $handler]);
    }

    protected function getMockModel(string $name): string
    {
        $path = sprintf('%s/Mocks/%s.xml', __DIR__, $name);
        return file_get_contents($path);
    }
}

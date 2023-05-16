<?php

namespace App\Service;

use Psr\Cache\CacheItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MixRepository
{
    public function __construct(
        private HttpClientInterface $githubContentClient,
        private CacheInterface $cache,
        #[Autowire('%kernel.debug%')]
        private bool $isDebug
    ) {}

    public function findAll(): array
    {
        /*
        $output = new BufferedOutput();
        $this->twigDebugCommand->run(new ArrayInput([]), $output);
        dd($output);
        */

        return $this->cache->get('mixes_data', function(CacheItemInterface $cacheItem) {
            $cacheItem->expiresAfter($this->isDebug ? 5 : 60);
            $response = $this->githubContentClient->request('GET', '/SymfonyCasts/vinyl-mixes/main/mixes.json');

            return $response->toArray();
        });
    }
}

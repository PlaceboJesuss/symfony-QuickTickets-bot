<?php

namespace App\Clients;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class QuickTicketsClient
{
    public function __construct(
        private readonly HttpClientInterface $http,
    ) {
    }

    public function getPageByUrl(string $url): string
    {
        $response = $this->http->request('GET', $url);

        if (!$response->getStatusCode() === 200) {
            throw new \Exception("Не удалось получить страницу $url, HTTP статус: " . $response->getStatusCode());
        }

        return $response->getContent(false);
    }
}

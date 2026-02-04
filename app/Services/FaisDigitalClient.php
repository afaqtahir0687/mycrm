<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class FaisDigitalClient
{
    private string $baseUrl;
    private ?string $apiKey;
    private ?string $scrapeEndpoint;
    private ?string $quotationsEndpoint;
    private int $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.fais_digital.base_url', ''), '/');
        $this->apiKey = config('services.fais_digital.api_key');
        $this->scrapeEndpoint = config('services.fais_digital.scrape_endpoint');
        $this->quotationsEndpoint = config('services.fais_digital.quotations_endpoint');
        $this->timeout = (int) config('services.fais_digital.timeout', 20);
    }

    public function fetchScrapedLeads(array $filters = []): array
    {
        if (empty($this->baseUrl) || empty($this->scrapeEndpoint)) {
            throw new \RuntimeException('Fais Digital scrape endpoint is not configured.');
        }

        $response = $this->client()->get($this->scrapeEndpoint, $filters);
        $response->throw();

        $payload = $response->json();
        return $this->normalizeDataArray($payload);
    }

    public function fetchQuotations(array $filters = []): array
    {
        if (empty($this->baseUrl) || empty($this->quotationsEndpoint)) {
            throw new \RuntimeException('Fais Digital quotations endpoint is not configured.');
        }

        $response = $this->client()->get($this->quotationsEndpoint, $filters);
        $response->throw();

        $payload = $response->json();
        return $this->normalizeDataArray($payload);
    }

    private function client(): PendingRequest
    {
        $client = Http::baseUrl($this->baseUrl)
            ->timeout($this->timeout)
            ->acceptJson();

        if (!empty($this->apiKey)) {
            $client = $client->withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ]);
        }

        return $client;
    }

    private function normalizeDataArray($payload): array
    {
        if (is_array($payload) && array_key_exists('data', $payload) && is_array($payload['data'])) {
            return $payload['data'];
        }

        if (is_array($payload)) {
            return $payload;
        }

        return [];
    }
}



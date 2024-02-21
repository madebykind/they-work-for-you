<?php

namespace InLineStudio\TheyWorkForYou\services;

use Exception;
use GuzzleHttp\Client;
use stdClass;

class TheyWorkForYouClient
{
    private Client $client;
    private string $apiKey;

    /**
     * valid functions and parameters
     * @var array<string,array<string>> $validFunctions
     */
    private array $validFunctions = [
        'getQuota'          => [],
        'convertURL'        => ['url'],
        'getConstituency'   => ['name', 'postcode'],
        'getConstituencies' => ['date', 'search', 'latitude', 'longitude', 'distance'],
        'getPerson'         => ['id'],
        'getMP'             => ['id', 'constituency', 'postcode', 'always_return'],
        'getMPInfo'         => ['id'],
        'getMPsInfo'        => ['id'],
        'getMPs'            => ['date', 'party', 'search'],
        'getLord'           => ['id'],
        'getLords'          => ['date', 'party', 'search'],
        'getMLA'            => ['id', 'constituency', 'postcode', 'always_return'],
        'getMLAs'           => ['date', 'party', 'search'],
        'getMSP'            => ['id', 'constituency', 'postcode', 'always_return'],
        'getMSPs'           => ['date', 'party', 'search'],
        'getGeometry'       => ['name'],
        'getBoundary'       => ['name'],
        'getCommittee'      => ['name', 'date'],
        'getDebates'        => ['type', 'date', 'search', 'person', 'gid', 'order', 'page', 'num'],
        'getWrans'          => ['date', 'search', 'person', 'gid', 'order', 'page', 'num'],
        'getWMS'            => ['date', 'search', 'person', 'gid', 'order', 'page', 'num'],
        'getHansard'        => ['search', 'person', 'order', 'page', 'num'],
        'getComments'       => ['start_date', 'end_date', 'pid', 'page', 'num'],
    ];

    public function __construct(
        string $url,
        string $apiKey
    ) {
        // do we hav a training slash?
        if (substr($url, -1) !== '/') {
            $url .= '/';
        }
        // initiate the client
        $this->client = new Client([
            'base_uri' => $url,
        ]);

        $this->apiKey = $apiKey;
    }

    /**
     * hit the TWFY API
     */
    public function get(string $function, array $query = []): mixed
    {
        $this->validateEndpoint($function);
        $this->validateQuery($function, $query);

        $fullQuery = array_merge($query, [
            'key' => $this->apiKey,
            'output' => 'json',
        ]);

        $response = $this->client->get($function, [
            'query' => $fullQuery,
        ]);

        $contents = $response->getBody()->getContents();

        return json_decode($contents);
    }

    /**
     * validate the query is valid for the function
     */
    protected function validateQuery(string $function, array $query): bool
    {
        $validParams = $this->validFunctions[$function];
        foreach ($query as $key => $value) {
            if (false === in_array($key, $validParams)) {
                throw new Exception("Invalid parameter: {$key}");
            }
        }
        // validate the query
        return true;
    }

    /**
     * validate the endpoint is valid
     */
    protected function validateEndpoint(string $function): bool
    {
        if (false === in_array($function, array_keys($this->validFunctions))) {
            throw new \Exception("Invalid API function: {$function}");
        }

        return true;
    }
}
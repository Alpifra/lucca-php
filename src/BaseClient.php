<?php 

namespace Alpifra\LuccaPHP;

use Alpifra\LuccaPHP\Helper\QueryHelper;
use Alpifra\LuccaPHP\Exception\RequestException;
use Alpifra\LuccaPHP\Exception\ResponseException;

/**
 * Base client for various Lucca API services
 * 
 * @see https://developers.lucca.fr/docs/lucca-legacyapi/ZG9jOjM3OTk0NDk5-getting-started
 */
class BaseClient {

    /**
     * API key available on your manager
     */
    private string $key;
    
    /**
     * API domain available on your manager
     */
    private string $domain;

    /**
     * Listing items offset for pagination
     */
    private int $pagingOffset = 0;

    /**
     * Listing items limit for pagination
     */
    private int $pagingLimit = 1000;

    public function __construct(string $key, string $domain)
    {
        $this->key = $key;
        $this->domain = $domain;
    }
    
    public function getKey(): string
    {
        return $this->key;
    }
    
    public function getDomain(): string
    {
        return $this->domain;
    }
    
    public function getPagingOffset(): int
    {
        return $this->pagingOffset;
    }
    
    public function setPagingOffset(int $pagingOffset): self
    {
        $this->pagingOffset = $pagingOffset;
        return $this;
    }
    
    public function getPagingLimit(): int
    {
        return $this->pagingLimit;
    }
    
    public function setPagingLimit(int $pagingLimit): self
    {
        $this->pagingLimit = $pagingLimit;
        return $this;
    }

    /**
     * httpRequest
     *
     * @param  string $method Can be GET, POST, PUT, DELETE
     * @param  string $path
     * @param  null|array<string, string|int|array<array-key, string>> $params
     * @return array <mixed>
     * 
     * @throws RequestException
     * @throws ResponseException
     */
    protected function httpRequest(string $method = 'GET', string $path, null|array $params = null): array
    {
        set_time_limit(0);

        if ($params) {
            $params = QueryHelper::formatQueryParameters($params);
        }

        if (false === $ch = curl_init($this->domain . $path)) {
            throw new RequestException(sprintf('Request initialization to "%s" failed.', $path));
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Autorization: lucca application=' . $this->key,
            'Cache-Control: no-cache',
            'Accept-Encoding: gzip, deflate, br'
        ]);

        switch ($method) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_PUT, true);
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (false === $result = curl_exec($ch)) {
            curl_close($ch);

            throw new ResponseException(sprintf(
                'Failed to get response from "%s". Response: %s.',
                $path,
                $result
            ));
        }

        if (200 !== $code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
            throw new ResponseException(sprintf(
                'Server returned "%s" status code. Response: %s.',
                $code,
                $result
            ));
        }

        curl_close($ch);

        $responseArray = json_decode($result, true);

        return $responseArray;
    }

}
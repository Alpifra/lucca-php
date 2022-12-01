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
class BaseClient
{

    /** @var array<array-key, string> */
    public const METHODS = ['GET', 'POST', 'PUT', 'DELETE'];
    private string $key;
    private string $domain;
    private int $pagingOffset = 0;
    private int $pagingLimit = 1000;
    /** @var array<array-key, array<array-key, string>> */
    private array $fields = [];

    public function __construct(string $key, string $domain)
    {
        $this->key = $key;
        $this->domain = $domain;
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
     * getFields
     *
     * @return array<array-key, array<array-key, string>> $params
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * Specify all wanted fields in the response. API default ire id, name, url
     *
     * @param array<array-key, array<array-key, string>> $fieds
     * @return self
     * 
     * @see https://developers.lucca.fr/docs/lucca-legacyapi/a57b02f39ecaf-api-v3-conventions
     */
    public function setFields(array $fields): self
    {
        $this->fields = ['fields' => $fields];
        return $this;
    }

    /**
     * httpRequest
     *
     * @param  string $method
     * @param  string $path
     * @param  array<string, string|int|array<array-key, string>> $params
     * @return \stdClass
     * 
     * @throws RequestException
     * @throws ResponseException
     */
    protected function httpRequest(string $method, string $path, array $params = []): \stdClass
    {
        set_time_limit(0);

        $params = array_merge($this->fields, $params);
        $params = QueryHelper::formatQueryParameters($params);

        $request = $this->domain . $path . $params;

        if (false === $ch = curl_init($request)) {
            throw new RequestException(sprintf('Request initialization to "%s" failed.', $request));
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json;charset=utf-8',
            'Content-type: text/plain',
            'Authorization: lucca application=' . $this->key,
            'Cache-Control: no-cache',
        ]);

        $method = strtoupper($method);
        $method = in_array($method, self::METHODS) ? $method : 'GET';

        switch ($method) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_PUT, true);
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
        }

        if (false === $result = curl_exec($ch)) {
            curl_close($ch);
            throw new ResponseException(sprintf(
                'Failed to get response from "%s". Response: %s.',
                $path,
                $result
            ));
        }

        if (200 !== $code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
            curl_close($ch);
            throw new ResponseException(sprintf(
                'Server returned "%s" status code. Response: %s.',
                $code,
                $result
            ));
        }

        curl_close($ch);

        return json_decode($result);
    }
}

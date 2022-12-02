<?php

namespace Alpifra\LuccaPHP;

use Alpifra\LuccaPHP\Http\Request;

/**
 * Base client for various Lucca API services
 * 
 * @see https://developers.lucca.fr/docs/lucca-legacyapi/ZG9jOjM3OTk0NDk5-getting-started
 */
class BaseClient
{

    private int $pagingOffset = 0;
    private int $pagingLimit = 1000;
    /** @var array<array-key, array<array-key, string>> */
    private array $fields = [];

    public function __construct(
        private string $key,
        private string $domain
    ) {}

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

    protected function initRequest(): Request
    {
        return new Request($this->key, $this->domain);
    }

}

<?php 

namespace Alpifra\LuccaPHP\Http;

use Alpifra\LuccaPHP\Helper\QueryHelper;
use Alpifra\LuccaPHP\Exception\RequestException;
use Alpifra\LuccaPHP\Exception\ResponseException;

final class Request 
{

    public const TIME_LIMIT = 5000;

    private \CurlHandle|false $ch = false;

    public function __construct(
        private string $key,
        private string $domain
    ) {}

    /**
     * get
     *
     * @param  string $path
     * @param  array<string, string|int|array<array-key, string>> $params
     * @return \stdClass
     * 
     * @throws RequestException
     */
    public function get(string $path, array $params = []): \stdClass
    {
        set_time_limit(self::TIME_LIMIT);
        
        $this->init($path, $params)->setOptions();

        return json_decode($this->exec());
    }

    /**
     * get
     *
     * @param  string $path
     * @param  array<string, string|int|array<array-key, string>> $params
     * @return \stdClass
     * 
     * @throws RequestException
     */
    public function post(string $path, array $params = []): \stdClass
    {
        set_time_limit(self::TIME_LIMIT);
        
        $this->init($path, $params)->this->setOptions();
        curl_setopt($this->ch, CURLOPT_POST, true);

        return json_decode($this->exec());
    }

    /**
     * get
     *
     * @param  string $path
     * @param  array<string, string|int|array<array-key, string>> $params
     * @return \stdClass
     * 
     * @throws RequestException
     */
    public function put(string $path, array $params = []): \stdClass
    {
        set_time_limit(self::TIME_LIMIT);

        $this->init($path, $params)->this->setOptions();
        curl_setopt($this->ch, CURLOPT_PUT, true);

        return json_decode($this->exec());
    }

    /**
     * get
     *
     * @param  string $path
     * @param  array<string, string|int|array<array-key, string>> $params
     * @return \stdClass
     * 
     * @throws RequestException
     */
    public function delete(string $path, array $params = []): \stdClass
    {
        set_time_limit(self::TIME_LIMIT);

        $this->init($path, $params)->this->setOptions();
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "DELETE");

        return json_decode($this->exec());
    }

    /**
     * @param  string $path
     * @param  array<string, string|int|array<array-key, string>> $params
     * @return self
     * 
     * @throws RequestException
     */
    private function init(string $path, array $params): self
    {
        $url = $this->domain;
        $url .= $path;
        $url .= QueryHelper::formatQueryParameters($params);

        $this->ch = curl_init($url);
        if ($this->ch === false) {
            throw new RequestException(sprintf('Request initialization to "%s" failed.', $url));
        }

        return $this;
    }
    
    /**
     * setOptions
     *
     * @param  \CurlHandle $ch
     * @return self
     */
    private function setOptions(): self
    {
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json;charset=utf-8',
            'Content-type: text/plain',
            'Authorization: lucca application=' . $this->key,
            'Cache-Control: no-cache',
        ]);

        return $this;
    }
    
    /**
     * exec
     *
     * @return string
     */
    private function exec(): string
    {
        $result = curl_exec($this->ch);
        if ($result === false) {
            $info = curl_getinfo($this->ch);
            curl_close($this->ch);
            throw new ResponseException("Failed to get response for {$info['url']}. Message: {$result}");
        }

        $code = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        if ($code !== 200) {
            curl_close($this->ch);
            throw new ResponseException("Server returned {$code} status code. Response: {$result}.");
        }

        curl_close($this->ch);

        return $result;
    }

}
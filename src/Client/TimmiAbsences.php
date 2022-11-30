<?php

namespace Alpifra\LuccaPHP\Client;

use Alpifra\LuccaPHP\BaseClient;

/**
 * Timmi Absences service manager from Lucca API
 * 
 * @see https://developers.lucca.fr/docs/lucca-legacyapi/2713ebbef0217-timmi-absences-api
 */
class TimmiAbsences extends BaseClient
{
    
    /**
     * List all leaves
     *
     * @param  int|array<array-key, int> $ownerId
     * @param  string|array<array-key, string> $date
     * @return array<mixed>
     */
    public function list(int|array $ownerId, string|array $date = ['since', '2021-01-01']): array
    {
        $params = [
            'date' => $date,
            'leavePeriod.ownerId' => $ownerId,
            'paging' => [$this->getPagingOffset(), $this->getPagingLimit()]
        ];

        return $this->httpRequest('GET', '/api/v3/leaves', $params);
    }
    
    /**
     * List all leaves requuests
     *
     * @param  int|array<array-key, int> $ownerId
     * @param  string|array<array-key, string> $date
     * @return array<mixed>
     */
    public function listRequests(): array
    {
        return $this->httpRequest('GET', '/api/v3/leavesrequests');
    }

}